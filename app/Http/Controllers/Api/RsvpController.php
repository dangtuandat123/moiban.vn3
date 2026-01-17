<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserCard;
use App\Models\RsvpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * RsvpController - API xử lý RSVP responses
 */
class RsvpController extends Controller
{
    /**
     * Submit RSVP response
     * POST /api/rsvp/{cardId}
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
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'attendance' => 'required|in:yes,no,maybe',
            'guest_count' => 'nullable|integer|min:1|max:10',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Kiểm tra subscription có RSVP không (hoặc đang trial)
        if ($card->status !== 'trial' && (!$card->subscription || !$card->subscription->has_rsvp)) {
            return response()->json([
                'success' => false,
                'message' => 'Tính năng RSVP chưa được kích hoạt'
            ], 403);
        }

        // Tạo response
        $rsvp = RsvpResponse::create([
            'user_card_id' => $card->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'attendance' => $request->attendance,
            'guest_count' => $request->guest_count ?? 1,
            'message' => $request->message,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã gửi xác nhận thành công!',
            'data' => $rsvp
        ]);
    }

    /**
     * Lấy danh sách RSVP responses cho chủ thiệp
     * GET /api/rsvp/{cardId}
     */
    public function index(Request $request, int $cardId)
    {
        $card = UserCard::find($cardId);
        
        if (!$card) {
            return response()->json(['success' => false], 404);
        }

        // Kiểm tra quyền
        if ($card->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $responses = $card->rsvpResponses()
            ->orderByDesc('created_at')
            ->get();

        $stats = [
            'total' => $responses->count(),
            'attending' => $responses->where('attendance', 'yes')->count(),
            'not_attending' => $responses->where('attendance', 'no')->count(),
            'maybe' => $responses->where('attendance', 'maybe')->count(),
            'total_guests' => $responses->where('attendance', 'yes')->sum('guest_count'),
        ];

        return response()->json([
            'success' => true,
            'data' => $responses,
            'stats' => $stats
        ]);
    }
}
