@extends('layouts.app')

@section('title', 'Quản lý Templates - Admin')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Quản lý Templates</h1>
            <p class="text-secondary text-sm">{{ $templates->count() }} mẫu thiệp</p>
        </div>
        <div class="flex gap-3">
            <form action="{{ route('admin.templates.sync') }}" method="POST">
                @csrf
                <button type="submit" class="btn-secondary px-4 py-2 rounded-lg text-sm">
                    🔄 Sync từ thư mục
                </button>
            </form>
            <a href="{{ route('admin.templates.create') }}" class="btn-primary px-4 py-2 rounded-lg text-sm">
                ➕ Tạo mới
            </a>
        </div>
    </div>
    
    {{-- Templates Grid --}}
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($templates as $template)
        <div class="glass rounded-xl overflow-hidden">
            {{-- Thumbnail --}}
            <div class="aspect-[4/3] bg-dark-700 relative">
                @if($template->thumbnail)
                <img src="{{ $template->thumbnail_url }}" alt="" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center text-5xl">💒</div>
                @endif
                
                {{-- Badges --}}
                <div class="absolute top-2 left-2 flex gap-2">
                    @if($template->is_premium)
                    <span class="px-2 py-1 rounded text-xs bg-yellow-500/90 text-white">Premium</span>
                    @endif
                    @if(!$template->is_active)
                    <span class="px-2 py-1 rounded text-xs bg-red-500/90 text-white">Tắt</span>
                    @endif
                </div>
                
                <div class="absolute top-2 right-2">
                    <span class="px-2 py-1 rounded text-xs bg-black/50 text-white">
                        {{ $template->user_cards_count }} thiệp
                    </span>
                </div>
            </div>
            
            {{-- Info --}}
            <div class="p-4">
                <h3 class="font-semibold text-white mb-1">{{ $template->name }}</h3>
                <p class="text-xs text-muted mb-3">Code: {{ $template->code }}</p>
                
                {{-- Actions --}}
                <div class="flex gap-2">
                    <a href="{{ route('admin.templates.edit', $template) }}" 
                       class="flex-1 btn-secondary py-2 rounded-lg text-sm text-center">
                        ✏️ Sửa
                    </a>
                    
                    <form action="{{ route('admin.templates.toggle-active', $template) }}" method="POST" class="flex-1">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full py-2 rounded-lg text-sm {{ $template->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                            {{ $template->is_active ? '✅ Bật' : '❌ Tắt' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full glass rounded-xl p-12 text-center">
            <div class="text-5xl mb-4">🎨</div>
            <p class="text-muted">Chưa có template nào</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
