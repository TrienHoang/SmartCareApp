@extends('reception.dashboard')

@section('title', 'Lịch sử khám - ' . $patient->full_name)

@section('content')
    <div class="container mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-notes-medical mr-2"></i> Lịch sử khám - {{ $patient->full_name }}
        </h1>

        <div class="bg-white shadow rounded p-6">
            @if ($appointments->isEmpty())
                <p class="text-gray-600">Bệnh nhân chưa có lịch sử khám.</p>
            @else
                <table class="w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="border border-gray-200 px-4 py-2">Ngày khám</th>
                            <th class="border border-gray-200 px-4 py-2">Bác sĩ</th>
                            <th class="border border-gray-200 px-4 py-2">Trạng thái</th>
                            <th class="border border-gray-200 px-4 py-2">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $appointment)
                            <tr>
                                <td class="border border-gray-200 px-4 py-2">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y H:i') }}
                                </td>
                                <td class="border border-gray-200 px-4 py-2">{{ $appointment->doctor_name }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $appointment->status }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $appointment->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="mt-4">
            <a href="{{ route('receptionist.patients.index') }}"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded">
                <i class="fas fa-arrow-left mr-1"></i> Quay lại
            </a>
        </div>
    </div>
@endsection
