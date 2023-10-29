<?php

declare(strict_types=1);

namespace Payment_API\Utils\Validation;

use Payment_API\Entity\MethodsEntity;
use Payment_API\Enums\MethodType;

class MethodsValidation extends Abs_Validation
{
    private string $umid;

    private string $method_name;

    private MethodType $method_type;

    private array $sanitizedData;

    public array $validationError;

    public array $validationResult;

    public MethodsEntity $methodsEntity;

    public function __construct(protected array $requestContent)
    {
        $this->sanitizedData = $this->santizeData($this->requestContent);
        $this->validateRequestContent();
    }

    private function validateRequestContent(): void
    {
        $this->generateUMID();
        $this->validateMethodName();
        $this->validateMethodType();
    }

    private function generateUMID(): void
    {
        $method_name = $this->sanitizedData['method_name'];
        if (empty($method_name)) {
            return;
        }

        $umid = 'met_' . bin2hex($method_name);
        $this->umid = substr($umid, 0, 20);
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

        $this->method_name = $this->validationResult['method_name'] = strtoupper($method_name);
    }

    public function validateMethodType(): void
    {
        $method_type = $this->sanitizedData['method_type'];
        if (empty($method_type)) {
            return;
        }

        if ($method_type !== MethodType::CARD || $method_type !== MethodType::BANK) {
            $this->validationError['method_type'] = "Please enter a valid method type";
            return;
        }

        $this->method_type = $this->validationResult['method_type'] = $method_type;
    }

    public function getEntities(): MethodsEntity
    {
        $methodsEntity = new MethodsEntity;

        $this->umid ?? $methodsEntity->setUMID($this->umid);
        $this->method_name ?? $methodsEntity->setMethodName($this->method_name);
        $this->method_type ?? $methodsEntity->setMethodType($this->method_type);

        return $methodsEntity;
    }
}
