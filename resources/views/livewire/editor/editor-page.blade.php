{{-- 
    Livewire Editor - Split View
    Left: Config Panel | Right: Preview iframe
--}}
<div class="min-h-screen bg-dark-900">
    {{-- Top Bar --}}
    <div class="fixed top-0 left-0 right-0 z-50 h-14 glass-heavy border-b border-glass-border flex items-center justify-between px-4">
        {{-- Left: Back & Card Info --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('cards.my-cards') }}" class="text-secondary hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-white font-medium truncate max-w-[200px]">{{ $card->title ?? 'Thiệp của tôi' }}</h1>
                <p class="text-xs text-muted">{{ $template->name }}</p>
            </div>
        </div>
        
        {{-- Center: Status --}}
        <div class="hidden md:flex items-center gap-2">
            @if($card->status === 'trial')
                <span class="px-3 py-1 rounded-full text-xs bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                    🕐 Trial - còn {{ $card->trial_ends_at?->diffForHumans() }}
                </span>
            @elseif($card->status === 'active')
                <span class="px-3 py-1 rounded-full text-xs bg-green-500/20 text-green-400 border border-green-500/30">
                    ✅ Active
                </span>
            @endif
            
            <span class="text-sm {{ $saveStatus === 'Đã lưu!' ? 'text-green-400' : 'text-muted' }}">
                {{ $saveStatus }}
            </span>
        </div>
        
        {{-- Right: Actions --}}
        <div class="flex items-center gap-3">
            <a href="{{ $this->previewUrl }}" target="_blank" 
               class="btn-secondary px-4 py-2 rounded-lg text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <span class="hidden sm:inline">Xem trước</span>
            </a>
            
            <button wire:click="save" 
                    wire:loading.attr="disabled"
                    class="btn-primary px-4 py-2 rounded-lg text-sm flex items-center gap-2">
                <span wire:loading.remove wire:target="save">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </span>
                <span wire:loading wire:target="save">
                    <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                </span>
                Lưu
            </button>
        </div>
    </div>
    
    {{-- Main Content --}}
    <div class="pt-14 flex h-screen">
        {{-- Left Panel: Config --}}
        <div class="w-full md:w-[400px] lg:w-[450px] h-full overflow-y-auto border-r border-glass-border bg-dark-800/50">
            {{-- Section Tabs --}}
            <div class="sticky top-0 z-10 glass-heavy border-b border-glass-border overflow-x-auto">
                <div class="flex">
                    @foreach($schema as $section)
                    <button wire:click="setActiveSection('{{ $section['section_id'] }}')"
                            class="px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors
                                   {{ $activeSection === $section['section_id'] 
                                      ? 'text-white border-b-2 border-primary-500' 
                                      : 'text-secondary hover:text-white' }}">
                        {{ $section['label'] ?? $section['section_id'] }}
                    </button>
                    @endforeach
                </div>
            </div>
            
            {{-- Fields --}}
            <div class="p-4 space-y-6">
                @foreach($schema as $section)
                <div x-show="'{{ $section['section_id'] }}' === '{{ $activeSection }}'" 
                     x-transition
                     class="space-y-5">
                    
                    @foreach($section['fields'] ?? [] as $field)
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-secondary">
                            {{ $field['label'] ?? $field['key'] }}
                        </label>
                        
                        @switch($field['type'])
                            @case('text')
                                <input type="text"
                                       wire:model.live.debounce.500ms="content.{{ $field['key'] }}"
                                       class="input-glass w-full px-4 py-3 rounded-lg text-sm"
                                       placeholder="{{ $field['default'] ?? '' }}">
                                @break
                                
                            @case('textarea')
                                <textarea wire:model.live.debounce.500ms="content.{{ $field['key'] }}"
                                          rows="{{ $field['rows'] ?? 3 }}"
                                          class="input-glass w-full px-4 py-3 rounded-lg text-sm resize-none"
                                          placeholder="{{ $field['default'] ?? '' }}"></textarea>
                                @break
                                
                            @case('datetime')
                                <input type="datetime-local"
                                       wire:model.live="content.{{ $field['key'] }}"
                                       class="input-glass w-full px-4 py-3 rounded-lg text-sm">
                                @break
                                
                            @case('color')
                                <div class="flex items-center gap-3">
                                    <input type="color"
                                           wire:model.live="content.{{ $field['key'] }}"
                                           class="w-12 h-12 rounded-lg cursor-pointer border-0">
                                    <input type="text"
                                           wire:model.live="content.{{ $field['key'] }}"
                                           class="input-glass flex-1 px-4 py-3 rounded-lg text-sm">
                                </div>
                                @break
                                
                            @case('upload_image')
                                <div class="space-y-3">
                                    @if(isset($content[$field['key']]) && $content[$field['key']])
                                    <div class="relative w-full h-40 rounded-xl overflow-hidden bg-dark-700">
                                        <img src="{{ asset('storage/' . $content[$field['key']]) }}" 
                                             class="w-full h-full object-cover">
                                        <button wire:click="$set('content.{{ $field['key'] }}', null)"
                                                class="absolute top-2 right-2 p-1.5 rounded-lg glass text-red-400 hover:bg-red-500/20">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @endif
                                    
                                    <label class="flex items-center justify-center gap-2 p-4 rounded-xl border-2 border-dashed border-glass-border hover:border-primary-500/50 cursor-pointer transition">
                                        <input type="file" 
                                               wire:model="tempImage"
                                               wire:change="uploadImage('{{ $field['key'] }}')"
                                               accept="image/*"
                                               class="hidden">
                                        <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-sm text-muted">Chọn ảnh</span>
                                    </label>
                                    
                                    @if($field['helper'] ?? false)
                                    <p class="text-xs text-muted">{{ $field['helper'] }}</p>
                                    @endif
                                </div>
                                @break
                                
                            @case('upload_audio')
                                <div class="space-y-3">
                                    @if(isset($content[$field['key']]) && $content[$field['key']])
                                    <div class="flex items-center gap-3 p-3 rounded-xl glass">
                                        <span class="text-2xl">🎵</span>
                                        <span class="text-sm text-secondary flex-1 truncate">{{ basename($content[$field['key']]) }}</span>
                                        <button wire:click="$set('content.{{ $field['key'] }}', null)"
                                                class="text-red-400 hover:text-red-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @endif
                                    
                                    <label class="flex items-center justify-center gap-2 p-4 rounded-xl border-2 border-dashed border-glass-border hover:border-primary-500/50 cursor-pointer transition">
                                        <input type="file" 
                                               wire:model="tempAudio"
                                               wire:change="uploadAudio('{{ $field['key'] }}')"
                                               accept="audio/mp3,audio/wav"
                                               class="hidden">
                                        <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                        </svg>
                                        <span class="text-sm text-muted">Chọn nhạc MP3</span>
                                    </label>
                                </div>
                                @break
                                
                            @default
                                <input type="text"
                                       wire:model.live.debounce.500ms="content.{{ $field['key'] }}"
                                       class="input-glass w-full px-4 py-3 rounded-lg text-sm">
                        @endswitch
                    </div>
                    @endforeach
                    
                </div>
                @endforeach
            </div>
        </div>
        
        {{-- Right Panel: Preview --}}
        <div class="hidden md:flex flex-1 h-full items-center justify-center p-4 bg-dark-900">
            <div class="w-full max-w-[375px] h-[667px] rounded-[40px] bg-black p-3 shadow-2xl ring-4 ring-gray-800">
                <div class="w-full h-full rounded-[32px] overflow-hidden bg-white">
                    <iframe src="{{ $this->previewUrl }}" 
                            class="w-full h-full"
                            id="preview-frame"></iframe>
                </div>
            </div>
            
            {{-- Device selector --}}
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 glass rounded-full px-4 py-2 flex gap-2">
                <button class="p-2 rounded-lg bg-primary-500/20 text-primary-400" title="Mobile">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </button>
                <button class="p-2 rounded-lg text-muted hover:text-white transition" title="Desktop">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    {{-- Loading overlay --}}
    <div wire:loading.flex wire:target="save, uploadImage, uploadAudio" 
         class="fixed inset-0 bg-black/50 z-[100] items-center justify-center">
        <div class="glass rounded-2xl p-6 flex items-center gap-3">
            <svg class="animate-spin w-6 h-6 text-primary-500" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            <span class="text-white">Đang xử lý...</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Reload preview khi content thay đổi
    Livewire.on('saved', () => {
        document.getElementById('preview-frame')?.contentWindow?.location?.reload();
    });
</script>
@endpush
