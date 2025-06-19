<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết thanh toán</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #000;
            margin: 40px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 22px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .section-title {
            background-color: #dbeafe;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            color: #1e3a8a;
        }

        .highlight {
            font-weight: bold;
            color: #1c1c1c;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .no-border th, .no-border td {
            border: none;
            padding-top: 0;
            padding-bottom: 0;
        }
    </style>
</head>
<body>
    <h2>Chi tiết thanh toán #{{ $history->id }}</h2>

    <table>
        <tr><th colspan="2" class="section-title">Thông tin hóa đơn</th></tr>
        <tr><th>Mã hóa đơn</th><td>{{ $history->payment_id }}</td></tr>
        <tr><th>Trạng thái</th><td>{{ ucfirst($history->payment->status ?? 'Chưa xác định') }}</td></tr>
    </table>

    <table>
        <tr><th colspan="2" class="section-title">Thông tin bệnh nhân</th></tr>
        <tr><th>Họ tên</th><td>{{ optional($history->payment->patient)->full_name ?? '---' }}</td></tr>
        <tr><th>Email</th><td>{{ optional($history->payment->patient)->email ?? '---' }}</td></tr>
        <tr><th>SĐT</th><td>{{ optional($history->payment->patient)->phone ?? '---' }}</td></tr>
        <tr><th>Ngày sinh</th><td>{{ optional(optional($history->payment->appointment->patient)->date_of_birth)->format('d/m/Y') ?? '---' }}</td></tr>
    </table>

    <table>
        <tr><th colspan="2" class="section-title">Thông tin dịch vụ</th></tr>
        <tr><th>Tên dịch vụ</th><td>{{ optional($history->payment->service)->name ?? '---' }}</td></tr>
        <tr><th>Mô tả</th><td>{{ optional($history->payment->service)->description ?? '---' }}</td></tr>
    </table>

    <table>
        <tr><th colspan="2" class="section-title">Thông tin bác sĩ</th></tr>
        <tr><th>Họ tên</th><td>{{ optional($history->payment->doctor->user)->full_name ?? '---' }}</td></tr>
        <tr><th>Chuyên môn</th><td>{{ optional($history->payment->doctor)->specialization ?? '---' }}</td></tr>
        <tr><th>Phòng ban</th><td>{{ optional($history->payment->doctor->department)->name ?? '---' }}</td></tr>
    </table>

    <table>
        <tr><th colspan="2" class="section-title">Chi tiết thanh toán</th></tr>
        <tr><th>Số tiền</th><td class="highlight">{{ number_format($history->amount, 0, ',', '.') }} ₫</td></tr>
        <tr><th>Phương thức</th><td>{{ $history->payment_method ?? '---' }}</td></tr>
        <tr><th>Ngày thanh toán</th>
            <td>
                {{ $history->payment_date ? \Carbon\Carbon::parse($history->payment_date)->format('d/m/Y H:i') : 'Chưa thanh toán' }}
            </td>
        </tr>
    </table>

    <table>
        <tr><th colspan="2" class="section-title">Khuyến mãi (nếu có)</th></tr>
        <tr><th>Tên chương trình</th><td>{{ optional($history->payment->promotion)->title ?? 'Không áp dụng' }}</td></tr>
        <tr><th>Giảm giá (%)</th><td>{{ optional($history->payment->promotion)->discount_percentage ?? 0 }}%</td></tr>
    </table>
</body>
</html>
