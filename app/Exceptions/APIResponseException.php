<?php

namespace App\Exceptions;

use Exception;

class APIResponseException extends Exception
{
    public function __construct(string $message = '', int $code = 400, Exception $previous = null, public $error_source = [])
    {
        parent::__construct($message, $code, $previous);
    }

    public function render()
    {
        return response()->errorResponse($this->getCode(), $this->error_source, $this->getMessage());
    }
}