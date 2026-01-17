@extends('layouts.app')

@section('title', 'Ví của tôi - MoiBan.vn')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    {{-- Header --}}
    <div class="text-center mb-12">
        <h1 class="text-3xl font-serif font-bold text-white mb-2">Ví Của Tôi</h1>
        <p class="text-secondary">Nạp tiền để kích hoạt thiệp cưới</p>
    </div>
    
    @php
        $wallet = auth()->user()->getOrCreateWallet();
    @endphp
    
    {{-- Balance Card --}}
    <div class="glass-heavy rounded-3xl p-8 mb-8 text-center relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-20 -right-20 w-60 h-60 bg-primary-500/20 rounded-full blur-[80px]"></div>
            <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-nude-400/20 rounded-full blur-[80px]"></div>
        </div>
        
        <div class="relative z-10">
            <div class="text-lg text-secondary mb-2">Số dư hiện tại</div>
            <div class="text-5xl md:text-6xl font-bold text-white mb-4">
                {{ number_format($wallet->balance, 0, ',', '.') }} <span class="text-3xl">đ</span>
            </div>
            <div class="text-sm text-muted">
                ID: {{ auth()->id() }} • Cập nhật: {{ $wallet->updated_at->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>
    
    <div class="grid md:grid-cols-2 gap-8">
        {{-- Deposit QR --}}
        <div class="glass rounded-2xl p-6" x-data="{ amount: 199000 }">
            <h3 class="text-xl font-semibold text-white mb-4">💳 Nạp tiền qua VietQR</h3>
            
            {{-- Quick amounts --}}
            <div class="grid grid-cols-3 gap-2 mb-4">
                @foreach([99000, 199000, 500000] as $amt)
                <button @click="amount = {{ $amt }}"
                        :class="amount === {{ $amt }} ? 'bg-primary-500 text-white' : 'glass text-secondary'"
                        class="py-2 rounded-lg text-sm font-medium transition">
                    {{ number_format($amt, 0, ',', '.') }}đ
                </button>
                @endforeach
            </div>
            
            {{-- Custom amount --}}
            <div class="mb-4">
                <label class="block text-sm text-muted mb-2">Hoặc nhập số tiền khác:</label>
                <input type="number" x-model="amount" min="10000" step="1000"
                       class="input-glass w-full px-4 py-3 rounded-lg text-lg">
            </div>
            
            {{-- QR Code --}}
            @php
                $bankName = \App\Models\Setting::get('bank_name', 'ACB');
                $bankAccount = \App\Models\Setting::get('bank_account', '11183041');
                $bankHolder = \App\Models\Setting::get('bank_holder', 'DANG TUAN DAT');
                $paymentMessage = 'MOIBAN+' . auth()->id() . '+NAP';
            @endphp
            
            <div class="bg-white rounded-2xl p-4 text-center mb-4">
                <img :src="`https://img.vietqr.io/image/{{ $bankName }}-{{ $bankAccount }}-compact.png?amount=${amount}&addInfo={{ urlencode($paymentMessage) }}&accountName={{ urlencode($bankHolder) }}`"
                     alt="VietQR"
                     class="w-48 h-48 mx-auto">
            </div>
            
            {{-- Bank Info --}}
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-muted">Ngân hàng:</span>
                    <span class="text-white">{{ $bankName }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-muted">Số TK:</span>
                    <span class="text-white">{{ $bankAccount }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-muted">Chủ TK:</span>
                    <span class="text-white">{{ $bankHolder }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-muted">Nội dung CK:</span>
                    <span class="text-primary-400 font-mono">{{ $paymentMessage }}</span>
                </div>
            </div>
            
            <div class="mt-4 p-4 bg-yellow-500/10 border border-yellow-500/30 rounded-xl">
                <p class="text-sm text-yellow-400">
                    ⚠️ <strong>Quan trọng:</strong> Vui lòng ghi đúng nội dung chuyển khoản để hệ thống tự động cộng tiền.
                </p>
            </div>
        </div>
        
        {{-- Transaction History --}}
        <div class="glass rounded-2xl p-6">
            <h3 class="text-xl font-semibold text-white mb-4">📜 Lịch sử giao dịch</h3>
            
            @php
                $transactions = $wallet->transactions()->latest()->take(10)->get();
            @endphp
            
            @if($transactions->count() > 0)
            <div class="space-y-3 max-h-[400px] overflow-y-auto">
                @foreach($transactions as $tx)
                <div class="flex items-center justify-between py-3 border-b border-glass-border last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-xl
                            {{ in_array($tx->type, ['deposit', 'refund']) ? 'bg-green-500/20' : 'bg-red-500/20' }}">
                            {{ in_array($tx->type, ['deposit', 'refund']) ? '➕' : '➖' }}
                        </div>
                        <div>
                            <div class="text-white font-medium">
                                @switch($tx->type)
                                    @case('deposit') Nạp tiền @break
                                    @case('withdraw') Rút tiền @break
                                    @case('purchase') Mua gói @break
                                    @case('refund') Hoàn tiền @break
                                @endswitch
                            </div>
                            <div class="text-xs text-muted">{{ $tx->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-medium {{ in_array($tx->type, ['deposit', 'refund']) ? 'text-green-400' : 'text-red-400' }}">
                            {{ in_array($tx->type, ['deposit', 'refund']) ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }}đ
                        </div>
                        <div class="text-xs text-muted">
                            Còn: {{ number_format($tx->balance_after, 0, ',', '.') }}đ
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12 text-muted">
                <div class="text-4xl mb-3">📭</div>
                <p>Chưa có giao dịch nào</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
