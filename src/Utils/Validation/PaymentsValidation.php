<?php

declare(strict_types=1);

namespace Payment_API\Utils\Validation;

use Payment_API\Entity\PaymentsEntity;
use Payment_API\Enums\PaymentStatus;
use Payment_API\Enums\PaymentType;

class PaymentsValidation extends Abs_Validation
{
    private array $sanitizedData;

    public array $validationError;

    public array $validationResult;

    public PaymentsEntity $paymentsEntity;

    public function __construct(protected array $requestContent, protected string $requestMethod)
    {
        $this->sanitizedData = $this->santizeData($this->requestContent);
        $this->validateRequestContent();
    }

    private function validateRequestContent(): void
    {
        $this->validatePaymentAmount();
        $this->validatePaymentStatus();
        $this->validatePaymentType();
    }

    private function getUPID(): void
    {
        if ($this->requestMethod === "POST") {
            $this->generateUPID();
        }

        return;
    }

    private function generateUPID(): void
    {
        $amount = $this->sanitizedData['amount'];
        if (empty($amount)) {
            $this->validationError['amount'] = "Invalid; amount field is empty";
            return;
        }

        $upid = 'pay_' . bin2hex($amount);
        $this->validationResult['amount'] = substr($upid, 0, 20);
    }

    private function validatePaymentAmount(): void
    {
        $amount = $this->sanitizedData['amount'];
        if (empty($amount)) {
            $this->validationError['amount'] = "Amount field is empty; please enter a valid amount";
            return;
        }

        $amount = filter_var($amount, FILTER_VALIDATE_FLOAT);

        if ($amount === false) {
            $this->validationError['amount'] = "Invalid amount";
            return;
        }

        $this->validationResult['amount'] = $amount;
    }

    private function validatePaymentStatus(): void
    {
        $payment_status = $this->sanitizedData['payment_status'];
        if (empty($payment_status)) {
            return;
        }

        if ($payment_status !== PaymentStatus::PAID->value && $payment_status !== PaymentStatus::PENDING->value) {
            $this->validationError['payment_status'] = "Please enter a valid payment status";
            return;
        }

        $this->validationResult['payment_status'] = $payment_status;
    }

    public function validatePaymentType(): void
    {
        $payment_type = $this->sanitizedData['payment_type'];
        if (empty($payment_type)) {
            return;
        }

        if ($payment_type !== PaymentType::CREDIT->value && $payment_type !== PaymentType::DEBIT->value) {
            $this->validationError['payment_type'] = "Please enter a valid payment type";
            return;
        }

        $this->validationResult['payment_type'] = $payment_type;
    }

    public function createPaymentEntity(PaymentsEntity $paymentEntity): PaymentsEntity
    {
        $this->getUPID();

        if (isset($this->validationResult['upid'])) {
            $paymentEntity->setUPID($this->validationResult['upid']);
        }

        if (isset($this->validationResult['amount'])) {
            $paymentEntity->setAmount($this->validationResult['amount']);
        }

        if (isset($this->validationResult['payment_status'])) {
            $paymentEntity->setPaymentStatus($this->validationResult['payment_status']);
        }

        if (isset($this->validationResult['payment_type'])) {
            $paymentEntity->setPaymentType($this->validationResult['payment_type']);
        }

        return $paymentEntity;
    }

    public function updatePaymentEntity(PaymentsEntity $paymentEntity): PaymentsEntity
    {
        if (isset($this->validationResult['payment_amount'])) {
            $paymentEntity->setAmount($this->validationResult['payment_amount']);
        }

        if (isset($this->validationResult['payment_status'])) {
            $paymentEntity->setPaymentStatus($this->validationResult['payment_status']);
        }

        if (isset($this->validationResult['payment_type'])) {
            $paymentEntity->setPaymentType($this->validationResult['payment_type']);
        }

        return $paymentEntity;
    }
}
