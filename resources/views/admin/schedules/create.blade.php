@extends('admin.dashboard')
@section('title', 'Create Schedule')
@section('content')
    <div class="container">
        <h1 class="mb-4">Create New Schedule</h1>
        <form action="{{ route('admin.schedules.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="doctor_id" class="form-label">Select Doctor</label>
                <select class="form-select" id="doctor_id" name="doctor_id" required>
                    <option value="">Choose a doctor</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->user->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="day" class="form-label">Day</label>
                <input type="date" class="form-control" id="day" name="day" required>
            </div>
            <div class="mb-3">
                <label for="day_of_week" class="form-label">Day of Week</label>
                <select class="form-select" id="day_of_week" name="day_of_week" required>
                    <option value="">Choose a day</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="start_time" class="form-label">Start Time</label>
                <input type="time" class="form-control" id="start_time" name="start_time" required>
            </div>

            <div class="mb-3">
                <label for="end_time" class="form-label">End Time</label>
                <input type="time" class="form-control" id="end_time" name="end_time" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Schedule</button>
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Back to Schedules</a>
        </form>
    </div>

@endsection
