<?php 
namespace App\Exceptions\Booking;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InsufficientSeatsException extends Exception
{
    use ApiResponse;
    /**
     * Report the exception (Optional: for logging)
     */
    public function report(): bool
    {
        // ممكن تبعت تنبيه للسيستم هنا لو حابب
        return false; 
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): JsonResponse
    {
        return $this->error(__('messages.no_seats_available'),422);
    }
}