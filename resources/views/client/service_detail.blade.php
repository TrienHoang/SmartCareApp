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
            color: #2c3e50;
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

        .medpro-app-banner {
            background-color: #007bff;
            /* Blue background matching the image */
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            position: sticky;
            /* Make it sticky */
            top: 20px;
            /* Distance from top when scrolled */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .medpro-app-banner h4 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .medpro-app-banner p {
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .app-store-logos img {
            height: 45px;
            /* Adjust size as needed */
            margin: 0 8px;
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
                    Đặt khám Bệnh Dạ dày - Đại tràng
                </h1>
                <p class="text-gray-700 text-lg mb-2 mt-3 flex items-start">
                    <i data-lucide="building-2" class="w-5 h-5 mr-2"></i>
                    Trung Tâm Nội Soi Tiêu Hoá Doctor Check
                    <i data-lucide="check-circle-2" class="w-5 h-5 ml-1 text-blue-600"></i>
                </p>
                <p class="text-gray-600 mb-2 flex items-start">
                    <i data-lucide="map-pin" class="w-5 h-5 mr-2"></i>
                    429 Tô Hiến Thành, Phường 14, Quận 10, Thành phố Hồ Chí Minh
                </p>
                <p class="text-gray-600 flex items-start">
                    <i data-lucide="clock" class="w-5 h-5 mr-2"></i>
                    Lịch khám: Thứ 2,3,4,5,6,7,Chủ nhật
                </p>
            </div>
            {{-- Right Column: Price and Button --}}
            <div class="w-full md:w-1/3 text-right flex flex-col items-end">
                <span class="price-display mb-2 text-2xl font-bold text-blue-600">200.000đ</span>
                <a href="#" class="inline-block px-6 py-2 rounded-md bg-blue-600 text-white font-semibold shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">Đặt khám ngay</a>
            </div>
        </div>

        {{-- <hr class="my-5"> Divider --}}

        {{-- Content grid: details + banner --}}
        <div id="content" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Left Column: Package Details --}}
            <div class="lg:col-span-8">
                <h3 class="package-title text-xl font-semibold text-gray-800">1. Tên gói khám: Gói Khám Chuyên Sâu Bệnh Lý Dạ Dày & Đại Tràng</h3>
                <p class="package-value text-blue-600 font-medium mt-1">Trị giá gói khám: 200.000VNĐ</p>

                <h3 class="package-title text-xl font-semibold text-gray-800 mt-6">2. Lợi ích gói khám bệnh lý dạ dày & đại tràng là gì?</h3>
                <ul class="list-benefits mt-2 space-y-2 text-gray-700">
                    <li class="flex items-start">
                        <i data-lucide="chevron-right" class="w-4 h-4 mr-2 mt-1 shrink-0"></i>
                        Hiện nay, bệnh ở dạ dày & đại tràng là bệnh lý dễ tái phát nếu không tìm ra chính xác nguyên nhân gây bệnh. Khi đó các triệu chứng tiêu hoá dai dẳng gây ảnh hưởng rất lớn đến chất lượng cuộc sống của bạn.
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="chevron-right" class="w-4 h-4 mr-2 mt-1 shrink-0"></i>
                        Bác sĩ Doctor Check sẽ giúp Bạn Khám Ra Bệnh - Trị Hết Bệnh & Ngừa Ung Thư.
                    </li>
                    {{-- Add more benefits as needed --}}
                </ul>

                <h3 class="package-title text-xl font-semibold text-gray-800 mt-6">3. Quy trình thực hiện gói khám nội soi dạ dày và đại tràng gồm những bước nào?</h3>
                <ul class="list-benefits mt-2 space-y-2 text-gray-700">
                    <li class="flex items-start">
                        <i data-lucide="chevron-right" class="w-4 h-4 mr-2 mt-1 shrink-0"></i>
                        Bước 1: Đặt lịch khám ưu tiên qua Medpro để được khám nhanh, không chờ đợi.
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="chevron-right" class="w-4 h-4 mr-2 mt-1 shrink-0"></i>
                        Bước 2: Đến phòng khám Doctor Check, báo mã đặt khám cho lễ tân và vào khám ngay.
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="chevron-right" class="w-4 h-4 mr-2 mt-1 shrink-0"></i>
                        Bước 3: Gặp bác sĩ chuyên khoa tiêu hoá, thực hiện nội soi theo hướng dẫn và nhận kết quả, tư vấn điều trị.
                    </li>
                    {{-- Add more steps as needed --}}
                </ul>

                {{-- Additional services in the package --}}
                <div class="mt-8">
                    <h3 class="package-title text-xl font-semibold text-gray-800">Các dịch vụ có trong gói khám là gì?</h3>
                    <ul class="list-benefits mt-2 space-y-2 text-gray-700">
                        <li class="flex items-start">
                            <i data-lucide="chevron-right" class="w-4 h-4 mr-2 mt-1 shrink-0"></i>
                            Đo dấu hiệu sinh tồn (mạch, huyết áp, nhiệt độ, SPO2, chiều cao, cân nặng, BMI).
                        </li>
                        <li class="flex items-start">
                            <i data-lucide="chevron-right" class="w-4 h-4 mr-2 mt-1 shrink-0"></i>
                            Bảng câu hỏi sàng lọc chuyên sâu bệnh lý dạ dày & đại tràng (tiền sử gia đình, thói quen sinh hoạt, triệu chứng...).
                        </li>
                        <li class="flex items-start">
                            <i data-lucide="chevron-right" class="w-4 h-4 mr-2 mt-1 shrink-0"></i>
                            Bác sĩ chuyên khoa tiêu hóa đọc và chẩn đoán, đưa ra phác đồ điều trị phù hợp (nếu có).
                        </li>
                    </ul>
                </div>
            </div>

{{-- Right Column: Doctor Selection --}}
<div class="lg:col-span-4 mt-8 lg:mt-0">
    <div class="space-y-4">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Chọn bác sĩ</h3>

        @foreach (['Nguyễn Văn A', 'Trần Thị B', 'Lê Văn C'] as $doctor)
            <div class="doctor-card bg-white rounded-lg shadow p-4 flex items-center space-x-4 hover:shadow-md transition">
                {{-- Doctor Image --}}
                <img src="https://via.placeholder.com/80" alt="{{ $doctor }}" class="w-20 h-20 rounded-full object-cover">

                {{-- Doctor Info --}}
                <div class="flex-1">
                    <h4 class="text-lg font-semibold text-gray-800">{{ $doctor }}</h4>

                    {{-- Rating --}}
                    <div class="flex items-center text-yellow-500 text-sm mt-1">
                        @for ($i = 0; $i < 5; $i++)
                            <i data-lucide="star" class="w-4 h-4 fill-current {{ $i < 4 ? 'text-yellow-500' : 'text-gray-300' }}"></i>
                        @endfor
                        <span class="text-gray-600 ml-2">(4.0)</span>
                    </div>

                    {{-- Select Button --}}
                    <button class="mt-2 inline-block px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                        Chọn bác sĩ
                    </button>
                </div>
            </div>
        @endforeach
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
            @for ($i = 0; $i < 3; $i++)
                {{-- Loop to simulate multiple packages --}}
                <div class="h-full bg-white rounded-lg shadow-sm transition duration-300 ease-in-out hover:shadow-lg flex flex-col">
                    <img src="https://via.placeholder.com/400x250?text=Goi+Kham+{{ $i + 1 }}" alt="Gói khám liên quan" class="w-full h-40 object-cover rounded-t-lg">
                    <div class="p-4 flex flex-col flex-1">
                        <h5 class="text-lg font-semibold mb-2">Gói khám [Tên Gói Khám {{ $i + 1 }}]</h5>
                        <p class="text-sm text-gray-600 flex-grow">
                            Mô tả ngắn về gói khám, ví dụ: tầm soát ung thư, nội soi không đau...
                        </p>
                        <p class="text-red-600 font-bold text-md mt-2">
                            Giá: {{ number_format(500000 + $i * 100000, 0, ',', '.') }} VNĐ
                        </p>
                        <a href="#" class="mt-3 inline-block px-4 py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-600 hover:text-white transition">Đặt khám ngay</a>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>

    </div>
@endsection

@push('scripts')
@endpush
