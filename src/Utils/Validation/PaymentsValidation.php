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

        $this->paymentValidationMiddleware();
    }

    private function paymentValidationMiddleware(): void
    {
        $expectedFields = ['amount', 'payment_status', 'payment_type'];

        $suppliedFields = array_keys($this->sanitizedData);

        foreach ($suppliedFields as $field) {
            if (!in_array($field, $expectedFields)) {
                $this->validationError[] = "{$field} key is invalid";
            }
        }

        if (count($suppliedFields) < count($expectedFields)) {
            $this->validationError['bad request'] = [
                'expected' => $expectedFields,
                'supplied' => $suppliedFields
            ];
        }

        if (count($suppliedFields) > count($expectedFields)) {
            $this->validationError['bad request'] = [
                'expected' => count($expectedFields) . "fields",
                'supplied' => count($suppliedFields) . "fields"
            ];
        }

        if (empty($this->validationError)) {
            $this->validateRequestContent();
        }
    }

    private function validateRequestContent(): void
    {
        $this->validatePaymentAmount();
        $this->validatePaymentStatus();
        $this->validatePaymentType();
    }

    private function validatePaymentAmount(): void
    {
        $amount = (float) $this->sanitizedData['amount'];
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
            $this->validationError['payment_status'] = "Payment status field is empty; please enter a valid payment status";
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
            $this->validationError['payment_type'] = "Payment type filed is empty; please enter a valid payment type";
            return;
        }

        if ($payment_type !== PaymentType::CREDIT->value && $payment_type !== PaymentType::DEBIT->value) {
            $this->validationError['payment_type'] = "Please enter a valid payment type";
            return;
        }

        $this->validationResult['payment_type'] = $payment_type;
    }

    private function getPaymentUPID(): void
    {
        if ($this->requestMethod === "POST") {
            $this->generatePaymentUPID();
        }

        return;
    }

    private function generatePaymentUPID(): void
    {
        $amount = $this->sanitizedData['amount'];
        if (empty($amount)) {
            $this->validationError['amount'] = "Invalid; amount field is empty";
            return;
        }

        $upid = 'pay_' . bin2hex($amount);

        $this->validationResult['upid'] = substr($upid, 0, 20);
    }

    public function createPaymentEntity(PaymentsEntity $paymentEntity): PaymentsEntity
    {
        $this->getPaymentUPID();

        if (isset($this->validationResult['upid'])) {
            $paymentEntity->setUPID($this->validationResult['upid']);
        }

        if (isset($this->validationResult['amount'])) {
            $paymentEntity->setAmount((float) $this->validationResult['amount']);
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
