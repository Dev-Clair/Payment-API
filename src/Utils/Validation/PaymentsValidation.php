<?php

declare(strict_types=1);

namespace Payment_API\Utils\Validation;

use Payment_API\Entity\PaymentsEntity;
use Payment_API\Enums\PaymentStatus;
use Payment_API\Enums\PaymentType;

class PaymentsValidation extends Abs_Validation
{
    private string $upid;

    private float $amount;

    private PaymentStatus $payment_status;

    private PaymentType $payment_type;

    private array $sanitizedData;

    public array $validationError;

    public array $validationResult;

    public PaymentsEntity $paymentsEntity;

    public function __construct(protected array $requestContent)
    {
        $this->sanitizedData = $this->santizeData($this->requestContent);
        $this->validateRequestContent();
    }

    private function validateRequestContent(): void
    {
        $this->generateUPID();
        $this->validatePaymentAmount();
        $this->validatePaymentStatus();
        $this->validatePaymentType();
    }

    private function generateUPID(): void
    {
        $amount = $this->sanitizedData['amount'];
        if (empty($amount)) {
            return;
        }

        $upid = 'pay_' . bin2hex($amount);
        $this->upid = substr($upid, 0, 20);
    }

    private function validatePaymentAmount(): void
    {
        $amount = $this->sanitizedData['amount'];
        if (empty($amount)) {
            return;
        }

        $amount = filter_var($amount, FILTER_VALIDATE_FLOAT);

        if ($amount === false) {
            $this->validationError['amount'] = "Invalid amount";
            return;
        }

        $this->amount = $this->validationResult['amount'] = $amount;
    }

    private function validatePaymentStatus(): void
    {
        $payment_status = $this->sanitizedData['payment_status'];
        if (empty($payment_status)) {
            return;
        }

        if ($payment_status !== PaymentStatus::PAID || $payment_status !== PaymentStatus::PENDING) {
            $this->validationError['payment_status'] = "Please enter a valid payment status";
            return;
        }

        $this->payment_status = $this->validationResult['payment_status'] = $payment_status;
    }

    public function validatePaymentType(): void
    {
        $payment_type = $this->sanitizedData['payment_type'];
        if (empty($payment_type)) {
            return;
        }

        if ($payment_type !== PaymentType::CREDIT || $payment_type !== PaymentType::DEBIT) {
            $this->validationError['payment_type'] = "Please enter a valid payment type";
            return;
        }

        $this->payment_type = $this->validationResult['payment_type'] = $payment_type;
    }

    public function getEntities(): PaymentsEntity
    {
        $paymentsEntity = new PaymentsEntity;

        $this->upid ?? $paymentsEntity->setUPID($this->upid);
        $this->amount ?? $paymentsEntity->setAmount($this->amount);
        $this->payment_status ?? $paymentsEntity->setPaymentStatus($this->payment_status);
        $this->payment_type ?? $paymentsEntity->setPaymentType($this->payment_type);

        return $paymentsEntity;
    }
}
