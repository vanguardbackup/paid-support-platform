<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Mail\InformCustomerMail;
use App\Mail\InformTeamMail;
use App\Models\SupportTimePurchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            $payment = Mollie::api()->payments->get($request->input('id'));

            match ($payment->status) {
                'paid' => $this->processPaidPayment($payment),
                'canceled' => $this->processCancelledPayment($payment),
                'failed', 'expired' => $this->processFailedPayment($payment),
                default => $this->logUnhandledStatus($payment),
            };

            return response('OK', 200);
        } catch (ApiException $e) {
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
            $existingPurchase = SupportTimePurchase::where('payment_id', $payment->id)->first();

            if ($existingPurchase) {
                if ($existingPurchase->status !== 'completed') {
                    $existingPurchase->status = 'completed';
                    $existingPurchase->save();
                    Log::info('Existing purchase status updated to completed', [
                        'payment_id' => $payment->id,
                        'user_id' => $user->id,
                    ]);
                }

                return;
            }

            $latestPurchase = $user->supportTimePurchases()
                ->whereNull('expired_at')
                ->where('support_type', $metadata->support_type)
                ->where('created_at', '>', now()->subMinutes(5))
                ->orderByDesc('created_at')
                ->first();

            if ($latestPurchase) {
                $latestPurchase->quantity += $metadata->quantity;
                $latestPurchase->amount += $payment->amount->value;
                $latestPurchase->save();

                Log::info('Support time purchase consolidated', [
                    'user_id' => $user->id,
                    'quantity' => $metadata->quantity,
                    'purchase_id' => $latestPurchase->id,
                ]);
            } else {
                $newPurchase = new SupportTimePurchase([
                    'user_id' => $user->id,
                    'quantity' => $metadata->quantity,
                    'support_type' => $metadata->support_type,
                    'details' => $metadata->details,
                    'payment_id' => $payment->id,
                    'amount' => $payment->amount->value,
                    'status' => 'completed',
                ]);
                $newPurchase->save();

                Log::info('New support time purchase created', [
                    'user_id' => $user->id,
                    'quantity' => $metadata->quantity,
                    'purchase_id' => $newPurchase->id,
                ]);
            }

            $this->sendNotificationEmails($user, $metadata, $payment->id);
        });
    }

    /**
     * Process a cancelled payment.
     */
    private function processCancelledPayment(Payment $payment): void
    {
        $metadata = $payment->metadata;
        $user = User::findOrFail($metadata->user_id);

        DB::transaction(function () use ($user, $payment) {
            $existingPurchase = SupportTimePurchase::where('payment_id', $payment->id)->first();

            if ($existingPurchase) {
                $existingPurchase->status = 'cancelled';
                $existingPurchase->save();

                Log::info('Support time purchase cancelled', [
                    'user_id' => $user->id,
                    'payment_id' => $payment->id,
                    'purchase_id' => $existingPurchase->id,
                ]);
            } else {
                Log::info('Cancelled payment webhook received for non-existent purchase', [
                    'payment_id' => $payment->id,
                    'user_id' => $user->id,
                ]);
            }
        });
    }

    /**
     * Process a failed or expired payment.
     */
    private function processFailedPayment(Payment $payment): void
    {
        Log::info('Payment failed or expired', [
            'status' => $payment->status,
            'payment_id' => $payment->id,
        ]);

        // Optionally, update any existing purchase record to 'failed' status
        $existingPurchase = SupportTimePurchase::where('payment_id', $payment->id)->first();
        if ($existingPurchase) {
            $existingPurchase->status = 'failed';
            $existingPurchase->save();
        }
    }

    /**
     * Log unhandled payment status.
     */
    private function logUnhandledStatus(Payment $payment): void
    {
        Log::info('Unhandled payment status received', [
            'status' => $payment->status,
            'payment_id' => $payment->id,
        ]);
    }

    /**
     * Send notification emails to the support team and the customer.
     */
    private function sendNotificationEmails(User $user, object $metadata, string $paymentId): void
    {
        Mail::to('support@vanguardbackup.com')->queue(new InformTeamMail(
            $user,
            $metadata->quantity,
            $metadata->support_type,
            $metadata->details,
            $paymentId
        ));

        Mail::to($user->email)->queue(new InformCustomerMail(
            $user,
            $metadata->quantity,
            $metadata->support_type,
            $metadata->details,
            $paymentId
        ));
    }
}
