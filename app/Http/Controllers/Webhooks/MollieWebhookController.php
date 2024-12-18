<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\SupportTimePurchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Resources\Payment;
use Mollie\Laravel\Facades\Mollie;

/**
 * Handles Mollie webhook notifications for support time purchases.
 */
class MollieWebhookController extends Controller
{
    /**
     * Handle the webhook notification from Mollie.
     */
    public function handleWebhookNotification(Request $request): Response
    {
        try {
            // Retrieve the payment object using Mollie API
            $payment = Mollie::api()->payments->get($request->input('id'));

            // Match the payment status and handle accordingly
            match ($payment->status) {
                'paid' => $this->processPaidPayment($payment),
                'canceled' => $this->processCancelledPayment($payment),
                'failed', 'expired' => $this->processFailedPayment($payment),
                default => $this->logUnhandledStatus($payment),
            };

            return response('OK', 200);
        } catch (ApiException $e) {
            // Log Mollie API errors and return an error response
            Log::error('Mollie API error in webhook', [
                'error' => $e->getMessage(),
                'paymentId' => $request->input('id'),
            ]);

            return response('Error', 500);
        }
    }

    /**
     * Process a successful payment.
     */
    private function processPaidPayment(Payment $payment): void
    {
        $metadata = $payment->metadata;
        $user = User::findOrFail($metadata->user_id);

        DB::transaction(function () use ($user, $metadata, $payment) {
            // Create a new support time purchase record
            SupportTimePurchase::create([
                'user_id' => $user->id,
                'quantity' => $metadata->quantity,
                'support_type' => $metadata->support_type,
                'details' => $metadata->details,
                'payment_id' => $payment->id,
                'amount' => $payment->amount->value,
                'status' => 'completed',
            ]);

            // Log the successful purchase
            Log::info('New support time purchase created', [
                'user_id' => $user->id,
                'payment_id' => $payment->id,
            ]);
        });
    }

    /**
     * Process a cancelled payment.
     */
    private function processCancelledPayment(Payment $payment): void
    {
        // Log the cancellation of the payment
        Log::info('Payment was canceled', [
            'payment_id' => $payment->id,
        ]);
    }

    /**
     * Process a failed or expired payment.
     */
    private function processFailedPayment(Payment $payment): void
    {
        // Log the failure or expiration of the payment
        Log::info('Payment failed or expired', [
            'payment_id' => $payment->id,
        ]);
    }

    /**
     * Log unhandled payment status.
     */
    private function logUnhandledStatus(Payment $payment): void
    {
        // Log unexpected payment statuses
        Log::info('Unhandled payment status', [
            'status' => $payment->status,
            'payment_id' => $payment->id,
        ]);
    }
}
