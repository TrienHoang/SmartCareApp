@extends('admin.dashboard')
@section('title', 'Quản lý trạng thái rút tiền')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-100 via-blue-200 to-blue-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-8">

            {{-- Alert thành công --}}
            @if (session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-400 text-emerald-800 p-4 rounded-lg shadow-sm">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9
                                          10.586 7.707 9.293a1 1 0 00-1.414 1.414l2
                                          2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            {{-- Header --}}
            <div
                class="bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 rounded-2xl shadow-xl p-6 sm:p-8 text-white relative">
                <div class="absolute top-0 right-0 w-48 sm:w-64 h-48 sm:h-64 opacity-10">
                    <svg viewBox="0 0 200 200" class="w-full h-full">
                        <circle cx="100" cy="100" r="80" fill="currentColor" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <h1 class="text-2xl sm:text-4xl font-bold mb-2">Quản lý rút tiền</h1>
                    <p class="text-blue-200 text-sm sm:text-lg">Theo dõi và xử lý các giao dịch rút tiền</p>
                    <div class="flex flex-wrap gap-4 mt-6">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2 min-w-[120px]">
                            <span class="text-xs text-indigo-100 block">Tổng giao dịch</span>
                            <div class="text-xl font-bold">{{ count($transactions) }}</div>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2 min-w-[120px]">
                            <span class="text-xs text-indigo-100 block">Chờ xử lý</span>
                            <div class="text-xl font-bold">
                                {{ collect($transactions)->where('status', 'Chờ xử lý')->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Người dùng
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Kiểu giao dịch
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Số tiền</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase hidden md:table-cell">
                                    Ngân hàng</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase hidden lg:table-cell">
                                    Tên Tài Khoản</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase hidden lg:table-cell">
                                    Mô tả</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Trạng thái
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Thời gian</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Hành động
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($transactions as $transaction)
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-gray-900 text-sm">
                                            {{ $transaction->wallet->user->username }}</div>
                                        <div class="text-xs text-gray-500"></div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">{{ $transaction->type }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-sm">
                                        {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm hidden md:table-cell truncate max-w-[120px]"
                                        title="{{ $transaction->bank_name }}">
                                        {{ $transaction->bank_name }}
                                    </td>
                                    <td class="px-4 py-3 text-sm hidden lg:table-cell truncate max-w-[120px]"
                                        title="{{ $transaction->account_holder_name }}">
                                        {{ $transaction->account_holder_name }}
                                    </td>
                                    <td class="px-4 py-3 text-sm hidden lg:table-cell truncate max-w-[150px]"
                                        title="{{ $transaction->description }}">
                                        {{ $transaction->description }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @php
                                            $statusConfig = [
                                                'Chờ xử lý' => [
                                                    'bg' => 'bg-yellow-100',
                                                    'text' => 'text-yellow-800',
                                                    'icon' => '⏳',
                                                ],
                                                'Hoàn thành' => [
                                                    'bg' => 'bg-green-100',
                                                    'text' => 'text-green-800',
                                                    'icon' => '✓',
                                                ],
                                                'Không thành công' => [
                                                    'bg' => 'bg-red-100',
                                                    'text' => 'text-red-800',
                                                    'icon' => '✗',
                                                ],
                                            ];
                                            $config = $statusConfig[$transaction->status] ?? $statusConfig['Chờ xử lý'];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }} max-w-full truncate">
                                            {{ $config['icon'] }} <span class="ml-1">{{ $transaction->status }}</span>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div>{{ $transaction->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($transaction->status === 'Chờ xử lý')
                                            <form action="{{ route('admin.wallet.updateStatus', $transaction->id) }}"
                                                method="POST" class="flex flex-col sm:flex-row gap-2 items-center">
                                                @csrf
                                                <select name="status"
                                                    class="border border-gray-300 rounded-lg px-2 py-1 text-xs focus:ring-2 focus:ring-indigo-500">
                                                    <option value="Chờ xử lý"
                                                        {{ $transaction->status === 'Chờ xử lý' ? 'selected' : '' }}>Chờ xử
                                                        lý</option>
                                                    <option value="Hoàn thành">Hoàn thành</option>
                                                    <option value="Không thành công">Không thành công</option>
                                                </select>
                                                <button type="submit"
                                                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded-lg text-xs font-medium">Cập
                                                    nhật</button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 italic text-xs">Không khả dụng</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center space-y-2">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2
                                                              2 0 00-2 2v7m16 0v5a2 2 0
                                                              01-2 2H6a2 2 0
                                                              01-2-2v-5m16 0h-2.586a1 1 0
                                                              00-.707.293l-2.414 2.414a1 1 0
                                                              01-.707.293h-3.172a1 1 0
                                                              01-.707-.293l-2.414-2.414A1 1
                                                              0 006.586 13H4">
                                                </path>
                                            </svg>
                                            <span class="text-lg font-medium">Không có giao dịch rút tiền</span>
                                            <span class="text-sm">Chưa có giao dịch nào được tạo</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if (method_exists($transactions, 'hasPages') && $transactions->hasPages())
                    <div
                        class="bg-gray-50 px-4 sm:px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center">
                        <div class="text-sm text-gray-700">
                            Hiển thị
                            <span class="font-medium">{{ $transactions->firstItem() }}</span>
                            đến
                            <span class="font-medium">{{ $transactions->lastItem() }}</span>
                            trong tổng số
                            <span class="font-medium">{{ $transactions->total() }}</span>
                            kết quả
                        </div>
                        <div>{{ $transactions->links() }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Custom Scrollbar --}}
    <style>
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
@endsection
