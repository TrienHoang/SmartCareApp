@extends('admin.dashboard')
@section('title', 'Quản lý trạng thái rút tiền')
@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-100 via-blue-200 to-blue-300">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            {{-- Alert thông báo thành công --}}
            @if (session('success'))
                <div
                    class="bg-gradient-to-r from-emerald-50 to-green-50 border-l-4 border-emerald-400 text-emerald-800 p-5 rounded-lg shadow-sm animate-pulse">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Header với gradient xanh da trời --}}
            <div class="bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 rounded-2xl shadow-xl overflow-hidden">
                <div class="px-8 py-10 text-white relative">
                    <div class="absolute top-0 right-0 w-64 h-64 opacity-10">
                        <svg viewBox="0 0 200 200" class="w-full h-full">
                            <circle cx="100" cy="100" r="80" fill="currentColor" />
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <h1 class="text-4xl font-bold mb-2">Quản lý rút tiền</h1>
                        <p class="text-blue-200 text-lg">Theo dõi và xử lý các giao dịch rút tiền của người dùng</p>
                        <div class="flex items-center mt-6 space-x-6">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                                <span class="text-sm text-indigo-100">Tổng giao dịch</span>
                                <div class="text-2xl font-bold">{{ count($transactions) }}</div>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                                <span class="text-sm text-indigo-100">Chờ xử lý</span>
                                <div class="text-2xl font-bold">
                                    {{ collect($transactions)->where('status', 'Chờ xử lý')->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters và Search --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                    <div class="flex flex-col sm:flex-row gap-4 items-center">
                        <select
                            class="bg-gray-50 border border-gray-200 text-gray-700 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                            <option>Tất cả trạng thái</option>
                            <option>Chờ xử lý</option>
                            <option>Hoàn thành</option>
                            <option>Không thành công</option>
                        </select>
                        <input type="text" placeholder="Tìm kiếm người dùng..."
                            class="bg-gray-50 border border-gray-200 text-gray-700 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 min-w-[250px]">
                    </div>
                </div>
            </div>

            {{-- Table với design hiện đại --}}
            <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                    Người dùng</th>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                    Kiểu giao dịch</th>
                                <th
                                    class="px-6 py-4 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                    Số tiền</th>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                    Mô tả</th>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                    Trạng thái</th>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                    Thời gian</th>
                                <th
                                    class="px-6 py-4 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                    Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($transactions as $transaction)
                                <tr
                                    class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                                    {{-- User Info --}}
                                    <td class="px-6 py-5">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-gradient-to-r from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                                {{ substr($transaction->wallet->user->username, 0, 2) }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="font-semibold text-gray-900">
                                                    {{ $transaction->wallet->user->username }}</div>
                                                <div class="text-sm text-gray-500">ID: #{{ $transaction->id }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Transaction Type --}}
                                    <td class="px-6 py-5">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z" />
                                                </svg>
                                            </div>
                                            <span class="font-medium text-gray-800">{{ $transaction->type }}</span>
                                        </div>
                                    </td>

                                    {{-- Amount --}}
                                    <td class="px-6 py-5 text-right">
                                        <div class="text-lg font-bold text-gray-900">
                                            {{ number_format($transaction->amount, 0, ',', '.') }}</div>
                                        <div class="text-sm text-gray-500">VNĐ</div>
                                    </td>

                                    {{-- Description --}}
                                    <td class="px-6 py-5">
                                        <div class="text-gray-800 max-w-xs truncate"
                                            title="{{ $transaction->description }}">
                                            {{ $transaction->description }}
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-5">
                                        @php
                                            $statusConfig = [
                                                'Chờ xử lý' => [
                                                    'bg' => 'bg-gradient-to-r from-yellow-100 to-orange-100',
                                                    'text' => 'text-orange-800',
                                                    'border' => 'border-orange-200',
                                                ],
                                                'Hoàn thành' => [
                                                    'bg' => 'bg-gradient-to-r from-green-100 to-emerald-100',
                                                    'text' => 'text-emerald-800',
                                                    'border' => 'border-emerald-200',
                                                ],
                                                'Không thành công' => [
                                                    'bg' => 'bg-gradient-to-r from-red-100 to-pink-100',
                                                    'text' => 'text-red-800',
                                                    'border' => 'border-red-200',
                                                ],
                                            ];
                                            $config = $statusConfig[$transaction->status] ?? $statusConfig['Chờ xử lý'];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold border {{ $config['bg'] }} {{ $config['text'] }} {{ $config['border'] }}">
                                            {{ $transaction->status }}
                                        </span>
                                    </td>

                                    {{-- Date --}}
                                    <td class="px-6 py-5">
                                        <div class="text-gray-900 font-medium">
                                            {{ $transaction->created_at->format('d/m/Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $transaction->created_at->format('H:i') }}
                                        </div>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-5">
                                        <form action="{{ route('admin.wallet.updateStatus', $transaction->id) }}"
                                            method="POST" class="flex items-center justify-center space-x-3">
                                            @csrf
                                            <select name="status"
                                                class="bg-gray-50 border border-gray-200 text-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                                                <option value="Chờ xử lý"
                                                    {{ $transaction->status === 'Chờ xử lý' ? 'selected' : '' }}>Chờ xử lý
                                                </option>
                                                <option value="Hoàn thành"
                                                    {{ $transaction->status === 'Hoàn thành' ? 'selected' : '' }}>Hoàn
                                                    thành</option>
                                                <option value="Không thành công"
                                                    {{ $transaction->status === 'Không thành công' ? 'selected' : '' }}>
                                                    Không thành công</option>
                                            </select>
                                            <button type="submit"
                                                class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-medium px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md text-sm">
                                                Cập nhật
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center space-y-4">
                                            <div
                                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                                </svg>
                                            </div>
                                            <div class="text-gray-500">
                                                <div class="font-medium text-lg">Không có giao dịch rút tiền</div>
                                                <div class="text-sm">Chưa có giao dịch nào được thực hiện</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if (method_exists($transactions, 'hasPages') && $transactions->hasPages())
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Custom CSS for animations --}}
    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
    </style>
@endsection
