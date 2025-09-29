<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Services\BrevoMailerService;

class BiddingEmailService
{
    protected $brevoMailer;

    public function __construct(BrevoMailerService $brevoMailer)
    {
        $this->brevoMailer = $brevoMailer;
    }

    /**
     * Send payment success email using Brevo
     */
    public function sendPaymentSuccessEmail($bid, $customer)
    {
        try {
            $to = [
                'email' => $customer->email,
                'name' => $customer->name
            ];

            $subject = 'Bid Payment Confirmed - ' . ($bid['product_name'] ?? 'Auction Item');

            // Render the email view
            $htmlContent = view('shop::emails.bidding.payment_success', [
                'bid' => $bid,
                'customerName' => $customer->name,
                'product' => $bid['product_name'] ?? 'Bidding Product',
                'paymentDate' => now()->format('F jS, Y g:i A'),
                'paymentIntentId' => $bid['stripe_payment_intent_id'] ?? 'N/A'
            ])->render();

            // Send via Brevo
            $success = $this->brevoMailer->sendEmail($to, $subject, $htmlContent, [
                'customer_name' => $customer->name,
                'product_name' => $bid['product_name'] ?? 'Auction Item',
                'bid_amount' => number_format($bid['bid_amount'], 2),
                'bid_id' => $bid['sku'] ?? 'N/A'
            ]);

            if ($success) {
                Log::info('Payment success email sent via Brevo to: ' . $customer->email);
                return true;
            } else {
                // Fallback to Laravel mailer
                return $this->sendFallbackPaymentSuccessEmail($bid, $customer);
            }

        } catch (\Exception $e) {
            Log::error('Failed to send payment success email via Brevo: ' . $e->getMessage());
            return $this->sendFallbackPaymentSuccessEmail($bid, $customer);
        }
    }

    /**
     * Send payment failed email using Brevo
     */
    public function sendPaymentFailedEmail($bid, $customer, $errorMessage = null)
    {
        try {
            $to = [
                'email' => $customer->email,
                'name' => $customer->name
            ];

            $subject = 'Bid Payment Failed - ' . ($bid['product_name'] ?? 'Auction Item');

            $htmlContent = view('shop::emails.bidding.payment_failed', [
                'bid' => $bid,
                'customerName' => $customer->name,
                'errorMessage' => $errorMessage,
                'product' => $bid['product_name'] ?? 'Bidding Product'
            ])->render();

            $success = $this->brevoMailer->sendEmail($to, $subject, $htmlContent, [
                'customer_name' => $customer->name,
                'product_name' => $bid['product_name'] ?? 'Auction Item',
                'bid_amount' => number_format($bid['bid_amount'], 2),
                'error_message' => $errorMessage
            ]);

            if ($success) {
                Log::info('Payment failed email sent via Brevo to: ' . $customer->email);
                return true;
            } else {
                return $this->sendFallbackPaymentFailedEmail($bid, $customer, $errorMessage);
            }

        } catch (\Exception $e) {
            Log::error('Failed to send payment failed email via Brevo: ' . $e->getMessage());
            return $this->sendFallbackPaymentFailedEmail($bid, $customer, $errorMessage);
        }
    }

    /**
     * Send outbid notification email using Brevo
     */
    public function sendOutbidNotification($bid, $customer, $newBidAmount)
    {
        try {
            $to = [
                'email' => $customer->email,
                'name' => $customer->name
            ];

            $subject = 'You\'ve Been Outbid - ' . ($bid['product_name'] ?? 'Auction Item');

            $htmlContent = view('shop::emails.bidding.outbid_notification', [
                'customerName' => $customer->name,
                'productName' => $bid['product_name'] ?? 'Auction Item',
                'currentBid' => $newBidAmount,
                'yourBid' => $bid['bid_amount'],
                'auctionEnd' => $bid['auction_end'] ?? 'N/A',
                'productUrl' => route('shop.bidding.bidding_single', ['id' => $bid['bidding_id']])
            ])->render();

            $success = $this->brevoMailer->sendEmail($to, $subject, $htmlContent, [
                'customer_name' => $customer->name,
                'product_name' => $bid['product_name'] ?? 'Auction Item',
                'new_bid_amount' => number_format($newBidAmount, 2),
                'your_bid_amount' => number_format($bid['bid_amount'], 2),
                'product_url' => route('shop.bidding.bidding_single', ['id' => $bid['bidding_id']])
            ]);

            if ($success) {
                Log::info('Outbid notification sent via Brevo to: ' . $customer->email);
                return true;
            } else {
                return $this->sendFallbackOutbidNotification($bid, $customer, $newBidAmount);
            }

        } catch (\Exception $e) {
            Log::error('Failed to send outbid notification via Brevo: ' . $e->getMessage());
            return $this->sendFallbackOutbidNotification($bid, $customer, $newBidAmount);
        }
    }

    /**
     * Fallback methods using Laravel Mail
     */
    private function sendFallbackPaymentSuccessEmail($bid, $customer)
    {
        try {
            Mail::send('shop::emails.bidding.payment_success', [
                'bid' => $bid,
                'customerName' => $customer->name,
                'product' => $bid['product_name'] ?? 'Bidding Product',
                'paymentDate' => now()->format('F jS, Y g:i A'),
                'paymentIntentId' => $bid['stripe_payment_intent_id'] ?? 'N/A'
            ], function ($message) use ($customer, $bid) {
                $message->to($customer->email, $customer->name)
                        ->subject('Bid Payment Confirmed - ' . ($bid['product_name'] ?? 'Auction Item'));
            });

            Log::info('Fallback payment success email sent to: ' . $customer->email);
            return true;
        } catch (\Exception $e) {
            Log::error('Fallback email also failed: ' . $e->getMessage());
            return false;
        }
    }

    private function sendFallbackPaymentFailedEmail($bid, $customer, $errorMessage)
    {
        try {
            Mail::send('shop::emails.bidding.payment_failed', [
                'bid' => $bid,
                'customerName' => $customer->name,
                'errorMessage' => $errorMessage,
                'product' => $bid['product_name'] ?? 'Bidding Product'
            ], function ($message) use ($customer, $bid) {
                $message->to($customer->email, $customer->name)
                        ->subject('Bid Payment Failed - ' . ($bid['product_name'] ?? 'Auction Item'));
            });

            Log::info('Fallback payment failed email sent to: ' . $customer->email);
            return true;
        } catch (\Exception $e) {
            Log::error('Fallback email also failed: ' . $e->getMessage());
            return false;
        }
    }

    private function sendFallbackOutbidNotification($bid, $customer, $newBidAmount)
    {
        try {
            Mail::send('shop::emails.bidding.outbid_notification', [
                'customerName' => $customer->name,
                'productName' => $bid['product_name'] ?? 'Auction Item',
                'currentBid' => $newBidAmount,
                'yourBid' => $bid['bid_amount'],
                'auctionEnd' => $bid['auction_end'] ?? 'N/A',
                'productUrl' => route('shop.bidding.bidding_single', ['id' => $bid['bidding_id']])
            ], function ($message) use ($customer, $bid) {
                $message->to($customer->email, $customer->name)
                        ->subject('You\'ve Been Outbid - ' . ($bid['product_name'] ?? 'Auction Item'));
            });

            Log::info('Fallback outbid notification sent to: ' . $customer->email);
            return true;
        } catch (\Exception $e) {
            Log::error('Fallback email also failed: ' . $e->getMessage());
            return false;
        }
    }
}
