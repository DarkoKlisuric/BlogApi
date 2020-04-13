<?php

namespace App\Exception;

use Throwable;

/**
 * Class InvalidConfirmationTokenException
 * @package App\Exception
 */
class InvalidConfirmationTokenException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Confirmation token is invalid';

    /**
     * InvalidConfirmationTokenException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($this->message, $code, $previous);
    }
}