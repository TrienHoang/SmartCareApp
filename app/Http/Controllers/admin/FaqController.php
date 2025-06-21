<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $faqs = DB::table('faqs')
            ->join('service_categories', 'faqs.service_category_id', '=', 'service_categories.id')
            ->select('faqs.*', 'service_categories.name as category_name')
            ->when(
                $request->service_category_id,
                fn($q) =>
                $q->where('faqs.service_category_id', $request->service_category_id)
            )
            ->orderByDesc('faqs.id')
            ->paginate(10);

        $categories = DB::table('service_categories')->pluck('name', 'id');
        return view('admin.faqs.index', compact('faqs', 'categories'));
    }

    public function create()
    {
        $categories = DB::table('service_categories')->pluck('name', 'id');
        return view('admin.faqs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|unique:faqs,question',
            'answer' => 'required',
            'service_category_id' => 'required|exists:service_categories,id',
        ]);

        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'service_category_id' => $request->service_category_id,
            'display_order' => $request->display_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'Đã thêm câu hỏi thành công.');
    }

    public function edit(Faq $faq)
    {
        $categories = DB::table('service_categories')->pluck('name', 'id');
        return view('admin.faqs.edit', compact('faq', 'categories'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|unique:faqs,question,' . $faq->id,
            'answer' => 'required',
            'service_category_id' => 'required|exists:service_categories,id',
        ]);

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'service_category_id' => $request->service_category_id,
            'display_order' => $request->display_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'Đã cập nhật câu hỏi.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'Đã xoá câu hỏi.');
    }
}
