@extends('layouts.app')

@section('title', 'Cài đặt hệ thống - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Cài đặt Hệ thống</h1>
            <p class="text-secondary text-sm">Quản lý cấu hình MoiBan.vn</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn-secondary px-4 py-2 rounded-lg text-sm">
            ← Quay lại Dashboard
        </a>
    </div>
    
    {{-- Flash messages --}}
    @if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-green-500/20 border border-green-500/30 text-green-400">
        ✅ {{ session('success') }}
    </div>
    @endif
    
    {{-- Settings Form --}}
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        @foreach($settings as $group => $items)
        <div class="glass rounded-xl p-6 mb-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                @switch($group)
                    @case('general') ⚙️ Cài đặt chung @break
                    @case('payment') 💳 Thanh toán @break
                    @case('telegram') 📱 Telegram @break
                    @case('seo') 🔍 SEO @break
                    @default 📋 {{ ucfirst($group) }}
                @endswitch
            </h3>
            
            <div class="space-y-4">
                @foreach($items as $setting)
                <div class="flex items-start gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-secondary mb-1">
                            {{ $setting->key }}
                            <span class="text-xs text-muted">({{ $setting->type }})</span>
                        </label>
                        
                        @if($setting->type === 'boolean')
                        <select name="settings[{{ $setting->key }}]" class="input-glass w-full px-4 py-2 rounded-lg">
                            <option value="1" {{ $setting->value ? 'selected' : '' }}>Bật</option>
                            <option value="0" {{ !$setting->value ? 'selected' : '' }}>Tắt</option>
                        </select>
                        @elseif(strlen($setting->value ?? '') > 100)
                        <textarea name="settings[{{ $setting->key }}]" rows="3"
                                  class="input-glass w-full px-4 py-2 rounded-lg resize-none font-mono text-sm">{{ $setting->value }}</textarea>
                        @else
                        <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}"
                               class="input-glass w-full px-4 py-2 rounded-lg">
                        @endif
                    </div>
                    
                    <form action="{{ route('admin.settings.destroy', $setting->key) }}" method="POST" class="pt-6">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-red-400 hover:text-red-300 transition" 
                                onclick="return confirm('Xóa setting này?')">
                            🗑️
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
        
        <button type="submit" class="w-full btn-primary py-3 rounded-lg">
            💾 Lưu tất cả
        </button>
    </form>
    
    {{-- Add new setting --}}
    <div class="glass rounded-xl p-6 mt-8">
        <h3 class="text-lg font-semibold text-white mb-4">➕ Thêm Setting Mới</h3>
        
        <form action="{{ route('admin.settings.store') }}" method="POST" class="grid md:grid-cols-4 gap-4">
            @csrf
            
            <div>
                <label class="block text-sm text-muted mb-1">Key</label>
                <input type="text" name="key" required pattern="^[a-z_]+$"
                       class="input-glass w-full px-4 py-2 rounded-lg" placeholder="my_setting">
            </div>
            
            <div>
                <label class="block text-sm text-muted mb-1">Value</label>
                <input type="text" name="value" class="input-glass w-full px-4 py-2 rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm text-muted mb-1">Type</label>
                <select name="type" class="input-glass w-full px-4 py-2 rounded-lg">
                    <option value="string">String</option>
                    <option value="integer">Integer</option>
                    <option value="boolean">Boolean</option>
                    <option value="json">JSON</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm text-muted mb-1">Group</label>
                <input type="text" name="group" required class="input-glass w-full px-4 py-2 rounded-lg" placeholder="general">
            </div>
            
            <div class="md:col-span-4">
                <button type="submit" class="btn-secondary px-6 py-2 rounded-lg">
                    ➕ Thêm
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
