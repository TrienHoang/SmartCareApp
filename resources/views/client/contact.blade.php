@extends('client.layouts.app')

@section('title', 'Liên Hệ')

@push('styles')
    <style>
        /* Custom gradient text for headings */
        .gradient-text {
            background: linear-gradient(to right, #3B82F6, #60A5FA);
            /* blue-600 to blue-400 */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Custom gradient background for buttons */
        .gradient-bg {
            background: linear-gradient(to right, #3B82F6, #60A5FA);
        }

        /* Hover effect for cards */
        .hover-scale {
            transition: transform 0.3s ease-in-out;
        }

        .hover-scale:hover {
            transform: translateY(-5px);
        }

        /* Simple toast styling (replace with your actual toast implementation if more complex) */
        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1000;
        }

        .toast {
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .toast-success {
            background-color: #D1FAE5;
            /* green-100 */
            color: #065F46;
            /* green-800 */
            border: 1px solid #A7F3D0;
            /* green-300 */
        }

        .toast-destructive {
            background-color: #FEE2E2;
            /* red-100 */
            color: #991B1B;
            /* red-800 */
            border: 1px solid #FCA5A5;
            /* red-300 */
        }

        .toast-icon {
            margin-right: 0.75rem;
        }
    </style>
@endpush
@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 gradient-text">Liên Hệ Với Chúng Tôi</h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn. Hãy liên hệ để được tư vấn
                    và giải đáp mọi thắc mắc về dịch vụ y tế.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold mb-8 gradient-text">Gửi Tin Nhắn</h2>

                    @if (session('success'))
                        <div class="alert alert-success text-green-600 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif


                    <form id="contactForm" method="POST" action="{{ route('contact.submit') }}" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Họ và tên <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name"
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Nhập họ và tên">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="email" name="email"
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Nhập email">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Số điện
                                    thoại</label>
                                <input type="tel" id="phone" name="phone"
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Nhập số điện thoại">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Chủ đề <span
                                        class="text-red-500">*</span></label>
                                <select id="title" name="title"
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Chọn chủ đề</option>
                                    <option value="Đặt lịch hẹn">Đặt lịch hẹn</option>
                                    <option value="Tư vấn y khoa">Tư vấn y khoa</option>
                                    <option value="Khiếu nại">Khiếu nại</option>
                                    <option value="Góp ý">Góp ý</option>
                                    <option value="Khác">Khác</option>
                                </select>
                                @error('title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Nội dung <span class="text-red-500">*</span>
                            </label>
                            <textarea id="message" name="message" rows="6"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Nhập nội dung tin nhắn..."></textarea>
                            @error('message')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="w-full gradient-bg text-white py-4 rounded-lg font-semibold hover:opacity-90 transition-opacity flex items-center justify-center">Gửi
                            Tin Nhắn</button>
                    </form>

                </div>

                <div class="space-y-8">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h3 class="text-2xl font-bold mb-6 gradient-text">Số Điện Thoại Các Bộ Phận</h3>
                        <div class="space-y-4" id="departmentsInfo">
                        </div>
                    </div>

                    <div class="bg-red-50 border-2 border-red-200 rounded-xl p-8">
                        <h3 class="text-2xl font-bold mb-4 text-red-600">Trường Hợp Khẩn Cấp</h3>
                        <p class="text-red-700 mb-4">
                            Nếu bạn gặp tình huống y tế khẩn cấp, vui lòng gọi ngay:
                        </p>
                        <div class="text-center">
                            <a href="tel:0987654321"
                                class="inline-block bg-red-600 text-white px-8 py-4 rounded-full font-bold text-xl hover:bg-red-700 transition-colors">
                                📞 0999.889.998
                            </a>
                        </div>
                        <p class="text-sm text-red-600 mt-4 text-center">
                            Hoặc đến trực tiếp phòng cấp cứu 24/7
                        </p>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h3 class="text-2xl font-bold mb-6 gradient-text">Câu Hỏi Thường Gặp</h3>
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-semibold mb-2">Làm thế nào để đặt lịch hẹn?</h4>
                                <p class="text-gray-600 text-sm">
                                    Bạn có thể đặt lịch qua website, gọi điện hoặc đến trực tiếp phòng khám.
                                </p>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-2">Có cần thanh toán trước không?</h4>
                                <p class="text-gray-600 text-sm">
                                    Không cần thanh toán trước. Bạn chỉ thanh toán sau khi khám xong.
                                </p>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-2">Có thể hủy lịch hẹn không?</h4>
                                <p class="text-gray-600 text-sm">
                                    Có thể hủy hoặc đổi lịch trước 24h qua điện thoại hoặc website.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-16">
                <h2 class="text-3xl font-bold text-center mb-12 gradient-text">Vị Trí Phòng Khám</h2>
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-xl font-bold mb-4">Hướng Dẫn Di Chuyển</h3>
                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-semibold text-blue-600">🚗 Di chuyển bằng ô tô:</h4>
                                    <p class="text-gray-600">Phòng khám có bãi đỗ xe ô tô miễn phí dành cho bệnh nhân, thuận
                                        tiện và dễ tìm.</p>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-blue-600">🚌 Di chuyển bằng xe buýt:</h4>
                                    <p class="text-gray-600">Các tuyến xe buýt số 01, 08, 19 dừng ngay trước cửa phòng khám.
                                    </p>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-blue-600">🚇 Di chuyển bằng tàu điện ngầm (metro):</h4>
                                    <p class="text-gray-600">Ga Metro số 1 chỉ cách phòng khám khoảng 200m, thuận tiện cho
                                        việc đi lại.</p>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-blue-600">🏍️ Di chuyển bằng xe máy:</h4>
                                    <p class="text-gray-600">Khu vực để xe máy rộng rãi, an toàn, có người trông giữ.</p>
                                </div>
                            </div>

                        </div>
                        <div class="rounded-lg overflow-hidden shadow-lg">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.804950433347!2d105.73387658047295!3d21.04048903740518!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3134550ab1db2433%3A0x9febb50e17509deb!2zMTMgUC4gVHLhu4tuaCBWxINuIELDtCwgWHXDom4gUGjGsMahbmcsIE5hbSBU4burIExpw6ptLCBIw6AgTuG7mWksIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1753007264074!5m2!1svi!2s"
                                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="submittedMessage" class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 hidden">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <span id="checkCircleIcon"></span>
            </div>
            <h2 class="text-2xl font-bold mb-4 text-gray-900">Tin Nhắn Đã Được Gửi!</h2>
            <p class="text-gray-600 mb-6">
                Cảm ơn bạn đã liên hệ với chúng tôi. Chúng tôi sẽ phản hồi trong vòng 24h.
            </p>
            <button id="sendNewMessageBtn"
                class="w-full gradient-bg text-white py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity">
                Gửi Tin Nhắn Mới
            </button>
        </div>
    </div>
    <div class="toast-container" id="toastContainer"></div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        // Initialize lucide icons
        lucide.createIcons();
    </script>
    <script src="{{ asset('js/contact.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const contactForm = document.getElementById('contactForm');
            const submittedMessageSection = document.getElementById('submittedMessage');
            const mainContent = document.querySelector('.min-h-screen.bg-gray-50.py-12');
            const sendNewMessageBtn = document.getElementById('sendNewMessageBtn');
            const toastContainer = document.getElementById('toastContainer');

            // Function to show toast messages
            const showToast = (title, description, variant = 'default') => {
                const toastDiv = document.createElement('div');
                toastDiv.classList.add('toast');
                if (variant === 'destructive') {
                    toastDiv.classList.add('toast-destructive');
                } else {
                    toastDiv.classList.add('toast-success');
                }

                let iconSvg = '';
                if (variant === 'destructive') {
                    iconSvg =
                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle toast-icon"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>';
                } else {
                    iconSvg =
                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle toast-icon"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>';
                }

                toastDiv.innerHTML = `
            ${iconSvg}
            <div>
                <div class="font-bold">${title}</div>
                <div class="text-sm">${description}</div>
            </div>
        `;

                toastContainer.appendChild(toastDiv);

                setTimeout(() => {
                    toastDiv.remove();
                }, 5000); // Remove toast after 5 seconds
            };

            // Data for contact info, departments
            const contactInfo = [{
                    iconSvg: '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin w-8 h-8 text-blue-600"><path d="M12 2v20M18 10a6 6 0 0 0-12 0c0 1.58 1.15 3.03 2.5 4.14V17l.5.5c.34.34.8.5 1.3.5h.4a2 2 0 0 0 2-2V13h2v2a2 2 0 0 0 2 2h.4c.5 0 .96-.16 1.3-.5l.5-.5v-2.86c1.35-1.11 2.5-2.56 2.5-4.14Z"/><circle cx="12" cy="10" r="3"/></svg>',
                    title: 'Địa Chỉ',
                    details: [
                        '13 Trịnh Văn Bô, Quận Nam Từ Liêm, Hà Nội',
                    ]
                },
                {
                    iconSvg: '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone w-8 h-8 text-blue-600"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2 3.18 2 2 0 0 1 4.08 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-1.18 2.19l-.7.68a19 19 0 0 0 6 6l.68-.7a2 2 0 0 1 2.19-1.18 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
                    title: 'Điện Thoại',
                    details: [
                        'Hotline: 0999.988.988',
                        'Khẩn cấp: 0936.636.363',
                        'Tư vấn: 0111.222.333'
                    ]
                },
                {
                    iconSvg: '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail w-8 h-8 text-blue-600"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>',
                    title: 'Email',
                    details: [
                        'smartcare@gmai.com',
                        'support@medcare.com',
                        'tuvan@medcare.com'
                    ]
                },
                {
                    iconSvg: '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-8 h-8 text-blue-600"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>',
                    title: 'Giờ Làm Việc',
                    details: [
                        'Thứ 2 - Thứ 7: 7:00 - 17:00',
                        'Khẩn cấp: 24/7'
                    ]
                }
            ];

            const departments = [{
                    name: 'Tổng Đài',
                    phone: '0999.383.838'
                },
                {
                    name: 'Đặt Lịch Hẹn',
                    phone: '0666.456.789'
                },
                {
                    name: 'Khẩn Cấp',
                    phone: '0363.366.666'
                },
                {
                    name: 'Tư Vấn Y Khoa',
                    phone: '0122.242.222'
                },
                {
                    name: 'Kế Toán',
                    phone: '0355.456.553'
                },
                {
                    name: 'Khiếu Nại',
                    phone: '0677.767.766'
                }
            ];

            // Populate Contact Info Cards
            const contactInfoContainer = document.querySelector(
                '.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-4.gap-8.mb-16');
            contactInfo.forEach(info => {
                const card = document.createElement('div');
                card.classList.add('bg-white', 'p-8', 'rounded-xl', 'shadow-lg', 'hover:shadow-xl',
                    'transition-shadow', 'hover-scale');
                card.innerHTML = `
            <div class="mb-6 flex justify-center">${info.iconSvg}</div>
            <h3 class="text-xl font-bold text-center mb-4">${info.title}</h3>
            <div class="space-y-2">
                ${info.details.map(detail => `<p class="text-gray-600 text-center text-sm">${detail}</p>`).join('')}
            </div>
        `;
                contactInfoContainer.appendChild(card);
            });

            // Populate Departments Info
            const departmentsInfoContainer = document.getElementById('departmentsInfo');
            departments.forEach(dept => {
                const deptDiv = document.createElement('div');
                deptDiv.classList.add('flex', 'justify-between', 'items-center', 'py-2', 'border-b',
                    'border-gray-100', 'last:border-b-0');
                deptDiv.innerHTML = `
            <span class="font-medium">${dept.name}</span>
            <a href="tel:${dept.phone}" class="text-blue-600 hover:text-blue-800 font-semibold">
                ${dept.phone}
            </a>
        `;
                departmentsInfoContainer.appendChild(deptDiv);
            });

            // Add icons to buttons and map section
            document.getElementById('sendIcon').innerHTML =
                '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send"><path d="m22 2-7 20-4-9-9-4 20-7Z"/><path d="M9 15l-6 6"/></svg>';
            document.getElementById('checkCircleIcon').innerHTML =
                '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle text-green-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>';
            document.getElementById('mapPinIcon').innerHTML =
                '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin w-12 h-12 text-gray-500"><path d="M12 2v20M18 10a6 6 0 0 0-12 0c0 1.58 1.15 3.03 2.5 4.14V17l.5.5c.34.34.8.5 1.3.5h.4a2 2 0 0 0 2-2V13h2v2a2 2 0 0 0 2 2h.4c.5 0 .96-.16 1.3-.5l.5-.5v-2.86c1.35-1.11 2.5-2.56 2.5-4.14Z"/><circle cx="12" cy="10" r="3"/></svg>';


            // Handle form submission
            contactForm.addEventListener('submit', (e) => {
                e.preventDefault();

                const formData = {
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    subject: document.getElementById('subject').value,
                    message: document.getElementById('message').value
                };

                if (!formData.name || !formData.email || !formData.message) {
                    showToast("Thiếu thông tin", "Vui lòng điền đầy đủ thông tin bắt buộc", "destructive");
                    return;
                }

                // Simulate API call or form submission
                console.log('Form data submitted:', formData);

                // Show submitted message and hide main content
                mainContent.classList.add('hidden');
                submittedMessageSection.classList.remove('hidden');

                showToast("Gửi tin nhắn thành công!", "Chúng tôi sẽ phản hồi trong vòng 24h.", "success");
            });

            // Handle "Send New Message" button click
            sendNewMessageBtn.addEventListener('click', () => {
                submittedMessageSection.classList.add('hidden');
                mainContent.classList.remove('hidden');

                // Reset form fields
                contactForm.reset();
            });
        });
    </script>
@endsection
