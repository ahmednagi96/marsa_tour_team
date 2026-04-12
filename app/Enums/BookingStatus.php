<?php 
namespace App\Enums;

enum BookingStatus: string
{
    case PENDING = 'pending';     // لسه مكملش دفع
    case CONFIRMED = 'confirmed'; // دفع والحجز اتأكد
    case CANCELLED = 'cancelled'; // العميل ألغى
    case EXPIRED = 'expired';     // الـ 15 دقيقة خلصوا ومدفعش
    case COMPLETED = 'completed'; // الرحلة تمت بسلام
    case REFUNDED = 'refunded';   // الفلوس رجعت للعميل
}