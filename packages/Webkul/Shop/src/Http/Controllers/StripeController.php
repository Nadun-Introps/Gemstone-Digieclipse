<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Shop\Http\Controllers\Controller;
use Stripe\Exception\ApiErrorException;

class StripeController extends Controller
{
    /**
     * Order repository instance
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * Create a new controller instance
     *
     * @param \Webkul\Sales\Repositories\OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Process Stripe payment
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(Request $request)
    {
        try {
            $cart = Cart::getCart();

            if (! $cart) {
                return response()->json([
                    'error' => 'Cart not found'
                ], 400);
            }

            $stripe = app(\Webkul\Payment\Payment\Stripe::class);

            $paymentIntent = $stripe->createPaymentIntent();

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
     * Handle successful payment
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success(Request $request)
    {
        try {
            $paymentIntentId = $request->get('payment_intent');

            if (! $paymentIntentId) {
                throw new \Exception('Payment intent ID is required');
            }

            $stripe = app(\Webkul\Payment\Payment\Stripe::class);
            $paymentIntent = $stripe->retrievePaymentIntent($paymentIntentId);

            if ($paymentIntent->status !== 'succeeded') {
                throw new \Exception('Payment was not successful');
            }

            // Process the order
            $order = $this->orderRepository->create(Cart::prepareDataForOrder());

            Cart::deActivateCart();

            session()->flash('order', $order);

            return redirect()->route('shop.checkout.onepage.success');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            return redirect()->route('shop.checkout.cart.index');
        }
    }

    /**
     * Handle payment cancellation
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        session()->flash('warning', 'Stripe payment was cancelled.');

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Handle Stripe webhooks
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                // Handle successful payment
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                // Handle failed payment
                break;
        }

        return response()->json(['status' => 'success']);
    }
}
