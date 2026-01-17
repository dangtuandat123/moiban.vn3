@extends('layouts.app')

@section('title', 'Sửa Template - Admin')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.templates.index') }}" class="text-secondary hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-white">Sửa Template: {{ $template->name }}</h1>
    </div>
    
    <form action="{{ route('admin.templates.update', $template) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="glass rounded-xl p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-secondary mb-2">Code (không thể sửa)</label>
                <input type="text" value="{{ $template->code }}" disabled
                       class="input-glass w-full px-4 py-3 rounded-lg opacity-50">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-secondary mb-2">Tên template *</label>
                <input type="text" name="name" value="{{ old('name', $template->name) }}" required
                       class="input-glass w-full px-4 py-3 rounded-lg">
                @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-secondary mb-2">Mô tả</label>
                <textarea name="description" rows="3"
                          class="input-glass w-full px-4 py-3 rounded-lg resize-none">{{ old('description', $template->description) }}</textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-secondary mb-2">Thumbnail</label>
                @if($template->thumbnail)
                <div class="mb-3 relative w-40 h-32 rounded-lg overflow-hidden bg-dark-700">
                    <img src="{{ $template->thumbnail_url }}" alt="" class="w-full h-full object-cover">
                </div>
                @endif
                <input type="file" name="thumbnail" accept="image/*"
                       class="input-glass w-full px-4 py-3 rounded-lg">
            </div>
            
            <div class="flex gap-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_premium" value="1" 
                           {{ $template->is_premium ? 'checked' : '' }}
                           class="w-5 h-5 rounded border-glass-border bg-dark-700 text-primary-500">
                    <span class="text-secondary">Template Premium</span>
                </label>
                
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" 
                           {{ $template->is_active ? 'checked' : '' }}
                           class="w-5 h-5 rounded border-glass-border bg-dark-700 text-primary-500">
                    <span class="text-secondary">Đang hoạt động</span>
                </label>
            </div>
        </div>
        
        {{-- Schema Editor --}}
        <div class="glass rounded-xl p-6">
            <label class="block text-sm font-medium text-secondary mb-2">Schema (JSON)</label>
            <textarea name="schema" rows="10"
                      class="input-glass w-full px-4 py-3 rounded-lg font-mono text-sm resize-none">{{ json_encode($template->schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
            <p class="text-xs text-muted mt-2">Cấu trúc JSON định nghĩa các fields của template</p>
        </div>
        
        <div class="flex gap-4">
            <button type="submit" class="flex-1 btn-primary py-3 rounded-lg">
                💾 Lưu thay đổi
            </button>
            
            <form action="{{ route('admin.templates.destroy', $template) }}" method="POST" 
                  onsubmit="return confirm('Bạn có chắc muốn xóa template này?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-3 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition">
                    🗑️ Xóa
                </button>
            </form>
        </div>
    </form>
</div>
@endsection
