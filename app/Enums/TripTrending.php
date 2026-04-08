<?php 

namespace App\Enums;

enum TripTrending: int
{
    case TRENDING = 1;
    case NOTTRENDING = 0;
    
    // ميزة الـ Enum: تقدر تعمل ميثود تجيب لك اللون أو النص
    public function label(): string
    {
        return match($this) {
            self::TRENDING => __("messages.trending"),
            self::NOTTRENDING =>  __("messages.nottrending"),
        };
    }
}