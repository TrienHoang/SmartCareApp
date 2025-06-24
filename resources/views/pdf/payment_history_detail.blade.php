<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Hóa đơn thanh toán</title>
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      margin: 30px 50px;
      color: #000;
    }

    h2 {
      text-align: center;
      color: #28a745;
      font-size: 20px;
      margin-bottom: 10px;
    }

    p.desc {
      text-align: center;
      font-size: 13px;
      margin-bottom: 20px;
      color: #333;
    }

    .sec-title {
      font-size: 13px;
      font-weight: bold;
      margin-top: 18px;
      margin-bottom: 6px;
      border-bottom: 1px solid #ccc;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 8px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 5px 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
      font-weight: bold;
    }

    .badge {
      background: #28a745;
      color: white;
      padding: 2px 5px;
      border-radius: 3px;
      font-size: 11px;
    }

    .footer {
      text-align: center;
      font-style: italic;
      color: #555;
      margin-top: 25px;
      border-top: 1px dashed #bbb;
      padding-top: 10px;
    }
  </style>
</head>
<body>

  <h2>🎉 Thanh toán thành công!</h2>
  <p class="desc">Cảm ơn bạn đã tin tưởng sử dụng dịch vụ. Dưới đây là thông tin hóa đơn của bạn.</p>

  <div class="sec-title">Thông tin hóa đơn</div>
  <table>
    <tr><th>Mã</th><td>{{ $history->payment_id }}</td></tr>
    <tr><th>Trạng thái</th><td><span class="badge">{{ ucfirst($history->payment->status ?? 'Không rõ') }}</span></td></tr>
    <tr><th>Ngày</th><td>{{ $history->payment_date ? \Carbon\Carbon::parse($history->payment_date)->format('d/m/Y H:i') : '---' }}</td></tr>
    <tr><th>Phương thức</th><td>{{ $history->payment_method ?? '---' }}</td></tr>
    <tr><th>Số tiền</th><td><strong>{{ number_format($history->amount, 0, ',', '.') }} ₫</strong></td></tr>
  </table>

  <div class="sec-title">Bệnh nhân</div>
  <table>
    <tr><th>Họ tên</th><td>{{ optional($history->payment->appointment->patient)->full_name ?? '---' }}</td></tr>
    <tr><th>Liên hệ</th>
      <td>
        {{ optional($history->payment->appointment->patient)->phone ?? '---' }} / 
        {{ optional($history->payment->appointment->patient)->email ?? '---' }}
      </td>
    </tr>
    <tr><th>Ngày sinh</th><td>{{ optional($history->payment->appointment->patient->date_of_birth)->format('d/m/Y') ?? '---' }}</td></tr>
    <tr><th>Địa chỉ</th><td>{{ optional($history->payment->appointment->patient)->address ?? '---' }}</td></tr>
  </table>

  <div class="sec-title">Dịch vụ</div>
  <table>
    <tr><th>Tên</th><td>{{ optional($history->payment->appointment->service)->name ?? '---' }}</td></tr>
    <tr><th>Mô tả</th><td>{{ optional($history->payment->appointment->service)->description ?? '---' }}</td></tr>
  </table>

  <div class="sec-title">Bác sĩ</div>
  <table>
    <tr><th>Họ tên</th><td>{{ optional($history->payment->appointment->doctor->user)->full_name ?? '---' }}</td></tr>
    <tr><th>Chuyên môn</th><td>{{ optional($history->payment->appointment->doctor)->specialization ?? '---' }}</td></tr>
    <tr><th>Phòng ban</th><td>{{ optional($history->payment->appointment->doctor->department)->name ?? '---' }}</td></tr>
  </table>

  <div class="sec-title">Ưu đãi</div>
  <table>
    <tr><th>Chương trình</th><td>{{ optional($history->payment->promotion)->title ?? 'Không có' }}</td></tr>
    <tr><th>Giảm giá</th><td>{{ optional($history->payment->promotion)->discount_percentage ?? 0 }}%</td></tr>
  </table>

  <div class="footer">
    🌟 Chúc quý khách mạnh khỏe, hạnh phúc.<br>
    ❤️ Cảm ơn bạn đã đồng hành cùng chúng tôi!
  </div>

</body>
</html>
