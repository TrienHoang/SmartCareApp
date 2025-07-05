<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết lịch hẹn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
@section('content')
<style>
    /* Container chính với màu mộc mạc */
    .appointment-container {
        background: linear-gradient(135deg, #8B6F47, #D9C2A6); /* Nâu đất và be nhạt */
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        max-width: 1400px;
        margin: 2rem auto;
    }

    .appointment-container:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
    }

    /* Card chi tiết lịch hẹn với hiệu ứng 3D */
    .appointment-card {
        background: #F5F5F5; /* Màu be nhạt */
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease;
    }

    .appointment-card:hover {
        transform: scale(1.02);
    }

    /* Header của card */
    .appointment-card-header {
        background: linear-gradient(to right, #4A7043, #8B9A46); /* Xanh olive và nâu nhạt */
        color: #FFF8E7; /* Màu kem nhạt cho chữ */
        padding: 1.5rem;
        border-bottom: 4px solid #3F5E3A; /* Xanh olive đậm */
        text-align: center;
    }

    .appointment-card-header h1 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 600;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    }

    /* Body của card */
    .appointment-card-body {
        padding: 1.5rem;
    }

    /* Thông tin chi tiết */
    .appointment-detail {
        font-size: 1.1rem;
        color: #3F5E3A; /* Xanh olive đậm */
        margin-bottom: 1rem;
    }

    .appointment-detail strong {
        color: #6B4E31; /* Nâu đậm */
    }

    /* Nút quay lại */
    .btn-back {
        background: #6B4E31; /* Nâu đậm */
        border: none;
        color: #FFF8E7; /* Màu kem nhạt */
        border-radius: 6px;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn-back:hover {
        background: #8B6F47; /* Nâu nhạt */
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .appointment-container {
            padding: 1rem;
            margin: 1rem;
        }

        .appointment-card-header h1 {
            font-size: 1.5rem;
        }

        .appointment-detail {
            font-size: 1rem;
        }

        .btn-back {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
    }

    @media (max-width: 576px) {
        .appointment-card-header h1 {
            font-size: 1.2rem;
        }

        .appointment-detail {
            font-size: 0.9rem;
        }

        .btn-back {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }
    }
</style>

<div class="appointment-container">
    <div class="appointment-card">
        <div class="appointment-card-header">
            <h1><i class="bx bx-calendar-check me-2"></i>Chi tiết lịch hẹn #{{ $appointment->id }}</h1>
        </div>
        <div class="appointment-card-body">
            <p class="appointment-detail"><strong>Thời gian:</strong> {{ $appointment->appointment_time }}</p>
            <p class="appointment-detail"><strong>Bệnh nhân ID:</strong> {{ $appointment->patient_id }}</p>
            <p class="appointment-detail"><strong>Dịch vụ ID:</strong> {{ $appointment->service_id }}</p>
            <a href="{{ route('doctor.calendar.index') }}" class="btn btn-back">Quay lại lịch</a>
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```