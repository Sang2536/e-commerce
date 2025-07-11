<x-mail::message>
# Tin nhắn liên hệ mới

Bạn đã nhận được một tin nhắn liên hệ mới từ website:

<x-mail::panel>
**Người gửi:** {{ $contact->name }} ({{ $contact->email }})<br>
**Chủ đề:** {{ $contact->subject }}<br>
**Thời gian:** {{ $contact->created_at->format('d/m/Y H:i') }}
</x-mail::panel>

<x-mail::panel>
{{ $contact->message }}
</x-mail::panel>

<x-mail::button :url="route('admin.contacts.show', $contact->id)">
Xem chi tiết
</x-mail::button>

Trân trọng,<br>
{{ config('app.name') }}
</x-mail::message>
