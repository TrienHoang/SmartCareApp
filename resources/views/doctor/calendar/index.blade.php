@extends('doctor.dashboard')

@section('content')
<style>
    /* Container chính với màu mộc mạc */
    .calendar-container {
        background: linear-gradient(135deg, #8B6F47, #D9C2A6); /* Nâu đất và be nhạt */
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        max-width: 1400px;
        margin: 2rem auto;
    }

    .calendar-container:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
    }

    /* Card lịch với hiệu ứng 3D */
    .calendar-card {
        background: #F5F5F5; /* Màu be nhạt */
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease;
    }

    .calendar-card:hover {
        transform: scale(1.02);
    }

    /* Header của card */
    .calendar-card-header {
        background: linear-gradient(to right, #4A7043, #8B9A46); /* Xanh olive và nâu nhạt */
        color: #FFF8E7; /* Màu kem nhạt cho chữ */
        padding: 1.5rem;
        border-bottom: 4px solid #3F5E3A; /* Xanh olive đậm */
        text-align: center;
    }

    .calendar-card-header h4 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 600;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    }

    /* Body của card */
    .calendar-card-body {
        padding: 1.5rem;
    }

    /* FullCalendar */
    #calendar {
        max-width: 100%;
        height: 700px; /* Tăng chiều cao */
        border-radius: 8px;
        background: #FAF3E0; /* Màu be nhạt mộc mạc */
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Tùy chỉnh FullCalendar */
    .fc-daygrid-day-number {
        font-size: 1.2rem;
        font-weight: 500;
        color: #3F5E3A; /* Xanh olive đậm */
        transition: color 0.3s ease;
    }

    .fc-daygrid-day-number:hover {
        color: #8B9A46; /* Nâu nhạt */
    }

    .fc-event {
        border-radius: 6px;
        font-size: 1rem;
        padding: 6px;
        cursor: pointer;
        color: #FFF8E7; /* Màu kem nhạt cho chữ */
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .fc-event:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    }

    /* Sự kiện công việc (màu nâu đất) */
    .fc-event.task {
        background: #8B6F47;
        border: 1px solid #6B4E31;
    }

    /* Sự kiện lịch hẹn (màu xanh olive) */
    .fc-event.appointment {
        background: #4A7043;
        border: 1px solid #3F5E3A;
    }

    .fc-button {
        border-radius: 6px !important;
        background: #6B4E31 !important; /* Nâu đậm */
        border: none !important;
        color: #FFF8E7 !important; /* Màu kem nhạt */
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .fc-button:hover {
        background: #8B6F47 !important; /* Nâu nhạt */
        transform: translateY(-1px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .calendar-container {
            padding: 1rem;
            margin: 1rem;
        }

        .calendar-card-header h4 {
            font-size: 1.5rem;
        }

        #calendar {
            height: 500px;
        }

        .fc-daygrid-day-number {
            font-size: 1rem;
        }

        .fc-event {
            font-size: 0.9rem;
            padding: 4px;
        }
    }

    @media (max-width: 576px) {
        .calendar-card-header h4 {
            font-size: 1.2rem;
        }

        #calendar {
            height: 400px;
        }

        .fc-button {
            font-size: 0.8rem;
            padding: 0.5rem;
        }
    }
</style>

<div class="calendar-container">
    <div class="calendar-card">
        <div class="calendar-card-header">
            <h4><i class="bx bx-calendar me-2"></i>Lịch làm việc của bạn</h4>
        </div>
        <div class="calendar-card-body">
            <!-- Lịch -->
            <div id="calendar"></div>
        </div>
    </div>
</div>



@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'vi',
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: function (fetchInfo, successCallback, failureCallback) {
            const params = {
                start: fetchInfo.startStr,
                end: fetchInfo.endStr,
            };

            console.log('📤 Request Parameters:', params);

            axios.get("{{ route('doctor.calendar.events') }}", { params })
                .then(response => {
                    console.log('✅ Đã tải sự kiện:', response.data);
                    // Thêm class cho sự kiện để áp dụng màu riêng
                    response.data.forEach(event => {
                        if (event.id.startsWith('task_')) {
                            event.classNames = ['task'];
                        } else if (event.id.startsWith('appt_')) {
                            event.classNames = ['appointment'];
                        }
                    });
                    successCallback(response.data);
                })
                .catch(error => {
                    console.error('❌ Lỗi khi tải sự kiện:', error);
                    alert('Không thể tải sự kiện. Vui lòng thử lại.');
                    failureCallback(error);
                });
        },
        eventClick: function (info) {
            console.log('🖱️ Sự kiện được nhấp:', info.event);
            info.jsEvent.preventDefault();
            if (info.event.url && info.event.url !== '#') {
                window.location.href = info.event.url; // Chuyển hướng trong cùng tab
            } else {
                console.warn('URL sự kiện không hợp lệ:', info.event.url);
                alert('Không thể xem chi tiết sự kiện do URL không hợp lệ.');
            }
        },
        loading: function(bool) {
            console.log('📊 Trạng thái tải lịch:', bool);
        },
        eventDidMount: function(info) {
            console.log('📅 Sự kiện được gắn:', info.event.title);
        }
    });

    calendar.render();
});
</script>
@endpush
