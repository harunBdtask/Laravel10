<?php

namespace App\Exceptions;

use Throwable;

class DeleteNotPossibleException extends \Exception
{
    public function __construct($message = 'This data can not delete', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}