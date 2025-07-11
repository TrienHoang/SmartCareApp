```html
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết công việc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        /* Container chính với màu mộc mạc */
        .task-container {
            background: linear-gradient(135deg, #8B6F47, #D9C2A6); /* Nâu đất và be nhạt */
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.4);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            max-width: 1200px; /* Thanh lịch hơn */
            margin: 3rem auto;
        }

        .task-container:hover {
            transform: translateY(-12px);
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.5);
        }

        /* Card chi tiết công việc với hiệu ứng 3D */
        .task-card {
            background: #F5F5F5; /* Màu be nhạt */
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .task-card:hover {
            transform: scale(1.03);
        }

        /* Header của card */
        .task-card-header {
            background: linear-gradient(to right, #4A7043, #8B9A46); /* Xanh olive và nâu nhạt */
            color: #FFF8E7; /* Màu kem nhạt cho chữ */
            padding: 1.5rem;
            border-bottom: 4px solid #3F5E3A; /* Xanh olive đậm */
            text-align: center;
        }

        .task-card-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 600;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }

        /* Body của card */
        .task-card-body {
            padding: 2rem;
        }

        /* Thông tin chi tiết */
        .task-detail {
            font-size: 1.2rem;
            color: #3F5E3A; /* Xanh olive đậm */
            margin-bottom: 1.2rem;
            padding: 0.5rem;
            border-left: 4px solid #8B6F47; /* Nâu đất */
            transition: background 0.3s ease;
        }

        .task-detail:hover {
            background: rgba(139, 111, 71, 0.1); /* Nâu nhạt mờ */
        }

        .task-detail strong {
            color: #6B4E31; /* Nâu đậm */
            font-weight: 600;
        }

        /* Nút quay lại */
        .btn-back {
            background: #6B4E31; /* Nâu đậm */
            border: none;
            color: #FFF8E7; /* Màu kem nhạt */
            border-radius: 8px;
            padding: 0.8rem 1.8rem;
            font-size: 1.1rem;
            transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-back:hover {
            background: #8B6F47; /* Nâu nhạt */
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .task-container {
                padding: 1.5rem;
                margin: 1.5rem;
            }

            .task-card-header h1 {
                font-size: 1.6rem;
            }

            .task-detail {
                font-size: 1rem;
            }

            .btn-back {
                font-size: 1rem;
                padding: 0.6rem 1.2rem;
            }
        }

        @media (max-width: 576px) {
            .task-card-header h1 {
                font-size: 1.3rem;
            }

            .task-detail {
                font-size: 0.9rem;
            }

            .btn-back {
                font-size: 0.9rem;
                padding: 0.5rem 1rem;
            }
        }
    </style>

    <div class="task-container">
        <div class="task-card">
            <div class="task-card-header">
                <h1><i class="bx bx-task me-2"></i>Chi tiết công việc #{{ $task->id }}</h1>
            </div>
            <div class="task-card-body">
                <p class="task-detail"><strong>Tiêu đề:</strong> {{ $task->title }}</p>
                <p class="task-detail"><strong>Hạn chót:</strong> {{ $task->deadline }}</p>
                <a href="{{ route('doctor.calendar.index') }}" class="btn btn-back">Quay lại lịch</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```