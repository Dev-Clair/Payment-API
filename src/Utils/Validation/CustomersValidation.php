<?php

declare(strict_types=1);

namespace Payment_API\Utils\Validation;

use Payment_API\Entity\CustomersEntity;

class CustomersValidation extends Abs_Validation
{
    private string $ucid;

    private string $name;

    private string $email;

    private string $password;

    private string $address;

    private array $sanitizedData;

    public array $validationErrors;

    public CustomersEntity $customersEntity;

    public function __construct(protected ?array $requestContent = null)
    {
        $this->sanitizedData = $this->santizeData($this->requestContent);
        $this->validateRequestContent();
    }

    private function validateRequestContent(): void
    {
        $this->generateUCID();
        $this->validateCustomerName();
        $this->validateCustomerEmail();
        $this->validateCustomerPassword();
        $this->validateCustomerAddress();
    }

    private function generateUCID(): null
    {
        $name = $this->sanitizedData['name'] ?? null;
        if (is_null($name)) {
            return null;
        }

        $ucid = 'cus' . bin2hex($name);
        $this->ucid = substr($ucid, 0, 20);
    }

    private function validateCustomerName(): null
    {
        $name = $this->sanitizedData['name'] ?? null;
        if (is_null($name)) {
            return null;
        }

        $regex_pattern = '/^[A-Za-z]+(?:\s[A-Za-z]+)?$/';
        $name = filter_var($name, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => $regex_pattern]]);

        if ($name === false) {
            $this->validationErrors['name'] = "Name should contain only letters and spaces; please enter a valid first and/or last name";
        }

        $this->name = $name;
    }

    private function validateCustomerEmail(): null
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

    private function validateCustomerPassword(): null
    {
        $password = $this->sanitizedData['password'] ?? null;
        if (is_null($password)) {
            return null;
        }

        $confirm_password = $this->sanitizedData['confirm_password'] ?? null;
        if (is_null($confirm_password)) {
            return null;
        }

        if (empty($password)) {
            $this->validationErrors['password'] = "Password is empty; please enter password";
        }

        if ($password !== $confirm_password) {
            $this->validationErrors['password'] = "Passwords do not match; please enter a valid password";
        }

        $this->password = $password;
    }

    private function validateCustomerAddress(): null
    {
        $address = $this->sanitizedData['address'] ?? null;
        if (is_null($address)) {
            return null;
        }

        if (!is_string($address)) {
            $this->validationErrors['address'] = "Please enter a valid home or office address";
        }

        $this->address = $address;

        // Add extra validation using any third-party email validation api service
    }

    public function getEntities(): CustomersEntity
    {
        $customersEntity = new CustomersEntity;

        $this->ucid ?? $customersEntity->setUCID($this->ucid);
        $this->name ?? $customersEntity->setName($this->name);
        $this->email ?? $customersEntity->setEmail($this->email);
        $this->password ?? $customersEntity->setPassword($this->password);
        $this->address ?? $customersEntity->setAddress($this->address);

        return $customersEntity;
    }
}
