<?php 
namespace App\Enums;

enum BookingStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';
    case COMPLETED = 'completed';
    case REFUNDED = 'refunded';

    // الدالة الفخمة للترجمة
    public function label(): string
    {
        return match($this) {
            self::PENDING   => __('status.pending'),
            self::CONFIRMED => __('status.confirmed'),
            self::CANCELLED => __('status.cancelled'),
            self::EXPIRED   => __('status.expired'),
            self::COMPLETED => __('status.completed'),
            self::REFUNDED  => __('status.refunded'),
        };
    }
}