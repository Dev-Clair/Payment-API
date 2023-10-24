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

    private PaymentStatus $status;

    private PaymentType $type;

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
        $status = $this->sanitizedData['status'];
        if (empty($status)) {
            return;
        }

        if ($status !== PaymentStatus::PAID || $status !== PaymentStatus::PENDING) {
            $this->validationError['status'] = "Please enter a valid status";
            return;
        }

        $this->status = $this->validationResult['status'] = $status;
    }

    public function validatePaymentType(): void
    {
        $type = $this->sanitizedData['type'];
        if (empty($type)) {
            return;
        }

        if ($type !== PaymentType::CREDIT || $type !== PaymentType::DEBIT) {
            $this->validationError['type'] = "Please enter a valid payment type";
            return;
        }

        $this->type = $this->validationResult['type'] = $type;
    }

    public function getEntities(): PaymentsEntity
    {
        $paymentsEntity = new PaymentsEntity;

        $this->upid ?? $paymentsEntity->setUPID($this->upid);
        $this->amount ?? $paymentsEntity->setAmount($this->amount);
        $this->status ?? $paymentsEntity->setStatus($this->status);
        $this->type ?? $paymentsEntity->setType($this->type);

        return $paymentsEntity;
    }
}
