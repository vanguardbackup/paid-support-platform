<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\SupportTimePurchase;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Resources\Payment;
use Mollie\Laravel\Facades\Mollie;

/**
 * Handles support time purchase and related operations.
 */
class SupportTimePurchaseController extends Controller
{
    private const UNIT_PRICE = 30; // Price per hour of support time in GBP

    /**
     * Display the support time purchase form.
     */
    public function showPurchaseForm(): View
    {
        return view('support.purchase', ['unitPrice' => self::UNIT_PRICE]);
    }

    /**
     * Initiate the purchase of support time.
     */
    public function initiatePurchase(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'support_type' => ['required', 'string', 'in:technical,install,other'],
            'details' => ['nullable', 'string', 'max:500'],
            'terms' => ['required', 'accepted'],
        ]);

        $user = auth()->user();

        try {
            // Create a Mollie payment for the requested support time
            $payment = $this->createMolliePayment($user, $validated);

            // Redirect the user to Mollie's checkout page
            return redirect($payment->getCheckoutUrl(), 303);
        } catch (ApiException $e) {
            // Log Mollie API errors and return an error message
            Log::error('Mollie API error during payment initiation', ['error' => $e->getMessage()]);

            return redirect()->route('home')
                ->with('error', 'An error occurred while initiating your payment. Please try again.');
        } catch (Exception $e) {
            // Log general errors and return a generic error message
            Log::error('Error during support time purchase', ['error' => $e->getMessage()]);

            return redirect()->route('home')
                ->with('error', 'An unexpected error occurred. Please try again.');
        }
    }

    /**
     * Handle the payment callback from Mollie.
     */
    public function handlePaymentCallback(Request $request): RedirectResponse
    {
        try {
            // Retrieve the payment object using Mollie API
            $payment = Mollie::api()->payments->get($request->query('id'));

            if ($payment->isPaid()) {
                // If payment is successful, notify the user
                return redirect()->route('home')
                    ->with('success', 'Your payment has been successfully processed.');
            }

            if ($payment->isOpen() || $payment->isPending()) {
                // If payment is still pending, notify the user
                return redirect()->route('home')
                    ->with('info', 'Your payment is still being processed. Please wait.');
            }

            if ($payment->isCanceled() || $payment->isExpired() || $payment->isFailed()) {
                // If payment failed or was canceled, notify the user
                return redirect()->route('home')
                    ->with('error', 'Your payment could not be completed.');
            }

            // Handle unexpected payment statuses
            return redirect()->route('home')->with('error', 'Unexpected payment status.');
        } catch (ApiException $e) {
            // Log Mollie API errors and return an error message
            Log::error('Mollie API error during callback', ['error' => $e->getMessage()]);
            return redirect()->route('home')->with('error', 'An error occurred while processing your payment.');
        }
    }

    /**
     * Create a Mollie payment for support time purchase.
     */
    private function createMolliePayment(User $user, array $validated): Payment
    {
        $totalAmount = $validated['quantity'] * self::UNIT_PRICE;

        return Mollie::api()->payments->create([
            'amount' => [
                'currency' => 'GBP',
                'value' => number_format($totalAmount, 2, '.', ''),
            ],
            'description' => "Purchase of {$validated['quantity']} hour(s) of {$validated['support_type']} support",
            'redirectUrl' => route('support.payment.callback'),
            'webhookUrl' => route('webhooks.mollie'),
            'metadata' => [
                'user_id' => $user->id,
                'quantity' => $validated['quantity'],
                'support_type' => $validated['support_type'],
                'details' => $validated['details'],
            ],
        ]);
    }
}
