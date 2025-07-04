<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Chi tiết hóa đơn #{{ $history->payment_id }}</title>
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      color: #000;
      margin: 30px 50px;
    }
    h2 {
      text-align: center;
      font-size: 18px;
      color: #28a745;
      margin-bottom: 5px;
    }
    .desc {
      text-align: center;
      font-size: 13px;
      margin-bottom: 20px;
      color: #444;
    }
    .section-title {
      font-weight: bold;
      font-size: 13px;
      margin-top: 22px;
      margin-bottom: 6px;
      border-bottom: 1px solid #ccc;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 8px;
    }
    th, td {
      text-align: left;
      padding: 5px 8px;
      border: 1px solid #ccc;
    }
    th {
      background-color: #f2f2f2;
    }
    .badge {
      background-color: #28a745;
      color: white;
      padding: 2px 6px;
      border-radius: 3px;
      font-size: 11px;
    }
    .footer {
      margin-top: 30px;
      text-align: center;
      font-style: italic;
      color: #555;
      border-top: 1px dashed #aaa;
      padding-top: 8px;
    }
  </style>
</head>
<body>

  <h2>🎉 Thanh toán thành công!</h2>
  <p class="desc">Cảm ơn bạn đã tin tưởng sử dụng dịch vụ. Dưới đây là hóa đơn thanh toán chi tiết của bạn.</p>

  <div class="section-title">1. Thông tin hóa đơn</div>
  <table>
    <tr><th>Mã hóa đơn</th><td>{{ $history->payment_id }}</td></tr>
    <tr><th>Trạng thái</th><td><span class="badge">{{ ucfirst($history->payment->status ?? 'Không rõ') }}</span></td></tr>
    <tr><th>Ngày thanh toán</th><td>{{ optional($history->payment_date)->format('d/m/Y H:i') ?? '---' }}</td></tr>
    <tr><th>Phương thức</th><td>{{ $history->payment_method ?? '---' }}</td></tr>
    <tr><th>Số tiền</th><td><strong>{{ number_format($history->amount, 0, ',', '.') }} ₫</strong></td></tr>
  </table>

  <div class="section-title">2. Thông tin bệnh nhân</div>
  <table>
    <tr><th>Họ tên</th><td>{{ optional($history->payment->appointment->patient)->full_name ?? '---' }}</td></tr>
    <tr><th>Liên hệ</th>
      <td>
        {{ optional($history->payment->appointment->patient)->phone ?? '---' }}
        @if(optional($history->payment->appointment->patient)->email)
          / {{ optional($history->payment->appointment->patient)->email }}
        @endif
      </td>
    </tr>
    <tr><th>Ngày sinh</th><td>{{ optional($history->payment->appointment->patient->date_of_birth)->format('d/m/Y') ?? '---' }}</td></tr>
    <tr><th>Địa chỉ</th><td>{{ optional($history->payment->appointment->patient)->address ?? '---' }}</td></tr>
  </table>

  <div class="section-title">3. Dịch vụ</div>
  <table>
    <tr><th>Tên dịch vụ</th><td>{{ optional($history->payment->appointment->service)->name ?? '---' }}</td></tr>
    <tr><th>Mô tả</th><td>{{ optional($history->payment->appointment->service)->description ?? '---' }}</td></tr>
  </table>

  <div class="section-title">4. Bác sĩ phụ trách</div>
  <table>
    <tr><th>Họ tên</th><td>{{ optional($history->payment->appointment->doctor->user)->full_name ?? '---' }}</td></tr>
    <tr><th>Chuyên môn</th><td>{{ optional($history->payment->appointment->doctor)->specialization ?? '---' }}</td></tr>
    <tr><th>Phòng ban</th><td>{{ optional($history->payment->appointment->doctor->department)->name ?? '---' }}</td></tr>
  </table>

  <div class="section-title">5. Khuyến mãi</div>
  <table>
    <tr><th>Chương trình</th><td>{{ optional($history->payment->promotion)->title ?? 'Không có' }}</td></tr>
    <tr><th>Giảm giá</th><td>{{ optional($history->payment->promotion)->discount_percentage ?? 0 }}%</td></tr>
  </table>

  <div class="footer">
    🌟 Cảm ơn quý khách. Chúng tôi mong được phục vụ bạn lần tiếp theo!<br>
    ❤️ Chúc bạn luôn mạnh khỏe và hạnh phúc.
  </div>

</body>
</html>
