@extends('reception.dashboard')

@section('title', 'Quản lý lịch làm việc bác sĩ')

@section('content')
    <div class="container">
        <h3 class="mb-4">Quản lý lịch làm việc bác sĩ</h3>

        <form action="{{ url('receptionist/doctors/working-schedule') }}" method="GET" class="mb-4 d-flex align-items-center"
            onsubmit="event.preventDefault(); 
                  let d=document.getElementById('date').value; 
                  if(d){ window.location=this.action+'/'+d; }">

            <label for="date" class="me-2 fw-bold">Chọn ngày:</label>
            <input type="date" id="date" class="form-control me-2" style="width: 200px;"
                value="{{ now()->format('Y-m-d') }}">
            <button type="submit" class="btn btn-primary">Xem lịch</button>
        </form>

        <p>Chọn ngày và nhấn <strong>Xem lịch</strong> để xem lịch làm việc của bác sĩ.</p>
    </div>
@endsection
