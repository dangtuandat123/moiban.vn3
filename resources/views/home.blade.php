@extends('layouts.app')

@section('title', 'MoiBan.vn - Tạo thiệp cưới online đẹp, miễn phí')
@section('description', 'Nền tảng tạo thiệp cưới online #1 Việt Nam. Hàng trăm mẫu thiệp đẹp, chỉnh sửa dễ dàng, chia sẻ nhanh chóng.')

@section('content')
{{-- Hero Section --}}
<section class="relative min-h-[90vh] flex items-center justify-center overflow-hidden">
    {{-- Background gradient blobs --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary-500/30 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-nude-400/30 rounded-full blur-[120px] animate-pulse"></div>
    </div>
    
    <div class="relative z-10 max-w-5xl mx-auto px-4 text-center">
        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 glass px-4 py-2 rounded-full mb-8">
            <span class="text-lg">✨</span>
            <span class="text-sm text-secondary">Dùng thử 2 ngày miễn phí</span>
        </div>
        
        {{-- Heading --}}
        <h1 class="font-serif text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-tight mb-6">
            Tạo Thiệp Cưới<br>
            <span class="bg-gradient-to-r from-nude-300 via-nude-400 to-primary-400 bg-clip-text text-transparent">
                Online Trong 5 Phút
            </span>
        </h1>
        
        <p class="text-xl md:text-2xl text-secondary max-w-2xl mx-auto mb-10 leading-relaxed">
            Hàng trăm mẫu thiệp đẹp. Chỉnh sửa trực tiếp. 
            Chia sẻ link thiệp chỉ với 1 click.
        </p>
        
        {{-- CTA Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/templates') }}" 
               class="btn-primary px-8 py-4 rounded-xl text-lg font-semibold flex items-center justify-center gap-2">
                <span>🎨</span>
                Chọn mẫu thiệp
            </a>
            <a href="{{ url('/demo') }}" 
               class="btn-secondary px-8 py-4 rounded-xl text-lg font-medium flex items-center justify-center gap-2">
                <span>👁️</span>
                Xem demo
            </a>
        </div>
        
        {{-- Stats --}}
        <div class="flex justify-center gap-8 md:gap-16 mt-16">
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-white">500+</div>
                <div class="text-sm text-muted mt-1">Mẫu thiệp</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-white">10K+</div>
                <div class="text-sm text-muted mt-1">Cặp đôi tin dùng</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-white">99%</div>
                <div class="text-sm text-muted mt-1">Hài lòng</div>
            </div>
        </div>
    </div>
</section>

{{-- Features Section --}}
<section class="py-24 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="font-serif text-4xl md:text-5xl font-bold text-white mb-4">
                Tại sao chọn MoiBan.vn?
            </h2>
            <p class="text-secondary text-lg max-w-2xl mx-auto">
                Thiệp cưới online đẹp, chuyên nghiệp và đầy đủ tính năng nhất
            </p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            {{-- Feature 1 --}}
            <div class="glass rounded-2xl p-8 hover:bg-glass-bg-hover transition-all duration-300 group">
                <div class="text-5xl mb-6">🎨</div>
                <h3 class="text-xl font-semibold text-white mb-3">Hàng trăm mẫu đẹp</h3>
                <p class="text-secondary leading-relaxed">
                    Thiết kế đa dạng phong cách: Tối giản, Truyền thống, Hiện đại, Boho... Cập nhật liên tục.
                </p>
            </div>
            
            {{-- Feature 2 --}}
            <div class="glass rounded-2xl p-8 hover:bg-glass-bg-hover transition-all duration-300 group">
                <div class="text-5xl mb-6">📱</div>
                <h3 class="text-xl font-semibold text-white mb-3">Chỉnh sửa dễ dàng</h3>
                <p class="text-secondary leading-relaxed">
                    Giao diện kéo thả trực quan. Chỉnh sửa trực tiếp trên điện thoại. Không cần kỹ năng thiết kế.
                </p>
            </div>
            
            {{-- Feature 3 --}}
            <div class="glass rounded-2xl p-8 hover:bg-glass-bg-hover transition-all duration-300 group">
                <div class="text-5xl mb-6">🔗</div>
                <h3 class="text-xl font-semibold text-white mb-3">Chia sẻ 1 click</h3>
                <p class="text-secondary leading-relaxed">
                    Gửi link thiệp qua Zalo, Facebook, Messenger. Preview đẹp với hình ảnh OG tự động.
                </p>
            </div>
            
            {{-- Feature 4 --}}
            <div class="glass rounded-2xl p-8 hover:bg-glass-bg-hover transition-all duration-300 group">
                <div class="text-5xl mb-6">🎵</div>
                <h3 class="text-xl font-semibold text-white mb-3">Nhạc nền lãng mạn</h3>
                <p class="text-secondary leading-relaxed">
                    Upload nhạc yêu thích hoặc chọn từ thư viện. Tự động phát khi khách mở thiệp.
                </p>
            </div>
            
            {{-- Feature 5 --}}
            <div class="glass rounded-2xl p-8 hover:bg-glass-bg-hover transition-all duration-300 group">
                <div class="text-5xl mb-6">✉️</div>
                <h3 class="text-xl font-semibold text-white mb-3">RSVP thông minh</h3>
                <p class="text-secondary leading-relaxed">
                    Khách xác nhận tham dự trực tiếp trên thiệp. Quản lý danh sách khách mời dễ dàng.
                </p>
            </div>
            
            {{-- Feature 6 --}}
            <div class="glass rounded-2xl p-8 hover:bg-glass-bg-hover transition-all duration-300 group">
                <div class="text-5xl mb-6">💝</div>
                <h3 class="text-xl font-semibold text-white mb-3">Mừng cưới VietQR</h3>
                <p class="text-secondary leading-relaxed">
                    Tích hợp QR chuyển khoản. Khách mừng tiền thuận tiện mà không cần mang theo phong bì.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Templates Preview --}}
<section class="py-24 px-4 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-gradient-to-b from-primary-500/20 to-transparent blur-[100px]"></div>
    </div>
    
    <div class="relative z-10 max-w-6xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="font-serif text-4xl md:text-5xl font-bold text-white mb-4">
                Mẫu Thiệp Nổi Bật
            </h2>
            <p class="text-secondary text-lg">
                Chọn mẫu yêu thích và bắt đầu tùy chỉnh ngay
            </p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @for($i = 1; $i <= 4; $i++)
            <div class="group relative aspect-[3/4] rounded-2xl overflow-hidden glass">
                <div class="absolute inset-0 bg-gradient-to-br from-nude-{{ $i * 100 }}/20 to-primary-500/10 flex items-center justify-center">
                    <span class="text-6xl">💒</span>
                </div>
                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <a href="{{ url('/templates') }}" class="btn-primary px-6 py-3 rounded-lg text-sm">
                        Xem chi tiết
                    </a>
                </div>
            </div>
            @endfor
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ url('/templates') }}" class="btn-secondary px-8 py-4 rounded-xl text-lg inline-flex items-center gap-2">
                Xem tất cả mẫu thiệp
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- Pricing Preview --}}
<section class="py-24 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="font-serif text-4xl md:text-5xl font-bold text-white mb-4">
                Bảng Giá Đơn Giản
            </h2>
            <p class="text-secondary text-lg">
                Dùng thử miễn phí 2 ngày. Thanh toán khi hài lòng.
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-8">
            {{-- Basic --}}
            <div class="glass rounded-3xl p-8">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-white mb-2">Gói Basic</h3>
                    <div class="text-4xl font-bold text-white">
                        99.000 <span class="text-lg font-normal text-secondary">đ</span>
                    </div>
                    <p class="text-muted mt-2">/ 30 ngày</p>
                </div>
                
                <ul class="space-y-4 mb-8">
                    <li class="flex items-center gap-3 text-secondary">
                        <span class="text-green-400">✓</span> Thiệp cơ bản
                    </li>
                    <li class="flex items-center gap-3 text-secondary">
                        <span class="text-green-400">✓</span> 5 ảnh
                    </li>
                    <li class="flex items-center gap-3 text-secondary">
                        <span class="text-green-400">✓</span> Google Maps
                    </li>
                    <li class="flex items-center gap-3 text-muted line-through">
                        <span class="text-red-400">✗</span> Nhạc nền
                    </li>
                    <li class="flex items-center gap-3 text-muted line-through">
                        <span class="text-red-400">✗</span> RSVP
                    </li>
                </ul>
                
                <a href="{{ url('/register') }}" class="block w-full btn-secondary py-4 rounded-xl text-center font-semibold">
                    Bắt đầu miễn phí
                </a>
            </div>
            
            {{-- Premium --}}
            <div class="relative glass-heavy rounded-3xl p-8 border-2 border-primary-500/50">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                    <span class="bg-primary-500 text-white px-4 py-1 rounded-full text-sm font-medium">
                        🔥 Phổ biến nhất
                    </span>
                </div>
                
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-white mb-2">Gói Premium</h3>
                    <div class="text-4xl font-bold text-white">
                        199.000 <span class="text-lg font-normal text-secondary">đ</span>
                    </div>
                    <p class="text-muted mt-2">/ Vĩnh viễn</p>
                </div>
                
                <ul class="space-y-4 mb-8">
                    <li class="flex items-center gap-3 text-secondary">
                        <span class="text-green-400">✓</span> Tất cả mẫu thiệp
                    </li>
                    <li class="flex items-center gap-3 text-secondary">
                        <span class="text-green-400">✓</span> 20 ảnh + Album
                    </li>
                    <li class="flex items-center gap-3 text-secondary">
                        <span class="text-green-400">✓</span> Nhạc nền
                    </li>
                    <li class="flex items-center gap-3 text-secondary">
                        <span class="text-green-400">✓</span> RSVP + Lời chúc
                    </li>
                    <li class="flex items-center gap-3 text-secondary">
                        <span class="text-green-400">✓</span> VietQR Mừng cưới
                    </li>
                    <li class="flex items-center gap-3 text-secondary">
                        <span class="text-green-400">✓</span> Không có watermark
                    </li>
                </ul>
                
                <a href="{{ url('/register') }}" class="block w-full btn-primary py-4 rounded-xl text-center font-semibold">
                    Mua ngay
                </a>
            </div>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-24 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="glass-heavy rounded-3xl p-12 md:p-16 text-center relative overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500/20 rounded-full blur-[80px]"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-nude-400/20 rounded-full blur-[80px]"></div>
            </div>
            
            <div class="relative z-10">
                <div class="text-6xl mb-6">💒</div>
                <h2 class="font-serif text-3xl md:text-4xl font-bold text-white mb-4">
                    Sẵn sàng tạo thiệp cưới?
                </h2>
                <p class="text-secondary text-lg mb-8 max-w-xl mx-auto">
                    Chỉ mất 5 phút để có ngay thiệp cưới online đẹp lung linh. 
                    Dùng thử miễn phí ngay hôm nay!
                </p>
                <a href="{{ url('/register') }}" class="btn-primary px-10 py-4 rounded-xl text-lg inline-flex items-center gap-2">
                    <span>🚀</span>
                    Tạo thiệp ngay
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
