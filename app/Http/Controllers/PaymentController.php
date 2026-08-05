<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function notify(Request $request)
    {
        $merchantId = $request->input('merchant_id');
        $orderId = $request->input('order_id');
        $payhereAmount = $request->input('payhere_amount');
        $payhereCurrency = $request->input('payhere_currency');
        $statusCode = $request->input('status_code');
        $md5sigReceived = $request->input('md5sig');

        $merchantSecret = config('services.payhere.merchant_secret');
        
        $md5sig = strtoupper(md5(
            $merchantId . 
            $orderId . 
            $payhereAmount . 
            $payhereCurrency . 
            $statusCode . 
            strtoupper(md5($merchantSecret))
        ));

        if ($md5sig === $md5sigReceived) {
            $purchase = Purchase::find($orderId);
            
            if ($purchase) {
                // Status code 2 is success
                if ($statusCode == 2) {
                    $purchase->update([
                        'status' => 'completed',
                        'payhere_order_id' => $request->input('payment_id'),
                    ]);
                    Log::info("PayHere webhook: Payment completed for order $orderId");
                } elseif ($statusCode < 0) {
                    $purchase->update(['status' => 'failed']);
                    Log::info("PayHere webhook: Payment failed for order $orderId");
                }
            } else {
                Log::warning("PayHere webhook: Order $orderId not found");
            }
        } else {
            Log::error("PayHere webhook: Invalid signature for order $orderId");
        }

        return response('OK', 200);
    }

    public function returnHandler(Request $request)
    {
        return redirect()->route('dashboard')->with('payment-success', 'Payment successful! You can now start your paper.');
    }

    public function cancelHandler(Request $request)
    {
        $paperId = $request->query('paper_id');
        if ($paperId) {
            return redirect()->route('paper.buy', $paperId)->with('payment-cancel', 'Payment was cancelled. You can try again.');
        }
        return redirect()->route('dashboard');
    }
}
