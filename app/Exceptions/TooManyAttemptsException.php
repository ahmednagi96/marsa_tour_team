<?php 
namespace App\Exceptions;

use Exception;

class TooManyAttemptsException extends Exception
{
    protected $seconds;

    public function __construct($message = "", $seconds = 0)
    {
        parent::__construct($message ?: 'Too many attempts.', 429);
        $this->seconds = $seconds;
    }

    public function getSeconds() {
        return (int) $this->seconds;
    }
}