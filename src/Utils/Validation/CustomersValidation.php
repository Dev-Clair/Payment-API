<?php

declare(strict_types=1);

namespace Payment_API\Utils\Validation;

use Payment_API\Entity\CustomersEntity;
use Payment_API\Enums\CustomerType;

class CustomersValidation extends Abs_Validation
{
    private string $ucid;

    private string $name;

    private string $email;

    private int $phone;

    private string $password;

    private string $address;

    private CustomerType $type;

    private array $sanitizedData;

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
        $this->validateCustomerPhone();
        $this->validateCustomerPassword();
        $this->validateCustomerAddress();
        $this->validateCustomerType();
    }

    private function generateUCID(): void
    {
        $name = $this->sanitizedData['name'];
        if (empty($name)) {
            return;
        }

        $ucid = 'cus_' . bin2hex($name);
        $this->ucid = substr($ucid, 0, 20);
    }

    private function validateCustomerName(): void
    {
        $name = $this->sanitizedData['name'];
        if (empty($name)) {
            $this->validationError['name'] = "Name is empty; Please enter a valid first and/or last name";
            return;
        }

        $regex_pattern = '/^[A-Za-z]+(?:\s[A-Za-z]+)?$/';
        $name = filter_var($name, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => $regex_pattern]]);

        if ($name === false) {
            $this->validationError['name'] = "Name should contain only letters and spaces; Please enter a valid first and/or last name";
            return;
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
            return;
        }

        $this->email = $this->validationError['email'] = $email;

        // Add extra validation using any third-party email validation service
    }

    private function validateCustomerPhone(): void
    {
        $phone = $this->sanitizedData['phone'];

        $phone = preg_replace("/[^0-9]/", "", $phone);

        if (empty($phone)) {
            $this->validationError['phone'] = "Phone number is empty; Please enter a phone/mobile number";
            return;
        }

        $regex_pattern = '/^[0-9]{11,}$/';

        if (!preg_match($regex_pattern, $phone)) {
            $this->validationError['phone'] = "Invalid phone number format; Please enter a valid phone/mobile number";
            return;
        }

        $this->phone = $this->validationResult['phone'] = $phone;
    }

    private function validateCustomerPassword(): void
    {
        $password = $this->sanitizedData['password'];
        if (empty($password)) {
            $this->validationError['password'] = "Password is empty; Please enter password";
            return;
        }

        $confirm_password = $this->sanitizedData['confirm_password'];
        if (empty($confirm_password)) {
            $this->validationError['confirm_password'] = "Password is empty; Please enter password";
            return;
        }

        if ($password !== $confirm_password) {
            $this->validationError['password'] = "Passwords do not match; Please enter a valid password";
            return;
        }

        $this->password = $this->validationResult['password'] = $password;
    }

    private function validateCustomerAddress(): void
    {
        $address = $this->sanitizedData['address'];
        if (empty($address)) {
            $this->validationError['address'] = "Address field is empty; Please enter a valid home or office address";
            return;
        }

        if (!is_string($address)) {
            $this->validationError['address'] = "Invalid type; Please enter a valid home or office address";
            return;
        }

        $this->address = $this->validationResult['address'] = $address;

        // Add extra validation using any third-party geo-location validation service
    }

    public function validateCustomerType(): void
    {
        $type = $this->sanitizedData['type'];
        if (empty($type)) {
            return;
        }

        if ($type !== CustomerType::IND || $type !== CustomerType::ORG) {
            $this->validationError['type'] = "Please enter a valid customer type";
            return;
        }

        $this->type = $this->validationResult['type'] = $type;
    }

    public function getEntities(): CustomersEntity
    {
        $customersEntity = new CustomersEntity;

        $this->ucid ?? $customersEntity->setUCID($this->ucid);
        $this->name ?? $customersEntity->setName($this->name);
        $this->email ?? $customersEntity->setEmail($this->email);
        $this->phone ?? $customersEntity->setPhone($this->phone);
        $this->password ?? $customersEntity->setPassword($this->password);
        $this->address ?? $customersEntity->setAddress($this->address);
        $this->type ?? $customersEntity->setType($this->type);

        return $customersEntity;
    }
}
