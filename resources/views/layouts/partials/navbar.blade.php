<!-- Navigation Bar - Glassmorphism Style -->
<nav class="fixed top-0 left-0 right-0 z-50 glass-light" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <span class="text-2xl">💒</span>
                    <span class="font-serif text-xl font-semibold text-white">MoiBan<span class="text-nude-400">.vn</span></span>
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ url('/') }}" class="text-secondary hover:text-white transition-colors">Trang chủ</a>
                <a href="{{ url('/templates') }}" class="text-secondary hover:text-white transition-colors">Mẫu thiệp</a>
                <a href="{{ url('/pricing') }}" class="text-secondary hover:text-white transition-colors">Bảng giá</a>
                <a href="{{ url('/huong-dan') }}" class="text-secondary hover:text-white transition-colors">Hướng dẫn</a>
            </div>
            
            <!-- Auth Buttons -->
            <div class="hidden md:flex items-center gap-4">
                @auth
                    <div class="flex items-center gap-4">
                        <!-- Wallet Balance -->
                        <div class="glass px-4 py-2 rounded-lg flex items-center gap-2">
                            <span class="text-nude-400">💰</span>
                            <span class="text-sm text-secondary">{{ number_format(auth()->user()->balance, 0, ',', '.') }} đ</span>
                        </div>
                        
                        <!-- User Menu -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 glass px-3 py-2 rounded-lg hover:bg-glass-bg-hover transition-colors">
                                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                                <span class="text-sm text-secondary">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-56 glass-heavy rounded-xl shadow-2xl overflow-hidden">
                                
                                <a href="{{ url('/dashboard') }}" class="block px-4 py-3 text-secondary hover:bg-glass-bg-hover hover:text-white transition-colors">
                                    📊 Dashboard
                                </a>
                                <a href="{{ url('/my-cards') }}" class="block px-4 py-3 text-secondary hover:bg-glass-bg-hover hover:text-white transition-colors">
                                    💒 Thiệp của tôi
                                </a>
                                <a href="{{ url('/wallet') }}" class="block px-4 py-3 text-secondary hover:bg-glass-bg-hover hover:text-white transition-colors">
                                    💰 Nạp tiền
                                </a>
                                <div class="border-t border-glass-border"></div>
                                <a href="{{ url('/settings') }}" class="block px-4 py-3 text-secondary hover:bg-glass-bg-hover hover:text-white transition-colors">
                                    ⚙️ Cài đặt
                                </a>
                                
                                @if(auth()->user()->isAdmin())
                                <a href="{{ url('/admin') }}" class="block px-4 py-3 text-primary-400 hover:bg-glass-bg-hover transition-colors">
                                    🛡️ Admin Panel
                                </a>
                                @endif
                                
                                <div class="border-t border-glass-border"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-red-400 hover:bg-glass-bg-hover transition-colors">
                                        🚪 Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary px-4 py-2 rounded-lg text-sm">
                        Đăng nhập
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary px-4 py-2 rounded-lg text-sm">
                        Tạo thiệp miễn phí
                    </a>
                @endauth
            </div>
            
            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="glass p-2 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="md:hidden glass-heavy border-t border-glass-border">
        <div class="px-4 py-4 space-y-2">
            <a href="{{ url('/') }}" class="block px-4 py-3 rounded-lg text-secondary hover:bg-glass-bg-hover hover:text-white transition-colors">Trang chủ</a>
            <a href="{{ url('/templates') }}" class="block px-4 py-3 rounded-lg text-secondary hover:bg-glass-bg-hover hover:text-white transition-colors">Mẫu thiệp</a>
            <a href="{{ url('/pricing') }}" class="block px-4 py-3 rounded-lg text-secondary hover:bg-glass-bg-hover hover:text-white transition-colors">Bảng giá</a>
            <a href="{{ url('/huong-dan') }}" class="block px-4 py-3 rounded-lg text-secondary hover:bg-glass-bg-hover hover:text-white transition-colors">Hướng dẫn</a>
            
            @guest
                <div class="pt-4 flex gap-3">
                    <a href="{{ route('login') }}" class="flex-1 btn-secondary px-4 py-3 rounded-lg text-sm text-center">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="flex-1 btn-primary px-4 py-3 rounded-lg text-sm text-center">Tạo thiệp</a>
                </div>
            @endguest
        </div>
    </div>
</nav>

<!-- Spacer for fixed navbar -->
<div class="h-16"></div>
