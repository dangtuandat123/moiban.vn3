{{-- 
    Template: Minimalist Modern - Phong cách Tối giản
--}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['page_title'] ?? 'Wedding' }}</title>
    
    <!-- SEO -->
    <meta property="og:title" content="{{ $content['page_title'] ?? 'Wedding' }}">
    <meta property="og:image" content="{{ $card->og_image_url ?? '' }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['Playfair Display', 'Georgia', 'serif'],
                        sans: ['Montserrat', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        accent: '{{ $content["primary_color"] ?? "#2C3E50" }}',
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        html { scroll-behavior: smooth; }
        
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body class="font-sans text-gray-800 bg-white">

    {{-- Watermark --}}
    @if($card->shouldShowWatermark())
    <div class="fixed inset-0 z-50 pointer-events-none flex items-center justify-center">
        <div class="text-6xl font-bold text-gray-200 opacity-30 rotate-[-30deg]">moiban.vn</div>
    </div>
    @endif

    {{-- Hero Section --}}
    <section class="hero-section relative bg-gray-50">
        <div class="absolute inset-0 opacity-5">
            <div class="w-full h-full" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23000000\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
        </div>
        
        <div class="relative z-10 text-center px-6 py-20">
            <p class="text-gray-500 uppercase tracking-[0.3em] text-sm mb-8 font-light">
                We're Getting Married
            </p>
            
            <h1 class="font-serif text-6xl md:text-8xl lg:text-9xl font-bold text-accent mb-6">
                {{ $content['groom_name'] ?? 'John' }}
                <span class="block text-3xl md:text-4xl font-normal my-4 text-gray-400">&</span>
                {{ $content['bride_name'] ?? 'Jane' }}
            </h1>
            
            @if($content['quote'] ?? false)
            <p class="text-gray-500 italic max-w-xl mx-auto text-lg mt-10">
                "{{ $content['quote'] }}"
            </p>
            @endif
            
            {{-- Countdown --}}
            @if($content['wedding_date'] ?? false)
            <div class="flex justify-center gap-8 mt-16" x-data="countdown('{{ $content['wedding_date'] }}')">
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold text-accent" x-text="days">0</div>
                    <div class="text-xs uppercase tracking-widest text-gray-400 mt-2">Days</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold text-accent" x-text="hours">0</div>
                    <div class="text-xs uppercase tracking-widest text-gray-400 mt-2">Hours</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold text-accent" x-text="minutes">0</div>
                    <div class="text-xs uppercase tracking-widest text-gray-400 mt-2">Minutes</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold text-accent" x-text="seconds">0</div>
                    <div class="text-xs uppercase tracking-widest text-gray-400 mt-2">Seconds</div>
                </div>
            </div>
            @endif
        </div>
    </section>

    {{-- Couple Photo --}}
    @if($content['couple_photo'] ?? false)
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-6">
            <img src="{{ asset('storage/' . $content['couple_photo']) }}" 
                 alt="Couple"
                 class="w-full rounded-lg shadow-2xl">
        </div>
    </section>
    @endif

    {{-- Event Details --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h2 class="font-serif text-4xl font-bold text-accent mb-12">When & Where</h2>
            
            <div class="bg-white rounded-2xl p-10 shadow-lg">
                <p class="text-5xl font-serif font-bold text-accent mb-4">
                    {{ \Carbon\Carbon::parse($content['wedding_date'] ?? now())->format('d') }}
                </p>
                <p class="text-xl text-gray-600 mb-8">
                    {{ \Carbon\Carbon::parse($content['wedding_date'] ?? now())->format('F Y, H:i') }}
                </p>
                
                <hr class="my-8 border-gray-200">
                
                <h3 class="font-serif text-2xl font-semibold text-accent mb-2">
                    {{ $content['venue_name'] ?? 'Venue' }}
                </h3>
                <p class="text-gray-600 mb-6">{{ $content['venue_address'] ?? '' }}</p>
                
                @if($content['google_map_link'] ?? false)
                <a href="{{ $content['google_map_link'] }}" target="_blank"
                   class="inline-block px-8 py-3 border-2 border-accent text-accent hover:bg-accent hover:text-white transition rounded-full text-sm uppercase tracking-widest">
                    View Map
                </a>
                @endif
            </div>
        </div>
    </section>

    {{-- Gallery --}}
    @if(!empty($content['gallery_images']))
    <section class="py-20">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="font-serif text-4xl font-bold text-accent text-center mb-12">Our Moments</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($content['gallery_images'] as $image)
                <div class="aspect-square overflow-hidden rounded-lg">
                    <img src="{{ asset('storage/' . $image) }}" alt="" class="w-full h-full object-cover hover:scale-110 transition duration-500">
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- RSVP --}}
    @include('components.widgets.rsvp-form', ['card' => $card])

    {{-- Guestbook --}}
    @include('components.widgets.guestbook', ['card' => $card])

    {{-- Footer --}}
    <footer class="py-12 bg-accent text-white text-center">
        <p class="font-serif text-2xl mb-2">{{ $content['groom_name'] ?? '' }} & {{ $content['bride_name'] ?? '' }}</p>
        <p class="text-white/60 text-sm">Made with ❤️ by moiban.vn</p>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('countdown', (targetDate) => ({
                days: 0, hours: 0, minutes: 0, seconds: 0,
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
