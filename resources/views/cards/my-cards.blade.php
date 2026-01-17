@extends('layouts.app')

@section('title', 'Thiệp của tôi - MoiBan.vn')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-serif font-bold text-white">Thiệp của tôi</h1>
            <p class="text-secondary mt-1">Quản lý và chỉnh sửa các thiệp cưới của bạn</p>
        </div>
        
        <a href="{{ route('cards.create') }}" class="btn-primary px-6 py-3 rounded-xl flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tạo thiệp mới
        </a>
    </div>
    
    {{-- Cards Grid --}}
    @if($cards->count() > 0)
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($cards as $card)
        <div class="glass rounded-2xl overflow-hidden group hover:bg-glass-bg-hover transition-all">
            {{-- Thumbnail --}}
            <div class="aspect-[4/3] bg-dark-700 relative overflow-hidden">
                @if($card->getContentValue('hero_image'))
                <img src="{{ asset('storage/' . $card->getContentValue('hero_image')) }}" 
                     alt="{{ $card->title }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                <div class="w-full h-full flex items-center justify-center text-6xl">
                    💒
                </div>
                @endif
                
                {{-- Status Badge --}}
                <div class="absolute top-3 right-3">
                    @switch($card->status)
                        @case('trial')
                            <span class="px-3 py-1 rounded-full text-xs bg-yellow-500/80 text-white font-medium">
                                ⏰ Trial
                            </span>
                            @break
                        @case('active')
                            <span class="px-3 py-1 rounded-full text-xs bg-green-500/80 text-white font-medium">
                                ✅ Active
                            </span>
                            @break
                        @case('locked')
                        @case('expired')
                            <span class="px-3 py-1 rounded-full text-xs bg-red-500/80 text-white font-medium">
                                🔒 Hết hạn
                            </span>
                            @break
                    @endswitch
                </div>
            </div>
            
            {{-- Info --}}
            <div class="p-5">
                <h3 class="text-lg font-semibold text-white truncate mb-1">
                    {{ $card->couple_names }}
                </h3>
                <p class="text-sm text-muted mb-4">
                    {{ $card->template->name ?? 'Template' }} • {{ $card->created_at->format('d/m/Y') }}
                </p>
                
                {{-- Stats --}}
                <div class="flex items-center gap-4 text-sm text-secondary mb-4">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        {{ number_format($card->view_count) }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $card->rsvpResponses->where('attendance', 'yes')->count() }} RSVP
                    </span>
                </div>
                
                {{-- Actions --}}
                <div class="flex gap-2">
                    <a href="{{ route('cards.edit', $card) }}" 
                       class="flex-1 btn-secondary py-2.5 rounded-lg text-sm text-center">
                        ✏️ Chỉnh sửa
                    </a>
                    <a href="{{ route('cards.public', $card->slug) }}" 
                       target="_blank"
                       class="flex-1 btn-primary py-2.5 rounded-lg text-sm text-center">
                        🔗 Xem thiệp
                    </a>
                </div>
                
                {{-- Trial/Activate --}}
                @if($card->status === 'trial' || $card->status === 'locked')
                <a href="{{ route('cards.edit', $card) }}?action=upgrade" 
                   class="block w-full mt-3 py-2.5 rounded-lg text-sm text-center bg-gradient-to-r from-nude-400 to-primary-500 text-white font-medium">
                    🚀 Kích hoạt ngay
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    {{-- Empty State --}}
    <div class="glass rounded-3xl p-12 text-center">
        <div class="text-7xl mb-6">💒</div>
        <h2 class="text-2xl font-serif font-bold text-white mb-3">Chưa có thiệp nào</h2>
        <p class="text-secondary mb-8 max-w-md mx-auto">
            Bắt đầu tạo thiệp cưới online đẹp lung linh chỉ trong vài phút!
        </p>
        <a href="{{ route('cards.create') }}" class="btn-primary px-8 py-4 rounded-xl inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tạo thiệp đầu tiên
        </a>
    </div>
    @endif
</div>
@endsection
