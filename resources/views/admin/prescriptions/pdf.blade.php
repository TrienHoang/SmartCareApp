<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn thuốc #{{ $prescription->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .clinic-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 5px;
        }

        .clinic-info {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }

        .prescription-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-top: 15px;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            width: 120px;
            display: inline-block;
        }

        .patient-doctor-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .patient-info,
        .doctor-info {
            width: 48%;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .medicine-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .medicine-table th,
        .medicine-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .medicine-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }

        .medicine-table .text-center {
            text-align: center;
        }

        .medicine-table .text-right {
            text-align: right;
        }

        .total-row {
            background-color: #f0f8ff;
            font-weight: bold;
        }

        .notes-section {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }

        .notes-title {
            font-weight: bold;
            color: #856404;
            margin-bottom: 10px;
        }

        .instructions {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .instructions h4 {
            color: #dc3545;
            margin-bottom: 10px;
        }

        .instructions ul {
            margin-left: 20px;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            margin-bottom: 5px;
        }

        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="clinic-name">PHÒNG KHÁM ĐA KHOA SMARTCARE</div>
        <div class="clinic-info">
            Địa chỉ: 123 Trịnh Văn Bô, Phương Canh, Nam Từ Liêm, TP. Hà Nội<br>
            Điện thoại: 09856458248 | Email: smartcare@gmail.com
        </div>
        <div class="prescription-title">ĐƠN THUỐC</div>
        <div style="font-size: 14px; margin-top: 10px;">Số: {{ str_pad($prescription->id, 6, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="patient-doctor-info">
        <div class="patient-info">
            <div class="section-title">THÔNG TIN BỆNH NHÂN</div>
            <div class="info-row">
                <span class="info-label">Họ và tên:</span>
                <span>{{ $prescription->medicalRecord->appointment->patient->full_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Giới tính:</span>
                <span>{{ $prescription->medicalRecord->appointment->patient->gender ?? 'Không xác định' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ngày sinh:</span>
                <span>
                    @if ($prescription->medicalRecord->appointment->patient->date_of_birth)
                        {{ \Carbon\Carbon::parse($prescription->medicalRecord->appointment->patient->date_of_birth)->format('d/m/Y') }}
                        ({{ \Carbon\Carbon::parse($prescription->medicalRecord->appointment->patient->date_of_birth)->age }}
                        tuổi)
                    @else
                        Không xác định
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Điện thoại:</span>
                <span>{{ $prescription->medicalRecord->appointment->patient->phone }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Địa chỉ:</span>
                <span>{{ $prescription->medicalRecord->appointment->patient->address ?? 'Không xác định' }}</span>
            </div>
        </div>

        <div class="doctor-info">
            <div class="section-title">THÔNG TIN BÁC SĨ</div>
            <div class="info-row">
                <span class="info-label">Bác sĩ:</span>
                <span>{{ $prescription->medicalRecord->appointment->doctor->user->full_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Chuyên môn:</span>
                <span>{{ $prescription->medicalRecord->appointment->doctor->specialization }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ngày kê đơn:</span>
                <span>{{ $prescription->prescribed_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Hồ sơ BA:</span>
                <span>{{ $prescription->medicalRecord->code }}</span>
            </div>
        </div>
    </div>

    @if ($prescription->medicalRecord->diagnosis)
        <div class="info-section">
            <div class="section-title">CHẨN ĐOÁN</div>
            <div style="padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
                {{ $prescription->medicalRecord->diagnosis }}
            </div>
        </div>
    @endif

    <div class="section-title">DANH SÁCH THUỐC</div>
    <table class="medicine-table">
        <thead>
            <tr>
                <th width="5%">STT</th>
                <th width="30%">Tên thuốc</th>
                <th width="10%">Đơn vị</th>
                <th width="8%">SL</th>
                <th width="12%">Đơn giá</th>
                <th width="12%">Thành tiền</th>
                <th width="12%">Ngày SX</th>
                <th width="23%">Cách dùng</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($prescription->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->medicine->name }}</strong>
                        @if ($item->medicine->dosage)
                            <br><small style="color: #666;">{{ $item->medicine->dosage }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->medicine->unit }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->medicine->price, 3, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->quantity * $item->medicine->price, 3, ',', '.') }}
                    </td>
                    <td class="text-center">{{ $item->medicine->created_at->format('d/m/Y') }}</td>
                    <td>{{ $item->usage_instructions ?: 'Theo chỉ dẫn của bác sĩ' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-center"><strong>TỔNG CỘNG</strong></td>
                <td class="text-right">
                    <strong>{{ number_format(
                        $prescription->items->sum(function ($item) {
                            return $item->quantity * $item->medicine->price;
                        }),
                        3,
                        ',',
                        '.',
                    ) }}
                        VNĐ</strong>
                </td>
                <td colspan="2"></td>
            </tr>
        </tfoot>

    </table>

    @if ($prescription->notes)
        <div class="notes-section">
            <div class="notes-title">GHI CHÚ CỦA BÁC SĨ:</div>
            <div>{{ $prescription->notes }}</div>
        </div>
    @endif

    <div class="instructions">
        <h4>🚨 LƯU Ý QUAN TRỌNG KHI SỬ DỤNG THUỐC:</h4>
        <ul>
            <li><strong>Sử dụng đúng liều lượng:</strong> Uống thuốc đúng số lượng và thời gian theo chỉ dẫn của bác sĩ
            </li>
            <li><strong>Không tự ý thay đổi:</strong> Không tự ý tăng, giảm liều hoặc ngừng thuốc mà không có sự đồng ý
                của bác sĩ</li>
            <li><strong>Theo dõi tác dụng phụ:</strong> Nếu có triệu chứng bất thường, hãy liên hệ ngay với bác sĩ</li>
            <li><strong>Bảo quản thuốc:</strong> Để thuốc ở nơi khô ráo, thoáng mát, tránh ánh sáng trực tiếp</li>
            <li><strong>Hạn sử dụng:</strong> Kể từ 6 tháng trước khi sản xuất</li>
            <li><strong>Tái khám:</strong> Đến tái khám đúng lịch hẹn để theo dõi hiệu quả điều trị</li>
        </ul>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <div><strong>BỆNH NHÂN</strong></div>
            <div>(Ký và ghi rõ họ tên)</div>
            <div class="signature-line"></div>
            <div>{{ $prescription->medicalRecord->appointment->patient->full_name }}</div>
        </div>

        <div class="signature-box">
            <div><strong>BÁC SĨ KÊ ĐơN</strong></div>
            <div>(Ký và ghi rõ họ tên)</div>
            <div class="signature-line"></div>
            <div>{{ $prescription->medicalRecord->appointment->doctor->user->full_name }}</div>
        </div>
    </div>

    <div class="footer">
        <div style="font-size: 10px; color: #666; margin-top: 30px;">
            Đơn thuốc được in vào lúc {{ now()->format('H:i d/m/Y') }}<br>
            Đây là đơn thuốc điện tử, có giá trị như đơn thuốc giấy
        </div>
    </div>
</body>

</html>
