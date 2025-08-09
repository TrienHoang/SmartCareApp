<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function send(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Đã gửi tin nhắn thành công (mock)',
        ]);
    }
}
