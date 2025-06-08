@extends('admin.dashboard')
@section('title', 'Schedules')
@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Schedules</h1>
            <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">Create New Schedule</a>
        </div>

        <!-- Search Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.schedules.index') }}" method="get" class="row g-2 align-items-end">
                    <div class="col-auto">
                        <input type="text" name="keyword" class="form-control" placeholder="Enter keyword..." value="{{ $keyword ?? '' }}" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-sm btn-primary">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Schedules Table -->
        <div class="card">
            <div class="card-body">
                @if($schedules->isEmpty())
                    <div class="alert alert-info text-center" role="alert">
                        No schedules found.
                    </div>
                @else
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Doctor</th>
                                <th>Day of Week</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Day</th>
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
                                    <td>{{ $schedule->created_at}}</td> <!-- Định dạng ngày rõ ràng -->
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.schedules.show', $schedule->id) }}" class="btn btn-info btn-sm">View</a>
                                            <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" style="display:inline;"
                                                  onsubmit="return confirm('Are you sure you want to delete this schedule?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="card-footer d-flex justify-content-end">
                        {{ $schedules->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
