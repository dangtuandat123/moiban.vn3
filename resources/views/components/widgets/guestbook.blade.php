{{-- 
    Widget: Guestbook - Sổ lưu bút
    Nhận biến: $card (UserCard model)
--}}
@php
    $subscription = $card->subscription;
    $hasFeature = $subscription && $subscription->has_guestbook;
    $messages = $card->guestbookMessages()->approved()->latest()->take(20)->get();
@endphp

@if($hasFeature || $card->status === 'trial')
<section class="py-20 px-4" id="guestbook">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-center font-serif text-4xl md:text-5xl font-bold text-gray-800 mb-4">
            Sổ Lưu Bút
        </h2>
        <p class="text-center text-gray-600 mb-12">
            Gửi lời chúc phúc đến cô dâu chú rể nhé!
        </p>
        
        {{-- Form --}}
        <div class="bg-white rounded-3xl p-6 shadow-lg mb-8"
             x-data="{ 
                 loading: false, 
                 success: false,
                 form: { name: '', message: '' }
             }">
            
            <form @submit.prevent="submitWish()" class="space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <input type="text" 
                           x-model="form.name"
                           required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition"
                           placeholder="Tên của bạn *">
                    
                    <button type="submit"
                            :disabled="loading"
                            class="bg-primary text-white px-6 py-3 rounded-xl font-medium hover:opacity-90 transition disabled:opacity-50">
                        <span x-show="!loading">💝 Gửi lời chúc</span>
                        <span x-show="loading">Đang gửi...</span>
                    </button>
                </div>
                
                <textarea x-model="form.message"
                          required
                          rows="3"
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition resize-none"
                          placeholder="Viết lời chúc của bạn... *"></textarea>
                
                <div x-show="success" x-transition class="text-green-600 text-sm text-center">
                    ✅ Đã gửi lời chúc! Cảm ơn bạn rất nhiều!
                </div>
            </form>
        </div>
        
        {{-- Messages List --}}
        @if($messages->count() > 0)
        <div class="space-y-4" id="guestbook-messages">
            @foreach($messages as $msg)
            <div class="bg-white rounded-2xl p-5 shadow-md">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-nude-200 flex items-center justify-center text-lg shrink-0">
                        {{ mb_substr($msg->name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold text-gray-800">{{ $msg->name }}</span>
                            <span class="text-xs text-gray-400">{{ $msg->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-600 leading-relaxed">{{ $msg->message }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 text-gray-400">
            <div class="text-5xl mb-4">📝</div>
            <p>Chưa có lời chúc nào. Hãy là người đầu tiên!</p>
        </div>
        @endif
    </div>
</section>

<script>
function submitWish() {
    const el = document.querySelector('[x-data*="form"]');
    const data = Alpine.$data(el);
    data.loading = true;
    
    fetch('/api/guestbook/{{ $card->id }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data.form)
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            data.success = true;
            data.form = { name: '', message: '' };
            // Reload messages
            setTimeout(() => location.reload(), 1500);
        }
    })
    .finally(() => {
        data.loading = false;
    });
}
</script>
@endif
