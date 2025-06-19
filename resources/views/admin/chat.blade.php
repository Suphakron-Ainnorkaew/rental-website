@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if($rentalsWithMessages)
        <!-- รายการแชท -->
        <h1 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
            รายการแชท
            @if($rentalsWithMessages->sum('unread_messages') > 0)
                <span class="ml-3 bg-red-500 text-white text-sm font-semibold rounded-full px-2 py-1">
                    {{ $rentalsWithMessages->sum('unread_messages') }} ใหม่
                </span>
            @endif
        </h1>
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="p-4 bg-gradient-to-r from-purple-600 to-pink-600">
                <h2 class="text-xl font-semibold text-white">คำสั่งเช่าที่มีแชท</h2>
            </div>
            <div class="p-4">
                @if($rentalsWithMessages->isEmpty())
                    <p class="text-gray-500 text-center py-4">ยังไม่มีแชทจากผู้ใช้</p>
                @else
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b">
                                <th class="py-3 px-4">คำสั่งเช่า</th>
                                <th class="py-3 px-4">ผู้ใช้</th>
                                <th class="py-3 px-4">ชุด</th>
                                <th class="py-3 px-4">ข้อความล่าสุด</th>
                                <th class="py-3 px-4">การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rentalsWithMessages as $rentalItem)
                                <tr class="border-b hover:bg-gray-50 {{ $rentalItem->unread_messages > 0 ? 'bg-yellow-50' : '' }}">
                                    <td class="py-3 px-4">
                                        #{{ $rentalItem->id }}
                                        @if($rentalItem->unread_messages > 0)
                                            <span class="ml-2 bg-red-500 text-white text-xs rounded-full px-2 py-1">
                                                {{ $rentalItem->unread_messages }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">{{ $rentalItem->user->name }}</td>
                                    <td class="py-3 px-4">{{ $rentalItem->costume->name }}</td>
                                    <td class="py-3 px-4">
                                        {{ optional($rentalItem->messages->first())->message ?? 'ยังไม่มีข้อความ' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <a href="{{ route('admin.chat', $rentalItem->id) }}" class="text-pink-600 hover:underline">
                                            ดูแชท
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @elseif($rental)
        <!-- แชทเฉพาะ -->
        <h1 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
            <svg class="w-8 h-8 mr-3 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            แชทกับ {{ $rental->user->name }} <span class="text-pink-600">(คำสั่งเช่า #{{ $rental->id }})</span>
        </h1>

        <div class="bg-white shadow-lg rounded-xl overflow-hidden h-full flex flex-col">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    แชทกับผู้ใช้
                </h2>
            </div>

            <!-- เพิ่มส่วนหลักฐานการชำระเงิน -->
            @if($rental->payment_proof)
            <div class="p-4 bg-gradient-to-r from-pink-50 to-purple-50">
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-pink-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">หลักฐานการชำระเงิน</span>
                    </div>
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $rental->payment_proof) }}" 
                           target="_blank" 
                           class="relative inline-block group">
                            <img src="{{ asset('storage/' . $rental->payment_proof) }}" 
                                 alt="Payment Proof" 
                                 class="w-40 h-40 object-cover rounded-lg border-2 border-pink-100 shadow-sm transition transform group-hover:scale-105">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 flex items-center justify-center transition-all rounded-lg">
                                <span class="text-white opacity-0 group-hover:opacity-100 font-medium">ขยาย</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @if($rental->status == 'pending')
            <form id="confirm-order-form" action="{{ route('admin.rentals.confirm', $rental->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition duration-200">
                    ยืนยันคำสั่งซื้อ
                </button>
            </form>
            @elseif($rental->status == 'active')
                <span class="text-white font-medium">คำสั่งซื้อได้รับการยืนยันแล้ว</span>
            @endif
            
            <!-- Chat messages -->
            <div id="chat-box" class="flex-grow p-4 overflow-y-auto bg-gray-50 min-h-[400px]">
                @if(!isset($messages) || $messages->isEmpty())
                    <div class="flex justify-center py-10">
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="mt-4 text-gray-500">ยังไม่มีข้อความในการสนทนา</p>
                            <p class="text-gray-400 text-sm">ส่งข้อความแรกเพื่อเริ่มการสนทนา</p>
                        </div>
                    </div>
                @else
                    <div id="message-container">
                        @foreach ($messages as $message)
                            <div class="flex mb-4 items-start {{ $message->is_admin ? 'justify-end' : 'justify-start' }}" data-message-id="{{ $message->id }}">
                                @if (!$message->is_admin)
                                    <div class="flex-shrink-0 mr-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-700 font-bold">
                                            {{ substr($message->user->name, 0, 1) }}
                                        </div>
                                    </div>
                                @endif
                                <div class="{{ $message->is_admin ? 'bg-purple-600 text-white rounded-tr-none' : 'bg-pink-600 text-white rounded-tl-none' }} rounded-lg py-2 px-4 max-w-xs lg:max-w-md shadow-sm">
                                    <p class="text-sm mb-1 {{ $message->is_admin ? 'text-purple-100' : 'text-pink-100' }}">
                                        {{ $message->is_admin ? 'แอดมิน' : $message->user->name }}
                                    </p>
                                    <p>{{ $message->message }}</p>
                                    <p class="text-xs mt-1 text-right {{ $message->is_admin ? 'text-purple-200' : 'text-pink-200' }}">
                                        {{ \Carbon\Carbon::parse($message->created_at)->format('H:i') }}
                                    </p>
                                </div>
                                @if ($message->is_admin)
                                    <div class="flex-shrink-0 ml-3">
                                        <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center text-white font-bold">
                                            A
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Message input -->
            <div class="p-4 bg-white border-t border-gray-200">
                <form id="chat-form" class="flex items-end space-x-2">
                    @csrf
                    <input type="hidden" name="rental_id" value="{{ $rental->id }}">
                    <div class="flex-grow relative">
                        <textarea 
                            name="message" 
                            id="message" 
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500" 
                            placeholder="พิมพ์ข้อความถึงผู้ใช้..." 
                            rows="3"
                            required
                        ></textarea>
                    </div>
                    <button 
                        type="submit" 
                        class="h-12 bg-gradient-to-r from-purple-600 to-pink-600 text-white py-2 px-6 rounded-lg hover:from-purple-700 hover:to-pink-700 transition duration-200 font-medium flex items-center"
                    >
                        <span class="mr-2">ส่ง</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>

@push('scripts')
@if($rental)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatBox = document.getElementById('chat-box');
    const messageContainer = document.getElementById('message-container') || chatBox;
    chatBox.scrollTop = chatBox.scrollHeight;

    const displayedMessageIds = new Set(
        Array.from(messageContainer.querySelectorAll('[data-message-id]')).map(m => m.dataset.messageId)
    );
    let lastMessageId = displayedMessageIds.size > 0 ? Math.max(...displayedMessageIds) : 0;

    function addMessage(message, isAdmin, messageId, createdAt) {
        if (displayedMessageIds.has(String(messageId))) return;

        const timeString = new Date(createdAt).toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit' });
        const messageElement = document.createElement('div');
        messageElement.className = `flex mb-4 items-start ${isAdmin ? 'justify-end' : 'justify-start'}`;
        messageElement.dataset.messageId = messageId;

        const messageContent = `
            ${!isAdmin ? `
                <div class="flex-shrink-0 mr-3">
                    <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-700 font-bold">
                        ${message.user ? message.user.name.charAt(0) : '{{ substr($rental->user->name, 0, 1) }}'}
                    </div>
                </div>
            ` : ''}
            <div class="${isAdmin ? 'bg-purple-600 text-white rounded-tr-none' : 'bg-pink-600 text-white rounded-tl-none'} rounded-lg py-2 px-4 max-w-xs lg:max-w-md shadow-sm">
                <p class="text-sm mb-1 ${isAdmin ? 'text-purple-100' : 'text-pink-100'}">
                    ${isAdmin ? 'แอดมิน' : (message.user ? message.user.name : '{{ $rental->user->name }}')}
                </p>
                <p>${message.message || message}</p>
                <p class="text-xs mt-1 text-right ${isAdmin ? 'text-purple-200' : 'text-pink-200'}">${timeString}</p>
            </div>
            ${isAdmin ? `
                <div class="flex-shrink-0 ml-3">
                    <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center text-white font-bold">
                        A
                    </div>
                </div>
            ` : ''}
        `;
        
        messageElement.innerHTML = messageContent;
        messageContainer.appendChild(messageElement);
        displayedMessageIds.add(String(messageId));
        lastMessageId = Math.max(lastMessageId, messageId);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    setInterval(function() {
        fetch('{{ route('chat.messages', $rental->id) }}')
            .then(response => response.json())
            .then(data => {
                const messages = data.messages;
                const emptyMessageDiv = chatBox.querySelector('div.flex.justify-center.py-10');
                if (emptyMessageDiv && messages.length > 0) emptyMessageDiv.remove();

                messages.forEach(message => {
                    if (!displayedMessageIds.has(String(message.id))) {
                        addMessage(message, message.is_admin, message.id, message.created_at);
                    }
                });
            })
            .catch(error => console.error('Error fetching messages:', error));
    }, 5000);

    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const messageInput = document.getElementById('message');
        const message = messageInput.value.trim();
        const rentalId = document.querySelector('input[name="rental_id"]').value;
        
        if (!message) return;

        fetch('/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                rental_id: rentalId,
                message: message,
                is_admin: true,
            }),
        })
        .then(response => response.json())
        .then(data => {
            const messageId = data.message_id || (data.message && data.message.id);
            if (data.success && messageId) {
                addMessage({ message: message }, true, messageId, new Date().toISOString());
                messageInput.value = '';
                const emptyMessageDiv = chatBox.querySelector('div.flex.justify-center.py-10');
                if (emptyMessageDiv) emptyMessageDiv.remove();
            } else {
                alert('เกิดข้อผิดพลาดในการส่งข้อความ');
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
            alert('เกิดข้อผิดพลาดในการส่งข้อความ');
        });
    });

    const confirmForm = document.getElementById('confirm-order-form');
    if (confirmForm) {
        confirmForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!confirm('ยืนยันคำสั่งซื้อนี้หรือไม่?')) return;

            const form = this;
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: new FormData(form)
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        console.log('Non-JSON response:', text);
                        throw new Error('Server responded with status: ' + response.status);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    form.replaceWith('<span class="text-white font-medium">คำสั่งซื้อได้รับการยืนยันแล้ว</span>');
                    const confirmationMessage = `ยืนยันคำสั่งซื้อ ทางเราจะทำการจัดส่งให้ โปรดอ่านข้อตกลง\n1. หากชุดมีความเสียหายจะค่าปรับเพิ่ม\n2. หากไม่ยอมจัดส่งคืนเมื่อเกินสัญญาครบ 3 วัน จะไม่ได้ค่ามัดจำคืนและถูกดำเนินคดี\n3. เมื่อจัดส่งแล้วโปรดแจ้งหลักฐานการจัดส่ง เมื่อสินค้ามาถึงทางเราจะคืนค่ามัดจำและค่าจัดส่งให้ท่าน โดยส่งคืน บริษัทDapleP จำกัด จังหวัดเชียงใหม่ อำเภอเมือง ตึกเลขที่ 89/9\nขอบคุณที่ใช้บริการ`;
                    fetch('/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            rental_id: '{{ $rental->id }}',
                            message: confirmationMessage,
                            is_admin: true,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        const messageId = data.message_id || (data.message && data.message.id);
                        if (data.success && messageId) {
                            addMessage({ message: confirmationMessage }, true, messageId, new Date().toISOString());
                        }
                    })
                    .catch(error => console.error('Error sending confirmation message:', error));
                } else {
                    alert('เกิดข้อผิดพลาด: ' + (data.error || 'ไม่สามารถยืนยันคำสั่งซื้อได้'));
                }
            })
            .catch(error => {
                console.error('Error confirming order:', error);
                alert('เกิดข้อผิดพลาดในการยืนยันคำสั่งซื้อ: ' + error.message);
            });
        });
    }
});
</script>
@endif
@endpush
@endsection