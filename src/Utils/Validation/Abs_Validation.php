<?php

declare(strict_types=1);

namespace Payment_API\Utils\Validation;

abstract class Abs_Validation
{
    protected function santizeData(array $userInput): array
    {
        $sanitizedData = [];

        foreach ($userInput as $inputField => $inputValue) {
            $sanitizedData[$inputField] = filter_var($inputValue, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $sanitizedData;
    }
}
