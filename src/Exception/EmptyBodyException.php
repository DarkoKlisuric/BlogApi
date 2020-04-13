<?php

namespace App\Exception;

use Throwable;

class EmptyBodyException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'The body of the POST/PUT method cannot be empty!';

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($this->message, $code, $previous);
    }
}