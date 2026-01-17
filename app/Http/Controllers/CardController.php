<?php

namespace App\Http\Controllers;

use App\Models\UserCard;
use App\Models\Template;
use App\Services\CardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * CardController - Xử lý hiển thị thiệp và editor
 */
class CardController extends Controller
{
    protected CardService $cardService;

    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
    }

    /**
     * Hiển thị thiệp công khai
     * Route: GET /c/{slug}
     */
    public function show(string $slug)
    {
        $card = $this->cardService->findBySlug($slug);

        if (!$card) {
            abort(404, 'Không tìm thấy thiệp');
        }

        // Kiểm tra thiệp có bị khóa không
        if ($card->isLocked()) {
            return view('cards.locked', compact('card'));
        }

        // Tăng view count
        $card->incrementViewCount();

        // Lấy template và render
        $template = $card->template;
        $content = $card->content ?? [];

        return view($template->view_path, compact('card', 'content'));
    }

    /**
     * Trang thiệp bị khóa (hết trial/subscription)
     */
    public function locked(UserCard $card)
    {
        return view('cards.locked', compact('card'));
    }

    /**
     * Danh sách thiệp của user
     * Route: GET /my-cards
     */
    public function myCards()
    {
        $cards = $this->cardService->getUserCards(Auth::user());
        
        return view('cards.my-cards', compact('cards'));
    }

    /**
     * Form tạo thiệp mới - chọn template
     * Route: GET /cards/create
     */
    public function create()
    {
        $templates = Template::active()->get();
        
        return view('cards.create', compact('templates'));
    }

    /**
     * Tạo thiệp mới với template đã chọn
     * Route: POST /cards
     */
    public function store(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:templates,id',
        ]);

        $template = Template::findOrFail($request->template_id);
        $user = Auth::user();

        $card = $this->cardService->createCard($user, $template);

        return redirect()->route('cards.edit', $card->id)
            ->with('success', 'Đã tạo thiệp! Bạn có 2 ngày dùng thử miễn phí.');
    }

    /**
     * Editor - chỉnh sửa thiệp
     * Route: GET /cards/{id}/edit
     */
    public function edit(UserCard $card)
    {
        // Kiểm tra quyền sở hữu
        if ($card->user_id !== Auth::id()) {
            abort(403, 'Không có quyền truy cập');
        }

        $template = $card->template;
        $schema = $template->getSchemaSections();
        $content = $card->content ?? $template->getDefaultContent();

        return view('cards.editor', compact('card', 'template', 'schema', 'content'));
    }

    /**
     * Lưu thay đổi thiệp
     * Route: PUT /cards/{id}
     */
    public function update(Request $request, UserCard $card)
    {
        // Kiểm tra quyền
        if ($card->user_id !== Auth::id()) {
            abort(403);
        }

        $content = $request->input('content', []);
        $this->cardService->updateContent($card, $content);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Đã lưu thay đổi!');
    }

    /**
     * Xóa thiệp
     * Route: DELETE /cards/{id}
     */
    public function destroy(UserCard $card)
    {
        if ($card->user_id !== Auth::id()) {
            abort(403);
        }

        $card->delete();

        return redirect()->route('cards.my-cards')
            ->with('success', 'Đã xóa thiệp');
    }
}
