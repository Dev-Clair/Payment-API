<?php

namespace Payment_API\Exception;

use BadMethodCallException;

class InvalidMethodCallException extends BadMethodCallException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
