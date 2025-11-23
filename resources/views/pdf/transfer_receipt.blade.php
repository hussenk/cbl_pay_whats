<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .container { width: 100%; }
        .header { text-align: center; margin-bottom: 20px; }
        .section { margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 6px 8px; border: 1px solid #ddd; }
        .qr { text-align: center; margin-top: 12px; }
    </style>
    <title>إيصال التحويل</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>إيصال التحويل</h2>
            <small>{{ $transaction->created_at->format('Y-m-d H:i') }}</small>
        </div>

        <div class="section">
            <table>
                <tr>
                    <td>رقم العملية</td>
                    <td>{{ $transaction->id }}</td>
                </tr>
                <tr>
                    <td>حساب المرسل</td>
                    <td>{{ $transaction->sender->number }} - {{ $transaction->sender->name }}</td>
                </tr>
                <tr>
                    <td>حساب المستفيد</td>
                    <td>{{ $transaction->receiver->number }} - {{ $transaction->receiver->name }}</td>
                </tr>
                <tr>
                    <td>المبلغ</td>
                    <td>{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}</td>
                </tr>
                <tr>
                    <td>الحالة</td>
                    <td>{{ $transaction->status }}</td>
                </tr>
            </table>
        </div>

        <div class="qr">
            <img src="{{ $qrDataUri }}" alt="QR">
            <div>امسح رمز الاستجابة السريع للتحقق من العملية</div>
        </div>
    </div>
</body>
</html>


