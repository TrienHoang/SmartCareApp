@extends('reception.dashboard')

@section('title', 'Tạo hồ sơ bệnh nhân')

@section('content')
    <div class="container mx-auto py-8 px-4 max-w-2xl">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-user-plus mr-2"></i> Tạo hồ sơ bệnh nhân
        </h1>

        <form action="{{ route('receptionist.patients.store') }}" method="POST" class="bg-white p-6 rounded shadow space-y-4">
            @csrf

            <div>
                <label class="block font-medium text-gray-700">Họ tên <span class="text-red-500">*</span></label>
                <input type="text" name="full_name" value="{{ old('full_name') }}"
                    class="w-full mt-1 px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400"
                    required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full mt-1 px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400">
            </div>

            <div>
                <label class="block font-medium text-gray-700">Số điện thoại <span class="text-red-500">*</span></label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full mt-1 px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400"
                    required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Giới tính</label>
                <select name="gender"
                    class="w-full mt-1 px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400">
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Ngày sinh</label>
                <input type="date" name="date_of_birth"
                    class="w-full mt-1 px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400">
            </div>

            <div>
                <label class="block font-medium text-gray-700">Địa chỉ</label>
                <input type="text" name="address"
                    class="w-full mt-1 px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400">
            </div>

            <div class="pt-4 flex justify-end">
                <a href="{{ route('receptionist.patients.index') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2">
                    Quay lại
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
                    <i class="fas fa-save mr-1"></i> Lưu hồ sơ
                </button>
            </div>
        </form>
    </div>
@endsection
