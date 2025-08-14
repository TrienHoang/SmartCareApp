@extends('client.layouts.profile-layout')


@section('title', 'Ví')

@section('profile-content')
    <div class="max-w-5xl mx-auto px-4 py-8 space-y-8">
        <!-- Card số dư -->
        <div
            class="bg-gradient-to-r from-green-400 to-green-600 p-6 rounded-2xl shadow-lg text-white flex justify-between items-center">
            <div>
                <h2 class="text-lg opacity-90">Số dư ví</h2>
                <p class="text-4xl font-bold">
                    {{ number_format($wallet->balance, 0, ',', '.') }}
                    <span class="text-2xl">VNĐ</span>
                </p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.104 0-2 .672-2 1.5S10.896 11 12 11s2-.672 2-1.5S13.104 8 12 8zM4 7v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V7H4z" />
                </svg>
            </div>
        </div>

        <!-- Form rút tiền -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 9V7a4 4 0 00-8 0v2H5v10h14V9h-2z" />
                </svg>
                Rút tiền
            </h3>
            <form action="{{ route('wallet.withdraw') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <!-- Số tiền -->
                <input type="number" name="amount" placeholder="Số tiền cần rút"
                    class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>

                <!-- Ngân hàng -->
                <select name="bank_name"
                    class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    <option value="">-- Chọn ngân hàng --</option>
                    <option value="Vietcombank">Vietcombank - TMCP Ngoại thương VN</option>
                    <option value="VietinBank">VietinBank - TMCP Công Thương VN</option>
                    <option value="BIDV">BIDV - TMCP Đầu tư và Phát triển VN</option>
                    <option value="Agribank">Agribank - Ngân hàng Nông nghiệp</option>
                    <option value="MB Bank">MB Bank - Quân đội</option>
                    <option value="Techcombank">Techcombank - Kỹ Thương VN</option>
                    <option value="ACB">ACB - Á Châu</option>
                    <option value="Sacombank">Sacombank - Sài Gòn Thương Tín</option>
                    <option value="VPBank">VPBank - Việt Nam Thịnh Vượng</option>
                    <option value="SHB">SHB - Sài Gòn Hà Nội</option>
                    <option value="VIB">VIB - Quốc Tế</option>
                    <option value="HDBank">HDBank - Phát triển TP.HCM</option>
                    <option value="Eximbank">Eximbank - Xuất Nhập Khẩu VN</option>
                    <option value="LienVietPostBank">LienVietPostBank - Bưu điện Liên Việt</option>
                    <option value="OCB">OCB - Phương Đông</option>
                    <option value="SeABank">SeABank - Đông Nam Á</option>
                    <option value="NamABank">Nam A Bank - Nam Á</option>
                    <option value="BaoVietBank">Bảo Việt Bank</option>
                    <option value="ABBANK">ABBANK - An Bình</option>
                    <option value="PG Bank">PG Bank - Xăng dầu Petrolimex</option>
                </select>

                <!-- Số tài khoản -->
                <input type="text" name="bank_account" placeholder="Số tài khoản ngân hàng"
                    class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>

                <!-- Nút submit -->
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-2 rounded-lg shadow">
                    Gửi yêu cầu
                </button>
            </form>
        </div>

        <!-- Lịch sử giao dịch -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-6h13v6M9 7V3h13v4m-6 4v10" />
                </svg>
                Lịch sử giao dịch
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Loại</th>
                            <th class="px-4 py-2 text-right">Số tiền</th>
                            <th class="px-4 py-2">Mô tả</th>
                            <th class="px-4 py-2">Ngày</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wallet->transactions as $t)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-2">
                                    <span
                                        class="px-2 py-1 text-sm rounded-full
                                        {{ $t->type === 'deposit' || $t->type === 'refund' ? 'bg-green-100 text-green-600' : ($t->type === 'withdraw' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600') }}">
                                        {{ ucfirst($t->type) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-right font-medium">
                                    {{ number_format($t->amount, 0, ',', '.') }} VNĐ
                                </td>
                                <td class="px-4 py-2">{{ $t->description }}</td>
                                <td class="px-4 py-2 text-gray-500">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                    Chưa có giao dịch nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
