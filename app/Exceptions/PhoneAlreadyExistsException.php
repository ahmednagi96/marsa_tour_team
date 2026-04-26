<?php

namespace App\Exceptions;

use Exception;

class PhoneAlreadyExistsException extends Exception
{
    /**
     * تحديد كود الحالة الافتراضي للتعارض
     */
    protected $code = 409;

    public function __construct($message = null)
    {
        // إذا لم يتم تمرير رسالة، نستخدم الرسالة الافتراضية من ملف الترجمة
        parent::__construct($message ?: __('auth.phone_already_registered'));
    }

    /**
     * ميثود اختيارية إذا كنت تريد جلب الكود في الـ Handler
     */
    public function getStatusCode()
    {
        return $this->code;
    }
}