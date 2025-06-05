@extends('admin.dashboard')
@section('title', 'Danh sách Đơn thuốc')

@section('content')
    <div class="container">
        <h2>Chi tiết đơn thuốc</h2>
        <p><strong>Bệnh nhân:</strong>{{ $prescription->medicalRecord->appointment->patient->full_name ?? 'N/A' }}</p>
        <p><strong>Ngày kê:</strong>{{ $prescription->formatted_date }}</strong></p>
        <p><strong>Ghi chú:</strong> {{ $prescription->notes }}</p>
        <h4>Thuốc được kê:</h4>
        <ul>
            @foreach ($prescription->items as $item)
                <li>
                    {{ $item->medicine->name }} ({{ $item->quantity }}): {{ $item->usage_instructions }}
                </li>
            @endforeach
        </ul>
        <a href="" target="_blank" class="btn btn-outline-primary">In đơn thuốc</a>
    </div>
@endsection