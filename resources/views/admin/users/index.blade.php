@extends('layouts.app')

@section('title', 'Quản lý Users - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Quản lý Users</h1>
            <p class="text-secondary text-sm">Tổng: {{ $users->total() }} users</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn-secondary px-4 py-2 rounded-lg text-sm">
            ← Quay lại Dashboard
        </a>
    </div>
    
    {{-- Filters --}}
    <form method="GET" class="glass rounded-xl p-4 mb-6">
        <div class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="input-glass px-4 py-2 rounded-lg text-sm flex-1 min-w-[200px]"
                   placeholder="Tìm theo tên, email, SĐT...">
            
            <select name="role" class="input-glass px-4 py-2 rounded-lg text-sm">
                <option value="">Tất cả role</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            
            <select name="active" class="input-glass px-4 py-2 rounded-lg text-sm">
                <option value="">Tất cả trạng thái</option>
                <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Đang hoạt động</option>
                <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Đã khóa</option>
            </select>
            
            <button type="submit" class="btn-primary px-6 py-2 rounded-lg text-sm">
                🔍 Tìm kiếm
            </button>
        </div>
    </form>
    
    {{-- Users Table --}}
    <div class="glass rounded-xl overflow-hidden">
        <table class="w-full">
            <thead class="bg-dark-700/50">
                <tr class="text-left text-sm text-muted">
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Liên hệ</th>
                    <th class="px-4 py-3">Thiệp</th>
                    <th class="px-4 py-3">Số dư</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Trạng thái</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-glass-border">
                @forelse($users as $user)
                <tr class="hover:bg-glass-bg-hover transition">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->avatar_url }}" alt="" class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <div class="font-medium text-white">{{ $user->name }}</div>
                                <div class="text-xs text-muted">{{ $user->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm text-secondary">{{ $user->email }}</div>
                        <div class="text-xs text-muted">{{ $user->phone ?? '-' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-white">{{ $user->cards_count }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-white">{{ number_format($user->wallet?->balance ?? 0, 0, ',', '.') }}đ</span>
                    </td>
                    <td class="px-4 py-3">
                        @if($user->role === 'admin')
                        <span class="px-2 py-1 rounded text-xs bg-purple-500/20 text-purple-400">Admin</span>
                        @else
                        <span class="px-2 py-1 rounded text-xs bg-blue-500/20 text-blue-400">User</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($user->is_active)
                        <span class="px-2 py-1 rounded text-xs bg-green-500/20 text-green-400">Active</span>
                        @else
                        <span class="px-2 py-1 rounded text-xs bg-red-500/20 text-red-400">Locked</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="p-2 rounded-lg glass hover:bg-glass-bg-hover text-secondary hover:text-white transition"
                               title="Xem chi tiết">
                                👁️
                            </a>
                            
                            <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="p-2 rounded-lg glass hover:bg-glass-bg-hover transition"
                                        title="{{ $user->is_active ? 'Khóa' : 'Mở khóa' }}">
                                    {{ $user->is_active ? '🔒' : '🔓' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-muted">
                        Không tìm thấy user nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    <div class="mt-6">
        {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection
