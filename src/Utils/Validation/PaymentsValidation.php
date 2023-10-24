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

    public array $validationErrors;

    public PaymentsEntity $paymentsEntity;

    public function __construct(protected ?array $requestContent = null)
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

    private function generateUPID(): null
    {
        $amount = $this->sanitizedData['amount'] ?? null;
        if (is_null($amount)) {
            return null;
        }

        $upid = 'pay' . bin2hex($amount);
        $this->upid = substr($upid, 0, 20);
    }

    private function validatePaymentAmount(): null
    {
        $amount = $this->sanitizedData['amount'] ?? null;
        if (is_null($amount)) {
            return null;
        }

        $amount = filter_var($amount, FILTER_VALIDATE_FLOAT);

        if ($amount === false) {
            $this->validationErrors['amount'] = "Invalid payment amount";
        }

        $this->amount = $amount;
    }

    private function validatePaymentStatus(): null
    {
        $email = $this->sanitizedData['email'] ?? null;
        if (is_null($email)) {
            return null;
        }

        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($email === false) {
            $this->validationErrors['email'] = "Please enter a valid email address";
        }

        $this->email = $email;

        // Add extra validation using any third-party email validation api service
    }

    public function getEntities(): paymentsEntity
    {
        $paymentsEntity = new paymentsEntity;

        $this->upid ?? $paymentsEntity->setUPID($this->upid);
        $this->amount ?? $paymentsEntity->setAmount($this->amount);
        $this->status ?? $paymentsEntity->setStatus($this->status);
        $this->type ?? $paymentsEntity->setType($this->type);

        return $paymentsEntity;
    }
}
