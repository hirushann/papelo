<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Handle Lemon Squeezy webhook events.
     *
     * Verifies the HMAC-SHA256 signature before processing.
     * Supported events: subscription_created, subscription_updated,
     * subscription_cancelled, subscription_expired,
     * subscription_payment_success, subscription_payment_failed.
     */
    public function webhook(Request $request)
    {
        // 1. Verify signature
        $secret = $this->getSigningSecret();
        $signature = $request->header('X-Signature');
        $payload = $request->getContent();

        $computedSignature = hash_hmac('sha256', $payload, $secret);

        if (!hash_equals($computedSignature, $signature ?? '')) {
            Log::error('Lemon Squeezy webhook: Invalid signature');
            return response('Invalid signature', 403);
        }

        // 2. Parse the event
        $data = json_decode($payload, true);
        $eventName = $data['meta']['event_name'] ?? null;
        $customData = $data['meta']['custom_data'] ?? [];
        $attributes = $data['data']['attributes'] ?? [];

        Log::info("Lemon Squeezy webhook: {$eventName}", [
            'ls_subscription_id' => $data['data']['id'] ?? null,
            'custom_data' => $customData,
        ]);

        // 3. Route to handler
        match ($eventName) {
            'subscription_created' => $this->handleSubscriptionCreated($data, $customData, $attributes),
            'subscription_updated' => $this->handleSubscriptionUpdated($data, $attributes),
            'subscription_cancelled' => $this->handleSubscriptionCancelled($data, $attributes),
            'subscription_expired' => $this->handleSubscriptionExpired($data, $attributes),
            'subscription_payment_success' => $this->handlePaymentSuccess($data, $attributes),
            'subscription_payment_failed' => $this->handlePaymentFailed($data, $attributes),
            default => Log::info("Lemon Squeezy webhook: Unhandled event {$eventName}"),
        };

        return response('OK', 200);
    }

    /**
     * Handle subscription_created: link LS subscription to our user + plan.
     */
    private function handleSubscriptionCreated(array $data, array $customData, array $attributes): void
    {
        $userId = $customData['user_id'] ?? null;
        $planId = $customData['plan_id'] ?? null;
        $lsSubscriptionId = (string) ($data['data']['id'] ?? '');

        if (!$userId || !$planId) {
            Log::warning('Lemon Squeezy webhook: Missing user_id or plan_id in custom_data');
            return;
        }

        // Cancel any existing active subscriptions for this user
        Subscription::where('user_id', $userId)
            ->where('status', 'active')
            ->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        Subscription::create([
            'user_id' => $userId,
            'plan_id' => $planId,
            'ls_subscription_id' => $lsSubscriptionId,
            'ls_customer_id' => (string) ($attributes['customer_id'] ?? ''),
            'status' => 'active',
            'current_period_start' => $attributes['renews_at'] ? now() : now(),
            'current_period_end' => $attributes['renews_at'] ?? null,
            'attempts_used' => 0,
        ]);

        Log::info("Subscription created for user {$userId}, plan {$planId}");
    }

    /**
     * Handle subscription_updated: sync status and period dates.
     */
    private function handleSubscriptionUpdated(array $data, array $attributes): void
    {
        $subscription = $this->findSubscription($data);

        if (!$subscription) return;

        $status = $this->mapLsStatus($attributes['status'] ?? '');

        $subscription->update([
            'status' => $status,
            'current_period_end' => $attributes['renews_at'] ?? $subscription->current_period_end,
        ]);

        // If plan changed (upgrade/downgrade), update plan_id
        $variantId = (string) ($attributes['variant_id'] ?? '');
        if ($variantId) {
            $plan = Plan::where('ls_variant_id', $variantId)->first();
            if ($plan && $plan->id !== $subscription->plan_id) {
                $subscription->update(['plan_id' => $plan->id]);
                Log::info("Subscription {$subscription->id} plan changed to {$plan->slug}");
            }
        }
    }

    /**
     * Handle subscription_cancelled.
     */
    private function handleSubscriptionCancelled(array $data, array $attributes): void
    {
        $subscription = $this->findSubscription($data);

        if (!$subscription) return;

        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            // Keep current_period_end so user retains access until end of paid period
            'current_period_end' => $attributes['ends_at'] ?? $subscription->current_period_end,
        ]);

        Log::info("Subscription {$subscription->id} cancelled");
    }

    /**
     * Handle subscription_expired.
     */
    private function handleSubscriptionExpired(array $data, array $attributes): void
    {
        $subscription = $this->findSubscription($data);

        if (!$subscription) return;

        $subscription->update(['status' => 'expired']);

        Log::info("Subscription {$subscription->id} expired");
    }

    /**
     * Handle subscription_payment_success: reset paper counter for new period.
     */
    private function handlePaymentSuccess(array $data, array $attributes): void
    {
        $subscription = $this->findSubscription($data);

        if (!$subscription) return;

        $subscription->update([
            'status' => 'active',
            'current_period_start' => now(),
            'current_period_end' => $attributes['renews_at'] ?? $subscription->current_period_end,
            'attempts_used' => 0, // Reset for new billing period
        ]);

        Log::info("Payment success for subscription {$subscription->id}, attempts reset");
    }

    /**
     * Handle subscription_payment_failed.
     */
    private function handlePaymentFailed(array $data, array $attributes): void
    {
        $subscription = $this->findSubscription($data);

        if (!$subscription) return;

        $subscription->update(['status' => 'past_due']);

        // Notify admins
        \Illuminate\Support\Facades\Notification::send(
            User::where('is_admin', true)->get(),
            new \App\Notifications\AdminFailedPaymentNotification(null)
        );

        Log::info("Payment failed for subscription {$subscription->id}");
    }

    /**
     * Find a subscription by Lemon Squeezy subscription ID from webhook payload.
     */
    private function findSubscription(array $data): ?Subscription
    {
        $lsSubscriptionId = (string) ($data['data']['id'] ?? '');

        // For payment events, the subscription ID is in the relationships
        if (empty($lsSubscriptionId) || ($data['data']['type'] ?? '') !== 'subscriptions') {
            $lsSubscriptionId = (string) ($data['data']['attributes']['subscription_id'] ?? $lsSubscriptionId);
        }

        $subscription = Subscription::where('ls_subscription_id', $lsSubscriptionId)->first();

        if (!$subscription) {
            Log::warning("Lemon Squeezy webhook: Subscription not found for LS ID {$lsSubscriptionId}");
        }

        return $subscription;
    }

    /**
     * Map Lemon Squeezy status to our enum.
     */
    private function mapLsStatus(string $lsStatus): string
    {
        return match ($lsStatus) {
            'active' => 'active',
            'cancelled' => 'cancelled',
            'past_due' => 'past_due',
            'expired' => 'expired',
            'paused' => 'paused',
            'on_trial' => 'active',
            default => 'active',
        };
    }

    /**
     * Get the signing secret from admin settings or config.
     */
    private function getSigningSecret(): string
    {
        $setting = \App\Models\Setting::where('key', 'lsSigningSecret')->first();
        return $setting ? $setting->value : config('services.lemonsqueezy.signing_secret', '');
    }

    /**
     * Post-checkout success redirect.
     */
    public function success(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        if ($user) {
            // Fallback: Manually query Lemon Squeezy API to sync the latest subscription
            // in case the webhook hasn't arrived or the user is testing on localhost.
            try {
                $setting = \App\Models\Setting::where('key', 'lsApiKey')->first();
                $apiKey = $setting ? $setting->value : config('services.lemonsqueezy.api_key');

                $response = \Illuminate\Support\Facades\Http::withToken($apiKey)
                    ->get('https://api.lemonsqueezy.com/v1/subscriptions', [
                        'filter[user_email]' => $user->email,
                    ]);

                if ($response->successful()) {
                    $subscriptions = $response->json('data') ?? [];
                    
                    if (count($subscriptions) > 0) {
                        // Sort to get the most recently created one
                        usort($subscriptions, fn($a, $b) => strtotime($b['attributes']['created_at']) - strtotime($a['attributes']['created_at']));
                        
                        $latestSub = $subscriptions[0];
                        $lsSubscriptionId = (string) $latestSub['id'];
                        $attributes = $latestSub['attributes'];
                        // Lemon Squeezy returns custom_data on the subscription object? Wait, no, it usually returns variant_id.
                        // Let's find plan by variant ID instead if custom_data isn't there.
                        $variantId = $attributes['variant_id'] ?? null;
                        
                        $plan = \App\Models\Plan::where('ls_variant_id', $variantId)->first();
                        
                        if ($plan) {
                            $existing = \App\Models\Subscription::where('ls_subscription_id', $lsSubscriptionId)->first();
                            
                            if (!$existing) {
                                // Cancel existing active subscriptions
                                \App\Models\Subscription::where('user_id', $user->id)
                                    ->where('status', 'active')
                                    ->update(['status' => 'cancelled', 'cancelled_at' => now()]);

                                \App\Models\Subscription::create([
                                    'user_id' => $user->id,
                                    'plan_id' => $plan->id,
                                    'ls_subscription_id' => $lsSubscriptionId,
                                    'ls_customer_id' => (string) $attributes['customer_id'],
                                    'status' => $this->mapLsStatus($attributes['status']),
                                    'current_period_start' => $attributes['renews_at'] ? now() : now(),
                                    'current_period_end' => $attributes['renews_at'] ?? null,
                                    'attempts_used' => 0,
                                ]);
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to sync subscription on success route: ' . $e->getMessage());
            }
        }

        return redirect()->route('dashboard')->with('payment-success', 'Subscription activated! You now have access to all papers in your plan.');
    }
}
