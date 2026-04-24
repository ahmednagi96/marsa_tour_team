<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <style>
        .container { font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f4f7f6; }
        .card { background: #ffffff; padding: 30px; border-radius: 10px; border-top: 5px solid #28a745; text-align: center; }
        .btn { background-color: #2b78e4; color: white !important; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px; font-weight: bold; }
        .footer { font-size: 12px; color: #999; margin-top: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1 style="color: #28a745;">تم تأكيد حجزك بنجاح!</h1>
            <p>أهلاً {{ $booking->user->name }}،</p>
            <p>لقد استلمنا دفعتك لرحلة <strong>{{ $booking->tour->title }}</strong>.</p>
            <p>رقم الحجز الخاص بك هو: <strong>#{{ $booking->id }}</strong></p>
            
            @if($invoiceUrl)
                <p>يمكنك عرض وتحميل فاتورة الدفع الرسمية من Tap عبر الرابط التالي:</p>
                <a href="{{ $invoiceUrl }}" class="btn">تحميل فاتورة الدفع</a>
            @endif

            <p style="margin-top: 30px;">لقد أرفقنا لك أيضاً نسخة PDF من تفاصيل الحجز مع هذا الإيميل.</p>
        </div>
        <div class="footer">
            جميع الحقوق محفوظة &copy; {{ date('Y') }} لموقعنا.
        </div>
    </div>
</body>
</html>