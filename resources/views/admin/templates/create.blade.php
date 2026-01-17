@extends('layouts.app')

@section('title', 'Tạo Template - Admin')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.templates.index') }}" class="text-secondary hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-white">Tạo Template Mới</h1>
    </div>
    
    <form action="{{ route('admin.templates.store') }}" method="POST" enctype="multipart/form-data" class="glass rounded-xl p-6 space-y-4">
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-secondary mb-2">Code * <span class="text-muted">(chỉ chữ thường, số, gạch ngang)</span></label>
            <input type="text" name="code" value="{{ old('code') }}" required pattern="^[a-z0-9-]+$"
                   class="input-glass w-full px-4 py-3 rounded-lg" placeholder="elegant-floral">
            @error('code') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        
        <div>
            <label class="block text-sm font-medium text-secondary mb-2">Tên template *</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="input-glass w-full px-4 py-3 rounded-lg" placeholder="Elegant Floral">
            @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        
        <div>
            <label class="block text-sm font-medium text-secondary mb-2">Mô tả</label>
            <textarea name="description" rows="3" class="input-glass w-full px-4 py-3 rounded-lg resize-none"
                      placeholder="Mô tả ngắn về template...">{{ old('description') }}</textarea>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-secondary mb-2">Thumbnail</label>
            <input type="file" name="thumbnail" accept="image/*"
                   class="input-glass w-full px-4 py-3 rounded-lg">
        </div>
        
        <div class="flex gap-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_premium" value="1"
                       class="w-5 h-5 rounded border-glass-border bg-dark-700 text-primary-500">
                <span class="text-secondary">Template Premium</span>
            </label>
            
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked
                       class="w-5 h-5 rounded border-glass-border bg-dark-700 text-primary-500">
                <span class="text-secondary">Đang hoạt động</span>
            </label>
        </div>
        
        <button type="submit" class="w-full btn-primary py-3 rounded-lg">
            ➕ Tạo Template
        </button>
    </form>
</div>
@endsection
