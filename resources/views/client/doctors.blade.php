@extends('client.layouts.app')

@section('title', 'Đội ngũ bác sĩ')

@push('styles')
    <style>
        .doctor-banner {
            position: relative;
            width: 100%;
            height: 800px;
            overflow: hidden;
        }

        .banner-bg {
            background: url({{ asset('admin/assets/img/doctor-banner-bg-2.png') }}) center center / 100% 100% no-repeat;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .banner-person {
            background:  url({{ asset('admin/assets/img/doctor-banner-person-1.png') }}) right / contain no-repeat;
            position: absolute;
            top: 0px;
            right: 0;
            width: 100%;
            height: 700px;
            z-index: 1;
            mask-image: linear-gradient(to bottom, black 0%, transparent 100%);
        }

        .bg-doctor-top {
            background-image:  url({{ asset('admin/assets/img/doctor-item-top-bg.png') }});
            background-size: cover;
            background-position: top center;
        }

        .doctor-banner .container {
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
        }

        .content {
            position: relative;
            z-index: 4;
            max-width: 600px;
            color: #fff;
            padding-top: 100px;
            padding-left: 15px
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Filter animations */
        .filter-fade-in {
            animation: filterFadeIn 0.3s ease-out;
        }

        @keyframes filterFadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Doctor card hover effect */
        .doctor-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .doctor-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        /* Loading spinner */
        .loading-spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Search input focus effect */
        .search-input:focus {
            ring: 2px solid #3b82f6;
            border-color: #3b82f6;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .doctor-banner {
                height: 600px;
            }

            .content{
                padding: 0px 10px;
            }

            .banner-person{
                display: none
            }

            .banner-person {
                width: 70%;
                opacity: 0.6;
            }

            .content {
                padding-top: 60px;
            }

            .content h2 {
                font-size: 1.75rem;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Hero Banner -->
    <div class="doctor-banner">
        <div class="banner-bg"></div>
        <div class="container">
            <div class="banner-person"></div>
            <div class="content">
                <h2 class="text-3xl md:text-4xl font-bold mb-6 leading-tight">
                    Đội ngũ Bác sĩ ưu tú từ các<br>
                    <span class="text-yellow-300 mt-4">Bệnh viện hàng đầu</span>
                </h2>
                <p class="text-lg text-blue-100 leading-relaxed max-w-lg">
                    Đội ngũ Bác sĩ ưu tú với thâm niên trung bình 10 năm kinh nghiệm hiện công tác
                    tại các Bệnh viện hàng đầu Việt Nam, thăm khám trên nhiều chuyên khoa đa dạng.
                </p>
            </div>
        </div>
    </div>

    <!-- Doctors List Section -->
    <div class="max-w-7xl mx-auto mt-[-100px] relative z-20 px-4">
        <div class="bg-white rounded-3xl shadow-xl p-8">
            <!-- Header with Search and Filter -->
            <div class="mb-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold gradient-text mb-2">Danh sách bác sĩ</h2>
                    <p class="text-gray-600">Tìm bác sĩ phù hợp với nhu cầu của bạn</p>
                </div>

                <!-- Search and Filter Bar -->
                <div class="flex flex-col md:flex-row gap-4 mb-6">
                    <!-- Search Input -->
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input type="text" id="doctorSearch" placeholder="Tìm kiếm theo tên bác sĩ..."
                            class="search-input w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>

                    <!-- Department Filter -->
                    <div class="md:w-64">
                        <select id="departmentFilter"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white">
                            <option value="">Tất cả chuyên khoa</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort Options -->
                    <div class="md:w-48">
                        <select id="sortFilter"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white">
                            <option value="name">Tên A-Z</option>
                            <option value="experience">Kinh nghiệm</option>
                            <option value="rating">Đánh giá</option>
                        </select>
                    </div>
                </div>

                <!-- Active Filters Display -->
                <div id="activeFilters" class="flex flex-wrap gap-2 mb-4 hidden">
                    <span class="text-sm text-gray-600">Đang lọc:</span>
                </div>

                <!-- Results Count -->
                <div class="flex justify-between items-center text-sm text-gray-600">
                    <span id="resultsCount">Hiển thị {{ min(12, $doctors->count()) }} / {{ $doctors->count() }} bác
                        sĩ</span>
                    <button id="clearFilters" class="text-blue-600 hover:text-blue-800 hidden">Xóa bộ lọc</button>
                </div>
            </div>

            <!-- Doctors Grid -->
            <div id="doctorsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach ($doctors->take(12) as $doctor)
                    <div class="doctor-card bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm"
                        data-doctor-name="{{ strtolower($doctor->user->full_name) }}"
                        data-doctor-department="{{ $doctor->department->id }}"
                        data-doctor-experience="{{ $doctor->experience_years ?? 0 }}"
                        data-doctor-rating="{{ $doctor->average_rating ?? 0 }}">

                        <a href="{{ route('doctors.show', $doctor->id) }}" class="block">
                            <!-- Doctor Image -->
                            <div class="relative overflow-hidden bg-doctor-top">
                                <img src="{{ $doctor->user->avatar ? asset('storage/' . $doctor->user->avatar) : asset('images/default-avatar.png') }}"
                                    alt="{{ $doctor->user->full_name }}" class="w-full h-64 object-cover object-top">
                                <div
                                    class="absolute bottom-0 left-0 w-full h-20 bg-gradient-to-t from-white/90 to-transparent">
                                </div>
                            </div>

                            <!-- Doctor Info -->
                            <div class="p-5">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2 truncate">
                                    {{ $doctor->user->full_name }}
                                </h3>

                                <!-- Department Badge -->
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                                    <p class="text-green-800 text-sm font-medium text-center">
                                        {{ $doctor->department->name }}
                                    </p>
                                </div>

                                <!-- Experience & Rating -->
                                <div class="space-y-2">
                                    <!-- Experience -->
                                    <div class="flex items-center bg-blue-50 rounded-lg p-2">
                                        <i data-lucide="clock" class="w-4 h-4 text-blue-600 mr-2"></i>
                                        <span class="text-blue-800 text-sm font-medium">
                                            {{ $doctor->experience_years ?? 0 }} năm kinh nghiệm
                                        </span>
                                    </div>

                                    <!-- Rating -->
                                    @if ($doctor->average_rating)
                                        <div class="flex items-center bg-yellow-50 rounded-lg p-2">
                                            <i data-lucide="star" class="w-4 h-4 text-yellow-500 mr-2"></i>
                                            <span class="text-yellow-800 text-sm font-medium">
                                                {{ number_format($doctor->average_rating, 1) }}/5
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Load More Button -->
            @if ($doctors->count() > 6)
                <div class="text-center">
                    <button id="loadMoreBtn"
                        class="inline-flex items-center px-8 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-medium">
                        <span>Xem thêm bác sĩ</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 ml-2"></i>
                    </button>

                    <!-- Loading State -->
                    <button id="loadingBtn"
                        class="hidden inline-flex items-center px-8 py-3 bg-blue-400 text-white rounded-xl cursor-not-allowed font-medium">
                        <div class="loading-spinner mr-3"></div>
                        <span>Đang tải...</span>
                    </button>
                </div>
            @endif

            <!-- No Results Message -->
            <div id="noResults" class="hidden text-center py-12">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="search-x" class="w-10 h-10 text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Không tìm thấy bác sĩ</h3>
                <p class="text-gray-500">Vui lòng thử lại với từ khóa khác hoặc chọn chuyên khoa khác</p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // DOM elements
            const searchInput = document.getElementById('doctorSearch');
            const departmentFilter = document.getElementById('departmentFilter');
            const sortFilter = document.getElementById('sortFilter');
            const doctorsGrid = document.getElementById('doctorsGrid');
            const loadMoreBtn = document.getElementById('loadMoreBtn');
            const loadingBtn = document.getElementById('loadingBtn');
            const resultsCount = document.getElementById('resultsCount');
            const noResults = document.getElementById('noResults');
            const activeFilters = document.getElementById('activeFilters');
            const clearFilters = document.getElementById('clearFilters');

            // Get all doctor data
            const allDoctors = Array.from(document.querySelectorAll('.doctor-card'));
            let filteredDoctors = [...allDoctors];
            let displayedCount = 12;
            const loadStep = 12;

            // Filter and search functionality
            function filterDoctors() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const selectedDepartment = departmentFilter.value;
                const sortBy = sortFilter.value;

                // Filter doctors
                filteredDoctors = allDoctors.filter(doctor => {
                    const matchesSearch = !searchTerm ||
                        doctor.dataset.doctorName.includes(searchTerm);

                    const matchesDepartment = !selectedDepartment ||
                        doctor.dataset.doctorDepartment === selectedDepartment;

                    return matchesSearch && matchesDepartment;
                });

                // Sort doctors
                filteredDoctors.sort((a, b) => {
                    switch (sortBy) {
                        case 'experience':
                            return parseInt(b.dataset.doctorExperience) - parseInt(a.dataset
                                .doctorExperience);
                        case 'rating':
                            return parseFloat(b.dataset.doctorRating) - parseFloat(a.dataset.doctorRating);
                        default: // name
                            return a.dataset.doctorName.localeCompare(b.dataset.doctorName);
                    }
                });

                displayedCount = Math.min(loadStep, filteredDoctors.length);
                updateDisplay();
                updateActiveFilters();
            }

            // Update display
            function updateDisplay() {
                // Hide all doctors
                allDoctors.forEach(doctor => {
                    doctor.style.display = 'none';
                });

                // Show filtered doctors
                const doctorsToShow = filteredDoctors.slice(0, displayedCount);
                doctorsToShow.forEach(doctor => {
                    doctor.style.display = 'block';
                });

                // Update results count
                resultsCount.textContent = `Hiển thị ${doctorsToShow.length} / ${filteredDoctors.length} bác sĩ`;

                // Show/hide load more button
                if (displayedCount >= filteredDoctors.length) {
                    loadMoreBtn.style.display = 'none';
                } else {
                    loadMoreBtn.style.display = 'inline-flex';
                }

                // Show/hide no results
                if (filteredDoctors.length === 0) {
                    noResults.classList.remove('hidden');
                    doctorsGrid.style.display = 'none';
                } else {
                    noResults.classList.add('hidden');
                    doctorsGrid.style.display = 'grid';
                }
            }

            // Update active filters display
            function updateActiveFilters() {
                const filters = [];

                if (searchInput.value.trim()) {
                    filters.push(`Tìm kiếm: "${searchInput.value.trim()}"`);
                }

                if (departmentFilter.value) {
                    const selectedOption = departmentFilter.options[departmentFilter.selectedIndex];
                    filters.push(`Chuyên khoa: ${selectedOption.text}`);
                }

                if (filters.length > 0) {
                    activeFilters.innerHTML = `
                        <span class="text-sm text-gray-600">Đang lọc:</span>
                        ${filters.map(filter => `<span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm">${filter}</span>`).join('')}
                    `;
                    activeFilters.classList.remove('hidden');
                    clearFilters.classList.remove('hidden');
                } else {
                    activeFilters.classList.add('hidden');
                    clearFilters.classList.add('hidden');
                }
            }

            // Event listeners
            searchInput.addEventListener('input', debounce(filterDoctors, 300));
            departmentFilter.addEventListener('change', filterDoctors);
            sortFilter.addEventListener('change', filterDoctors);

            // Clear filters
            clearFilters.addEventListener('click', function() {
                searchInput.value = '';
                departmentFilter.value = '';
                sortFilter.value = 'name';
                displayedCount = loadStep;
                filterDoctors();
            });

            // Load more functionality
            loadMoreBtn.addEventListener('click', function() {
                loadMoreBtn.style.display = 'none';
                loadingBtn.classList.remove('hidden');

                // Simulate loading delay
                setTimeout(() => {
                    displayedCount = Math.min(displayedCount + loadStep, filteredDoctors.length);
                    updateDisplay();

                    loadingBtn.classList.add('hidden');
                    if (displayedCount < filteredDoctors.length) {
                        loadMoreBtn.style.display = 'inline-flex';
                    }
                }, 500);
            });

            // Debounce function
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Initialize
            updateDisplay();
        });
    </script>
@endpush
