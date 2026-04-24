<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Observers\PaymentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

#[ObservedBy(PaymentObserver::class)]
class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        "booking_id",
        "amount",
        "currency",
        "gateway",
        "transaction_id",
        "status",
        "payload"
    ];
    protected $casts = [
        "amount"  => "decimal:2",
        "status"  => PaymentStatus::class, // ربط الـ Enum هنا
        "payload" => "json", 
    ];
    public function booking():BelongsTo
    {
        return $this->belongsTo(Booking::class,"booking_id");
    }
    public function completeBooking(){
        $availability = $this->booking->tourAvailability;
        if (($availability->booked + $this->booking->adults_count) > $availability->capacity  && $this->status === PaymentStatus::CANCELLED) {
            // هنا كارثة: الراجل دفع والأماكن طارت!
            // 1. كمل الدفع عادي كـ Payment
            // 2. خلي حالة الحجز: MANUAL_INTERVENTION_REQUIRED أو OVERBOOKED
            // 3. ابعت Alert فوراً للأدمن
            Log::info("اصعب سيناريو ممكن يحصل ي معلم توجع دي");
        }else{
            $this->status=PaymentStatus::CAPTURED;
            $this->save();
        }
    }
    public function failedBooking(){
        $this->status=PaymentStatus::FAILED;
        $this->save();
    }


}
