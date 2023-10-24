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

    public array $sanitizedData;

    public array $validationError;

    public array $validationResult;

    public CustomersEntity $customersEntity;

    public function __construct(protected array $requestContent)
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

    private function generateUCID(): void
    {
        $name = $this->sanitizedData['name'];
        if (empty($name)) {
            return;
        }

        $ucid = 'cus' . bin2hex($name);
        $this->ucid = substr($ucid, 0, 20);
    }

    private function validateCustomerName(): void
    {
        $name = $this->sanitizedData['name'];
        if (empty($name)) {
            return;
        }

        $regex_pattern = '/^[A-Za-z]+(?:\s[A-Za-z]+)?$/';
        $name = filter_var($name, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => $regex_pattern]]);

        if ($name === false) {
            $this->validationError['name'] = "Name should contain only letters and spaces; please enter a valid first and/or last name";
        }

        $this->name = $this->validationResult['name'] = $name;
    }

    private function validateCustomerEmail(): void
    {
        $email = $this->sanitizedData['email'];
        if (empty($email)) {
            return;
        }

        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($email === false) {
            $this->validationError['email'] = "Please enter a valid email address";
        }

        $this->email = $this->validationError['email'] = $email;

        // Add extra validation using any third-party email validation service
    }

    private function validateCustomerPassword(): void
    {
        $password = $this->sanitizedData['password'];

        if (empty($password)) {
            $this->validationError['password'] = "Password is empty; please enter password";
            return;
        }

        $confirm_password = $this->sanitizedData['confirm_password'];

        if (empty($confirm_password)) {
            $this->validationError['confirm_password'] = "Password is empty; please enter password";
            return;
        }

        if ($password !== $confirm_password) {
            $this->validationError['password'] = "Passwords do not match; please enter a valid password";
        }

        $this->password = $this->validationResult['password'] = $password;
    }

    private function validateCustomerAddress(): void
    {
        $address = $this->sanitizedData['address'];
        if (empty($address)) {
            return;
        }

        if (!is_string($address)) {
            $this->validationError['address'] = "Please enter a valid home or office address";
        }

        $this->address = $this->validationResult['address'] = $address;

        // Add extra validation using any third-party geo-location validation service
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
