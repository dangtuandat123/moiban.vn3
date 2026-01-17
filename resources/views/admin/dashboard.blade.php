@extends('layouts.app')

@section('title', 'Admin Dashboard - MoiBan.vn')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-serif font-bold text-white">Admin Dashboard</h1>
            <p class="text-secondary mt-1">Quản lý hệ thống MoiBan.vn</p>
        </div>
        <div class="text-sm text-muted">
            {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
    
    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @php
            $totalUsers = \App\Models\User::count();
            $totalCards = \App\Models\UserCard::count();
            $activeCards = \App\Models\UserCard::where('status', 'active')->count();
            $totalRevenue = \App\Models\Transaction::where('type', 'deposit')->sum('amount');
        @endphp
        
        <div class="glass rounded-2xl p-6">
            <div class="text-4xl mb-3">👥</div>
            <div class="text-3xl font-bold text-white">{{ number_format($totalUsers) }}</div>
            <div class="text-sm text-muted">Tổng Users</div>
        </div>
        
        <div class="glass rounded-2xl p-6">
            <div class="text-4xl mb-3">💒</div>
            <div class="text-3xl font-bold text-white">{{ number_format($totalCards) }}</div>
            <div class="text-sm text-muted">Tổng Thiệp</div>
        </div>
        
        <div class="glass rounded-2xl p-6">
            <div class="text-4xl mb-3">✅</div>
            <div class="text-3xl font-bold text-white">{{ number_format($activeCards) }}</div>
            <div class="text-sm text-muted">Thiệp Active</div>
        </div>
        
        <div class="glass rounded-2xl p-6">
            <div class="text-4xl mb-3">💰</div>
            <div class="text-3xl font-bold text-white">{{ number_format($totalRevenue, 0, ',', '.') }}đ</div>
            <div class="text-sm text-muted">Doanh thu</div>
        </div>
    </div>
    
    {{-- Quick Actions --}}
    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('admin.users') ?? '#' }}" class="glass rounded-2xl p-6 hover:bg-glass-bg-hover transition group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary-500/20 flex items-center justify-center text-2xl">
                    👥
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-primary-400 transition">Quản lý Users</h3>
                    <p class="text-sm text-muted">Xem, sửa, khóa tài khoản</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.templates') ?? '#' }}" class="glass rounded-2xl p-6 hover:bg-glass-bg-hover transition group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center text-2xl">
                    🎨
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-green-400 transition">Quản lý Templates</h3>
                    <p class="text-sm text-muted">Thêm, sửa, xóa mẫu thiệp</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.settings') ?? '#' }}" class="glass rounded-2xl p-6 hover:bg-glass-bg-hover transition group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center text-2xl">
                    ⚙️
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-yellow-400 transition">Cài đặt hệ thống</h3>
                    <p class="text-sm text-muted">Bank, Telegram, SEO...</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="grid md:grid-cols-2 gap-6">
        {{-- Recent Users --}}
        <div class="glass rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Users mới đăng ký</h3>
            <div class="space-y-3">
                @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                <div class="flex items-center justify-between py-2 border-b border-glass-border last:border-0">
                    <div class="flex items-center gap-3">
                        <img src="{{ $user->avatar_url }}" alt="" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <div class="text-white font-medium">{{ $user->name }}</div>
                            <div class="text-xs text-muted">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-muted">
                        {{ $user->created_at->diffForHumans() }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        {{-- Recent Transactions --}}
        <div class="glass rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Giao dịch gần đây</h3>
            <div class="space-y-3">
                @foreach(\App\Models\Transaction::with('wallet.user')->latest()->take(5)->get() as $tx)
                <div class="flex items-center justify-between py-2 border-b border-glass-border last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-xl
                            {{ $tx->type === 'deposit' ? 'bg-green-500/20' : 'bg-red-500/20' }}">
                            {{ $tx->type === 'deposit' ? '➕' : '➖' }}
                        </div>
                        <div>
                            <div class="text-white font-medium">{{ $tx->wallet?->user?->name ?? 'N/A' }}</div>
                            <div class="text-xs text-muted">{{ ucfirst($tx->type) }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-medium {{ $tx->type === 'deposit' ? 'text-green-400' : 'text-red-400' }}">
                            {{ $tx->type === 'deposit' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }}đ
                        </div>
                        <div class="text-xs text-muted">{{ $tx->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
