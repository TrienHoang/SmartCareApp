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

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                <div style="font-size: 14px; font-weight: bold; color: #2c5aa0; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">THÔNG TIN BỆNH NHÂN</div>
                
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="font-weight: bold; width: 120px; padding: 4px 0; color: #333;">Họ và tên:</td>
                        <td style="color: #333; padding: 4px 0;">{{ $prescription->medicalRecord->appointment->patient->full_name }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; width: 120px; padding: 4px 0; color: #333;">Giới tính:</td>
                        <td style="color: #333; padding: 4px 0;">{{ $prescription->medicalRecord->appointment->patient->gender ?? 'Không xác định' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; width: 120px; padding: 4px 0; color: #333;">Ngày sinh:</td>
                        <td style="color: #333; padding: 4px 0;">
                            @if ($prescription->medicalRecord->appointment->patient->date_of_birth)
                                {{ \Carbon\Carbon::parse($prescription->medicalRecord->appointment->patient->date_of_birth)->format('d/m/Y') }}
                                ({{ \Carbon\Carbon::parse($prescription->medicalRecord->appointment->patient->date_of_birth)->age }} tuổi)
                            @else
                                Không xác định
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; width: 120px; padding: 4px 0; color: #333;">Điện thoại:</td>
                        <td style="color: #333; padding: 4px 0;">{{ $prescription->medicalRecord->appointment->patient->phone }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; width: 120px; padding: 4px 0; color: #333; vertical-align: top;">Địa chỉ:</td>
                        <td style="color: #333; padding: 4px 0;">{{ $prescription->medicalRecord->appointment->patient->address ?? 'Không xác định' }}</td>
                    </tr>
                </table>
            </td>
            
            <td style="width: 50%; vertical-align: top; padding-left: 20px;">
                <div style="font-size: 14px; font-weight: bold; color: #2c5aa0; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">THÔNG TIN BÁC SĨ</div>
                
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="font-weight: bold; width: 120px; padding: 4px 0; color: #333;">Bác sĩ:</td>
                        <td style="color: #333; padding: 4px 0;">{{ $prescription->medicalRecord->appointment->doctor->user->full_name }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; width: 120px; padding: 4px 0; color: #333;">Chuyên môn:</td>
                        <td style="color: #333; padding: 4px 0;">{{ $prescription->medicalRecord->appointment->doctor->specialization }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; width: 120px; padding: 4px 0; color: #333;">Ngày kê đơn:</td>
                        <td style="color: #333; padding: 4px 0;">{{ $prescription->prescribed_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; width: 120px; padding: 4px 0; color: #333;">Hồ sơ BA:</td>
                        <td style="color: #333; padding: 4px 0;">{{ $prescription->medicalRecord->code }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

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
                {{-- <th width="12%">Đơn giá</th>
                <th width="12%">Thành tiền</th> --}}
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
                    {{-- <td class="text-right">{{ number_format($item->medicine->price, 3, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->quantity * $item->medicine->price, 3, ',', '.') }}
                    </td> --}}
                    <td class="text-center">{{ $item->medicine->created_at->format('d/m/Y') }}</td>
                    <td>{{ $item->usage_instructions ?: 'Theo chỉ dẫn của bác sĩ' }}</td>
                </tr>
            @endforeach
        </tbody>
        {{-- <tfoot>
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
        </tfoot> --}}

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
            <li><strong>Bảo quản thuốc:</strong> Để thuốc ở nơi khô ráo, thông thoáng, tránh ánh sáng trực tiếp</li>
            <li><strong>Hạn sử dụng:</strong> Kể từ 6 tháng trước khi sản xuất</li>
            <li><strong>Tái khám:</strong> Đến tái khám đúng lịch hẹn để theo dõi hiệu quả điều trị</li>
        </ul>
    </div>

    <div class="signature-section">
        <table style="width: 100%; border-collapse: collapse; margin-top: 40px;">
            <tr>
                <td style="width: 50%; text-align: left; vertical-align: top; padding: 10px;">
                    <div style="color: #333; font-weight: bold; font-size: 12px; margin-bottom: 5px;">BỆNH NHÂN</div>
                    <div style="color: #333; font-size: 11px; margin-bottom: 5px;">(Ký và ghi rõ họ tên)</div>
                    <div style="height: 80px;"></div>
                    <div style="border-top: 1px solid #333; width: 200px; margin: 10px 0;"></div>
                    <div style="color: #333; font-size: 12px;">{{ $prescription->medicalRecord->appointment->patient->full_name }}</div>
                </td>
                <td style="width: 50%; text-align: right; vertical-align: top; padding: 10px;">
                    <div style="color: #333; font-weight: bold; font-size: 12px; margin-bottom: 5px;">BÁC SĨ KÊ ĐƠN</div>
                    <div style="color: #333; font-size: 11px; margin-bottom: 5px;">(Ký và ghi rõ họ tên)</div>
                    <div style="height: 80px;"></div>
                    <div style="border-top: 1px solid #333; width: 200px; margin: 10px 0 10px auto;"></div>
                    <div style="color: #333; font-size: 12px;">{{ $prescription->medicalRecord->appointment->doctor->user->full_name }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div style="font-size: 10px; color: #666; margin-top: 30px;">
            Đơn thuốc được in vào lúc {{ now()->format('H:i d/m/Y') }}<br>
            Đây là đơn thuốc điện tử, có giá trị như đơn thuốc giấy
        </div>
    </div>
</body>

</html>