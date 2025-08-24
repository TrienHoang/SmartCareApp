<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Faq;

class ContactController extends Controller
{
    // Hiển thị form liên hệ
    public function showForm()
    {
        $faqs = Faq::where('is_active', 1)
        ->orderBy('display_order', 'asc')
        ->get();

        return view('client.contact',compact('faqs'));
    }

    // Xử lý khi gửi form
    public function submitForm(Request $request)
    {
        // Validate tiếng Việt
        $validated = $request->validate(
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );

        // Lưu liên hệ
        Contact::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'phone'     => $validated['phone'],
            'title'     => $validated['title'],
            'message'   => $validated['message'],
            'is_hidden' => false,
        ]);

        return redirect()->back()->with('success', 'Gửi liên hệ thành công!');
    }

    private function rules()
    {
        return [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'nullable|string|max:10',
            'title'   => 'required|string|max:255',
            'message' => 'required|string|min:10|max:1000',
        ];
    }

    private function messages()
    {
        return [
            'required' => ':attribute không được để trống.',
            'email'    => ':attribute phải là địa chỉ email hợp lệ.',
            'max'      => ':attribute không được vượt quá :max ký tự.',
            'name.required'    => 'Vui lòng nhập họ tên.',
            'email.required'   => 'Vui lòng nhập email.',
            'email.email'      => 'Email không đúng định dạng.',
            'phone.max'        => 'Số điện thoại không được quá :max ký tự.',
            'message.required' => 'Vui lòng nhập nội dung liên hệ.',
            'message.min'      => 'Nội dung liên hệ phải có ít nhất :min ký tự.',
            'message.max'      => 'Nội dung liên hệ không được vượt quá :max ký tự.',
            'title.required'   => 'Vui lòng chọn chủ đề liên hệ.',
        ];
    }

    private function attributes()
    {
        return [
            'name'    => 'Họ và tên',
            'email'   => 'Email',
            'phone'   => 'Số điện thoại',
            'title'   => 'Chủ đề',
            'message' => 'Nội dung',
        ];
    }

    // public function contact()
    // {
    //     // Lấy các câu hỏi đang active và sắp xếp theo display_order
    //     $faqs = Faq::where('is_active', 1)
    //                 ->orderBy('display_order', 'asc')
    //                 ->get();

    //     // Truyền dữ liệu sang contact.blade.php
    //     return view('client.contact', compact('faqs'));
    // }
}
