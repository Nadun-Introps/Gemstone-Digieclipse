<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webkul\Shop\Http\Controllers\Controller;
use App\Services\BiddingEmailService;

class BiddingStripeController extends Controller
{
    protected $biddingEmailService;

    public function __construct(BiddingEmailService $biddingEmailService)
    {
        $this->biddingEmailService = $biddingEmailService;
    }

    /**
     * Process bidding payment with Stripe
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processBiddingPayment(Request $request)
    {
        try {
            $bid = session('current_bid');

            if (! $bid) {
                return response()->json([
                    'error' => 'No active bid found'
                ], 400);
            }

            // Use USD instead of LKR as Stripe might not support LKR
            $stripe = app(\Webkul\Payment\Payment\Stripe::class);

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $bid['bid_amount'] * 100, // Convert to cents
                'currency' => 'usd', // Use USD instead of LKR
                'metadata' => [
                    'bid_id' => $bid['bid_id'],
                    'session_id' => $bid['session_id'],
                    'bidding_id' => $bid['bidding_id'],
                    'customer_id' => auth()->id(),
                    'type' => 'bidding',
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Handle successful bidding payment
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function biddingSuccess(Request $request)
    {
        try {
            $paymentIntentId = $request->get('payment_intent');
            $bid = session('current_bid');

            if (! $paymentIntentId || ! $bid) {
                throw new \Exception('Payment intent ID or bid data is missing');
            }

            $stripe = app(\Webkul\Payment\Payment\Stripe::class);
            $paymentIntent = $stripe->retrievePaymentIntent($paymentIntentId);

            if ($paymentIntent->status !== 'succeeded') {
                throw new \Exception('Payment was not successful. Status: ' . $paymentIntent->status);
            }

            // Update the bid payment status in database
            DB::table('bidding_user_bids')
                ->where('bub_id', $bid['bid_id'])
                ->update([
                    'payment_status' => 'paid',
                    'stripe_payment_intent_id' => $paymentIntentId,
                    'payment_details' => json_encode([
                        'payment_intent_id' => $paymentIntentId,
                        'amount' => $paymentIntent->amount / 100,
                        'currency' => $paymentIntent->currency,
                        'payment_method' => $paymentIntent->payment_method,
                        'status' => $paymentIntent->status,
                        'paid_at' => now()->toDateTimeString()
                    ]),
                    'updated_at' => now(),
                ]);

            // Send payment success email
            if (auth()->check()) {
                $customer = auth()->user();
                $this->biddingEmailService->sendPaymentSuccessEmail($bid, $customer);
            }

            // Clear the session
            session()->forget('current_bid');

            return redirect()->route('shop.bidding.success')
                ->with('success', 'Bid placed successfully! Payment confirmed.');

        } catch (\Exception $e) {
            // Send payment failed email if user was authenticated
            if (auth()->check() && isset($bid)) {
                $customer = auth()->user();
                $this->biddingEmailService->sendPaymentFailedEmail($bid, $customer, $e->getMessage());
            }

            session()->flash('error', 'Failed to process bid: ' . $e->getMessage());
            return redirect()->route('shop.bidding.checkout');
        }
    }

    /**
     * Handle Stripe webhook for bidding payments (for localhost testing)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleBiddingWebhook(Request $request)
    {
        \Log::info('Stripe Webhook Received', ['payload' => $request->all()]);

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            // For local development, you might want to skip signature verification
            // or use the Stripe CLI to test webhooks
            $event = null;

            if (app()->environment('local')) {
                // For local development, parse JSON directly
                $event = json_decode($payload);
            } else {
                // For production, verify webhook signature
                $endpoint_secret = config('services.stripe.webhook_secret');
                $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
            }

            \Log::info('Stripe Event Type: ' . $event->type);

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    $this->handleSuccessfulBiddingPayment($paymentIntent);
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    $this->handleFailedBiddingPayment($paymentIntent);
                    break;
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            \Log::error('Stripe Webhook Error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook processing failed'], 400);
        }
    }

    /**
     * Handle successful bidding payment from webhook
     *
     * @param \Stripe\PaymentIntent $paymentIntent
     */
    private function handleSuccessfulBiddingPayment($paymentIntent)
    {
        try {
            $metadata = $paymentIntent->metadata;
            $bidId = $metadata->bid_id ?? null;
            $sessionId = $metadata->session_id ?? null;

            if ($bidId) {
                // Update by bid ID
                DB::table('bidding_user_bids')
                    ->where('bub_id', $bidId)
                    ->update([
                        'payment_status' => 'paid',
                        'stripe_payment_intent_id' => $paymentIntent->id,
                        'payment_details' => json_encode([
                            'payment_intent_id' => $paymentIntent->id,
                            'amount' => $paymentIntent->amount / 100,
                            'currency' => $paymentIntent->currency,
                            'payment_method' => $paymentIntent->payment_method,
                            'status' => $paymentIntent->status,
                            'paid_at' => now()->toDateTimeString()
                        ]),
                        'updated_at' => now(),
                    ]);

                // Get bid details and send email
                $bidRecord = DB::table('bidding_user_bids')
                    ->where('bub_id', $bidId)
                    ->first();

                if ($bidRecord) {
                    $customer = DB::table('users')->where('id', $bidRecord->user_id)->first();
                    $biddingProduct = DB::table('bidding_products')->where('bid_pro_id', $bidRecord->bidding_id)->first();

                    $bidData = [
                        'bid_id' => $bidId,
                        'bidding_id' => $bidRecord->bidding_id,
                        'bid_amount' => $bidRecord->bid_amount,
                        'product_name' => $biddingProduct->product_name ?? 'Auction Item',
                        'created_at' => $bidRecord->created_at
                    ];

                    $this->biddingEmailService->sendPaymentSuccessEmail($bidData, $customer);
                }
            }

            \Log::info('Bidding payment updated successfully', [
                'payment_intent_id' => $paymentIntent->id,
                'bid_id' => $bidId,
                'session_id' => $sessionId
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to update bidding payment: ' . $e->getMessage());
        }
    }

    /**
     * Handle failed bidding payment from webhook
     *
     * @param \Stripe\PaymentIntent $paymentIntent
     */
    private function handleFailedBiddingPayment($paymentIntent)
    {
        try {
            $metadata = $paymentIntent->metadata;
            $bidId = $metadata->bid_id ?? null;
            $sessionId = $metadata->session_id ?? null;

            if ($bidId) {
                DB::table('bidding_user_bids')
                    ->where('bub_id', $bidId)
                    ->update([
                        'payment_status' => 'failed',
                        'payment_details' => json_encode([
                            'error' => $paymentIntent->last_payment_error ?? 'Payment failed',
                            'failed_at' => now()->toDateTimeString()
                        ]),
                        'updated_at' => now(),
                    ]);
            } elseif ($sessionId) {
                DB::table('bidding_user_bids')
                    ->where('session_id', $sessionId)
                    ->where('payment_status', 'pending')
                    ->update([
                        'payment_status' => 'failed',
                        'payment_details' => json_encode($paymentIntent->last_payment_error),
                        'updated_at' => now(),
                    ]);
            }

            \Log::warning('Bidding payment failed', [
                'payment_intent_id' => $paymentIntent->id,
                'bid_id' => $bidId,
                'session_id' => $sessionId
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to update failed bidding payment: ' . $e->getMessage());
        }
    }
}
