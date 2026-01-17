@extends('layouts.app')

@section('title', 'Chọn mẫu thiệp - MoiBan.vn')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    {{-- Header --}}
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-4">Chọn Mẫu Thiệp</h1>
        <p class="text-xl text-secondary max-w-2xl mx-auto">
            Hàng trăm mẫu thiệp đẹp, chuyên nghiệp. Chọn mẫu yêu thích và bắt đầu tùy chỉnh!
        </p>
    </div>
    
    {{-- Templates Grid --}}
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($templates as $template)
        <form action="{{ route('cards.store') }}" method="POST" class="group">
            @csrf
            <input type="hidden" name="template_id" value="{{ $template->id }}">
            
            <div class="glass rounded-2xl overflow-hidden hover:bg-glass-bg-hover transition-all cursor-pointer"
                 onclick="this.closest('form').submit()">
                {{-- Thumbnail --}}
                <div class="aspect-[3/4] bg-dark-700 relative overflow-hidden">
                    @if($template->thumbnail)
                    <img src="{{ $template->thumbnail_url }}" 
                         alt="{{ $template->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-nude-200/20 to-primary-500/20">
                        <span class="text-7xl">💒</span>
                    </div>
                    @endif
                    
                    {{-- Overlay on hover --}}
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <button type="submit" class="btn-primary px-8 py-4 rounded-xl text-lg">
                            Chọn mẫu này
                        </button>
                    </div>
                    
                    {{-- Premium Badge --}}
                    @if($template->is_premium)
                    <div class="absolute top-3 right-3">
                        <span class="px-3 py-1 rounded-full text-xs bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-bold">
                            ⭐ Premium
                        </span>
                    </div>
                    @endif
                </div>
                
                {{-- Info --}}
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-white mb-1">{{ $template->name }}</h3>
                    <p class="text-sm text-muted line-clamp-2">
                        {{ $template->description ?? 'Mẫu thiệp đẹp, dễ tùy chỉnh' }}
                    </p>
                </div>
            </div>
        </form>
        @empty
        <div class="col-span-full text-center py-12">
            <div class="text-5xl mb-4">📭</div>
            <p class="text-secondary">Chưa có mẫu thiệp nào. Vui lòng quay lại sau!</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
