<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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


}
