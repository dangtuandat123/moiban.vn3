{{-- 
    Widget: RSVP Form
    Nhận biến: $card (UserCard model)
--}}
@php
    $subscription = $card->subscription;
    $hasFeature = $subscription && $subscription->has_rsvp;
@endphp

@if($hasFeature || $card->status === 'trial')
<section class="py-20 px-4 bg-white" id="rsvp">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-center font-serif text-4xl md:text-5xl font-bold text-gray-800 mb-4">
            Xác Nhận Tham Dự
        </h2>
        <p class="text-center text-gray-600 mb-12">
            Vui lòng xác nhận để chúng mình chuẩn bị chu đáo hơn nhé!
        </p>
        
        <div class="bg-nude-50 rounded-3xl p-8 shadow-lg" 
             x-data="{ 
                 loading: false, 
                 success: false, 
                 error: '',
                 form: {
                     name: '',
                     phone: '',
                     attendance: 'yes',
                     guest_count: 1,
                     message: ''
                 }
             }">
            
            {{-- Success Message --}}
            <div x-show="success" x-transition class="text-center py-8">
                <div class="text-6xl mb-4">🎉</div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Cảm ơn bạn!</h3>
                <p class="text-gray-600">Chúng mình đã nhận được xác nhận của bạn.</p>
            </div>
            
            {{-- Form --}}
            <form x-show="!success" @submit.prevent="submitRsvp()" class="space-y-6">
                {{-- Error --}}
                <div x-show="error" x-text="error" class="text-red-500 text-sm text-center"></div>
                
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Họ và tên *</label>
                    <input type="text" 
                           x-model="form.name"
                           required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition"
                           placeholder="Nhập họ tên của bạn">
                </div>
                
                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                    <input type="tel" 
                           x-model="form.phone"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition"
                           placeholder="Số điện thoại liên hệ">
                </div>
                
                {{-- Attendance --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bạn sẽ tham dự chứ? *</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" x-model="form.attendance" value="yes" class="hidden peer">
                            <div class="peer-checked:bg-green-100 peer-checked:border-green-500 peer-checked:text-green-700 border-2 rounded-xl p-4 text-center transition hover:bg-gray-50">
                                <div class="text-2xl mb-1">✅</div>
                                <div class="text-sm font-medium">Sẽ đến</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" x-model="form.attendance" value="no" class="hidden peer">
                            <div class="peer-checked:bg-red-100 peer-checked:border-red-500 peer-checked:text-red-700 border-2 rounded-xl p-4 text-center transition hover:bg-gray-50">
                                <div class="text-2xl mb-1">❌</div>
                                <div class="text-sm font-medium">Không đến</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" x-model="form.attendance" value="maybe" class="hidden peer">
                            <div class="peer-checked:bg-yellow-100 peer-checked:border-yellow-500 peer-checked:text-yellow-700 border-2 rounded-xl p-4 text-center transition hover:bg-gray-50">
                                <div class="text-2xl mb-1">🤔</div>
                                <div class="text-sm font-medium">Chưa chắc</div>
                            </div>
                        </label>
                    </div>
                </div>
                
                {{-- Guest Count --}}
                <div x-show="form.attendance === 'yes'">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Số người tham dự</label>
                    <select x-model="form.guest_count"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition">
                        <option value="1">1 người</option>
                        <option value="2">2 người</option>
                        <option value="3">3 người</option>
                        <option value="4">4 người</option>
                        <option value="5">5 người</option>
                    </select>
                </div>
                
                {{-- Message --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lời nhắn</label>
                    <textarea x-model="form.message"
                              rows="3"
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition resize-none"
                              placeholder="Gửi lời chúc đến cặp đôi..."></textarea>
                </div>
                
                {{-- Submit --}}
                <button type="submit"
                        :disabled="loading"
                        class="w-full bg-primary text-white py-4 rounded-xl font-semibold hover:opacity-90 transition disabled:opacity-50">
                    <span x-show="!loading">Xác nhận</span>
                    <span x-show="loading" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Đang gửi...
                    </span>
                </button>
            </form>
        </div>
    </div>
</section>

<script>
function submitRsvp() {
    const data = Alpine.$data(document.querySelector('[x-data]'));
    data.loading = true;
    data.error = '';
    
    fetch('/api/rsvp/{{ $card->id }}', {
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
        } else {
            data.error = result.message || 'Có lỗi xảy ra, vui lòng thử lại!';
        }
    })
    .catch(() => {
        data.error = 'Có lỗi xảy ra, vui lòng thử lại!';
    })
    .finally(() => {
        data.loading = false;
    });
}
</script>
@endif
