<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class InvalidAmountException extends Exception
{

    public function __construct($message = "Invalid Amount!", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
