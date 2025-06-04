@extends('admin.dashboard')
@section('title', 'Schedules')
@section('content')
    <div class="container">
        <h1 class="mb-4">Schedules</h1>
        <div class="mb-3">
            <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">Create New Schedule</a>
        </div>
        @if($schedules->isEmpty())
            <p>No schedules found.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Doctor</th>
                        <th>Day of Week</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->id }}</td>
                            <td>{{ $schedule->doctor->user->full_name }}</td>
                            <td>{{ $schedule->day_of_week }}</td>
                            <td>{{ $schedule->start_time }}</td>
                            <td>{{ $schedule->end_time }}</td>
                            <td>
                                <a href="{{ route('admin.schedules.show', $schedule->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this schedule?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
