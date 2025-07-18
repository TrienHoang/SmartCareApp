@extends('client.layouts.app')

@section('title', 'Thông Tin Bác Sĩ')


@section('content')

    <body class="bg-gray-50 scroll-smooth">
        <section class="gradient-bg text-white py-12">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                    {{-- thông tin bác sĩ chỉnh lần 1 --}}
                    <div class="lg:col-span-2 fade-in">
                        <div
                            class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-6">
                            @if ($doctor->user && $doctor->user->avatar)
                                <img src="{{ asset('storage/' . $doctor->user->avatar) }}" alt="{{ $doctor->user->full_name }}"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                            @else
                                <img src="{{ asset('images/default-doctor.png') }}" alt="{{ $doctor->user->full_name }}"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                            @endif

                            <div class="flex-1">
                                <h1 class="text-4xl font-bold mb-2">BS. {{ $doctor->user->full_name }}</h1>
                                <p class="text-xl text-blue-100 mb-4">{{ $doctor->department->name ?? 'Chuyên khoa' }}</p>

                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400 mr-2">
                                            @for ($i = 0; $i < round($doctor->average_rating ?? 5); $i++)
                                                <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                            @endfor
                                        </div>
                                        <span class="text-blue-100">{{ $doctor->average_rating ?? '5.0' }}/5
                                            ({{ $doctor->review_count ?? 0 }} đánh giá)</span>
                                    </div>
                                    <span class="text-blue-200">•</span>
                                    <span class="text-blue-100">{{ $doctor->experience_years ?? 'Nhiều' }} năm kinh
                                        nghiệm</span>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    @if ($doctor->specialties && $doctor->specialties->count())
                                        @foreach ($doctor->specialties as $specialty)
                                            <span class="px-3 py-1 bg-white/20 rounded-full text-sm">{{ $specialty->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm">Đa khoa</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="fade-in">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6">
                            <h3 class="text-xl font-bold mb-4">Đặt Lịch Nhanh</h3>
                            <div class="space-y-3">
                                <div class="flex items-center text-blue-100">
                                    <i data-lucide="calendar" class="w-5 h-5 mr-3"></i>
                                    <span>Lịch hẹn hôm nay: 8 ca</span>
                                </div>
                                <div class="flex items-center text-blue-100">
                                    <i data-lucide="clock" class="w-5 h-5 mr-3"></i>
                                    <span>Thời gian khám: 20-30 phút</span>
                                </div>
                                <div class="flex items-center text-blue-100">
                                    <i data-lucide="dollar-sign" class="w-5 h-5 mr-3"></i>
                                    <span>Phí khám: 300.000đ</span>
                                </div>
                            </div>
                            <button onclick="scrollToBooking()"
                                class="w-full bg-white text-blue-600 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors mt-4">
                                Đặt Lịch Ngay
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Navigation Tabs -->
        <section class="bg-white border-b border-gray-200 sticky top-[70px] z-40">
            <div class="container mx-auto px-4 py-2">
                <nav class="flex space-x-8 overflow-x-auto">
                    <button onclick="showTab('info')" id="tab-info"
                        class="tab-btn py-4 px-2 border-b-2 border-blue-600 text-blue-600 font-semibold whitespace-nowrap">
                        Thông tin
                    </button>
                    <button onclick="showTab('schedule')" id="tab-schedule"
                        class="tab-btn py-4 px-2 border-b-2 border-transparent text-gray-600 hover:text-blue-600 whitespace-nowrap">
                        Lịch khám
                    </button>
                    <button onclick="showTab('reviews')" id="tab-reviews"
                        class="tab-btn py-4 px-2 border-b-2 border-transparent text-gray-600 hover:text-blue-600 whitespace-nowrap">
                        Đánh giá
                    </button>
                    <button onclick="showTab('location')" id="tab-location"
                        class="tab-btn py-4 px-2 border-b-2 border-transparent text-gray-600 hover:text-blue-600 whitespace-nowrap">
                        Địa điểm
                    </button>
                </nav>
            </div>
        </section>

        <!-- Content -->
        <main class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <!-- Main Content -->
                <!-- Doctor Info Tab -->
                <div id="content-info" class="tab-content">
                    <!-- About Section -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 card-hover">
                        <h2 class="text-2xl font-bold mb-6 gradient-text">Về Bác Sĩ</h2>
                        <p class="text-gray-700 leading-relaxed mb-6">
                            {{ $doctor->about ?? 'Thông tin về bác sĩ đang được cập nhật.' }}
                        </p>
                        <p class="text-gray-700 leading-relaxed">
                            {{ $doctor->user->bio ?? 'Bác sĩ tận tâm với nhiều năm kinh nghiệm trong nghề.' }}
                        </p>
                    </div>

                    <!-- Education & Experience  học vấn cc-->
                    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 card-hover">
                        <h2 class="text-2xl font-bold mb-6 gradient-text">Học Vấn & Kinh Nghiệm</h2>
                        <div class="space-y-6">

                            {{-- Hiển thị Kinh Nghiệm Làm Việc --}}
                            @foreach ($doctor->experiences as $experience)
                                <div class="timeline-item">
                                    <div class="font-semibold text-gray-800">
                                        {{ $experience->start_year }} - {{ $experience->end_year ?? 'Hiện tại' }}
                                    </div>
                                    <div class="text-blue-600 font-medium">{{ $experience->position }}</div>
                                    <div class="text-gray-600">{{ $experience->institution }}</div>
                                </div>
                            @endforeach

                            {{-- Hiển thị Học Vấn --}}
                            @foreach ($doctor->educations as $education)
                                <div class="timeline-item">
                                    <div class="font-semibold text-gray-800">
                                        {{ $education->start_year }} - {{ $education->end_year }}
                                    </div>
                                    <div class="text-blue-600 font-medium">{{ $education->degree }}</div>
                                    <div class="text-gray-600">{{ $education->school }}</div>
                                </div>
                            @endforeach

                        </div>
                    </div>


                    <!-- Specialties đã xong phần này như cc -->
                    <!-- Specialties -->
                    @if ($doctor->specialties && $doctor->specialties->count())
                        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 card-hover">
                            <h2 class="text-2xl font-bold mb-6 gradient-text">Chuyên Môn</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($doctor->specialties as $specialty)
                                    <div class="skill-tag p-4 rounded-lg">
                                        <i data-lucide="heart" class="w-5 h-5 text-blue-600 mb-2"></i>
                                        <h3 class="font-semibold mb-1">{{ $specialty->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $specialty->description }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif


                    <!-- Achievements -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 card-hover">
                        <h2 class="text-2xl font-bold mb-6 gradient-text">Thành Tích & Chứng Chỉ</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($doctor->achievements as $achievement)
                                <div class="flex items-start space-x-3">
                                    <div class="bg-blue-100 p-2 rounded-lg">
                                        <i data-lucide="award" class="w-6 h-6 text-blue-600"></i> {{-- icon có thể tuỳ chỉnh nếu
                                        bạn lưu trong DB --}}
                                    </div>
                                    <div>
                                        <h3 class="font-semibold">{{ $achievement->title }}</h3>
                                        <p class="text-gray-600 text-sm">
                                            {{ $achievement->description ?? 'Thông tin chưa cập nhật' }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <!-- Review Section -->
                <div class="mt-10 space-y-8">

                    <!-- Danh sách đánh giá -->
                    <div class="space-y-6">
                        @forelse ($doctor->reviews->sortByDesc('created_at') as $review)
                            <div class="border-b pb-6">
                                <div class="flex items-start space-x-4">

                                    <!-- Avatar -->
                                    <div
                                        class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-600">
                                        {{ $review->patient ? strtoupper(Str::substr($review->patient->full_name, 0, 1)) . '***' : 'Ẩn danh' }}
                                    </div>

                                    <div class="flex-1">
                                        <!-- Stars & Date -->
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="flex text-yellow-400">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i data-lucide="star"
                                                        class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : '' }}"></i>
                                                @endfor
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</div>
                                        </div>

                                        <!-- Nội dung -->
                                        <p class="text-gray-700 mb-2">{{ $review->comment }}</p>

                                        <!-- Hữu ích + Trả lời -->
                                        <div class="flex space-x-4 text-sm text-gray-500">
                                            <form method="POST" action="{{ route('reviews.useful', $review->id) }}">
                                                @csrf
                                                <button class="flex items-center space-x-1 hover:text-blue-600">
                                                    <i data-lucide="thumbs-up" class="w-4 h-4"></i>
                                                    <span>Hữu ích ({{ $review->useful_count ?? 0 }})</span>
                                                </button>
                                            </form>

                                            @auth
                                                <button type="button" class="flex items-center space-x-1 hover:text-blue-600"
                                                    onclick="toggleReplyForm({{ $review->id }})">
                                                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                                                    <span>Trả lời</span>
                                                </button>
                                            @endauth
                                        </div>

                                        <!-- Phản hồi -->
                                        @if ($review->replies->count())
                                            <div class="mt-3 space-y-2 border-l pl-4 border-gray-200">
                                                @foreach ($review->replies as $reply)
                                                    <div class="text-sm bg-gray-100 p-2 rounded">
                                                        <strong>{{ $reply->user->name ?? 'Ẩn danh' }}</strong>: {{ $reply->content }}
                                                        <div class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Form phản hồi -->
                                        @auth
                                            <form id="reply-form-{{ $review->id }}" class="mt-3 hidden" method="POST"
                                                action="{{ route('reviews.replies.store', $review->id) }}">
                                                @csrf
                                                <textarea name="content" rows="2" class="w-full border rounded p-2 text-sm"
                                                    placeholder="Nhập phản hồi..."></textarea>
                                                <div class="text-right mt-2">
                                                    <button type="submit"
                                                        class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700 text-sm">
                                                        Gửi phản hồi
                                                    </button>
                                                </div>
                                            </form>
                                        @endauth

                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">Chưa có đánh giá nào.</p>
                        @endforelse
                    </div>

                    <!-- Gửi đánh giá hoặc phản hồi -->
                    @auth
                        <div class="bg-gray-50 p-6 rounded-lg border">
                            <h3 class="text-lg font-semibold mb-4">Gửi đánh giá của bạn</h3>

                            @if (session('success'))
                                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if (!$appointment)
                                <p class="text-sm text-gray-500">Bạn cần hoàn tất một cuộc hẹn với bác sĩ để gửi đánh giá.</p>
                            @elseif ($alreadyReviewed && $userReview)
                                <!-- Đã đánh giá => hiển thị lại và cho phép nhận xét thêm -->
                                <div class="mb-4 p-4 border rounded bg-gray-100">
                                    <p class="text-sm mb-1 text-gray-700">Bạn đã đánh giá: <strong>{{ $userReview->rating }}
                                            sao</strong></p>
                                    <p class="text-sm text-gray-800 italic">"{{ $userReview->comment }}"</p>
                                </div>

                                <form method="POST" action="{{ route('reviews.replies.store', $userReview->id) }}">
                                    @csrf
                                    <div class="mb-2">
                                        <label for="content" class="block text-sm font-medium mb-1">Bổ sung nhận xét:</label>
                                        <textarea name="content" rows="2" class="w-full border rounded p-2 text-sm"
                                            placeholder="Nhận xét thêm về trải nghiệm của bạn..."></textarea>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-medium">
                                            Gửi phản hồi bổ sung
                                        </button>
                                    </div>
                                </form>
                            @else
                                <!-- Gửi đánh giá mới -->
                                <form action="{{ route('reviews.store', $doctor->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                                    @if ($appointment->service)
                                        <input type="hidden" name="service_id" value="{{ $appointment->service->id }}">
                                    @endif

                                    <div class="flex items-center mb-4">
                                        <label class="mr-4">Đánh giá:</label>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <label class="cursor-pointer mr-2 flex items-center">
                                                <input type="radio" name="rating" value="{{ $i }}" class="hidden rating-input" required>
                                                <i data-lucide="star" class="w-6 h-6 text-gray-300 rating-star"
                                                    data-index="{{ $i }}"></i>
                                            </label>
                                        @endfor
                                    </div>

                                    <div class="mb-4">
                                        <label for="comment" class="block text-sm font-medium mb-1">Nhận xét:</label>
                                        <textarea name="comment" id="comment" rows="3" class="w-full border rounded p-2 text-sm"
                                            placeholder="Nhận xét của bạn..." required>{{ old('comment') }}</textarea>
                                    </div>

                                    <div class="text-right">
                                        <button type="submit"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-medium">
                                            Gửi đánh giá
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @endauth
                </div>

                <!-- JS: Toggle Reply Form + Rating -->
                <script>
                    function toggleReplyForm(id) {
                        const form = document.getElementById(`reply-form-${id}`);
                        if (form) {
                            form.classList.toggle('hidden');
                        }
                    }

                    document.addEventListener('DOMContentLoaded', function () {
                        const ratingInputs = document.querySelectorAll('input[name="rating"]');
                        const stars = document.querySelectorAll('.rating-star');

                        ratingInputs.forEach(input => {
                            input.addEventListener('change', function () {
                                const selectedRating = parseInt(this.value);

                                stars.forEach((star, index) => {
                                    if (index < selectedRating) {
                                        star.classList.add('text-yellow-400');
                                        star.classList.remove('text-gray-300');
                                    } else {
                                        star.classList.remove('text-yellow-400');
                                        star.classList.add('text-gray-300');
                                    }
                                });
                            });
                        });

                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    });
                </script>









            </div>



            <!-- Location Tab -->
            <div id="content-location" class="tab-content hidden">
                <div class="bg-white rounded-2xl shadow-lg p-8 card-hover">
                    <h2 class="text-2xl font-bold mb-6 gradient-text">Địa Điểm Khám</h2>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Thông Tin Phòng Khám</h3>
                            <div class="space-y-4">
                                <div class="flex items-start space-x-3">
                                    <i data-lucide="map-pin" class="w-5 h-5 text-blue-600 mt-1"></i>
                                    <div>
                                        <p class="font-medium">Bệnh viện Đa khoa Trung ương</p>
                                        <p class="text-gray-600">123 Đường Láng, Đống Đa, Hà Nội</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <i data-lucide="phone" class="w-5 h-5 text-blue-600 mt-1"></i>
                                    <div>
                                        <p class="font-medium">Hotline</p>
                                        <p class="text-gray-600">1900 232 389</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <i data-lucide="clock" class="w-5 h-5 text-blue-600 mt-1"></i>
                                    <div>
                                        <p class="font-medium">Giờ làm việc</p>
                                        <p class="text-gray-600">T2-T6: 8:00 - 17:30</p>
                                        <p class="text-gray-600">T7: 8:00 - 12:00</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <i data-lucide="car" class="w-5 h-5 text-blue-600 mt-1"></i>
                                    <div>
                                        <p class="font-medium">Đỗ xe</p>
                                        <p class="text-gray-600">Có bãi đỗ xe miễn phí</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="font-semibold mb-3">Cách đi</h4>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <p><strong>Xe bus:</strong> Tuyến 01, 18, 23 - Dừng tại Bến xe Láng</p>
                                    <p><strong>Taxi/Grab:</strong> Di chuyển đến 123 Đường Láng</p>
                                    <p><strong>Xe máy:</strong> Có chỗ gửi xe trong khuôn viên</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Bản Đồ</h3>
                            <div class="bg-gray-200 rounded-lg h-64 flex items-center justify-center">
                                <div class="text-center text-gray-500">
                                    <i data-lucide="map" class="w-12 h-12 mx-auto mb-2"></i>
                                    <p>Bản đồ tương tác</p>
                                    <p class="text-sm">123 Đường Láng, Đống Đa, Hà Nội</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button
                                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                    Mở trong Google Maps
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </main>

        @push('styles')
            <style>
                .gradient-bg {
                    background: linear-gradient(135deg, #667eea 0%, #2563eb 100%);
                }

                .gradient-text {
                    background: linear-gradient(135deg, #667eea 0%, #2563eb 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                }

                .card-hover {
                    transition: all 0.3s ease;
                }

                .card-hover:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                }

                .btn-primary {
                    background: linear-gradient(135deg, #667eea 0%, #2563eb 100%);
                    transition: all 0.3s ease;
                }

                .btn-primary:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
                }

                .skill-tag {
                    background: linear-gradient(135deg, #667eea20 0%, #2563eb20 100%);
                    border: 1px solid #667eea40;
                }

                .timeline-item {
                    position: relative;
                    padding-left: 2rem;
                }

                .timeline-item::before {
                    content: '';
                    position: absolute;
                    left: 0;
                    top: 0.5rem;
                    width: 12px;
                    height: 12px;
                    background: #667eea;
                    border-radius: 50%;
                    border: 3px solid white;
                    box-shadow: 0 0 0 3px #667eea20;
                }

                .timeline-item:not(:last-child)::after {
                    content: '';
                    position: absolute;
                    left: 5px;
                    top: 1.5rem;
                    width: 2px;
                    height: calc(100% + 1rem);
                    background: linear-gradient(to bottom, #667eea, #2563eb);
                    opacity: 0.3;
                }

                .rating-star {
                    color: #fbbf24;
                }

                .fade-in {
                    opacity: 0;
                    transform: translateY(20px);
                    animation: fadeIn 0.6s ease forwards;
                }

                @keyframes fadeIn {
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .calendar-slot {
                    transition: all 0.3s ease;
                }

                .calendar-slot:hover {
                    background: #667eea;
                    color: white;
                    transform: scale(1.05);
                }

                .calendar-slot.selected {
                    background: #667eea;
                    color: white;
                }

                .calendar-slot.unavailable {
                    background: #f3f4f6;
                    color: #9ca3af;
                    cursor: not-allowed;
                }

                .scroll-smooth {
                    scroll-behavior: smooth;
                }

                .sticky-header {
                    backdrop-filter: blur(10px);
                    background: rgba(255, 255, 255, 0.9);
                }
            </style>
        @endpush



        @push('scripts')
            <script>
                // Initialize Lucide icons
                lucide.createIcons();

                // Tab functionality
                function showTab(tabName) {
                    // Hide all content
                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Remove active class from all tabs
                    document.querySelectorAll('.tab-btn').forEach(btn => {
                        btn.classList.remove('border-blue-600', 'text-blue-600');
                        btn.classList.add('border-transparent', 'text-gray-600');
                    });

                    // Show selected content
                    document.getElementById(`content-${tabName}`).classList.remove('hidden');

                    // Add active class to selected tab
                    const activeTab = document.getElementById(`tab-${tabName}`);
                    activeTab.classList.remove('border-transparent', 'text-gray-600');
                    activeTab.classList.add('border-blue-600', 'text-blue-600');
                }

                // Date selection
                function selectDate(button) {
                    document.querySelectorAll('.date-btn').forEach(btn => {
                        btn.classList.remove('bg-blue-600', 'text-white');
                        btn.classList.add('bg-gray-200', 'text-gray-700');
                    });

                    button.classList.remove('bg-gray-200', 'text-gray-700');
                    button.classList.add('bg-blue-600', 'text-white');
                }

                // Time slot selection
                function selectTime(button) {
                    if (button.classList.contains('unavailable')) {
                        return;
                    }

                    document.querySelectorAll('.calendar-slot').forEach(slot => {
                        slot.classList.remove('selected');
                    });

                    button.classList.add('selected');
                }

                // Scroll to booking form
                function scrollToBooking() {
                    document.getElementById('booking-form').scrollIntoView({
                        behavior: 'smooth'
                    });
                }

                // Add fade-in animation on scroll
                function animateOnScroll() {
                    const elements = document.querySelectorAll('.fade-in');
                    elements.forEach(element => {
                        const elementTop = element.getBoundingClientRect().top;
                        const elementVisible = 150;

                        if (elementTop < window.innerHeight - elementVisible) {
                            element.style.opacity = '1';
                            element.style.transform = 'translateY(0)';
                        }
                    });
                }

                // Initialize animations
                window.addEventListener('scroll', animateOnScroll);
                window.addEventListener('load', animateOnScroll);
            </script>

            <script>
                document.querySelectorAll('.reply-toggle').forEach(button => {
                    button.addEventListener('click', function () {
                        const reviewId = this.getAttribute('data-review-id');
                        const form = document.getElementById('reply-form-' + reviewId);
                        form.classList.toggle('hidden');
                    });
                });
            </script>
        @endpush
@endsection