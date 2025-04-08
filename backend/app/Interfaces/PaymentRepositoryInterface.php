<?php

namespace App\Interfaces;

interface PaymentRepositoryInterface
{
    public function createPaymentIntent($amount, $user, $course);
    public function confirmPayment($paymentIntentId);
    public function getPaymentHistory($userId);
    public function handleWebhook();
}