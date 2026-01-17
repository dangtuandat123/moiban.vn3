@extends('layouts.app')

@section('title', 'Chi tiết User - Admin')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.users') }}" class="text-secondary hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div class="flex items-center gap-4">
            <img src="{{ $user->avatar_url }}" alt="" class="w-16 h-16 rounded-full object-cover">
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                <p class="text-secondary">{{ $user->email }}</p>
            </div>
        </div>
    </div>
    
    <div class="grid md:grid-cols-3 gap-6 mb-8">
        {{-- Stats --}}
        <div class="glass rounded-xl p-6 text-center">
            <div class="text-3xl font-bold text-white">{{ $user->cards->count() }}</div>
            <div class="text-sm text-muted">Thiệp</div>
        </div>
        <div class="glass rounded-xl p-6 text-center">
            <div class="text-3xl font-bold text-green-400">{{ number_format($user->wallet?->balance ?? 0, 0, ',', '.') }}đ</div>
            <div class="text-sm text-muted">Số dư</div>
        </div>
        <div class="glass rounded-xl p-6 text-center">
            <div class="text-3xl font-bold text-white">{{ $user->created_at->format('d/m/Y') }}</div>
            <div class="text-sm text-muted">Ngày tạo</div>
        </div>
    </div>
    
    <div class="grid md:grid-cols-2 gap-6">
        {{-- User Info --}}
        <div class="glass rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Thông tin</h3>
            
            <table class="w-full text-sm">
                <tr class="border-b border-glass-border">
                    <td class="py-3 text-muted">Email</td>
                    <td class="py-3 text-white text-right">{{ $user->email }}</td>
                </tr>
                <tr class="border-b border-glass-border">
                    <td class="py-3 text-muted">Phone</td>
                    <td class="py-3 text-white text-right">{{ $user->phone ?? '-' }}</td>
                </tr>
                <tr class="border-b border-glass-border">
                    <td class="py-3 text-muted">Role</td>
                    <td class="py-3 text-right">
                        <form action="{{ route('admin.users.set-role', $user) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <select name="role" onchange="this.form.submit()" 
                                    class="input-glass px-3 py-1 rounded text-sm">
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </form>
                    </td>
                </tr>
                <tr class="border-b border-glass-border">
                    <td class="py-3 text-muted">Trạng thái</td>
                    <td class="py-3 text-right">
                        <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-1 rounded text-sm {{ $user->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                {{ $user->is_active ? 'Active' : 'Locked' }}
                            </button>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td class="py-3 text-muted">Last login</td>
                    <td class="py-3 text-white text-right">{{ $user->last_login_at?->format('d/m/Y H:i') ?? '-' }}</td>
                </tr>
            </table>
        </div>
        
        {{-- Add Balance --}}
        <div class="glass rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">💰 Nạp tiền thủ công</h3>
            
            <form action="{{ route('admin.users.add-balance', $user) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm text-muted mb-2">Số tiền (VNĐ)</label>
                    <input type="number" name="amount" min="1000" step="1000" required
                           class="input-glass w-full px-4 py-3 rounded-lg"
                           placeholder="99000">
                </div>
                <div>
                    <label class="block text-sm text-muted mb-2">Ghi chú</label>
                    <input type="text" name="note"
                           class="input-glass w-full px-4 py-3 rounded-lg"
                           placeholder="Lý do nạp...">
                </div>
                <button type="submit" class="w-full btn-primary py-3 rounded-lg">
                    ➕ Nạp tiền
                </button>
            </form>
        </div>
    </div>
    
    {{-- Cards --}}
    <div class="glass rounded-xl p-6 mt-6">
        <h3 class="text-lg font-semibold text-white mb-4">💒 Danh sách thiệp ({{ $user->cards->count() }})</h3>
        
        @if($user->cards->count() > 0)
        <div class="space-y-3">
            @foreach($user->cards as $card)
            <div class="flex items-center justify-between p-4 bg-dark-700/50 rounded-xl">
                <div class="flex items-center gap-4">
                    <div class="text-2xl">💒</div>
                    <div>
                        <div class="font-medium text-white">{{ $card->couple_names }}</div>
                        <div class="text-xs text-muted">{{ $card->template?->name }} • {{ $card->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-2 py-1 rounded text-xs {{ $card->status === 'active' ? 'bg-green-500/20 text-green-400' : ($card->status === 'trial' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">
                        {{ ucfirst($card->status) }}
                    </span>
                    <a href="{{ route('cards.public', $card->slug) }}" target="_blank" class="text-primary-400 hover:underline text-sm">
                        Xem →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-center text-muted py-8">Chưa có thiệp nào</p>
        @endif
    </div>
    
    {{-- Transactions --}}
    <div class="glass rounded-xl p-6 mt-6">
        <h3 class="text-lg font-semibold text-white mb-4">📜 Lịch sử giao dịch</h3>
        
        @if($user->wallet?->transactions->count() > 0)
        <div class="space-y-2 max-h-[300px] overflow-y-auto">
            @foreach($user->wallet->transactions as $tx)
            <div class="flex items-center justify-between py-2 border-b border-glass-border last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm
                        {{ in_array($tx->type, ['deposit', 'refund']) ? 'bg-green-500/20' : 'bg-red-500/20' }}">
                        {{ in_array($tx->type, ['deposit', 'refund']) ? '+' : '-' }}
                    </div>
                    <div>
                        <div class="text-sm text-white">{{ ucfirst($tx->type) }}</div>
                        <div class="text-xs text-muted">{{ $tx->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-medium {{ in_array($tx->type, ['deposit', 'refund']) ? 'text-green-400' : 'text-red-400' }}">
                        {{ in_array($tx->type, ['deposit', 'refund']) ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }}đ
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-center text-muted py-8">Chưa có giao dịch</p>
        @endif
    </div>
</div>
@endsection
