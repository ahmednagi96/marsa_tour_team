<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <style>
        .container { font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; }
        .card { background: #ffffff; padding: 30px; border-radius: 10px; border-top: 5px solid #dc3545; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .btn { background-color: #dc3545; color: white !important; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1 style="color: #dc3545;">عذراً، فشلت عملية الدفع</h1>
            <p>أهلاً {{ $booking->user->name }}،</p>
            <p>للأسف لم تكتمل عملية الدفع لحجزك رقم <strong>#{{ $booking->id }}</strong>.</p>
            <p>يرجى المحاولة مرة أخرى أو التواصل مع البنك الخاص بك للتأكد من سبب الرفض.</p>
            
            <a href="{{ url('/tours') }}" class="btn">حاول الحجز مرة أخرى</a>
        </div>
    </div>
</body>
</html>