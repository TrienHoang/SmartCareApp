<?php

namespace App\Http\Controllers\reception;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role_id', 5);

        if ($search = $request->input('search')) {
            $query->where('full_name', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhere('id', $search)
                ->orWhere('date_of_birth', 'like', "%$search%");
        }

        $patients = $query->orderByDesc('created_at')->paginate(10);

        return view('reception.patients.index', compact('patients'));
    }
    public function create()
    {
        return view('reception.patients.create');
    }

    private function generateUsername($fullName)
    {
        $slug = Str::slug($fullName);
        $random = rand(1000, 9999);
        return $slug . $random;
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'full_name'      => ['required', 'string', 'max:255'],
                'phone'          => [
                    'required',
                    'regex:/^(03|05|07|08|09)[0-9]{8}$/',
                    'unique:users,phone',
                ],
                'email'          => ['nullable', 'email', 'unique:users,email'],
                'gender' => ['nullable', 'in:Nam,Nữ'],
                'date_of_birth'  => ['nullable', 'date', 'before:today'],
                'address'        => ['nullable', 'string', 'max:255'],
            ],
            [
                'full_name.required'      => 'Vui lòng nhập họ tên.',
                'phone.required'          => 'Vui lòng nhập số điện thoại.',
                'phone.regex'             => 'Số điện thoại phải hợp lệ và có 10 số.',
                'phone.unique'            => 'Số điện thoại đã tồn tại.',
                'email.email'             => 'Email không đúng định dạng.',
                'email.unique'            => 'Email đã tồn tại.',
                'gender.in'               => 'Giới tính không hợp lệ.',
                'date_of_birth.before'    => 'Ngày sinh phải trước ngày hôm nay.',
            ]
        );

        User::create([
            'full_name'     => $validated['full_name'],
            'username'      => $this->generateUsername($validated['full_name']),
            'phone'         => $validated['phone'],
            'email'         => $validated['email'] ?? null,
            'gender'        => $validated['gender'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'address'       => $validated['address'] ?? null,
            'role_id'       => 5, // bệnh nhân không tài khoản
            'status'        => 'online',
            'password'      => bcrypt(Str::random(8)),
        ]);

        return redirect()->route('receptionist.patients.index')->with('success', 'Tạo hồ sơ thành công!');
    }

    public function edit($id)
    {
        $patient = User::where('role_id', 5)->findOrFail($id);
        return view('reception.patients.edit', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        $patient = User::where('role_id', 5)->findOrFail($id);

        $validated = $request->validate(
            [
                'full_name'      => ['required', 'string', 'max:255'],
                'phone'          => [
                    'required',
                    'regex:/^(03|05|07|08|09)[0-9]{8}$/',
                    'unique:users,phone,' . $patient->id,
                ],
                'email'          => ['nullable', 'email', 'unique:users,email,' . $patient->id],
                'gender'         => ['nullable', 'in:Nam,Nữ'],
                'date_of_birth'  => ['nullable', 'date', 'before:today'],
                'address'        => ['nullable', 'string', 'max:255'],
            ],
            [
                'full_name.required'      => 'Vui lòng nhập họ tên.',
                'phone.required'          => 'Vui lòng nhập số điện thoại.',
                'phone.regex'             => 'Số điện thoại phải hợp lệ và có 10 số.',
                'phone.unique'            => 'Số điện thoại đã tồn tại.',
                'email.email'             => 'Email không đúng định dạng.',
                'email.unique'            => 'Email đã tồn tại.',
                'gender.in'               => 'Giới tính không hợp lệ. Chỉ được chọn Nam hoặc Nữ.',
                'date_of_birth.before'    => 'Ngày sinh phải trước ngày hôm nay.',
            ]
        );

        $patient->update($validated);

        return redirect()->route('receptionist.patients.index')->with('success', 'Cập nhật hồ sơ thành công!');
    }
    // public function appointmentHistory($id)
    // {
    //     $patient = Patient::findOrFail($id);
    //     $appointments = $patient->appointments()->latest()->get(); // Quan hệ appointments

    //     return view('reception.patients.appointments', compact('patient', 'appointments'));
    // }
}
