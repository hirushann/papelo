<?php

namespace App\Livewire;

use App\Models\Paper;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PaperCheckout extends Component
{
    public Paper $paper;
    public $user;
    
    // User fields for PayHere
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '0000000000'; // Default since it's required by PayHere
    public $address = 'Sri Lanka'; // Default since it's required by PayHere
    public $city = 'Colombo';
    public $country = 'Sri Lanka';

    public function mount(Paper $paper)
    {
        $this->paper = $paper;
        $this->user = Auth::user();
        
        if (!$this->user) {
            return redirect()->route('login');
        }

        $names = explode(' ', $this->user->name);
        $this->first_name = $names[0];
        $this->last_name = count($names) > 1 ? end($names) : $names[0];
        $this->email = $this->user->email;
    }

    public function processPayment()
    {
        // Reuse existing pending purchase or create a new one
        $purchase = Purchase::updateOrCreate(
            [
                'user_id' => $this->user->id,
                'paper_id' => $this->paper->id,
            ],
            [
                'amount_paid' => $this->paper->price,
                'status' => 'pending',
            ]
        );

        $merchantIdSetting = \App\Models\Setting::where('key', 'merchantId')->first();
        $merchantId = $merchantIdSetting ? $merchantIdSetting->value : config('services.payhere.merchant_id');

        $merchantSecretSetting = \App\Models\Setting::where('key', 'merchantSecret')->first();
        $merchantSecret = $merchantSecretSetting ? $merchantSecretSetting->value : config('services.payhere.merchant_secret');

        $currency = 'LKR';
        $orderId = (string) $purchase->id;
        $amountFormatted = number_format($this->paper->price, 2, '.', '');
        
        // Hash formula: strtoupper(md5(merchant_id + order_id + amount + currency + strtoupper(md5(merchant_secret))))
        $secretHash = strtoupper(md5($merchantSecret));
        $hashString = $merchantId . $orderId . $amountFormatted . $currency . $secretHash;
        $hash = strtoupper(md5($hashString));

        \Illuminate\Support\Facades\Log::info('PayHere Hash Debug', [
            'merchant_id' => $merchantId,
            'order_id' => $orderId,
            'amount' => $amountFormatted,
            'currency' => $currency,
            'secret_hash' => $secretHash,
            'full_string' => $hashString,
            'final_hash' => $hash,
        ]);

        $modeSetting = \App\Models\Setting::where('key', 'payhereMode')->first();
        $isSandbox = $modeSetting ? ($modeSetting->value === 'Sandbox') : config('services.payhere.sandbox');

        // Dispatch browser event to submit the form
        $this->dispatch('initiate-payhere', [
            'merchant_id' => $merchantId,
            'return_url' => route('payhere.return'),
            'cancel_url' => route('payhere.cancel', ['paper_id' => $this->paper->id]),
            'notify_url' => url('/payhere/notify'),
            'order_id' => $orderId,
            'items' => $this->paper->title,
            'currency' => $currency,
            'amount' => $amountFormatted,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'hash' => $hash,
            'url' => $isSandbox ? 'https://sandbox.payhere.lk/pay/checkout' : 'https://www.payhere.lk/pay/checkout'
        ]);
    }

    public function render()
    {
        return view('livewire.paper-checkout')
            ->layout('layouts.quiz')->title('Checkout — Papelo');
    }
}
