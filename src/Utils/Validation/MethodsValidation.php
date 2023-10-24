<?php

declare(strict_types=1);

namespace Payment_API\Utils\Validation;

use Payment_API\Entity\MethodsEntity;
use Payment_API\Enums\MethodType;

class MethodsValidation extends Abs_Validation
{
    private string $umid;

    private string $name;

    private MethodType $type;

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
        $name = $this->sanitizedData['name'];
        if (empty($name)) {
            return;
        }

        $umid = 'met_' . bin2hex($name);
        $this->umid = substr($umid, 0, 20);
    }

    private function validateMethodName(): void
    {
        $name = $this->sanitizedData['name'];
        if (empty($name)) {
            return;
        }

        if (!is_string($name)) {
            $this->validationError['amount'] = "Invalid payment method name";
            return;
        }

        $this->name = $this->validationResult['name'] = $name;
    }

    public function validateMethodType(): void
    {
        $type = $this->sanitizedData['type'];
        if (empty($type)) {
            return;
        }

        if ($type !== MethodType::CARD || $type !== MethodType::BANK) {
            $this->validationError['type'] = "Please enter a valid method type";
            return;
        }

        $this->type = $this->validationResult['type'] = $type;
    }

    public function getEntities(): MethodsEntity
    {
        $methodsEntity = new MethodsEntity;

        $this->umid ?? $methodsEntity->setUMID($this->umid);
        $this->name ?? $methodsEntity->setName($this->name);
        $this->type ?? $methodsEntity->setType($this->type);

        return $methodsEntity;
    }
}
