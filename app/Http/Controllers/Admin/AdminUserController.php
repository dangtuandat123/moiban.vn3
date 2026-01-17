<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * AdminUserController - Quản lý users
 */
class AdminUserController extends Controller
{
    /**
     * Danh sách users
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Filter by role
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }
        
        // Filter by status
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }
        
        $users = $query->withCount('cards')
            ->with('wallet')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Chi tiết user
     */
    public function show(User $user)
    {
        $user->load(['wallet', 'cards.template', 'wallet.transactions']);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Toggle active status
     */
    public function toggleActive(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', $user->is_active ? 'Đã kích hoạt user' : 'Đã khóa user');
    }

    /**
     * Set role
     */
    public function setRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,admin'
        ]);

        $user->role = $request->role;
        $user->save();

        return back()->with('success', 'Đã cập nhật role');
    }

    /**
     * Nạp tiền thủ công
     */
    public function addBalance(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'note' => 'nullable|string|max:255'
        ]);

        $wallet = $user->getOrCreateWallet();
        
        $wallet->transactions()->create([
            'type' => 'deposit',
            'amount' => $request->amount,
            'balance_before' => $wallet->balance,
            'balance_after' => $wallet->balance + $request->amount,
            'description' => 'Admin nạp thủ công: ' . ($request->note ?? 'N/A'),
            'reference_id' => 'ADMIN_' . time(),
        ]);

        $wallet->increment('balance', $request->amount);

        return back()->with('success', 'Đã nạp ' . number_format($request->amount) . 'đ cho ' . $user->name);
    }
}
