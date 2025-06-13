@extends('admin.dashboard')

@section('content')
<style>
    /* Container tổng */
    .payment-history-container {
        max-width: 1000px;
        margin: 20px auto;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #2c3e50;
        font-weight: 700;
    }

    /* Form lọc */
    form.filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
        margin-bottom: 25px;
    }
    form.filter-form input[type="text"],
    form.filter-form input[type="date"],
    form.filter-form select {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        min-width: 150px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }
    form.filter-form input[type="text"]:focus,
    form.filter-form input[type="date"]:focus,
    form.filter-form select:focus {
        border-color: #3498db;
        outline: none;
    }
    form.filter-form button {
        background-color: #3498db;
        color: white;
        border: none;
        padding: 9px 20px;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    form.filter-form button:hover {
        background-color: #2980b9;
    }

    /* Bảng */
    table.payment-history-table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 2px 8px rgb(0 0 0 / 0.1);
        border-radius: 6px;
        overflow: hidden;
        background-color: #fff;
    }
    table.payment-history-table thead {
        background-color: #3498db;
        color: white;
        font-weight: 600;
    }
    table.payment-history-table th, 
    table.payment-history-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        font-size: 14px;
    }
    table.payment-history-table tbody tr:hover {
        background-color: #f0f8ff;
    }

    /* Link chi tiết */
    table.payment-history-table a {
        color: #2980b9;
        text-decoration: none;
        font-weight: 600;
    }
    table.payment-history-table a:hover {
        text-decoration: underline;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        padding: 15px 0;
        list-style: none;
        gap: 8px;
        font-size: 14px;
    }
    .pagination li a,
    .pagination li span {
        padding: 6px 12px;
        border-radius: 4px;
        border: 1px solid #ccc;
        color: #3498db;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .pagination li.active span {
        background-color: #3498db;
        color: white;
        border-color: #2980b9;
        cursor: default;
    }
    .pagination li a:hover {
        background-color: #2980b9;
        color: white;
        border-color: #2980b9;
    }
</style>

<div class="payment-history-container">

    <h1>Lịch sử thanh toán</h1>

    <form method="GET" action="{{ route('admin.payment_histories.index') }}" class="filter-form mb-4">
        <input type="text" name="patient_name" placeholder="Tên bệnh nhân" value="{{ request('patient_name') }}">
        <input type="date" name="date_from" value="{{ request('date_from') }}">
        <input type="date" name="date_to" value="{{ request('date_to') }}">
        <select name="service_id">
            <option value="">Dịch vụ</option>
            @foreach($services as $service)
                <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                    {{ $service->name }}
                </option>
            @endforeach
        </select>
        <select name="doctor_id">
            <option value="">Bác sĩ</option>
            @foreach($doctors as $doctor)
                <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                    {{ $doctor->name }}
                </option>
            @endforeach
        </select>
        <button type="submit">Lọc</button>
    </form>

    <table class="payment-history-table">
        <thead>
            <tr>
                <th>Mã hóa đơn</th>
                <th>Tên bệnh nhân</th>
                <th>Dịch vụ</th>
                <th>Bác sĩ</th>
                <th>Số tiền</th>
                <th>Phương thức</th>
                <th>Ngày thanh toán</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($histories as $history)
            <tr>
                <td>{{ $history->payment->id }}</td>
                <td>{{ optional($history->payment->patient)->full_name }}</td>
                <td>{{ optional($history->payment->service)->name }}</td>
                <td>{{ optional($history->payment->doctor->user)->full_name ?? 'N/A' }}</td>
                <td>{{ number_format($history->amount, 0, ',', '.') }}₫</td>
                <td>{{ $history->payment_method }}</td>
                <td>
                    {{ $history->payment_date ? \Carbon\Carbon::parse($history->payment_date)->format('d/m/Y H:i') : 'Chưa thanh toán' }}
                </td>
                <td><a href="{{ route('admin.payment_histories.show', $history->id) }}">Chi tiết</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $histories->links() }}

</div>
@endsection
