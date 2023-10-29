<?php

declare(strict_types=1);

namespace Payment_API\Utils\Validation;

use Payment_API\Entity\CustomersEntity;
use Payment_API\Enums\CustomerType;

class CustomersValidation extends Abs_Validation
{
    private string $ucid;

    private string $customer_name;

    private string $customer_email;

    private int $customer_phone;

    private string $customer_password;

    private string $customer_address;

    private CustomerType $customer_type;

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
        $customer_name = $this->sanitizedData['customer_name'];
        if (empty($customer_name)) {
            return;
        }

        $ucid = 'cus_' . bin2hex($customer_name);
        $this->ucid = substr($ucid, 0, 20);
    }

    private function validateCustomerName(): void
    {
        $customer_name = $this->sanitizedData['customer_name'];
        if (empty($customer_name)) {
            $this->validationError['customer_name'] = "Name is empty; Please enter a valid first and/or last name";
            return;
        }

        $regex_pattern = '/^[A-Za-z]+(?:\s[A-Za-z]+)?$/';
        $customer_name = filter_var($customer_name, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => $regex_pattern]]);

        if ($customer_name === false) {
            $this->validationError['customer_name'] = "Name should contain only letters and spaces; Please enter a valid first and/or last name";
            return;
        }

        $this->customer_name = $this->validationResult['customer_name'] = $customer_name;
    }

    private function validateCustomerEmail(): void
    {
        $customer_email = $this->sanitizedData['customer_email'];
        if (empty($customer_email)) {
            return;
        }

        $customer_email = filter_var($customer_email, FILTER_VALIDATE_EMAIL);

        if ($customer_email === false) {
            $this->validationError['customer_email'] = "Please enter a valid email address";
            return;
        }

        $this->customer_email = $this->validationError['customer_email'] = $customer_email;

        // Add extra validation using any third-party email validation service
    }

    private function validateCustomerPhone(): void
    {
        $customer_phone = $this->sanitizedData['customer_phone'];

        $customer_phone = preg_replace("/[^0-9]/", "", $customer_phone);

        if (empty($customer_phone)) {
            $this->validationError['customer_phone'] = "Phone number is empty; Please enter a phone/mobile number";
            return;
        }

        $regex_pattern = '/^[0-9]{11,}$/';

        if (!preg_match($regex_pattern, $customer_phone)) {
            $this->validationError['customer_phone'] = "Invalid phone number format; Please enter a valid phone/mobile number";
            return;
        }

        $this->customer_phone = $this->validationResult['customer_phone'] = (int) $customer_phone;
    }

    private function validateCustomerPassword(): void
    {
        $customer_password = $this->sanitizedData['customer_password'];
        if (empty($customer_password)) {
            $this->validationError['customer_password'] = "Password is empty; Please enter password";
            return;
        }

        $confirm_customer_password = $this->sanitizedData['confirm_customer_password'];
        if (empty($confirm_customer_password)) {
            $this->validationError['confirm_customer_password'] = "Password is empty; Please enter password";
            return;
        }

        if ($customer_password !== $confirm_customer_password) {
            $this->validationError['customer_password'] = "Passwords do not match; Please enter a valid password";
            return;
        }

        $this->customer_password = $this->validationResult['customer_password'] = $customer_password;
    }

    private function validateCustomerAddress(): void
    {
        $customer_address = $this->sanitizedData['customer_address'];
        if (empty($customer_address)) {
            $this->validationError['customer_address'] = "Address field is empty; Please enter a valid home or office address";
            return;
        }

        if (!is_string($customer_address)) {
            $this->validationError['customer_address'] = "Invalid type; Please enter a valid home or office address";
            return;
        }

        $this->customer_address = $this->validationResult['customer_address'] = $customer_address;

        // Add extra validation using any third-party geo-location validation service
    }

    public function validateCustomerType(): void
    {
        $customer_type = $this->sanitizedData['customer_type'];
        if (empty($customer_type)) {
            return;
        }

        if ($customer_type !== CustomerType::IND || $customer_type !== CustomerType::ORG) {
            $this->validationError['customer_type'] = "Please enter a valid customer type";
            return;
        }

        $this->customer_type = $this->validationResult['customer_type'] = $customer_type;
    }

    public function getEntities(): CustomersEntity
    {
        $customersEntity = new CustomersEntity;

        $this->ucid ?? $customersEntity->setUCID($this->ucid);
        $this->customer_name ?? $customersEntity->setCustomerName($this->customer_name);
        $this->customer_email ?? $customersEntity->setCustomerEmail($this->customer_email);
        $this->customer_phone ?? $customersEntity->setCustomerPhone($this->customer_phone);
        $this->customer_password ?? $customersEntity->setCustomerPassword($this->customer_password);
        $this->customer_address ?? $customersEntity->setCustomerAddress($this->customer_address);
        $this->customer_type ?? $customersEntity->setCustomerType($this->customer_type);

        return $customersEntity;
    }
}
