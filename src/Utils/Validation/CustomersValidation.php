<?php

declare(strict_types=1);

namespace Payment_API\Utils\Validation;

use Payment_API\Entity\CustomersEntity;
use Payment_API\Enums\CustomerType;

class CustomersValidation extends Abs_Validation
{
    private array $sanitizedData;

    public array $validationError;

    public array $validationResult;

    public CustomersEntity $customersEntity;

    public function __construct(protected array $requestContent, protected string $requestMethod)
    {
        $this->sanitizedData = $this->santizeData($this->requestContent);
        $this->validateRequestContent();
    }

    private function validateRequestContent(): void
    {
        $this->validateCustomerName();
        $this->validateCustomerEmail();
        $this->validateCustomerPhone();
        $this->validateCustomerPassword();
        $this->validateCustomerAddress();
        $this->validateCustomerType();
    }

    private function getUCID(): void
    {
        if ($this->requestMethod === "POST") {
            $this->generateUCID();
        }

        return;
    }

    private function generateUCID(): void
    {
        $customer_name = $this->sanitizedData['customer_name'];
        if (empty($customer_name)) {
            $this->validationError['customer_name'] = "Name is empty; Please enter a valid first and/or last name";
            return;
        }

        $ucid = 'cus_' . bin2hex($customer_name);
        $this->validationResult['ucid'] = substr($ucid, 0, 20);
    }

    private function validateCustomerName(): void
    {
        $customer_name = $this->sanitizedData['customer_name'];
        if (empty($customer_name)) {
            $this->validationError['customer_name'] = "Name field is empty; please enter a valid first and/or last name";
            return;
        }

        $regex_pattern = '/^[A-Za-z]+(?:\s[A-Za-z]+)?$/';
        $customer_name = filter_var($customer_name, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => $regex_pattern]]);

        if ($customer_name === false) {
            $this->validationError['customer_name'] = "Name should contain only letters and spaces; Please enter a valid first and/or last name";
            return;
        }

        $this->validationResult['customer_name'] = strtoupper($customer_name);
    }

    private function validateCustomerEmail(): void
    {
        $customer_email = $this->sanitizedData['customer_email'];
        if (empty($customer_email)) {
            $this->validationError['customer_email'] = "Email field is empty; please enter a valid email address";
            return;
        }

        $customer_email = filter_var($customer_email, FILTER_VALIDATE_EMAIL);

        if ($customer_email === false) {
            $this->validationError['customer_email'] = "Please enter a valid email address";
            return;
        }

        $this->validationResult['customer_email'] = $customer_email;

        // Add extra validation using any third-party email validation service
    }

    private function validateCustomerPhone(): void
    {
        $customer_phone = $this->sanitizedData['customer_phone'];

        $customer_phone = preg_replace("/[^0-9]/", "", $customer_phone);

        if (strlen($customer_phone) < 11) {
            $this->validationError['customer_phone'] = "Invalid phone number format; please enter a valid phone/mobile number";
            return;
        }

        $this->validationResult['customer_phone'] = (int) $customer_phone;
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

        $this->validationResult['customer_password'] = $customer_password;
    }

    private function validateCustomerAddress(): void
    {
        $customer_address = $this->sanitizedData['customer_address'];
        if (empty($customer_address)) {
            $this->validationError['customer_address'] = "Customer address field is empty; please enter a valid home or office address";
            return;
        }

        if (!is_string($customer_address)) {
            $this->validationError['customer_address'] = "Invalid variable type; please enter a valid home or office address";
            return;
        }

        $this->validationResult['customer_address'] = $customer_address;

        // Add extra validation using any third-party geo-location validation service
    }

    public function validateCustomerType(): void
    {
        $customer_type = $this->sanitizedData['customer_type'];
        if (empty($customer_type)) {
            $this->validationError['customer_type'] = "Customer type field is empty; please enter a valid customer type";
            return;
        }

        if ($customer_type !== CustomerType::IND->value && $customer_type !== CustomerType::ORG->value) {
            $this->validationError['customer_type'] = "Please enter a valid customer type";
            return;
        }

        $this->validationResult['customer_type'] = $customer_type;
    }

    public function createCustomerEntity(CustomersEntity $customerEntity): CustomersEntity
    {
        $this->getUCID();

        if (isset($this->validationResult['ucid'])) {
            $customerEntity->setUCID($this->validationResult['ucid']);
        }

        if (isset($this->validationResult['customer_name'])) {
            $customerEntity->setCustomerName($this->validationResult['customer_name']);
        }

        if (isset($this->validationResult['customer_email'])) {
            $customerEntity->setCustomerEmail($this->validationResult['customer_email']);
        }

        if (isset($this->validationResult['customer_phone'])) {
            $customerEntity->setCustomerPhone($this->validationResult['customer_phone']);
        }

        if (isset($this->validationResult['customer_password'])) {
            $customerEntity->setCustomerPassword($this->validationResult['customer_password']);
        }

        if (isset($this->validationResult['customer_address'])) {
            $customerEntity->setCustomerAddress($this->validationResult['customer_address']);
        }

        if (isset($this->validationResult['customer_type'])) {
            $customerEntity->setCustomerType($this->validationResult['customer_type']);
        }

        return $customerEntity;
    }

    public function updateCustomerEntity(CustomersEntity $customerEntity): CustomersEntity
    {
        if (isset($this->validationResult['customer_name'])) {
            $customerEntity->setCustomerName($this->validationResult['customer_name']);
        }

        if (isset($this->validationResult['customer_email'])) {
            $customerEntity->setCustomerEmail($this->validationResult['customer_email']);
        }

        if (isset($this->validationResult['customer_phone'])) {
            $customerEntity->setCustomerPhone($this->validationResult['customer_phone']);
        }

        if (isset($this->validationResult['customer_password'])) {
            $customerEntity->setCustomerPassword($this->validationResult['customer_password']);
        }

        if (isset($this->validationResult['customer_address'])) {
            $customerEntity->setCustomerAddress($this->validationResult['customer_address']);
        }

        if (isset($this->validationResult['customer_type'])) {
            $customerEntity->setCustomerType($this->validationResult['customer_type']);
        }

        return $customerEntity;
    }
}
