<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class PaymentFailedException extends Exception
{
    use ApiResponse;
    protected $details;

    public function __construct($message = "", $details = [])
    {
        parent::__construct($message);
        $this->details = $details;
    }

    public function render($request): JsonResponse
    {
        return $this->error($this->getMessage(), 400, $this->details);
    }
}
