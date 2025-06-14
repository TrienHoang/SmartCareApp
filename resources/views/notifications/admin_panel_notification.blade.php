@component('mail::message')
# Thông báo từ {{ config('app.name') }}

Chào {{ $user->full_name ?? 'bạn' }},

Bạn có một thông báo mới từ hệ thống:

**Tiêu đề:** {{ $notification['title'] }}

**Nội dung:**
{!! $notification['content'] !!} {{-- Sử dụng {!! !!} vì nội dung có thể có HTML từ WYSIWYG --}}

@component('mail::button', ['url' => url('/my-notifications')])
Xem tất cả thông báo
@endcomponent

Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!

Trân trọng,
{{ config('app.name') }}
@endcomponent