<?php

namespace App\Notifications;

use App\Models\Purchase;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AdminFailedPaymentNotification extends Notification
{
    use Queueable;

    public $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    public function via(object $notifiable): array
    {
        $setting = Setting::where('key', 'notifyFailedPayment')->first();
        $isEnabled = $setting ? (bool) $setting->value : true; // Default true

        return $isEnabled ? ['database'] : [];
    }

    public function toArray(object $notifiable): array
    {
        $userName = $this->purchase->user->name ?? 'Unknown user';
        $paperTitle = $this->purchase->paper->title ?? 'a paper';

        return [
            'title' => 'Failed payment',
            'description' => "A payment from {$userName} for '{$paperTitle}' failed.",
            'type' => 'error',
            'link' => route('admin.payments')
        ];
    }
}
