<?php

namespace App\Livewire;

use App\Models\Plan;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class SubscriptionCheckout extends Component
{
    public $user;
    public $currentSubscription = null;
    public $currentPlan = null;

    public function mount()
    {
        $this->user = Auth::user();

        if (!$this->user) {
            return redirect()->route('login');
        }

        // Check for existing active subscription
        $this->currentSubscription = $this->user->activeSubscription();
        if ($this->currentSubscription) {
            $this->currentPlan = $this->currentSubscription->plan;
        }
    }

    /**
     * Initiate a Lemon Squeezy checkout for the given plan.
     */
    public function subscribe(int $planId)
    {
        $plan = Plan::findOrFail($planId);

        if (!$plan->ls_variant_id) {
            session()->flash('error', 'This plan is not yet configured. Please contact support.');
            return;
        }

        // Get API credentials
        $apiKey = $this->getSetting('lsApiKey', config('services.lemonsqueezy.api_key'));
        $storeId = $this->getSetting('lsStoreId', config('services.lemonsqueezy.store_id'));

        if (!$apiKey || !$storeId) {
            session()->flash('error', 'Payment gateway not configured. Please contact support.');
            return;
        }

        // Create Lemon Squeezy checkout session via API
        $response = Http::withToken($apiKey)
            ->withHeaders([
                'Accept' => 'application/vnd.api+json',
                'Content-Type' => 'application/vnd.api+json',
            ])
            ->post('https://api.lemonsqueezy.com/v1/checkouts', [
                'data' => [
                    'type' => 'checkouts',
                    'attributes' => [
                        'checkout_data' => [
                            'email' => $this->user->email,
                            'name' => $this->user->name,
                            'custom' => [
                                'user_id' => (string) $this->user->id,
                                'plan_id' => (string) $plan->id,
                            ],
                        ],
                        'product_options' => [
                            'redirect_url' => route('subscribe.success'),
                        ],
                    ],
                    'relationships' => [
                        'store' => [
                            'data' => ['type' => 'stores', 'id' => (string) $storeId],
                        ],
                        'variant' => [
                            'data' => ['type' => 'variants', 'id' => (string) $plan->ls_variant_id],
                        ],
                    ],
                ],
            ]);

        if ($response->successful()) {
            $checkoutUrl = $response->json('data.attributes.url');
            $this->dispatch('redirect-to-checkout', url: $checkoutUrl);
        } else {
            \Illuminate\Support\Facades\Log::error('Lemon Squeezy checkout creation failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            session()->flash('error', 'Could not create checkout session. Please try again.');
        }
    }

    /**
     * Get a setting value from admin settings or fall back to config.
     */
    private function getSetting(string $key, ?string $fallback): ?string
    {
        $setting = Setting::where('key', $key)->first();
        return $setting ? $setting->value : $fallback;
    }

    public function render()
    {
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();

        return view('livewire.subscription-checkout', [
            'plans' => $plans,
        ])->layout('layouts.quiz')->title('Subscribe — Papelooo');
    }
}
