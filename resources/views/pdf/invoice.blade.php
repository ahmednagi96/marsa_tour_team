<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'XBRiyaz', sans-serif; direction: rtl; text-align: right; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #2b78e4; padding-bottom: 20px; }
        .title { color: #2b78e4; font-size: 28px; }
        .details-table { width: 100%; line-height: inherit; text-align: right; border-collapse: collapse; margin-top: 20px; }
        .details-table th { background: #f8f9fa; padding: 10px; border: 1px solid #dee2e6; }
        .details-table td { padding: 10px; border: 1px solid #dee2e6; }
        .total { font-weight: bold; font-size: 18px; color: #2b78e4; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div class="title">فاتورة حجز رقم #{{ $booking->id }}</div>
            <div>تاريخ الحجز: {{ $booking->created_at->format('Y-m-d') }}</div>
        </div>

        <table class="details-table">
            <tr>
                <th>العميل</th>
                <td>{{ $booking->user->name }}</td>
            </tr>
            <tr>
                <th>الرحلة</th>
                <td>{{ $booking->tour->title }}</td>
            </tr>
            <tr>
                <th>عدد الأفراد</th>
                <td>{{ $booking->adults_count }} فرد</td>
            </tr>
        </table>

        <table class="details-table" style="margin-top: 30px;">
            <thead>
                <tr>
                    <th>الوصف</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>سعر الرحلة (شامل الضرائب)</td>
                    <td class="total">{{ number_format($booking->total_price, 2) }} {{config('app.currency')}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>