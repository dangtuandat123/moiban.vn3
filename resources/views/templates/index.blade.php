@extends('layouts.app')

@section('title', 'Mẫu thiệp cưới - MoiBan.vn')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    {{-- Header --}}
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-4">
            Bộ Sưu Tập Mẫu Thiệp
        </h1>
        <p class="text-xl text-secondary max-w-2xl mx-auto">
            Hàng trăm mẫu thiệp đẹp, đa dạng phong cách. Chọn mẫu yêu thích và bắt đầu tạo thiệp ngay!
        </p>
    </div>
    
    {{-- Filter --}}
    <div class="flex flex-wrap items-center justify-center gap-3 mb-10">
        <button class="px-5 py-2 rounded-full text-sm font-medium bg-primary-500 text-white">
            Tất cả
        </button>
        <button class="px-5 py-2 rounded-full text-sm font-medium glass text-secondary hover:text-white hover:bg-glass-bg-hover transition">
            Tối giản
        </button>
        <button class="px-5 py-2 rounded-full text-sm font-medium glass text-secondary hover:text-white hover:bg-glass-bg-hover transition">
            Truyền thống
        </button>
        <button class="px-5 py-2 rounded-full text-sm font-medium glass text-secondary hover:text-white hover:bg-glass-bg-hover transition">
            Hiện đại
        </button>
        <button class="px-5 py-2 rounded-full text-sm font-medium glass text-secondary hover:text-white hover:bg-glass-bg-hover transition">
            Boho
        </button>
        <button class="px-5 py-2 rounded-full text-sm font-medium glass text-secondary hover:text-white hover:bg-glass-bg-hover transition">
            Floral
        </button>
    </div>
    
    {{-- Templates Grid --}}
    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($templates as $template)
        <div class="group glass rounded-2xl overflow-hidden hover:bg-glass-bg-hover transition-all">
            {{-- Thumbnail --}}
            <div class="aspect-[3/4] bg-dark-700 relative overflow-hidden">
                @if($template->thumbnail)
                <img src="{{ $template->thumbnail_url }}" 
                     alt="{{ $template->name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-nude-200/20 to-primary-500/20">
                    <span class="text-7xl">💒</span>
                </div>
                @endif
                
                {{-- Overlay --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-end pb-6 gap-3">
                    <a href="/demo/{{ $template->code }}" target="_blank"
                       class="btn-secondary px-6 py-2.5 rounded-lg text-sm">
                        👁️ Xem demo
                    </a>
                    <form action="{{ route('cards.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="template_id" value="{{ $template->id }}">
                        <button type="submit" class="btn-primary px-6 py-2.5 rounded-lg text-sm">
                            ✨ Dùng mẫu này
                        </button>
                    </form>
                </div>
                
                {{-- Badges --}}
                <div class="absolute top-3 left-3 right-3 flex items-start justify-between">
                    @if($template->is_premium)
                    <span class="px-3 py-1 rounded-full text-xs bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-bold shadow-lg">
                        ⭐ Premium
                    </span>
                    @else
                    <span class="px-3 py-1 rounded-full text-xs bg-green-500/90 text-white font-medium shadow-lg">
                        FREE
                    </span>
                    @endif
                </div>
            </div>
            
            {{-- Info --}}
            <div class="p-4">
                <h3 class="text-lg font-semibold text-white truncate">{{ $template->name }}</h3>
                <p class="text-sm text-muted mt-1 line-clamp-1">
                    {{ $template->description ?? 'Mẫu thiệp đẹp, dễ tùy chỉnh' }}
                </p>
            </div>
        </div>
        @empty
        <div class="col-span-full glass rounded-3xl p-12 text-center">
            <div class="text-7xl mb-6">🎨</div>
            <h2 class="text-2xl font-serif font-bold text-white mb-3">Đang cập nhật</h2>
            <p class="text-secondary">
                Bộ sưu tập mẫu thiệp đang được hoàn thiện. Vui lòng quay lại sau nhé!
            </p>
        </div>
        @endforelse
    </div>
    
    {{-- CTA --}}
    <div class="text-center mt-16">
        <div class="glass-heavy rounded-3xl p-12 max-w-3xl mx-auto">
            <h2 class="text-2xl md:text-3xl font-serif font-bold text-white mb-4">
                Không tìm thấy mẫu ưng ý?
            </h2>
            <p class="text-secondary mb-6">
                Liên hệ với chúng tôi để được hỗ trợ thiết kế mẫu thiệp theo yêu cầu riêng!
            </p>
            <a href="mailto:hello@moiban.vn" class="btn-primary px-8 py-4 rounded-xl inline-flex items-center gap-2">
                📧 Liên hệ ngay
            </a>
        </div>
    </div>
</div>
@endsection
