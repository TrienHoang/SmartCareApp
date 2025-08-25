@extends('client.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white shadow-lg rounded-lg p-6 text-center">
        <h1 class="text-3xl font-bold text-green-600 mb-4">Đặt lịch thành công!</h1>
        <p class="text-gray-700 mb-6">Cảm ơn bạn đã đặt lịch khám bệnh</p>

        @if (session('qr_code_data'))
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-3">Mã QR Check-in</h2>
                <img src="{{ route('qr.generate', ['data' => session('qr_code_data')]) }}" alt="Mã QR Check-in" class="mx-auto border border-gray-300 rounded-lg shadow-md" style="width: 200px; height: 200px;">
            </div>
        @endif

        <div class="border-t pt-4 mt-4">
            <p class="text-gray-600">Bạn sẽ nhận được email xác nhận chi tiết cuộc hẹn sớm nhất.</p>
            <a href="{{ route('home') }}" class="inline-block mt-6 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition duration-300">
                Quay về trang chủ
            </a>
        </div>
    </div>
</div>
@endsection
