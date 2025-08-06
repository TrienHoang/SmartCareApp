<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phiếu Thanh Toán - {{ $appointment->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #007bff;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 18px;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 11px;
            margin-bottom: 3px;
        }

        .receipt-title {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 15px 0 10px;
        }

        .receipt-number {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 20px;
        }

        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }

        .info-left,
        .info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 10px;
        }

        .info-left {
            border-right: 1px solid #dee2e6;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .info-table {
            width: 100%;
            font-size: 11px;
        }

        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .info-table .label {
            font-weight: bold;
            width: 40%;
        }

        .service-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .service-table th,
        .service-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }

        .service-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .service-table .text-center {
            text-align: center;
        }

        .service-table .text-right {
            text-align: right;
        }

        .total-section {
            float: right;
            width: 50%;
            margin-bottom: 30px;
        }

        .total-table {
            width: 100%;
            font-size: 12px;
        }

        .total-table td {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        .total-table .total-row {
            border-top: 2px solid #333;
            font-weight: bold;
            font-size: 14px;
        }

        .total-table .total-row td {
            padding: 10px 0;
            border-bottom: none;
        }

        .signature-section {
            clear: both;
            display: table;
            width: 100%;
            margin-top: 40px;
        }

        .signature-left,
        .signature-right {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .signature-box {
            padding: 20px;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .signature-note {
            font-size: 10px;
            margin-bottom: 60px;
        }

        .signature-name {
            font-weight: bold;
            border-top: 1px solid #333;
            padding-top: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 10px;
            color: #6c757d;
        }

        .badge {
            background-color: #28a745;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }

        .clearfix {
            clear: both;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>PHÒNG KHÁM ĐA KHOA SMARTCARE</h1>
            <p>Địa chỉ: 123 Trịnh Văn Bô, Phương Canh, Nam Từ Liêm, TP. Hà Nội</p>
            <p>Điện thoại: 09856458248 | Email: smartcare@gmail.com</p>

            <div class="receipt-title">PHIẾU THANH TOÁN</div>
            <div class="receipt-number">Số: PT{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        <!-- Thông tin khách hàng và lịch hẹn -->
        <div class="info-section">
            <div class="info-left">
                <div class="section-title">Thông tin bệnh nhân</div>
                <table class="info-table">
                    <tr>
                        <td class="label">Họ tên:</td>
                        <td>{{ $appointment->patient->full_name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Số điện thoại:</td>
                        <td>{{ $appointment->patient->phone ?? 'Chưa cập nhật' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email:</td>
                        <td>{{ $appointment->patient->email }}</td>
                    </tr>
                    <tr>
                        <td class="label">Địa chỉ:</td>
                        <td>{{ $appointment->patient->address ?? 'Chưa cập nhật' }}</td>
                    </tr>
                </table>
            </div>
            <div class="info-right">
                <div class="section-title">Thông tin lịch hẹn</div>
                <table class="info-table">
                    <tr>
                        <td class="label">Mã lịch hẹn:</td>
                        <td>LH{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Bác sĩ:</td>
                        <td>{{ $appointment->doctor->user->full_name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Thời gian khám:</td>
                        <td>{{ date('d/m/Y H:i', strtotime($appointment->appointment_time)) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Ngày thanh toán:</td>
                        <td>{{ date('d/m/Y H:i', strtotime($appointment->payment->paid_at)) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Chi tiết dịch vụ -->
        <div class="section-title">Chi tiết dịch vụ</div>
        <table class="service-table">
            <thead>
                <tr>
                    <th width="8%">STT</th>
                    <th width="50%">Tên dịch vụ</th>
                    <th width="18%">Đơn giá</th>
                    <th width="8%">SL</th>
                    <th width="18%">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>{{ $appointment->service->name }}</td>
                    <td class="text-right">{{ number_format($appointment->service->price, 0, ',', '.') }} VNĐ</td>
                    <td class="text-center">1</td>
                    <td class="text-right">{{ number_format($appointment->service->price, 0, ',', '.') }} VNĐ</td>
                </tr>
            </tbody>
        </table>

        <!-- Tổng tiền -->
        <div class="total-section">
            <table class="total-table">
                @if ($appointment->payment->promotion)
                    <tr>
                        <td>Giảm giá ({{ $appointment->payment->promotion->code }}):</td>
                        <td class="text-right">
                            -{{ number_format(($appointment->service->price * $appointment->payment->promotion->discount_percentage) / 100, 0, ',', '.') }}
                            VNĐ
                        </td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td><strong>TỔNG CỘNG:</strong></td>
                    <td class="text-right"><strong>{{ number_format($appointment->payment->amount, 0, ',', '.') }}
                            VNĐ</strong></td>
                </tr>
                <tr>
                    <td>Phương thức thanh toán:</td>
                    <td class="text-right">
                        @switch($appointment->payment->payment_method)
                            @case('cash')
                                Tiền mặt
                            @break

                            @case('vnpay')
                                Ngân hàng VNPay
                            @break

                            @case('card')
                                Thẻ ngân hàng
                            @break

                            @default
                                {{ $appointment->payment->payment_method }}
                        @endswitch
                    </td>
                </tr>
                <tr>
                    <td>Trạng thái:</td>
                    <td class="text-right">
                        <span class="badge">Đã thanh toán</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="clearfix"></div>

        <!-- Ghi chú -->
        @if ($appointment->payment->note)
            <div style="margin-bottom: 20px;">
                <div class="section-title">Ghi chú</div>
                <p>{{ $appointment->payment->note }}</p>
            </div>
        @endif

        <!-- Chữ ký -->
        <div class="signature-section">
            <div class="signature-left">
                <div class="signature-box">
                    <div class="signature-title">NGƯỜI THANH TOÁN</div>
                    <div class="signature-note">(Ký, ghi rõ họ tên)</div>
                    <div class="signature-name">{{ $appointment->patient->full_name }}</div>
                </div>
            </div>
            <div class="signature-right">
                <div class="signature-box" style="text-align: center;">
                    <div class="signature-title" style="font-weight: bold; margin-bottom: 5px;">THU NGÂN</div>
                    <div class="signature-note" style="font-style: italic; margin-bottom: 10px;">(Ký, ghi rõ họ tên)
                    </div>
                    <img src="{{ public_path('admin/assets/daumoc/daumoc1.png') }}" alt="Dấu mộc công ty"
                        style="height: 80px; margin: 0 auto 10px auto; opacity: 0.8; display: block;">
                    <div class="signature-name" style="margin-top: 5px;">{{ auth()->user()->full_name }}</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Phiếu được in lúc: {{ date('d/m/Y H:i:s') }}</p>
            <p>Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi!</p>
        </div>
    </div>
</body>

</html>
