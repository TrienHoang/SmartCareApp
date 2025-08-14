@extends('client.layouts.profile-layout')


@section('title', 'Lịch sử khám bệnh')

@section('profile-content')
    <div class="md:col-span-3 space-y-6">
                <!-- Lịch sử khám bệnh -->
                <div id="history" class="bg-white rounded-lg shadow p-6">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">🩺 Lịch sử khám bệnh của bạn</h2>
                    </div>

                    @if ($appointments->isEmpty())
                        <div class="bg-blue-50 border border-blue-200 text-blue-600 p-4 rounded text-center">
                            Bạn chưa có lịch sử khám bệnh nào.
                        </div>
                    @else
                        @foreach ($appointments as $appointment)
                            <div
                                class="bg-white border-l-4 border-indigo-500 rounded-lg shadow-md p-5 mb-5 hover:shadow-lg transition">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">
                                    {{ $appointment->service->name ?? 'Dịch vụ khám' }}
                                </h3>

                                <div class="flex items-center mb-2 text-sm text-gray-600">
                                    <i class="bx bx-calendar mr-2 text-indigo-500"></i>
                                    <strong class="w-36">Ngày khám:</strong>
                                    <span>{{ $appointment->appointment_time->format('d/m/Y H:i') }}</span>
                                </div>

                                <div class="flex items-center mb-2 text-sm text-gray-600">
                                    <i class="bx bx-user mr-2 text-indigo-500"></i>
                                    <strong class="w-36">Bác sĩ:</strong>
                                    <span>{{ $appointment->doctor->user->full_name ?? 'Chưa rõ' }}</span>
                                </div>

                                <div class="flex items-center mb-2 text-sm text-gray-600">
                                    <i class="bx bx-notepad mr-2 text-indigo-500"></i>
                                    <strong class="w-36">Chẩn đoán:</strong>
                                    <span>{{ $appointment->medicalRecord->diagnosis ?? 'Chưa có thông tin' }}</span>
                                </div>

                                <div class="flex items-center mb-2 text-sm text-gray-600">
                                    <i class="bx bx-credit-card mr-2 text-indigo-500"></i>
                                    <strong class="w-36">Thanh toán:</strong>
                                    <span>{{ $appointment->payment->status ?? 'Chưa thanh toán' }}</span>
                                </div>

                                <a href="{{ route('client.appointments.detail', $appointment->id) }}"
                                    class="inline-block mt-4 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md transition">
                                    <i class="bx bx-detail mr-1"></i> Xem chi tiết
                                </a>

                                <div class="mt-4">
                                    <a href="{{ route('doctor.show', $appointment->doctor->id) }}"
                                        class="inline-block text-sm font-medium bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md transition">
                                        <i class="bx bx-star mr-1"></i> Đánh giá bác sĩ
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

            </div>

    {{-- Load Boxicons --}}
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
@endsection
