<?php

declare(strict_types=1);

namespace Payment_API\Utils\Validation;

use Payment_API\Entity\MethodsEntity;
use Payment_API\Enums\MethodType;

class MethodsValidation extends Abs_Validation
{
    private array $sanitizedData;

    public array $validationError;

    public array $validationResult;

    public MethodsEntity $methodsEntity;

    public function __construct(protected array $requestContent, protected string $requestMethod)
    {
        $this->sanitizedData = $this->santizeData($this->requestContent);
        $this->validateRequestContent();
    }

    private function validateRequestContent(): void
    {
        $this->validateMethodName();
        $this->validateMethodType();
    }

    private function getUMID(): void
    {
        if ($this->requestMethod === "POST") {
            $this->generateUMID();
        }

        return;
    }

    private function generateUMID(): void
    {
        $method_name = $this->sanitizedData['method_name'];
        if (empty($method_name)) {
            return;
        }

        $umid = 'met_' . bin2hex($method_name);
        $this->validationResult['umid'] = substr($umid, 0, 20);
    }

    private function validateMethodName(): void
    {
        $method_name = $this->sanitizedData['method_name'];
        if (empty($method_name)) {
            return;
        }

        if (!is_string($method_name)) {
            $this->validationError['method_name'] = "Invalid payment method name";
            return;
        }

        $this->validationResult['method_name'] = strtoupper($method_name);
    }

    public function validateMethodType(): void
    {
        $method_type = $this->sanitizedData['method_type'];
        if (empty($method_type)) {
            return;
        }

        if ($method_type !== MethodType::CARD->value && $method_type !== MethodType::BANK->value) {
            $this->validationError['method_type'] = "Please enter a valid method type";
            return;
        }

        $this->validationResult['method_type'] = $method_type;
    }

    public function createMethodEntity(MethodsEntity $methodEntity): MethodsEntity
    {
        $this->getUMID();

        if (isset($this->validationResult['umid'])) {
            $methodEntity->setUMID($this->validationResult['umid']);
        }

        if (isset($this->validationResult['method_name'])) {
            $methodEntity->setMethodName($this->validationResult['method_name']);
        }

        if (isset($this->validationResult['method_type'])) {
            $methodEntity->setMethodType($this->validationResult['method_type']);
        }

        return $methodEntity;
    }

    public function updateMethodEntity(MethodsEntity $methodEntity): MethodsEntity
    {
        if (isset($this->validationResult['method_name'])) {
            $methodEntity->setMethodName($this->validationResult['method_name']);
        }

        if (isset($this->validationResult['method_type'])) {
            $methodEntity->setMethodType($this->validationResult['method_type']);
        }

        return $methodEntity;
    }
}
