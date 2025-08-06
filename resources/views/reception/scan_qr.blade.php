@extends('reception.dashboard')

@section('title', 'Check-in Bệnh nhân')

@push('styles')
    <style>
        #reader {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            border: 2px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .result-box {
            background-color: #f9f9f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

        .success-message {
            color: #10b981;
            /* green-500 */
        }

        .error-message {
            color: #ef4444;
            /* red-500 */
        }

        .camera-permission-error {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }

        /* CSS cho vé khám */
        #printArea {
            display: none;
        }

        .ticket {
            border: 2px solid #1e40af;
            /* Viền xanh đậm */
            background-color: #ffffff;
            /* Nền trắng */
            padding: 20px;
            max-width: 350px;
            margin: 0 auto;
            text-align: center;
            border-radius: 10px;
            /* Góc bo tròn */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Hiệu ứng bóng */
            font-family: 'Arial', sans-serif;
        }

        .ticket h2 {
            font-size: 1.8rem;
            color: #1e40af;
            /* Màu xanh đậm cho tiêu đề */
            margin-bottom: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            /* Chữ in hoa */
            border-bottom: 2px solid #1e40af;
            /* Đường kẻ dưới tiêu đề */
            padding-bottom: 5px;
        }

        .ticket p {
            margin: 0.8rem 0;
            font-size: 1.1rem;
            color: #374151;
            /* Màu chữ xám đậm */
            line-height: 1.5;
        }

        .ticket p strong {
            color: #1e40af;
            /* Màu xanh đậm cho nhãn */
            font-weight: 600;
        }

        .ticket .highlight {
            background-color: #e0f2fe;
            /* Nền nhạt cho thông tin quan trọng */
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }

        /* Tùy chỉnh cho ngày in */
        .ticket p:last-child {
            font-style: italic;
            color: #6b7280;
            /* Màu xám nhạt */
            font-size: 0.9rem;
            margin-top: 1rem;
            border-top: 1px dashed #1e40af;
            /* Đường kẻ đứt nét trên ngày in */
            padding-top: 0.5rem;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #printArea,
            #printArea * {
                visibility: visible;
            }

            #printArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
                font-family: Arial, sans-serif;
            }

            .ticket {
                border: 1px solid #000;
                padding: 20px;
                max-width: 300px;
                margin: 0 auto;
                text-align: center;
            }

            .ticket h2 {
                font-size: 1.5rem;
                margin-bottom: 1rem;
            }

            .ticket p {
                margin: 0.5rem 0;
                font-size: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Check-in Bệnh nhân</h1>

        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Quét mã QR</h2>

            <div id="cameraError" class="hidden bg-red-50 p-4 rounded-md">
                <h3 class="text-red-700 font-semibold mb-2">Không thể truy cập camera</h3>
                <p id="cameraErrorMessage" class="text-red-600 text-sm mb-3"></p>
                <button id="retryCamera" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                    Thử lại
                </button>
            </div>

            <div id="reader" class="relative">
                <div id="loadingSpinner"
                    class="hidden absolute inset-0 flex items-center justify-center bg-gray-100 bg-opacity-75">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-600"></div>
                </div>
            </div>

            <div id="result" class="hidden mt-4">
                <h3 class="text-lg font-semibold mb-2">Kết quả Check-in</h3>
                <p id="statusMessage" class="font-medium mb-4"></p>
                <div id="appointmentDetails" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700"></div>
                <div class="mt-6 flex flex-wrap gap-4">
                    <button id="scanAgainBtn"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition hidden">
                        Quét lại
                    </button>
                    <button id="confirmCheckinBtn"
                        class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition hidden">
                        Xác nhận Check-in
                    </button>
                    <button id="printTicketBtn"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition hidden">
                        In phiếu khám
                    </button>
                </div>
            </div>
        </div>

        <div id="printArea" class="hidden"></div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        class QRScanner {
            constructor() {
                this.html5QrCode = null;
                this.lastAppointmentData = null;
                this.isScanning = false;
                this.elements = {
                    result: document.getElementById('result'),
                    statusMessage: document.getElementById('statusMessage'),
                    appointmentDetails: document.getElementById('appointmentDetails'),
                    scanAgainBtn: document.getElementById('scanAgainBtn'),
                    printTicketBtn: document.getElementById('printTicketBtn'),
                    cameraError: document.getElementById('cameraError'),
                    cameraErrorMessage: document.getElementById('cameraErrorMessage'),
                    retryCamera: document.getElementById('retryCamera'),
                    confirmCheckinBtn: document.getElementById('confirmCheckinBtn'),
                    loadingSpinner: document.getElementById('loadingSpinner')
                };

                this.cameraConfigs = [{
                        facingMode: 'environment'
                    },
                    {
                        facingMode: 'user'
                    },
                    {
                        facingMode: {
                            exact: 'environment'
                        }
                    },
                    {
                        facingMode: {
                            exact: 'user'
                        }
                    }
                ];

                this.setupEventListeners();
            }

            setupEventListeners() {
                this.elements.scanAgainBtn.addEventListener('click', () => this.startScanner());
                this.elements.retryCamera.addEventListener('click', () => this.startScanner());
                this.elements.printTicketBtn.addEventListener('click', () => this.printTicket());
                this.elements.confirmCheckinBtn.addEventListener('click', () => this.confirmCheckin());
                window.addEventListener('beforeunload', () => this.cleanup());
            }

            async startScanner() {
                // Bỏ this.cleanup() ở đây
                if (this.isScanning) {
                    return;
                }
                this.isScanning = true;

                this.resetUI();
                this.showLoading(true);

                try {
                    await this.initializeScanner();
                } catch (err) {
                    this.handleCameraError(err);
                    this.isScanning = false;
                }
            }

            resetUI() {
                this.elements.result.classList.add('hidden');
                this.elements.cameraError.classList.add('hidden');
                this.elements.appointmentDetails.innerHTML = '';
                this.elements.scanAgainBtn.classList.add('hidden');
                this.elements.printTicketBtn.classList.add('hidden');
                this.elements.confirmCheckinBtn.classList.add('hidden');
                this.elements.statusMessage.classList.remove('success-message', 'error-message');
            }

            showLoading(show) {
                this.elements.loadingSpinner.classList.toggle('hidden', !show);
            }

            async initializeScanner() {
                if (this.html5QrCode && this.html5QrCode.getState() === Html5QrcodeScannerState.SCANNING) {
                    await this.html5QrCode.stop().catch(console.error);
                }

                this.html5QrCode = new Html5Qrcode('reader');
                const config = {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    },
                    aspectRatio: 1.0
                };

                let configIndex = 0;

                const tryCamera = async () => {
                    if (configIndex >= this.cameraConfigs.length) {
                        const devices = await Html5Qrcode.getCameras();
                        if (devices?.length > 0) {
                            return this.html5QrCode.start(devices[0].id, config, this.qrCodeSuccessCallback
                                .bind(this));
                        }
                        throw new Error('No cameras found');
                    }

                    try {
                        await this.html5QrCode.start(this.cameraConfigs[configIndex], config, this
                            .qrCodeSuccessCallback.bind(this));
                    } catch (err) {
                        configIndex++;
                        await tryCamera();
                    }
                };

                await tryCamera();
            }

            qrCodeSuccessCallback(decodedText) {
                this.html5QrCode.stop().then(() => {
                    this.isScanning = false;
                    this.processScannedData(decodedText);
                }).catch(console.error);
            }

            showCameraError(message) {
                this.elements.cameraError.classList.remove('hidden');
                this.elements.cameraErrorMessage.textContent = message;
                this.showLoading(false);
            }

            handleCameraError(err) {
                let message = 'Không thể khởi động máy quét QR. ';
                if (err.toString().includes('NotAllowedError') || err.toString().includes('Permission denied')) {
                    message += 'Vui lòng cho phép truy cập camera.';
                } else if (err.toString().includes('NotFoundError') || err.toString().includes('No cameras found')) {
                    message += 'Không tìm thấy camera.';
                } else if (err.toString().includes('NotSupportedError')) {
                    message += 'Trình duyệt không hỗ trợ.';
                } else if (err.toString().includes('NotReadableError')) {
                    message += 'Camera đang được sử dụng bởi ứng dụng khác.';
                } else {
                    message += `Lỗi: ${err.toString()}`;
                }
                this.showCameraError(message);
            }

            async processScannedData(qrCodeData) {
                this.elements.statusMessage.textContent = 'Đang xử lý...';
                this.elements.result.classList.remove('hidden');
                this.elements.confirmCheckinBtn.classList.remove('hidden');
                this.showLoading(true);

                try {
                    const response = await fetch('{{ route('receptionist.process.checkin') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            qr_code_data: qrCodeData
                        })
                    });

                    const data = await response.json();

                    this.elements.statusMessage.textContent = data.message || 'Xử lý hoàn tất';
                    this.elements.statusMessage.classList.toggle('success-message', data.success);
                    this.elements.statusMessage.classList.toggle('error-message', !data.success);

                    if (data.success && data.appointment) {
                        this.lastAppointmentData = data.appointment;
                        this.elements.appointmentDetails.innerHTML = `
                            <p><strong>Mã cuộc hẹn:</strong> ${data.appointment.id}</p>
                            <p><strong>Bệnh nhân:</strong> ${data.appointment.patient_name}</p>
                            <p><strong>Số điện thoại:</strong> ${data.appointment.patient_phone}</p>
                            <p><strong>Bác sĩ:</strong> ${data.appointment.doctor_name}</p>
                            <p><strong>Dịch vụ:</strong> ${data.appointment.service_name}</p>
                            <p><strong>Phòng khám:</strong> ${data.appointment.room}</p>
                            <p><strong>Khoa:</strong> ${data.appointment.department}</p>
                            <p><strong>Thời gian hẹn:</strong> ${data.appointment.appointment_time}</p>
                            <p><strong>Trạng thái:</strong> <span class="text-green-600 font-semibold">${data.appointment.status}</span></p>
                        `;
                        this.elements.printTicketBtn.classList.remove('hidden');
                    }
                } catch (error) {
                    this.elements.statusMessage.textContent = 'Lỗi kết nối hoặc xử lý dữ liệu.';
                    this.elements.statusMessage.classList.add('error-message');
                } finally {
                    this.elements.scanAgainBtn.classList.remove('hidden');
                    this.showLoading(false);
                }
            }

            printTicket() {
                if (!this.lastAppointmentData) {
                    alert('Không có dữ liệu để in.');
                    return;
                }

                const printWindow = window.open('', '', 'height=600,width=400');
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Vé Khám Bệnh</title>
                        <style>
                            body { font-family: Arial, sans-serif; padding: 20px; }
                            .ticket { border: 1px solid #000; padding: 20px; max-width: 300px; margin: 0 auto; text-align: center; }
                            .ticket h2 { font-size: 1.5rem; margin-bottom: 1rem; }
                            .ticket p { margin: 0.5rem 0; font-size: 1rem; }
                        </style>
                    </head>
                    <body>
                        <div class="ticket">
                            <h2>Vé Khám Bệnh</h2>
                            <p><strong>Bệnh nhân:</strong> ${this.lastAppointmentData.patient_name || 'N/A'}</p>
                            <p><strong>Số điện thoại:</strong> ${this.lastAppointmentData.patient_phone || 'N/A'}</p>
                            <p><strong>Bác sĩ:</strong> ${this.lastAppointmentData.doctor_name || 'N/A'}</p>
                            <p><strong>Dịch vụ:</strong> ${this.lastAppointmentData.service_name || 'N/A'}</p>
                            <p><strong>Phòng khám:</strong> ${this.lastAppointmentData.room}</p>
                            <p><strong>Khoa:</strong> ${this.lastAppointmentData.department}</p>
                            <p><strong>Thời gian hẹn:</strong> ${this.lastAppointmentData.appointment_time || 'N/A'}</p>
                            <p><strong>Ngày in:</strong> ${new Date().toLocaleString('vi-VN')}</p>
                        </div>
                    </body>
                    </html>
                `);
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            }

            async confirmCheckin() {
                if (!this.lastAppointmentData) {
                    alert('Không có dữ liệu để xác nhận check-in.');
                    return;
                }

                this.showLoading(true);

                try {
                    const response = await fetch('{{ route('receptionist.confirm.checkin') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            appointment_id: this.lastAppointmentData.id
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        alert('Check-in thành công!');
                        this.elements.appointmentDetails.querySelector('p:last-child').innerHTML =
                            `<strong>Trạng thái:</strong> <span class="text-green-600 font-semibold">checked_in</span>`;
                        this.elements.printTicketBtn.classList.remove('hidden');
                    } else {
                        alert(data.message || 'Có lỗi khi check-in.');
                    }
                } catch (error) {
                    alert('Lỗi mạng hoặc server.');
                } finally {
                    this.showLoading(false);
                }
            }

            cleanup() {
                // Chỉ dừng máy quét nếu nó tồn tại và đang chạy
                if (this.html5QrCode && this.html5QrCode.getState() === Html5QrcodeScannerState.SCANNING) {
                    this.html5QrCode.stop().catch(console.error);
                }
                this.isScanning = false;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => new QRScanner().startScanner(), 500);
        });
    </script>
@endpush
