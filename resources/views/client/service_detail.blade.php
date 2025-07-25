@extends('client.layouts.app')

@section('title', 'Chi tiết dịch vụ')

@push('styles')
    <style>
        .main-content-wrapper {
            padding: 2.5rem;
            position: relative;
            z-index: 2;
        }

        .hero-background-image {
            height: 100%;
            background-image: url(https://i.ibb.co/ZRQ9K48J/ace06379-7b9f-43a8-80eb-e0c72fc27839-frame-6-4.png);
            background-size: cover;
            background-position: center;
            position: absolute;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1;
            -webkit-mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));
            mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));
        }

        .main-content {
            max-width: 1180px;
            margin: 0 auto;
            margin-top: 100px
        }

        .clinic-info h1 {
            font-size: 1.8rem;
            font-weight: 700;
        }

        .clinic-info p {
            color: #555;
            font-size: 0.95rem;
        }

        #header-content {
            border-radius: 25px;
            background-color: white;
            padding: 20px 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        #content {
            border-radius: 25px;
            background-color: white;
            padding: 20px 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .price-display {
            color: #FF5722;
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
        }

        .btn-dat-kham-ngay {
            background-color: #007bff;
            /* Blue button from Bootstrap primary */
            border-color: #007bff;
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
            border-radius: 8px;
            /* Slightly more rounded */
        }

        .btn-dat-kham-ngay:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .package-title {
            color: #333;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .package-value {
            color: #FF5722;
            font-size: 1rem;
            font-weight: 600;
        }

        .list-benefits li {
            font-size: 0.95rem;
            color: #333;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: flex-start;
        }

        .list-benefits li i {
            color: #007bff;
            /* Blue icon color */
            margin-right: 8px;
            font-size: 1.1rem;
            flex-shrink: 0;
            /* Prevent icon from shrinking */
            margin-top: 3px;
            /* Align with text */
        }

        /* Override Tailwind's default list-style for cleaner look */
        ul {
            list-style: none;
            padding-left: 0;
        }
    </style>
@endpush

@section('content')
    <div class="hero-background-image"></div>
    {{-- Main Content Wrapper --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="main-content main-content-wrapper">
            {{-- Header row: info + price/button --}}
            <div id="header-content" class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                {{-- Left Column: Clinic Info --}}
                <div class="w-full md:w-2/3 clinic-info">
                    <h1 class="font-bold text-blue-700 text-3xl lg:text-4xl">
                        {{ $service->name }}
                    </h1>
                    <p class="text-gray-700 text-lg mb-2 mt-3 flex items-start">
                        <i data-lucide="building-2" class="w-5 h-5 mr-2 text-blue-700"></i>
                        Loại dịch vụ: {{ $service->category->name }}
                    </p>
                    <p class="text-gray-600 mb-2 flex items-start">
                        <i data-lucide="map-pin" class="w-5 h-5 mr-2 text-blue-700"></i>
                        Khoa: {{ $service->department->name }}
                    </p>
                    <p class="text-gray-600 mb-2 flex items-start">
                        {{ $service->description }}
                    </p>
                </div>
                {{-- Right Column: Price and Button --}}
                <div class="w-full md:w-1/3 text-right flex flex-col items-end">
                    <span class="price-display mb-2 text-2xl font-bold text-red-600">{{ number_format($service->price) }}
                        đ</span>
                    <form id="bookingForm" action="{{ route('booking.storeService') }}" method="POST">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                        <input type="hidden" name="doctor_id" id="doctor_id">
                        <input type="hidden" name="doctor_option" id="doctor_option_input" value="auto">
                        <button type="submit"
                            class="inline-block px-6 py-2 rounded-md bg-blue-600 text-white font-semibold shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            Đặt khám ngay
                        </button>
                    </form>
                </div>
            </div>

            {{-- <hr class="my-5"> Divider --}}

            {{-- Content grid: details + banner --}}
            <div id="content" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {{-- Left Column: Package Details --}}
                <div class="lg:col-span-8">
                    {!! $service->content !!}
                </div>

                {{-- Right Column: Doctor Selection --}}
                <div class="lg:col-span-4 mt-8 lg:mt-0">
                    <div class="space-y-4 bg-blue-600 p-5 rounded-lg">
                        <h3 class="text-2xl font-semibold text-white mb-2">Bác sĩ thực hiện</h3>
                        <div class="mb-4 text-white">
                            <p class="text-lg font-semibold mb-2">Lựa chọn bác sĩ</p>
                            <div class="flex flex-col space-y-2">
                                <label
                                    class="flex items-center space-x-2 p-2 rounded-md cursor-pointer border border-transparent has-[:checked]:border-green-500 has-[:checked]:bg-green-100 has-[:checked]:text-black transition">
                                    <input type="radio" name="doctor_option" value="auto" class="peer sr-only" checked>
                                    <span class="text-white font-medium">Tự động chọn bác sĩ phù hợp</span>
                                </label>
                                <label
                                    class="flex items-center space-x-2 p-2 rounded-md cursor-pointer border border-transparent has-[:checked]:border-green-500 has-[:checked]:bg-green-100 has-[:checked]:text-black transition">
                                    <input type="radio" name="doctor_option" value="manual" class="peer sr-only">
                                    <span class="text-white font-medium">Tôi muốn lựa chọn bác sĩ</span>
                                </label>
                            </div>
                        </div>
                        <div id="doctor-list"
                            class="hidden transform space-y-4 transition duration-300 ease-out translate-y-[-10px] opacity-0">
                            @foreach ($service->doctors as $doctor)
                                <label for="doctor_{{ $doctor->id }}"
                                    class="transform cursor-pointer m-1 transition duration-300 hover:scale-105 ">
                                    <div
                                        class="doctor-card transform bg-white rounded-lg shadow p-4 flex items-center space-x-4 hover:shadow-md transition border-2 border-transparent duration-300 hover:scale-105 focus-within:border-blue-500 has-[:checked]:border-green-500 has-[:checked]:bg-green-100">
                                        <input type="radio" id="doctor_{{ $doctor->id }}" name="selected_doctor"
                                            value="{{ $doctor->id }}" class="sr-only" hidden>
                                        <img src="{{ $doctor->user->avatar }}" alt="{{ $doctor->user->full_name }}"
                                            class="w-20 h-20 rounded-full object-cover">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-semibold text-gray-800">{{ $doctor->user->full_name }}
                                            </h4>
                                            <div class="flex items-center text-yellow-500 text-sm mt-1">
                                                @php $averageRating = $doctor->reviews->avg('rating'); @endphp
                                                @if ($averageRating)
                                                    @for ($i = 0; $i < 5; $i++)
                                                        <i data-lucide="star"
                                                            class="w-4 h-4 fill-current {{ $i < round($averageRating) ? 'text-yellow-500' : 'text-gray-300' }}"></i>
                                                    @endfor
                                                    <span
                                                        class="ml-2 text-sm text-gray-600">({{ number_format($averageRating, 1) }}/5)</span>
                                                @else
                                                    <span class="text-sm text-gray-500 italic">Chưa có đánh giá</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section for related packages or other content can follow here --}}
    <section class="related-packages py-5 lg:py-8 mt-5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 text-center mb-5">
                Các gói khám liên quan
            </h2>
            {{-- Responsive cards grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($relatedServices as $service)
                    {{-- Loop to simulate multiple packages --}}
                    <div
                        class="h-full bg-white rounded-lg shadow-sm transition duration-300 ease-in-out hover:shadow-lg flex flex-col">
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}"
                            class="w-full h-60 object-cover rounded-t-lg mb-3">
                        <div class="p-4 flex flex-col flex-1">
                            <h5 class="text-lg font-semibold mb-2">{{ $service->name }}</h5>
                            <p class="text-sm text-gray-600 flex-grow">
                                {{ $service->description }}
                            </p>
                            <p class="text-red-600 font-bold text-md mt-2">
                                Giá: {{ number_format($service->price, 0, ',', '.') }} VNĐ
                            </p>
                            <a href="{{ route('booking.showService', ['service_id' => $service->id]) }}"
                                class="mt-3 inline-block px-4 py-2 border border-blue-600 text-center text-blue-600 rounded-md hover:bg-blue-600 hover:text-white transition">
                                Xem chi tiết</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('bookingForm');
            const doctorRadios = document.querySelectorAll('input[name="selected_doctor"]');
            const doctorIdInput = document.getElementById('doctor_id');
            const doctorOptionInput = document.getElementById('doctor_option_input');
            const doctorOptionRadios = document.querySelectorAll('input[name="doctor_option"]');
            const doctorList = document.getElementById('doctor-list');

            // Cập nhật doctor_option_input khi người dùng chọn radio
            doctorOptionRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    doctorOptionInput.value = this.value;

                    if (this.value === 'manual') {
                        doctorList.classList.remove('hidden');
                        doctorList.classList.add('opacity-100', 'translate-y-0');
                    } else {
                        doctorList.classList.add('hidden');
                        doctorIdInput.value = '';
                    }
                });
            });

            // Cập nhật doctor_id khi chọn bác sĩ
            doctorRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    doctorIdInput.value = this.value;
                });
            });

            // Validate trước khi submit
            form.addEventListener('submit', function(event) {
                const selectedOption = document.querySelector('input[name="doctor_option"]:checked')?.value;

                if (selectedOption === 'manual' && !doctorIdInput.value) {
                    event.preventDefault();
                    alert('Vui lòng chọn một bác sĩ trước khi đặt khám.');
                }
            });
        });
    </script>
@endpush
