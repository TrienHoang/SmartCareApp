<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\PromotionUserUsage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PromotionController extends Controller
{
    /**
     * Hiển thị danh sách mã giảm giá đủ điều kiện cho user
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Lấy tất cả mã giảm giá còn hiệu lực
        $promotions = Promotion::where('valid_from', '<=', Carbon::now())
            ->where('valid_until', '>=', Carbon::now())
            ->get();

        $availablePromotions = [];
        $unavailablePromotions = [];

        foreach ($promotions as $promotion) {
            $canUse = $this->canUserUsePromotion($user, $promotion);

            if ($canUse) {
                $availablePromotions[] = [
                    'promotion' => $promotion,
                    'reason' => ''
                ];
            } else {
                $reason = $this->getUnavailableReason($user, $promotion);
                $unavailablePromotions[] = [
                    'promotion' => $promotion,
                    'reason' => $reason
                ];
            }
        }

        return view('client.promotions.index', compact('availablePromotions', 'unavailablePromotions'));
    }

    /**
     * Áp dụng mã giảm giá
     */
    public function apply(Request $request, $promotionId)
    {
        $user = Auth::user();
        $promotion = Promotion::findOrFail($promotionId);

        // Kiểm tra user có thể dùng mã này không
        if (!$this->canUserUsePromotion($user, $promotion)) {
            return redirect()->back()->with('error', 'Bạn không thể sử dụng mã giảm giá này!');
        }

        // Lưu thông tin mã giảm giá vào session
        $request->session()->put('selected_promotion_code', $promotion->code);
        $request->session()->put('selected_promotion_id', $promotion->id);
        $request->session()->put('selected_promotion_discount', $promotion->discount_percentage);

        return redirect()->route('booking.confirm')->with('success', 'Áp dụng mã giảm giá thành công!');
    }

    /**
     * Xóa mã giảm giá khỏi session
     */
    public function remove(Request $request)
    {
        $request->session()->forget(['selected_promotion_code', 'selected_promotion_id', 'selected_promotion_discount']);
        return redirect()->back()->with('success', 'Đã xóa mã giảm giá!');
    }

    /**
     * Xác nhận sử dụng mã giảm giá (gọi từ BookingController khi lưu thành công)
     */
    public static function confirmUsage($userId, $promotionId)
    {
        if ($promotionId) {
            $exists = PromotionUserUsage::where('user_id', $userId)
                ->where('promotion_id', $promotionId)
                ->exists();

            if (!$exists) {
                PromotionUserUsage::create([
                    'user_id' => $userId,
                    'promotion_id' => $promotionId,
                    'used_at' => now(),
                ]);
            }
        }
    }


    /**
     * Kiểm tra user có thể sử dụng mã giảm giá không
     */
    private function canUserUsePromotion($user, $promotion)
    {
        // Kiểm tra mã đã được user này sử dụng chưa
        $hasUsed = PromotionUserUsage::where('user_id', $user->id)
            ->where('promotion_id', $promotion->id)
            ->exists();

        if ($hasUsed) {
            return false; // ❌ KHÔNG được return redirect
        }

        // Kiểm tra nếu là mã cho người mới (discount = 20% hoặc chứa "NEW")
        if (strcasecmp(trim($promotion->code), 'FIRST20') === 0) {
            $appointmentCount = $user->appointments()->count();
            return $appointmentCount == 0;
        }

        return true;
    }

    /**
     * Lấy lý do không thể sử dụng mã
     */
    private function getUnavailableReason($user, $promotion)
    {
        // Kiểm tra đã sử dụng
        $hasUsed = PromotionUserUsage::where('user_id', $user->id)
            ->where('promotion_id', $promotion->id)
            ->exists();

        if ($hasUsed) {
            return 'Bạn đã sử dụng mã này rồi';
        }

        // Kiểm tra mã cho người mới
        if (strcasecmp(trim($promotion->code), 'FIRST20') === 0) {
            $appointmentCount = $user->appointments()->count();
            if ($appointmentCount > 0) {
                return 'Mã chỉ dành cho khách hàng mới';
            }
        }



        return 'Không đủ điều kiện sử dụng';
    }
}
