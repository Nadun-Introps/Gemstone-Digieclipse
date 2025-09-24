<?php

namespace Webkul\Payment\Payment;

use Illuminate\Support\Facades\Config;
use Stripe\Stripe as StripeAPI;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use Webkul\Checkout\Facades\Cart;
use Webkul\Payment\Payment\Payment;

class Stripe extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code = 'stripe';

    /**
     * Stripe API instance
     *
     * @var \Stripe\Stripe
     */
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeAPI();
        $this->stripe->setApiKey(Config::get('services.stripe.secret'));
    }

    /**
     * Get redirect URL
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return route('shop.stripe.process');
    }

    /**
     * Get Stripe publishable key
     *
     * @return string
     */
    public function getPublishableKey()
    {
        return Config::get('services.stripe.key');
    }

    /**
     * Create payment intent
     *
     * @return \Stripe\PaymentIntent
     */
    public function createPaymentIntent()
    {
        $cart = Cart::getCart();

        try {
            return PaymentIntent::create([
                'amount' => $cart->grand_total * 100, // Convert to cents
                'currency' => strtolower($cart->cart_currency_code),
                'metadata' => [
                    'cart_id' => $cart->id,
                    'customer_id' => $cart->customer_id,
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Retrieve payment intent
     *
     * @param string $paymentIntentId
     * @return \Stripe\PaymentIntent
     */
    public function retrievePaymentIntent($paymentIntentId)
    {
        try {
            return PaymentIntent::retrieve($paymentIntentId);
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Check if payment method is available
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->getConfigData('active') &&
               !empty(Config::get('services.stripe.key')) &&
               !empty(Config::get('services.stripe.secret'));
    }

    /**
     * Get payment method title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getConfigData('title') ?: 'Stripe';
    }

    /**
     * Get payment method description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getConfigData('description') ?: 'Pay securely with your credit card via Stripe';
    }

    /**
     * Get payment method sort order
     *
     * @return int
     */
    public function getSortOrder()
    {
        return $this->getConfigData('sort');
    }
}
