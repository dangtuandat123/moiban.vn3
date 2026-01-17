@extends('layouts.app')

@section('title', 'Bảng giá - MoiBan.vn')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-16">
    {{-- Header --}}
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-4">Bảng Giá Đơn Giản</h1>
        <p class="text-xl text-secondary max-w-2xl mx-auto">
            Dùng thử miễn phí 2 ngày. Thanh toán khi hài lòng.
        </p>
    </div>
    
    {{-- Pricing Cards --}}
    <div class="grid md:grid-cols-2 gap-8">
        @foreach($subscriptions as $sub)
        <div class="relative glass {{ $sub->slug === 'premium' ? 'glass-heavy border-2 border-primary-500/50' : '' }} rounded-3xl p-8">
            {{-- Popular Badge --}}
            @if($sub->slug === 'premium')
            <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                <span class="bg-primary-500 text-white px-4 py-1 rounded-full text-sm font-medium">
                    🔥 Phổ biến nhất
                </span>
            </div>
            @endif
            
            {{-- Header --}}
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-white mb-2">{{ $sub->name }}</h3>
                <div class="text-4xl font-bold text-white">
                    {{ number_format($sub->price, 0, ',', '.') }} 
                    <span class="text-lg font-normal text-secondary">đ</span>
                </div>
                <p class="text-muted mt-2">
                    / {{ $sub->duration_days > 0 ? $sub->duration_days . ' ngày' : 'Vĩnh viễn' }}
                </p>
            </div>
            
            {{-- Features --}}
            <ul class="space-y-4 mb-8">
                <li class="flex items-center gap-3 text-secondary">
                    <span class="{{ $sub->remove_watermark ? 'text-green-400' : 'text-red-400' }}">
                        {{ $sub->remove_watermark ? '✓' : '✗' }}
                    </span>
                    {{ $sub->remove_watermark ? 'Không watermark' : 'Có watermark' }}
                </li>
                <li class="flex items-center gap-3 text-secondary">
                    <span class="text-green-400">✓</span>
                    {{ $sub->max_images }} ảnh
                </li>
                <li class="flex items-center gap-3 text-secondary">
                    <span class="{{ $sub->has_music ? 'text-green-400' : 'text-red-400' }}">
                        {{ $sub->has_music ? '✓' : '✗' }}
                    </span>
                    Nhạc nền
                </li>
                <li class="flex items-center gap-3 text-secondary">
                    <span class="{{ $sub->has_rsvp ? 'text-green-400' : 'text-red-400' }}">
                        {{ $sub->has_rsvp ? '✓' : '✗' }}
                    </span>
                    RSVP
                </li>
                <li class="flex items-center gap-3 text-secondary">
                    <span class="{{ $sub->has_guestbook ? 'text-green-400' : 'text-red-400' }}">
                        {{ $sub->has_guestbook ? '✓' : '✗' }}
                    </span>
                    Sổ lưu bút
                </li>
                <li class="flex items-center gap-3 text-secondary">
                    <span class="{{ $sub->has_qr ? 'text-green-400' : 'text-red-400' }}">
                        {{ $sub->has_qr ? '✓' : '✗' }}
                    </span>
                    VietQR Mừng cưới
                </li>
                <li class="flex items-center gap-3 text-secondary">
                    <span class="{{ $sub->has_map ? 'text-green-400' : 'text-red-400' }}">
                        {{ $sub->has_map ? '✓' : '✗' }}
                    </span>
                    Google Maps
                </li>
            </ul>
            
            {{-- CTA --}}
            <a href="{{ route('register') }}" 
               class="block w-full py-4 rounded-xl text-center font-semibold
                      {{ $sub->slug === 'premium' ? 'btn-primary' : 'btn-secondary' }}">
                {{ $sub->slug === 'premium' ? 'Mua ngay' : 'Bắt đầu miễn phí' }}
            </a>
        </div>
        @endforeach
    </div>
    
    {{-- FAQ Section --}}
    <div class="mt-20">
        <h2 class="text-3xl font-serif font-bold text-white text-center mb-10">
            Câu hỏi thường gặp
        </h2>
        
        <div class="space-y-4 max-w-3xl mx-auto" x-data="{ open: null }">
            @foreach([
                ['Làm sao để dùng thử miễn phí?', 'Bạn chỉ cần đăng ký tài khoản và tạo thiệp. Hệ thống sẽ tự động cấp 2 ngày dùng thử miễn phí với đầy đủ tính năng.'],
                ['Sau khi trial hết, thiệp có bị xóa không?', 'Không. Thiệp của bạn vẫn được lưu trữ an toàn. Bạn chỉ cần nạp tiền và kích hoạt để tiếp tục sử dụng.'],
                ['Thanh toán bằng cách nào?', 'Bạn nạp tiền vào ví thông qua chuyển khoản ngân hàng (VietQR). Tiền sẽ được cộng tự động trong vài giây.'],
                ['Gói Premium vĩnh viễn là sao?', 'Gói Premium không có thời hạn, thiệp sẽ hoạt động mãi mãi chỉ với 199K một lần thanh toán.'],
            ] as $index => $faq)
            <div class="glass rounded-xl overflow-hidden">
                <button @click="open = open === {{ $index }} ? null : {{ $index }}"
                        class="w-full px-6 py-4 text-left flex items-center justify-between">
                    <span class="font-medium text-white">{{ $faq[0] }}</span>
                    <svg class="w-5 h-5 text-muted transition-transform" 
                         :class="{ 'rotate-180': open === {{ $index }} }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open === {{ $index }}" x-collapse class="px-6 pb-4">
                    <p class="text-secondary">{{ $faq[1] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
