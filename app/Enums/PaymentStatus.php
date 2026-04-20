<?php 

namespace App\Enums;

enum PaymentStatus: string
{
    case INITIATED = 'INITIATED'; // أول ما بنكريت العملية
    case CAPTURED  = 'CAPTURED';  // العملية تمت بنجاح والفلوس اتحصلت
    case FAILED    = 'FAILED';    // العملية فشلت
    case CANCELLED = 'CANCELLED'; // العميل كنسل
    case PENDING   = 'PENDING';   // في انتظار الرد

    // دالة مساعدة لو محتاج تجيب النص بالعربي في الفرونت إند
    public function label(): string
    {
        return match($this) {
            self::INITIATED => __('payments.status.initiated'),
            self::CAPTURED  => __('payments.status.captured'),
            self::FAILED    => __('payments.status.failed'),
            self::CANCELLED => __('payments.status.cancelled'),
            self::PENDING   => __('payments.status.pending'),
        };
    }
}