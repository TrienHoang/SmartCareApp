<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\Achievement;
use App\Models\Education;
use App\Models\Experience;
use App\Models\User;
use App\Models\Doctor;
use App\Models\DoctorSpecialty;
use App\Models\Specialty;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class DoctorProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // Lấy thông tin địa phương
        $province = $district = $ward = null;
        if ($user->province_code) {
            $province = Http::get("https://provinces.open-api.vn/api/p/{$user->province_code}")->json('name');
        }
        if ($user->district_code) {
            $district = Http::get("https://provinces.open-api.vn/api/d/{$user->district_code}")->json('name');
        }
        if ($user->ward_code) {
            $ward = Http::get("https://provinces.open-api.vn/api/w/{$user->ward_code}")->json('name');
        }

        // Tạo địa chỉ đầy đủ
        $addressParts = array_filter([$user->address, $ward, $district, $province]);
        $fullAddress = implode(', ', $addressParts);

        // Cập nhật: Thêm with('specialties') để lấy danh sách chuyên khoa
        $doctor = Doctor::where('user_id', $user->id)->with('department', 'specialties')->firstOrFail();

        // Lấy thông tin bổ sung và chuyên khoa
        $achievements = Achievement::where('doctor_id', $doctor->id)->get();
        $educations = Education::where('doctor_id', $doctor->id)->get();
        $experiences = Experience::where('doctor_id', $doctor->id)->get();


        return view('doctor.profile.show', compact('user', 'doctor', 'fullAddress', 'achievements', 'educations', 'experiences'));
    }

    /**
     * Hiển thị form chỉnh sửa thông tin bác sĩ
     */
    public function edit()
    {
        $user = Auth::user();

        // Lấy thông tin từ các bảng liên quan
        $doctor = Doctor::where('user_id', $user->id)->with('department')->firstOrFail();
        $achievements = Achievement::where('doctor_id', $doctor->id)->get();
        $educations = Education::where('doctor_id', $doctor->id)->get();
        $experiences = Experience::where('doctor_id', $doctor->id)->get();
        $specialties = $doctor->specialties;
        $departments = \App\Models\Department::all();


        return view('doctor.profile.edit', compact('user', 'doctor', 'achievements', 'educations', 'experiences', 'specialties', 'departments'));
    }

    /**
     * Cập nhật thông tin bác sĩ
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validation rules
        $request->validate([
            'full_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|regex:/^0\d{9}$/',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'province_code' => 'nullable|string|max:20',
            'district_code' => 'nullable|string|max:20',
            'ward_code' => 'nullable|string|max:20',

            // Doctor specific information
            'department_id' => 'required|exists:departments,id',
            'biography' => 'nullable|string',
            'specialty_ids' => 'nullable|array',
            'specialty_ids.*' => 'exists:specialties,id',

            // Achievements
            'achievements' => 'nullable|array',
            'achievements.*.id' => 'nullable|exists:achievements,id',
            'achievements.*.title' => 'required|string|max:255',
            'achievements.*.organization' => 'required|string|max:255',
            'achievements.*.year' => 'required|integer|min:1900|max:' . now()->year,
            'achievements.*.description' => 'nullable|string',

            // Educations
            'educations' => 'nullable|array',
            'educations.*.id' => 'nullable|exists:educations,id',
            'educations.*.degree' => 'required|string|max:255',
            'educations.*.school' => 'required|string|max:255',
            'educations.*.start_year' => 'required|integer|min:1900|max:' . now()->year,
            'educations.*.end_year' => 'nullable|integer|min:1900|max:' . now()->year . '|gte:educations.*.start_year',
            'educations.*.description' => 'nullable|string',

            // Experiences
            'experiences' => 'nullable|array',
            'experiences.*.id' => 'nullable|exists:experiences,id',
            'experiences.*.position' => 'required|string|max:255',
            'experiences.*.institution' => 'required|string|max:500',
            'experiences.*.start_year' => 'required|integer|min:1900|max:' . now()->year,
            'experiences.*.end_year' => 'nullable|integer|min:1900|max:' . now()->year . '|gte:experiences.*.start_year',
            'experiences.*.description' => 'nullable|string',
        ], [
            // Vietnamese error messages
            'full_name.required' => 'Vui lòng nhập họ tên.',
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'username.unique' => 'Tên đăng nhập đã được sử dụng.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email đã tồn tại.',
            'email.email' => 'Email không đúng định dạng.',
            'phone.regex' => 'Số điện thoại phải bắt đầu bằng 0 và gồm 10 chữ số.',
            'avatar.image' => 'Tệp tải lên phải là hình ảnh.',
            'avatar.mimes' => 'Ảnh đại diện phải có định dạng jpeg, png, jpg hoặc gif.',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 2MB.',
            'date_of_birth.before_or_equal' => 'Bạn phải đủ ít nhất 18 tuổi.',
            'department_id.required' => 'Vui lòng chọn khoa.',
            'department_id.exists' => 'Khoa không hợp lệ.',
            'specialty_ids.*.exists' => 'Chuyên môn không hợp lệ.',
            'achievements.*.title.required' => 'Vui lòng nhập tên thành tựu.',
            'achievements.*.organization.required' => 'Vui lòng nhập tổ chức cấp.',
            'achievements.*.year.required' => 'Vui lòng nhập năm đạt thành tựu.',
            'educations.*.degree.required' => 'Vui lòng nhập bằng cấp.',
            'educations.*.school.required' => 'Vui lòng nhập trường học.',
            'educations.*.start_year.required' => 'Vui lòng nhập năm bắt đầu học vấn.',
            'educations.*.end_year.gte' => 'Năm kết thúc phải lớn hơn hoặc bằng năm bắt đầu.',
            'experiences.*.position.required' => 'Vui lòng nhập vị trí/chức vụ.',
            'experiences.*.school.required' => 'Vui lòng nhập nơi làm việc.',
            'experiences.*.start_year.required' => 'Vui lòng nhập năm bắt đầu kinh nghiệm.',
            'experiences.*.end_year.gte' => 'Năm kết thúc phải lớn hơn hoặc bằng năm bắt đầu.',
            'experiences.*.institution.required' => 'Vui lòng nhập nơi làm việc.',
            'experiences.*.description.string' => 'Mô tả kinh nghiệm phải là chuỗi văn bản.',

        ]);

        try {
            DB::beginTransaction();

            // Handle avatar upload and deletion
            if ($request->hasFile('avatar')) {
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $path;
            }

            // Update user's basic information
            $user->fill($request->only([
                'full_name',
                'username',
                'email',
                'phone',
                'gender',
                'date_of_birth',
                'address',
                'province_code',
                'district_code',
                'ward_code'
            ]))->save();

            // Update doctor's information
            $doctor = Doctor::where('user_id', $user->id)->firstOrFail();
            $doctor->fill($request->only(['department_id', 'biography']))->save();

            // Handle many-to-many relationship with specialties
            $doctor->specialties()->sync($request->input('specialty_ids', []));

            // Handle one-to-many relationships (achievements, educations, experiences)
            $this->syncOneToMany($doctor, $request->input('achievements', []), 'achievements');
            $this->syncOneToMany($doctor, $request->input('educations', []), 'educations');
            $this->syncOneToMany($doctor, $request->input('experiences', []), 'experiences');

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Cập nhật thông tin thành công!',
                    'data' => [
                        'user' => $user->refresh(),
                        'doctor' => $doctor->refresh(),
                        'specialties' => $doctor->specialties()->pluck('id')->toArray(),
                    ]
                ], 200);
            }

            return redirect()->route('doctor.profile.show')->with('success', 'Cập nhật thông tin thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Lỗi khi cập nhật thông tin bác sĩ: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Có lỗi xảy ra, vui lòng thử lại.'], 500);
            }

            return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }

    /**
     * Helper method to sync one-to-many relationships.
     */
    protected function syncOneToMany(Doctor $doctor, array $data, string $relationName)
    {
        $existingIds = $doctor->{$relationName}()->pluck('id')->toArray();
        $updatedIds = Arr::pluck($data, 'id');

        $model = get_class($doctor->{$relationName}()->getRelated());

        // Delete records that are no longer in the request
        $model::where('doctor_id', $doctor->id)
            ->whereNotIn('id', array_filter($updatedIds))
            ->delete();

        // Create or update records
        foreach ($data as $item) {
            $model::updateOrCreate(
                ['id' => $item['id'] ?? null, 'doctor_id' => $doctor->id],
                $item
            );
        }
    }

    public function changePasswordForm()
    {
        return view('doctor.profile.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                function ($attribute, $value, $fail) use ($request) {
                    if (Hash::check($value, Auth::user()->password)) {
                        $fail('Mật khẩu mới không được trùng với mật khẩu hiện tại.');
                    }
                },
            ],
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
