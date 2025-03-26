<?php

namespace App\Repositories;

use App\Interfaces\PaymentRepositoryInterface;
use App\Models\Payment;
use Stripe\StripeClient;

class PaymentRepository implements PaymentRepositoryInterface
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.secret'));
    }

    public function createPaymentIntent($amount, $user, $course)
    {
        $intent = $this->stripe->paymentIntents->create([
            'amount' => $amount * 100,
            'currency' => 'usd',
            'metadata' => [
                'user_id' => $user->id,
                'course_id' => $course->id
            ]
        ]);

        return [
            'client_secret' => $intent->client_secret,
            'id' => $intent->id
        ];
    }

    public function confirmPayment($paymentIntentId)
    {
        $intent = $this->stripe->paymentIntents->retrieve($paymentIntentId);
        $payment = Payment::create ([
            'user_id' => $intent->metadata->user_id,
            'course_id' => $intent->metadata->course_id,
            'payment_id' => $intent->id,
            'amount' => $intent->amount / 100,
            'currency' => $intent->currency,
            'status' => $intent->status,
            'metadata' => $intent->metadata
        ]);

        return $payment;
    }

    public function getPaymentHistory($userId)
    {
        return Payment::where('user_id', $userId)->get();
    }

    public function handleWebhook()
    {
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = $this->stripe->webhooks->constructEvent(
                $payload, $sig_header
            );
        } catch (\UnexpectedValueException $e) {
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            http_response_code(400);
            exit();
        }
    }
}
