{{-- 
    Widget: VietQR Mừng Cưới
    Nhận biến: $content (array từ user_cards.content)
--}}
@php
    $groomBank = $content['groom_bank_name'] ?? '';
    $groomAccount = $content['groom_bank_account'] ?? '';
    $groomHolder = $content['groom_bank_holder'] ?? '';
    
    $brideBank = $content['bride_bank_name'] ?? '';
    $brideAccount = $content['bride_bank_account'] ?? '';
    $brideHolder = $content['bride_bank_holder'] ?? '';
    
    $hasGroom = $groomBank && $groomAccount;
    $hasBride = $brideBank && $brideAccount;
@endphp

@if($hasGroom || $hasBride)
<section class="py-20 px-4 bg-white" id="mung-cuoi">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-center font-serif text-4xl md:text-5xl font-bold text-gray-800 mb-4">
            💝 Hộp Mừng Cưới
        </h2>
        <p class="text-center text-gray-600 mb-12 max-w-xl mx-auto">
            Sự hiện diện của bạn là niềm vui lớn nhất của chúng mình. 
            Nếu muốn gửi quà mừng, bạn có thể chuyển khoản qua QR bên dưới.
        </p>
        
        <div class="grid md:grid-cols-2 gap-8" x-data="{ copied: '' }">
            {{-- Groom QR --}}
            @if($hasGroom)
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-3xl p-8 text-center shadow-lg">
                <div class="text-5xl mb-4">🤵</div>
                <h3 class="font-serif text-xl font-semibold text-gray-800 mb-6">
                    {{ $content['groom_name'] ?? 'Chú Rể' }}
                </h3>
                
                {{-- QR Code Image from VietQR API --}}
                <div class="bg-white rounded-2xl p-4 inline-block mb-6 shadow-md">
                    <img src="https://img.vietqr.io/image/{{ $groomBank }}-{{ $groomAccount }}-compact.png?addInfo=MUNG%20CUOI%20{{ urlencode($content['groom_name'] ?? '') }}"
                         alt="VietQR {{ $groomHolder }}"
                         class="w-48 h-48 mx-auto"
                         loading="lazy">
                </div>
                
                {{-- Bank Info --}}
                <div class="space-y-2 text-sm">
                    <p class="text-gray-600">
                        <span class="font-medium">Ngân hàng:</span> {{ $groomBank }}
                    </p>
                    <p class="text-gray-600">
                        <span class="font-medium">STK:</span> 
                        <span id="groom-stk">{{ $groomAccount }}</span>
                    </p>
                    <p class="text-gray-600">
                        <span class="font-medium">Chủ TK:</span> {{ $groomHolder }}
                    </p>
                </div>
                
                {{-- Copy Button --}}
                <button @click="navigator.clipboard.writeText('{{ $groomAccount }}'); copied = 'groom'; setTimeout(() => copied = '', 2000)"
                        class="mt-6 inline-flex items-center gap-2 bg-blue-500 text-white px-6 py-3 rounded-full font-medium hover:bg-blue-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                    </svg>
                    <span x-show="copied !== 'groom'">Copy STK</span>
                    <span x-show="copied === 'groom'" class="text-green-200">✓ Đã copy!</span>
                </button>
            </div>
            @endif
            
            {{-- Bride QR --}}
            @if($hasBride)
            <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-3xl p-8 text-center shadow-lg">
                <div class="text-5xl mb-4">👰</div>
                <h3 class="font-serif text-xl font-semibold text-gray-800 mb-6">
                    {{ $content['bride_name'] ?? 'Cô Dâu' }}
                </h3>
                
                {{-- QR Code Image --}}
                <div class="bg-white rounded-2xl p-4 inline-block mb-6 shadow-md">
                    <img src="https://img.vietqr.io/image/{{ $brideBank }}-{{ $brideAccount }}-compact.png?addInfo=MUNG%20CUOI%20{{ urlencode($content['bride_name'] ?? '') }}"
                         alt="VietQR {{ $brideHolder }}"
                         class="w-48 h-48 mx-auto"
                         loading="lazy">
                </div>
                
                {{-- Bank Info --}}
                <div class="space-y-2 text-sm">
                    <p class="text-gray-600">
                        <span class="font-medium">Ngân hàng:</span> {{ $brideBank }}
                    </p>
                    <p class="text-gray-600">
                        <span class="font-medium">STK:</span> 
                        <span id="bride-stk">{{ $brideAccount }}</span>
                    </p>
                    <p class="text-gray-600">
                        <span class="font-medium">Chủ TK:</span> {{ $brideHolder }}
                    </p>
                </div>
                
                {{-- Copy Button --}}
                <button @click="navigator.clipboard.writeText('{{ $brideAccount }}'); copied = 'bride'; setTimeout(() => copied = '', 2000)"
                        class="mt-6 inline-flex items-center gap-2 bg-pink-500 text-white px-6 py-3 rounded-full font-medium hover:bg-pink-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                    </svg>
                    <span x-show="copied !== 'bride'">Copy STK</span>
                    <span x-show="copied === 'bride'" class="text-green-200">✓ Đã copy!</span>
                </button>
            </div>
            @endif
        </div>
    </div>
</section>
@endif
