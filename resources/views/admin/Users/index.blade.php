@extends('layouts.admin')
@section('title', 'Danh sách người dùng')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">Danh sách người dùng</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tài khoản</th>
                                <th>Họ và tên</th>
                                <th>Số điện thoại</th>
                                <th>Giới tính</th>
                                <th>Ngày sinh</th>
                                <th>Địa chỉ</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->gender }}</td>
                                    <td>{{ $user->date_of_birth }}</td>
                                    <td>{{ $user->address }}</td>
                                    <td>{{ $user->role_id }}</td>
                                    <td>{{ $user->status ? 'Kích hoạt' : 'Không kích hoạt' }}</td>
                                    <td>
                                        <!-- Add action buttons here -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
