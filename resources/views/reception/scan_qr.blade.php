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
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Check-in Bệnh nhân tại quầy lễ tân</h1>

        <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Quét mã QR</h2>

            <!-- Hiển thị hướng dẫn và lỗi camera -->
            <div id="cameraError" class="camera-permission-error hidden">
                <h3 class="text-red-700 font-semibold mb-2">Không thể truy cập camera</h3>
                <p class="text-red-600 text-sm mb-2">Vui lòng:</p>
                <ul class="text-red-600 text-sm list-disc list-inside mb-3">
                    <li>Cho phép truy cập camera khi trình duyệt yêu cầu</li>
                    <li>Đảm bảo trang web được truy cập qua HTTPS</li>
                    <li>Kiểm tra camera không bị ứng dụng khác sử dụng</li>
                    <li>Thử refresh lại trang</li>
                </ul>
                <button id="retryCamera"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-300">
                    Thử lại
                </button>
            </div>

            <div id="reader"></div> {{-- Element để hiển thị máy quét QR --}}

            <div id="result" class="result-box hidden">
                <h3 class="text-lg font-semibold mb-2">Kết quả Check-in:</h3>
                <p id="statusMessage" class="font-medium mb-2"></p>
                <div id="appointmentDetails" class="text-gray-700 text-sm grid grid-cols-1 md:grid-cols-2 gap-2">
                    {{-- Chi tiết cuộc hẹn sẽ được hiển thị ở đây --}}
                </div>
                <div class="mt-4 flex space-x-4">
                    <button id="scanAgainBtn"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 hidden">
                        Quét lại
                    </button>
                    <button id="printTicketBtn"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-300 hidden">
                        In vé khám
                    </button>
                </div>
            </div>
        </div>

        <!-- Khu vực in vé khám -->
        <div id="printArea"></div>
    </div>
@endsection

@push('scripts')
    <!-- Thêm thư viện Html5Qrcode từ CDN -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        const resultDiv = document.getElementById('result');
        const statusMessage = document.getElementById('statusMessage');
        const appointmentDetails = document.getElementById('appointmentDetails');
        const scanAgainBtn = document.getElementById('scanAgainBtn');
        const printTicketBtn = document.getElementById('printTicketBtn');
        const cameraError = document.getElementById('cameraError');
        const retryCamera = document.getElementById('retryCamera');
        const printArea = document.getElementById('printArea');
        let html5QrCode;
        let lastAppointmentData = null; // Lưu dữ liệu cuộc hẹn để in

        function startScanner() {
            console.log('Starting QR scanner...');

            // Reset UI
            resultDiv.classList.add('hidden');
            cameraError.classList.add('hidden');
            appointmentDetails.innerHTML = '';
            scanAgainBtn.classList.add('hidden');
            printTicketBtn.classList.add('hidden');

            // Kiểm tra xem đã có scanner đang chạy không
            if (html5QrCode && html5QrCode.getState() === Html5QrcodeScannerState.SCANNING) {
                console.log('Scanner already running, stopping first...');
                html5QrCode.stop().then(() => {
                    initializeScanner();
                }).catch((err) => {
                    console.error('Failed to stop existing scanner:', err);
                    initializeScanner();
                });
            } else {
                initializeScanner();
            }
        }

        function initializeScanner() {
            html5QrCode = new Html5Qrcode("reader");

            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                console.log('QR Code scanned:', decodedText);
                // Khi quét thành công
                html5QrCode.stop().then((ignore) => {
                    console.log("QR Code scanner stopped successfully.");
                    processScannedData(decodedText);
                }).catch((err) => {
                    console.error("Failed to stop QR Code scanner:", err);
                    processScannedData(decodedText);
                });
            };

            const qrCodeErrorCallback = (errorMessage) => {
                // Bỏ qua lỗi thông thường khi không tìm thấy QR code
                // console.log('QR Code scan error:', errorMessage);
            };

            const config = {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                },
                aspectRatio: 1.0
            };

            // Thử các phương thức khác nhau để truy cập camera
            const cameraConfigs = [{
                    facingMode: "environment"
                }, // Camera sau
                {
                    facingMode: "user"
                }, // Camera trước
                {
                    facingMode: {
                        exact: "environment"
                    }
                },
                {
                    facingMode: {
                        exact: "user"
                    }
                }
            ];

            let configIndex = 0;

            function tryCamera() {
                if (configIndex >= cameraConfigs.length) {
                    // Thử với device ID nếu có
                    Html5Qrcode.getCameras().then(devices => {
                        if (devices && devices.length > 0) {
                            console.log('Found cameras:', devices);
                            // Thử camera đầu tiên
                            html5QrCode.start(devices[0].id, config, qrCodeSuccessCallback, qrCodeErrorCallback)
                                .catch(handleCameraError);
                        } else {
                            handleCameraError('No cameras found');
                        }
                    }).catch(handleCameraError);
                    return;
                }

                console.log('Trying camera config:', cameraConfigs[configIndex]);
                html5QrCode.start(cameraConfigs[configIndex], config, qrCodeSuccessCallback, qrCodeErrorCallback)
                    .then(() => {
                        console.log('Camera started successfully with config:', cameraConfigs[configIndex]);
                    })
                    .catch((err) => {
                        console.error(`Failed with config ${configIndex}:`, err);
                        configIndex++;
                        tryCamera();
                    });
            }

            tryCamera();
        }

        function handleCameraError(err) {
            console.error("Unable to start scanning:", err);
            cameraError.classList.remove('hidden');

            // Hiển thị thông báo lỗi cụ thể
            let errorMessage = 'Không thể khởi động máy quét QR. ';
            if (err.toString().includes('NotAllowedError') || err.toString().includes('Permission denied')) {
                errorMessage += 'Vui lòng cho phép truy cập camera.';
            } else if (err.toString().includes('NotFoundError') || err.toString().includes('No cameras found')) {
                errorMessage += 'Không tìm thấy camera.';
            } else if (err.toString().includes('NotSupportedError')) {
                errorMessage += 'Trình duyệt không hỗ trợ.';
            } else if (err.toString().includes('NotReadableError')) {
                errorMessage += 'Camera đang được sử dụng bởi ứng dụng khác.';
            } else {
                errorMessage += 'Lỗi không xác định: ' + err.toString();
            }

            const errorElement = cameraError.querySelector('p');
            if (errorElement) {
                errorElement.textContent = errorMessage;
            }
        }

        async function processScannedData(qrCodeData) {
            statusMessage.textContent = 'Đang xử lý...';
            statusMessage.classList.remove('success-message', 'error-message');
            resultDiv.classList.remove('hidden');
            appointmentDetails.innerHTML = '';

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

                console.log('Response from server:', data.appointment);

                if (data.success) {
                    statusMessage.textContent = data.message;
                    statusMessage.classList.remove('error-message');
                    statusMessage.classList.add('success-message');
                    if (data.appointment) {
                        lastAppointmentData = data.appointment; // Lưu dữ liệu để in
                        appointmentDetails.innerHTML = `
                            <p><strong>Mã cuộc hẹn:</strong> ${data.appointment.id}</p>
                            <p><strong>Bệnh nhân:</strong> ${data.appointment.patient_name}</p>
                            <p><strong>Số điện thoại:</strong> ${data.appointment.patient_phone}</p>
                            <p><strong>Bác sĩ:</strong> ${data.appointment.doctor_name}</p>
                            <p><strong>Dịch vụ:</strong> ${data.appointment.service_name}</p>
                            <p><strong>Phòng khám:</strong> ${data.appointment.room}</p>
                            <p><strong>Khoa:</strong> ${data.appointment.department}</p>
                            <p><strong>Thời gian hẹn:</strong> ${data.appointment.appointment_time}</p>
                            <p><strong>Thời gian Check-in:</strong> ${data.appointment.check_in_time}</p>
                            <p><strong>Trạng thái:</strong> <span class="text-green-600 font-semibold">${data.appointment.status}</span></p>
                        `;
                        printTicketBtn.classList.remove('hidden'); // Hiển thị nút in
                    }
                } else {
                    statusMessage.textContent = data.message;
                    statusMessage.classList.remove('success-message');
                    statusMessage.classList.add('error-message');
                }
            } catch (error) {
                console.error('Error during check-in processing:', error);
                statusMessage.textContent = 'Lỗi kết nối hoặc xử lý dữ liệu.';
                statusMessage.classList.add('error-message');
            } finally {
                scanAgainBtn.classList.remove('hidden');
            }
        }

        function printTicket() {
            if (!lastAppointmentData) {
                alert('Không có dữ liệu cuộc hẹn để in.');
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
                <p><strong>Bệnh nhân:</strong> ${lastAppointmentData.patient_name || 'N/A'}</p>
                <p><strong>Số điện thoại:</strong> ${lastAppointmentData.patient_phone || 'N/A'}</p>
                <p><strong>Bác sĩ:</strong> ${lastAppointmentData.doctor_name || 'N/A'}</p>
                <p><strong>Dịch vụ:</strong> ${lastAppointmentData.service_name || 'N/A'}</p>
                <p><strong>Phòng khám:</strong> ${lastAppointmentData.room}</p>
                <p><strong>Khoa:</strong> ${lastAppointmentData.department}</p>
                <p><strong>Thời gian hẹn:</strong> ${lastAppointmentData.appointment_time || 'N/A'}</p>
                <p><strong>Thời gian Check-in:</strong> ${lastAppointmentData.check_in_time || 'N/A'}</p>
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

        // Event listeners
        scanAgainBtn.addEventListener('click', startScanner);
        retryCamera.addEventListener('click', startScanner);
        printTicketBtn.addEventListener('click', printTicket);

        // Khởi động máy quét khi trang được tải
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, starting scanner...');
            // Kiểm tra HTTPS
            if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
                cameraError.classList.remove('hidden');
                cameraError.querySelector('h3').textContent = 'Yêu cầu HTTPS';
                cameraError.querySelector('p').textContent =
                    'QR Scanner chỉ hoạt động trên HTTPS. Vui lòng truy cập trang web qua HTTPS.';
                return;
            }

            // Delay một chút để đảm bảo DOM đã render hoàn toàn
            setTimeout(startScanner, 500);
        });

        // Cleanup khi rời khỏi trang
        window.addEventListener('beforeunload', function() {
            if (html5QrCode && html5QrCode.getState() === Html5QrcodeScannerState.SCANNING) {
                html5QrCode.stop().catch(console.error);
            }
        });
    </script>
@endpush
