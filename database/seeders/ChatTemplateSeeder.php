<?php

namespace Database\Seeders;

use App\Models\ChatTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $templates = [
            [
                'keyword' => 'hướng dẫn đặt lịch',
                'response' => 'Bước 1: Chọn dịch vụ hiện đang có:

Bước 2: Có 2 bước để chọn dịch vụ:
+ Chọn hệ thống Radum.
+ Chọn thủ công.
->Đặt lịch ngay:

Bước 3: Chọn ngày còn trống. -> Chọn giờ -> Nhập ghi chú bệnh đang mắc phải -> Xác nhận đặt lịch.

Bước 4: Kiểm tra thông tin cá nhân:
Nếu có mã giảm giá, hãy chọn mã giảm giá.
-> Bấm tiếp xác nhận đặt lịch.

Bước 5: Thanh toán.',
                'suggested_services' => null,
                'priority' => 10
            ],
            [
                'keyword' => 'giá',
                'response' => '💰 Bảng giá dịch vụ của chúng tôi rất cạnh tranh. Dưới đây là một số dịch vụ phổ biến:',
                'suggested_services' => [1, 2, 3], // ID của services
                'priority' => 8
            ],
            [
                'keyword' => 'bác sĩ',
                'response' => '👨‍⚕️ Chúng tôi có đội ngũ bác sĩ giàu kinh nghiệm, chuyên môn cao. Bạn có thể xem thông tin chi tiết về các bác sĩ trên website hoặc tôi có thể tư vấn bác sĩ phù hợp với tình trạng của bạn.',
                'suggested_services' => null,
                'priority' => 7
            ],
            [
                'keyword' => 'địa chỉ',
                'response' => '📍 Địa chỉ phòng khám:🏥 SmartCare📮 13 P.Trịnh Văn Bô, Xuân Phương, Nam Từ Liêm, Hà Nội📞 Hotline: 0123.456.789🕐 Giờ làm việc: 7:00 - 17:00 (T2-T7)',
                'suggested_services' => null,
                'priority' => 6
            ],
            [
                'keyword' => 'thời gian',
                'response' => '⏰ Phòng khám hoạt động:• Thứ 2 - Thứ 7: 7:00 - 17:00• Nghỉ Tết Nguyên đán• Có lịch trực cấp cứu 24/7',
                'suggested_services' => null,
                'priority' => 5
            ]
        ];

        foreach ($templates as $template) {
            ChatTemplate::create($template);
        }
    }
}
