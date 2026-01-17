<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserCard;
use App\Models\GuestbookMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * GuestbookController - API xử lý lời chúc
 */
class GuestbookController extends Controller
{
    /**
     * Submit lời chúc mới
     * POST /api/guestbook/{cardId}
     */
    public function store(Request $request, int $cardId)
    {
        $card = UserCard::find($cardId);
        
        if (!$card) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thiệp'
            ], 404);
        }

        // Validate
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Kiểm tra subscription
        if ($card->status !== 'trial' && (!$card->subscription || !$card->subscription->has_guestbook)) {
            return response()->json([
                'success' => false,
                'message' => 'Tính năng lời chúc chưa được kích hoạt'
            ], 403);
        }

        // Tạo message
        $message = GuestbookMessage::create([
            'user_card_id' => $card->id,
            'name' => $request->name,
            'message' => $request->message,
            'is_approved' => true, // Auto approve
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã gửi lời chúc!',
            'data' => $message
        ]);
    }

    /**
     * Lấy danh sách lời chúc
     * GET /api/guestbook/{cardId}
     */
    public function index(int $cardId)
    {
        $card = UserCard::find($cardId);
        
        if (!$card) {
            return response()->json(['success' => false], 404);
        }

        $messages = $card->guestbookMessages()
            ->approved()
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    /**
     * Xóa lời chúc (chủ thiệp)
     * DELETE /api/guestbook/{cardId}/{messageId}
     */
    public function destroy(int $cardId, int $messageId)
    {
        $card = UserCard::find($cardId);
        
        if (!$card || $card->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $message = GuestbookMessage::where('user_card_id', $cardId)
            ->where('id', $messageId)
            ->first();

        if ($message) {
            $message->delete();
        }

        return response()->json(['success' => true]);
    }
}
