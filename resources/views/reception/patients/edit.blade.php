@extends('reception.dashboard')

@section('title', 'Cập nhật hồ sơ bệnh nhân')

@section('content')
    <div class="container mx-auto py-8 px-4 max-w-2xl">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-user-edit mr-2"></i> Cập nhật hồ sơ bệnh nhân
        </h1>

        {{-- Thông báo flash --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Hiển thị lỗi validate --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('receptionist.patients.update', $patient->id) }}" method="POST"
            class="bg-white p-6 rounded shadow space-y-4">
            @csrf
            @method('PUT')

            {{-- Họ tên --}}
            <div>
                <label class="block font-medium text-gray-700">Họ tên <span class="text-red-500">*</span></label>
                <input type="text" name="full_name" value="{{ old('full_name', $patient->full_name) }}"
                    class="w-full mt-1 px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400"
                    required>
            </div>

            {{-- Email --}}
            <div>
                <label class="block font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $patient->email) }}"
                    class="w-full mt-1 px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400">
            </div>

            {{-- Số điện thoại --}}
            <div>
                <label class="block font-medium text-gray-700">Số điện thoại <span class="text-red-500">*</span></label>
                <input type="text" name="phone" value="{{ old('phone', $patient->phone) }}"
                    class="w-full mt-1 px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400"
                    required>
            </div>

            {{-- Giới tính --}}
            <div>
                <label class="block font-medium text-gray-700">Giới tính</label>
                <select name="gender"
                    class="w-full mt-1 px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400">
                    <option value="">-- Chọn giới tính --</option>
                    <option value="Nam" {{ old('gender', $patient->gender) === 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ old('gender', $patient->gender) === 'Nữ' ? 'selected' : '' }}>Nữ</option>
                </select>
            </div>

            {{-- Ngày sinh --}}
            <div>
                <label class="block font-medium text-gray-700">Ngày sinh</label>
                <input type="date" name="date_of_birth"
                    value="{{ old('date_of_birth', optional($patient->date_of_birth)->format('Y-m-d')) }}"
                    class="w-full mt-1 px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400">
            </div>

            {{-- Địa chỉ --}}
            <div>
                <label class="block font-medium text-gray-700">Địa chỉ</label>
                <input type="text" name="address" value="{{ old('address', $patient->address) }}"
                    class="w-full mt-1 px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400">
            </div>

            {{-- Nút hành động --}}
            <div class="pt-4 flex justify-end">
                <a href="{{ route('receptionist.patients.index') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2">
                    Quay lại
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded shadow">
                    <i class="fas fa-save mr-1"></i> Cập nhật
                </button>
            </div>
        </form>
    </div>
@endsection
