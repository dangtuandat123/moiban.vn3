{{-- 
    Template: Elegant Floral - Phong cách Hoa Cổ Điển
    Sử dụng: Tailwind CSS CDN + Alpine.js
    Biến: $content (array từ user_cards.content JSON)
--}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>{{ $content['page_title'] ?? 'Thiệp cưới' }}</title>
    
    <!-- SEO -->
    <meta name="description" content="Thiệp cưới của {{ $content['groom_name'] ?? '' }} & {{ $content['bride_name'] ?? '' }}">
    
    <!-- Open Graph -->
    <meta property="og:title" content="{{ $content['page_title'] ?? 'Thiệp cưới' }}">
    <meta property="og:description" content="Trân trọng kính mời bạn đến dự đám cưới của chúng mình">
    <meta property="og:image" content="{{ $card->og_image_url ?? '' }}">
    <meta property="og:type" content="website">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Quicksand:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['Cormorant Garamond', 'Georgia', 'serif'],
                        sans: ['Quicksand', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: '{{ $content["primary_color"] ?? "#D4A373" }}',
                        nude: {
                            50: '#fdf8f6',
                            100: '#f7ede8',
                            200: '#ecd9cc',
                            300: '#d4a373',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* Floral Background Pattern */
        .floral-bg {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23d4a373' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        /* Smooth scroll */
        html { scroll-behavior: smooth; }
        
        /* Fade in animation */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        /* Countdown box */
        .countdown-box {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="font-sans text-gray-800 bg-nude-50 floral-bg" x-data="{ showMusicPopup: true }">

    {{-- Watermark Layer (hiển thị khi trial) --}}
    @if($card->shouldShowWatermark())
    <div class="fixed inset-0 z-50 pointer-events-none select-none flex items-center justify-center">
        <div class="text-6xl font-bold text-gray-300 opacity-20 rotate-[-30deg]">
            moiban.vn
        </div>
    </div>
    @endif

    {{-- Music Player Popup --}}
    @if($content['bg_music'] ?? false)
    <div x-show="showMusicPopup" 
         class="fixed inset-0 z-40 bg-black/50 flex items-center justify-center"
         x-transition>
        <div class="bg-white rounded-2xl p-8 text-center max-w-sm mx-4 shadow-2xl animate-fade-in">
            <div class="text-6xl mb-4">🎵</div>
            <h3 class="text-xl font-serif font-semibold text-gray-800 mb-2">Nhạc nền</h3>
            <p class="text-gray-600 mb-6">Bật nhạc để trải nghiệm tốt hơn nhé!</p>
            <button @click="showMusicPopup = false; $refs.bgMusic.play()" 
                    class="w-full bg-primary text-white py-3 px-6 rounded-full font-medium hover:opacity-90 transition">
                Bật nhạc
            </button>
            <button @click="showMusicPopup = false" 
                    class="w-full text-gray-500 py-2 mt-2 text-sm">
                Bỏ qua
            </button>
        </div>
    </div>
    <audio x-ref="bgMusic" loop>
        <source src="{{ asset('storage/' . $content['bg_music']) }}" type="audio/mpeg">
    </audio>
    @endif

    {{-- Hero Section --}}
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        {{-- Background Image --}}
        @if($content['hero_image'] ?? false)
        <div class="absolute inset-0">
            <img src="{{ asset('storage/' . $content['hero_image']) }}" 
                 alt="Hero" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/30"></div>
        </div>
        @else
        <div class="absolute inset-0 bg-gradient-to-br from-nude-100 via-nude-50 to-white"></div>
        @endif
        
        {{-- Content --}}
        <div class="relative z-10 text-center px-4 py-20 animate-fade-in">
            <p class="text-white/80 text-lg mb-4 font-light tracking-widest uppercase">
                Trân trọng kính mời
            </p>
            
            <h1 class="font-serif text-5xl md:text-7xl lg:text-8xl font-bold text-white mb-6">
                {{ $content['groom_name'] ?? 'Chú Rể' }}
                <span class="block text-3xl md:text-4xl font-normal my-4">&</span>
                {{ $content['bride_name'] ?? 'Cô Dâu' }}
            </h1>
            
            <p class="text-white/90 text-lg md:text-xl max-w-2xl mx-auto mb-8">
                {{ $content['welcome_message'] ?? '' }}
            </p>
            
            {{-- Countdown --}}
            @if($content['wedding_date'] ?? false)
            <div class="flex justify-center gap-4 md:gap-6 mt-10" x-data="countdown('{{ $content['wedding_date'] }}')">
                <div class="countdown-box rounded-xl p-4 md:p-6 min-w-[70px] md:min-w-[90px]">
                    <div class="text-3xl md:text-4xl font-bold text-primary" x-text="days">0</div>
                    <div class="text-xs md:text-sm text-gray-600 mt-1">Ngày</div>
                </div>
                <div class="countdown-box rounded-xl p-4 md:p-6 min-w-[70px] md:min-w-[90px]">
                    <div class="text-3xl md:text-4xl font-bold text-primary" x-text="hours">0</div>
                    <div class="text-xs md:text-sm text-gray-600 mt-1">Giờ</div>
                </div>
                <div class="countdown-box rounded-xl p-4 md:p-6 min-w-[70px] md:min-w-[90px]">
                    <div class="text-3xl md:text-4xl font-bold text-primary" x-text="minutes">0</div>
                    <div class="text-xs md:text-sm text-gray-600 mt-1">Phút</div>
                </div>
                <div class="countdown-box rounded-xl p-4 md:p-6 min-w-[70px] md:min-w-[90px]">
                    <div class="text-3xl md:text-4xl font-bold text-primary" x-text="seconds">0</div>
                    <div class="text-xs md:text-sm text-gray-600 mt-1">Giây</div>
                </div>
            </div>
            @endif
            
            {{-- Scroll indicator --}}
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </div>
        </div>
    </section>

    {{-- Couple Section --}}
    <section class="py-20 px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-center font-serif text-4xl md:text-5xl font-bold text-gray-800 mb-16">
                Cô Dâu & Chú Rể
            </h2>
            
            <div class="grid md:grid-cols-2 gap-12">
                {{-- Groom --}}
                <div class="text-center">
                    <div class="w-48 h-48 mx-auto mb-6 rounded-full overflow-hidden border-4 border-primary/30 shadow-lg">
                        @if($content['groom_avatar'] ?? false)
                        <img src="{{ asset('storage/' . $content['groom_avatar']) }}" 
                             alt="{{ $content['groom_name'] }}"
                             class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full bg-nude-200 flex items-center justify-center text-6xl">
                            🤵
                        </div>
                        @endif
                    </div>
                    <h3 class="font-serif text-2xl font-semibold text-gray-800 mb-2">
                        {{ $content['groom_name'] ?? 'Chú Rể' }}
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $content['groom_story'] ?? '' }}
                    </p>
                </div>
                
                {{-- Bride --}}
                <div class="text-center">
                    <div class="w-48 h-48 mx-auto mb-6 rounded-full overflow-hidden border-4 border-primary/30 shadow-lg">
                        @if($content['bride_avatar'] ?? false)
                        <img src="{{ asset('storage/' . $content['bride_avatar']) }}" 
                             alt="{{ $content['bride_name'] }}"
                             class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full bg-nude-200 flex items-center justify-center text-6xl">
                            👰
                        </div>
                        @endif
                    </div>
                    <h3 class="font-serif text-2xl font-semibold text-gray-800 mb-2">
                        {{ $content['bride_name'] ?? 'Cô Dâu' }}
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $content['bride_story'] ?? '' }}
                    </p>
                </div>
            </div>
            
            {{-- Love Story --}}
            @if($content['love_story'] ?? false)
            <div class="mt-16 text-center">
                <h3 class="font-serif text-2xl font-semibold text-gray-800 mb-4">💕 Câu chuyện tình yêu</h3>
                <p class="text-gray-600 leading-relaxed max-w-2xl mx-auto">
                    {{ $content['love_story'] }}
                </p>
            </div>
            @endif
        </div>
    </section>

    {{-- Event Details Section --}}
    <section class="py-20 px-4 bg-white">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="font-serif text-4xl md:text-5xl font-bold text-gray-800 mb-16">
                Thời Gian & Địa Điểm
            </h2>
            
            <div class="bg-nude-50 rounded-3xl p-8 md:p-12 shadow-lg">
                <div class="text-6xl mb-6">📍</div>
                
                <p class="text-primary text-xl font-semibold mb-2">
                    {{ $content['ceremony_time'] ?? '17:30' }} - {{ \Carbon\Carbon::parse($content['wedding_date'] ?? now())->format('d/m/Y') }}
                </p>
                
                <h3 class="font-serif text-2xl md:text-3xl font-bold text-gray-800 mb-4">
                    {{ $content['location_name'] ?? 'Địa điểm tổ chức' }}
                </h3>
                
                <p class="text-gray-600 mb-8">
                    {{ $content['address'] ?? '' }}
                </p>
                
                @if($content['google_map_link'] ?? false)
                <a href="{{ $content['google_map_link'] }}" 
                   target="_blank"
                   class="inline-flex items-center gap-2 bg-primary text-white py-3 px-8 rounded-full font-medium hover:opacity-90 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    Xem bản đồ
                </a>
                @endif
            </div>
        </div>
    </section>

    {{-- Gallery Section --}}
    @if(!empty($content['gallery_images']))
    <section class="py-20 px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-center font-serif text-4xl md:text-5xl font-bold text-gray-800 mb-16">
                Album Ảnh Cưới
            </h2>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($content['gallery_images'] as $image)
                <div class="aspect-square rounded-xl overflow-hidden shadow-lg hover:scale-105 transition duration-300">
                    <img src="{{ asset('storage/' . $image) }}" 
                         alt="Gallery"
                         class="w-full h-full object-cover">
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- RSVP Widget --}}
    @include('components.widgets.rsvp-form', ['card' => $card])

    {{-- Guestbook Widget --}}
    @include('components.widgets.guestbook', ['card' => $card])

    {{-- VietQR Widget --}}
    @include('components.widgets.vietqr-box', ['content' => $content])

    {{-- Footer --}}
    <footer class="py-12 px-4 bg-nude-100 text-center">
        <div class="font-serif text-2xl text-gray-800 mb-4">
            {{ $content['groom_name'] ?? '' }} ❤️ {{ $content['bride_name'] ?? '' }}
        </div>
        <p class="text-gray-600 text-sm">
            Cảm ơn bạn đã ghé thăm thiệp cưới của chúng mình!
        </p>
        <p class="text-gray-400 text-xs mt-4">
            Tạo bởi <a href="https://moiban.vn" class="text-primary hover:underline">MoiBan.vn</a>
        </p>
    </footer>

    {{-- Countdown Script --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('countdown', (targetDate) => ({
                days: 0,
                hours: 0,
                minutes: 0,
                seconds: 0,
                init() {
                    this.updateCountdown();
                    setInterval(() => this.updateCountdown(), 1000);
                },
                updateCountdown() {
                    const target = new Date(targetDate).getTime();
                    const now = new Date().getTime();
                    const diff = target - now;

                    if (diff > 0) {
                        this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                        this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
                    }
                }
            }));
        });
    </script>
</body>
</html>
