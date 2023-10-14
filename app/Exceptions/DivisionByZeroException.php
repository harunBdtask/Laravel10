<?php

namespace App\Exceptions;

use Throwable;

class DivisionByZeroException extends \Exception
{
    public function __construct($message = "Division by Zero!", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}