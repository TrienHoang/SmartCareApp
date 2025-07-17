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

                <!-- Schedule Tab -->
                <div id="content-schedule" class="tab-content hidden">
                    <div class="bg-white rounded-2xl shadow-lg p-8 card-hover">
                        <h2 class="text-2xl font-bold mb-6 gradient-text">Lịch Khám</h2>

                        <!-- Date Selector -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Chọn ngày</label>
                            <div class="flex space-x-2 overflow-x-auto pb-2">
                                <button onclick="selectDate(this)"
                                    class="date-btn flex-shrink-0 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium">
                                    <div>T2</div>
                                    <div>15/7</div>
                                </button>
                                <button onclick="selectDate(this)"
                                    class="date-btn flex-shrink-0 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300">
                                    <div>T3</div>
                                    <div>16/7</div>
                                </button>
                                <button onclick="selectDate(this)"
                                    class="date-btn flex-shrink-0 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300">
                                    <div>T4</div>
                                    <div>17/7</div>
                                </button>
                                <button onclick="selectDate(this)"
                                    class="date-btn flex-shrink-0 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300">
                                    <div>T5</div>
                                    <div>18/7</div>
                                </button>
                                <button onclick="selectDate(this)"
                                    class="date-btn flex-shrink-0 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300">
                                    <div>T6</div>
                                    <div>19/7</div>
                                </button>
                            </div>
                        </div>

                        <!-- Time Slots -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Buổi Sáng</h3>
                            <div class="grid grid-cols-3 md:grid-cols-4 gap-3 mb-6">
                                <button onclick="selectTime(this)"
                                    class="calendar-slot p-3 border border-gray-300 rounded-lg text-center hover:bg-blue-600 hover:text-white transition-colors">
                                    08:00
                                </button>
                                <button onclick="selectTime(this)"
                                    class="calendar-slot p-3 border border-gray-300 rounded-lg text-center hover:bg-blue-600 hover:text-white transition-colors">
                                    08:30
                                </button>
                                <button onclick="selectTime(this)"
                                    class="calendar-slot p-3 border border-gray-300 rounded-lg text-center hover:bg-blue-600 hover:text-white transition-colors">
                                    09:00
                                </button>
                                <button onclick="selectTime(this)"
                                    class="calendar-slot unavailable p-3 border border-gray-300 rounded-lg text-center bg-gray-100 text-gray-400 cursor-not-allowed">
                                    09:30
                                </button>
                                <button onclick="selectTime(this)"
                                    class="calendar-slot p-3 border border-gray-300 rounded-lg text-center hover:bg-blue-600 hover:text-white transition-colors">
                                    10:00
                                </button>
                                <button onclick="selectTime(this)"
                                    class="calendar-slot p-3 border border-gray-300 rounded-lg text-center hover:bg-blue-600 hover:text-white transition-colors">
                                    10:30
                                </button>
                                <button onclick="selectTime(this)"
                                    class="calendar-slot p-3 border border-gray-300 rounded-lg text-center hover:bg-blue-600 hover:text-white transition-colors">
                                    11:00
                                </button>
                                <button onclick="selectTime(this)"
                                    class="calendar-slot unavailable p-3 border border-gray-300 rounded-lg text-center bg-gray-100 text-gray-400 cursor-not-allowed">
                                    11:30
                                </button>
                            </div>

                            <h3 class="text-lg font-semibold mb-4">Buổi Chiều</h3>
                            <div class="grid grid-cols-3 md:grid-cols-4 gap-3">
                                <button onclick="selectTime(this)"
                                    class="calendar-slot p-3 border border-gray-300 rounded-lg text-center hover:bg-blue-600 hover:text-white transition-colors">
                                    14:00
                                </button>
                                <button onclick="selectTime(this)"
                                    class="calendar-slot p-3 border border-gray-300 rounded-lg text-center hover:bg-blue-600 hover:text-white transition-colors">
                                    14:30
                                </button>
                                <button onclick="selectTime(this)"
                                    class="calendar-slot unavailable p-3 border border-gray-300 rounded-lg text-center bg-gray-100 text-gray-400 cursor-not-allowed">
                                    15:00
                                </button>
                                <button onclick="selectTime(this)"
                                    class="calendar-slot p-3 border border-gray-300 rounded-lg text-center hover:bg-blue-600 hover:text-white transition-colors">
                                    15:30
                                </button>
                                <button onclick="selectTime(this)"
                                    class="calendar-slot p-3 border border-gray-300 rounded-lg text-center hover:bg-blue-600 hover:text-white transition-colors">
                                    16:00
                                </button>
                                <button onclick="selectTime(this)"
                                    class="calendar-slot p-3 border border-gray-300 rounded-lg text-center hover:bg-blue-600 hover:text-white transition-colors">
                                    16:30
                                </button>
                                <button onclick="selectTime(this)"
                                    class="calendar-slot p-3 border border-gray-300 rounded-lg text-center hover:bg-blue-600 hover:text-white transition-colors">
                                    17:00
                                </button>
                                <button onclick="selectTime(this)"
                                    class="calendar-slot unavailable p-3 border border-gray-300 rounded-lg text-center bg-gray-100 text-gray-400 cursor-not-allowed">
                                    17:30
                                </button>
                            </div>
                        </div>

                        <div
                            class="flex flex-col md:flex-row md:items-center md:justify-between pt-6 border-t border-gray-200 gap-4">
                            <div class="text-sm text-gray-600">
                                <span class="inline-block w-3 h-3 bg-blue-600 rounded mr-2"></span>
                                Có thể đặt lịch
                                <span class="inline-block w-3 h-3 bg-gray-300 rounded mr-2 ml-4"></span>
                                Đã hết lịch
                            </div>
                            <button class="btn-primary text-white px-6 py-2 rounded-lg font-semibold mt-2 md:mt-0">
                                Xác nhận đặt lịch
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div id="content-reviews" class="tab-content hidden">
                    <div class="bg-white rounded-2xl shadow-lg p-8 card-hover">
                        <h2 class="text-2xl font-bold mb-6 gradient-text">Đánh Giá Từ Bệnh Nhân</h2>

                        <!-- Rating Summary -->
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-6 rounded-xl mb-6">
                            <div class="flex flex-col md:flex-row items-center justify-between">
                                <div class="text-center">
                                    <div class="text-4xl font-bold text-blue-600 mb-2">4.9</div>
                                    <div class="flex text-yellow-400 mb-2">
                                        <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                                        <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                                        <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                                        <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                                        <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                                    </div>
                                    <div class="text-sm text-gray-600">127 đánh giá</div>
                                </div>
                                <div class="flex-1 md:ml-8 w-full">
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <span class="text-sm w-8">5★</span>
                                            <div class="flex-1 bg-gray-200 rounded-full h-2 mx-2">
                                                <div class="bg-yellow-400 h-2 rounded-full" style="width: 78%"></div>
                                            </div>
                                            <span class="text-sm w-8">98</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="text-sm w-8">4★</span>
                                            <div class="flex-1 bg-gray-200 rounded-full h-2 mx-2">
                                                <div class="bg-yellow-400 h-2 rounded-full" style="width: 18%"></div>
                                            </div>
                                            <span class="text-sm w-8">23</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="text-sm w-8">3★</span>
                                            <div class="flex-1 bg-gray-200 rounded-full h-2 mx-2">
                                                <div class="bg-yellow-400 h-2 rounded-full" style="width: 3%"></div>
                                            </div>
                                            <span class="text-sm w-8">4</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="text-sm w-8">2★</span>
                                            <div class="flex-1 bg-gray-200 rounded-full h-2 mx-2">
                                                <div class="bg-yellow-400 h-2 rounded-full" style="width: 1%"></div>
                                            </div>
                                            <span class="text-sm w-8">1</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="text-sm w-8">1★</span>
                                            <div class="flex-1 bg-gray-200 rounded-full h-2 mx-2">
                                                <div class="bg-yellow-400 h-2 rounded-full" style="width: 0%"></div>
                                            </div>
                                            <span class="text-sm w-8">1</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Individual Reviews -->
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 pb-6">
                                <div class="flex items-start space-x-4">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold">L***</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <div class="flex text-yellow-400">
                                                <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                            </div>
                                            <span class="text-sm text-gray-500">3 ngày trước</span>
                                        </div>
                                        <p class="text-gray-700 mb-2">
                                            Bác sĩ An rất tận tâm và chuyên nghiệp. Khám rất kỹ và giải thích rõ ràng về
                                            tình trạng bệnh. Tôi cảm thấy rất an tâm khi khám với bác sĩ.
                                        </p>
                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            <button class="flex items-center space-x-1 hover:text-blue-600">
                                                <i data-lucide="thumbs-up" class="w-4 h-4"></i>
                                                <span>Hữu ích (12)</span>
                                            </button>
                                            <button class="flex items-center space-x-1 hover:text-blue-600">
                                                <i data-lucide="message-circle" class="w-4 h-4"></i>
                                                <span>Trả lời</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border-b border-gray-200 pb-6">
                                <div class="flex items-start space-x-4">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <span class="text-green-600 font-semibold">N***</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <div class="flex text-yellow-400">
                                                <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                            </div>
                                            <span class="text-sm text-gray-500">1 tuần trước</span>
                                        </div>
                                        <p class="text-gray-700 mb-2">
                                            Đặt lịch qua SmartCare rất tiện lợi. Bác sĩ An khám rất tỉ mỉ, đúng giờ hẹn.
                                            Tôi sẽ quay lại lần sau và giới thiệu cho bạn bè.
                                        </p>
                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            <button class="flex items-center space-x-1 hover:text-blue-600">
                                                <i data-lucide="thumbs-up" class="w-4 h-4"></i>
                                                <span>Hữu ích (8)</span>
                                            </button>
                                            <button class="flex items-center space-x-1 hover:text-blue-600">
                                                <i data-lucide="message-circle" class="w-4 h-4"></i>
                                                <span>Trả lời</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border-b border-gray-200 pb-6">
                                <div class="flex items-start space-x-4">
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                        <span class="text-purple-600 font-semibold">T***</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <div class="flex text-yellow-400">
                                                <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                <i data-lucide="star" class="w-4 h-4"></i>
                                            </div>
                                            <span class="text-sm text-gray-500">2 tuần trước</span>
                                        </div>
                                        <p class="text-gray-700 mb-2">
                                            Bác sĩ giải thích rất dễ hiểu về tình trạng tim mạch của tôi. Nhân viên y tế
                                            cũng rất thân thiện. Chỉ có điều phòng khám hơi nhỏ.
                                        </p>
                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            <button class="flex items-center space-x-1 hover:text-blue-600">
                                                <i data-lucide="thumbs-up" class="w-4 h-4"></i>
                                                <span>Hữu ích (5)</span>
                                            </button>
                                            <button class="flex items-center space-x-1 hover:text-blue-600">
                                                <i data-lucide="message-circle" class="w-4 h-4"></i>
                                                <span>Trả lời</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-8">
                            <button class="text-blue-600 hover:text-blue-800 font-semibold">
                                Xem thêm đánh giá
                            </button>
                        </div>
                    </div>
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
        @endpush
@endsection