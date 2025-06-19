@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">แชทกับแอดมิน</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <!-- Rental details section -->
        <div class="p-6 bg-gradient-to-r from-pink-50 to-purple-50">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                รายละเอียดการเช่า
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-pink-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h6v4H7V5zm8 8v2h1v1H4v-1h1v-2a1 1 0 011-1h8a1 1 0 011 1z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">รายละเอียดชุด</span>
                    </div>
                    <p class="mb-2"><span class="font-medium text-gray-700">ชุด:</span> <span class="text-gray-900">{{ $costume->name }}</span></p>
                    <p><span class="font-medium text-gray-700">ราคาต่อวัน:</span> <span class="text-pink-600 font-semibold">฿{{ number_format($costume->price) }}</span></p>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-pink-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">ระยะเวลาเช่า</span>
                    </div>
                    <p class="mb-2"><span class="font-medium text-gray-700">วันที่เริ่มเช่า:</span> <span class="text-gray-900">{{ $start_date }}</span></p>
                    <p class="mb-2"><span class="font-medium text-gray-700">วันที่สิ้นสุด:</span> <span class="text-gray-900">{{ $end_date }}</span></p>
                    <p class="mb-2">
                        <span class="font-medium text-gray-700">จำนวนวัน:</span> 
                        <span class="text-gray-900">
                            @php
                                $start = new DateTime($start_date);
                                $end = new DateTime($end_date);
                                $diff = $end->diff($start)->days + 1;
                                echo $diff . ' วัน';
                            @endphp
                        </span>
                    </p>
                    <p><span class="font-medium text-gray-700">ราคารวม:</span> <span class="text-pink-600 font-semibold">฿{{ number_format($diff * $costume->price) }}</span></p>
                </div>
            </div>
            
            <div class="mt-6 bg-white p-4 rounded-lg shadow-sm">
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

        <!-- Chat section -->
        <div class="p-6 border-t border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                แชทกับแอดมิน
            </h2>
            
            <div id="chat-box" class="bg-gray-50 p-4 rounded-lg h-96 overflow-y-auto mb-4 border border-gray-200 shadow-inner">
                @if($messages->isEmpty())
                    <div class="flex justify-center mb-4">
                        <span class="px-3 py-1 text-xs text-gray-500 bg-gray-100 rounded-full">เริ่มแชทกับแอดมิน</span>
                    </div>
                @else
                    <div id="message-container">
                        @foreach ($messages as $message)
                            <div class="flex mb-4 items-start {{ $message->is_admin ? 'justify-start' : 'justify-end' }}" data-message-id="{{ $message->id }}">
                                @if ($message->is_admin)
                                    <div class="flex-shrink-0 mr-3">
                                        <div class="w-8 h-8 rounded-full bg-pink-600 flex items-center justify-center text-white font-bold">
                                            A
                                        </div>
                                    </div>
                                @endif
                                <div class="{{ $message->is_admin ? 'bg-white rounded-tl-none' : 'bg-pink-600 text-white rounded-tr-none' }} rounded-lg py-2 px-4 max-w-xs lg:max-w-md shadow-sm">
                                    <p class="text-sm mb-1 {{ $message->is_admin ? 'text-gray-600' : 'text-pink-100' }}">
                                        {{ $message->is_admin ? 'แอดมิน' : 'คุณ' }}
                                    </p>
                                    <p class="{{ $message->is_admin ? 'text-gray-800' : 'text-white' }}">{{ $message->message }}</p>
                                    <p class="text-xs mt-1 text-right {{ $message->is_admin ? 'text-gray-400' : 'text-pink-200' }}">
                                        {{ \Carbon\Carbon::parse($message->created_at)->format('H:i') }}
                                    </p>
                                </div>
                                @if (!$message->is_admin)
                                    <div class="flex-shrink-0 ml-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-700 font-bold">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <form id="chat-form" class="space-y-4">
                @csrf
                <input type="hidden" name="rental_id" value="{{ $rental->id }}">
                <div class="relative">
                    <textarea 
                        name="message" 
                        id="message" 
                        class="w-full border border-gray-300 rounded-lg p-4 pr-24 focus:ring-2 focus:ring-pink-500 focus:border-pink-500" 
                        placeholder="พิมพ์ข้อความถึงแอดมิน..." 
                        rows="3"
                        required
                    ></textarea>
                    <button 
                        type="submit" 
                        class="absolute bottom-3 right-3 bg-pink-600 text-white py-2 px-4 rounded-lg hover:bg-pink-700 transition duration-200 font-medium flex items-center space-x-1"
                    >
                        <span>ส่ง</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
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
        if (displayedMessageIds.has(String(messageId))) {
            console.log(`Message ID ${messageId} already exists, skipping...`);
            return;
        }

        const timeString = new Date(createdAt).toLocaleTimeString('th-TH', { 
            hour: '2-digit', 
            minute: '2-digit', 
            timeZone: 'Asia/Bangkok'
        });

        const messageElement = document.createElement('div');
        messageElement.className = `flex mb-4 items-start ${isAdmin ? 'justify-start' : 'justify-end'}`;
        messageElement.dataset.messageId = messageId;

        const messageContent = `
            ${isAdmin ? `
                <div class="flex-shrink-0 mr-3">
                    <div class="w-8 h-8 rounded-full bg-pink-600 flex items-center justify-center text-white font-bold">
                        A
                    </div>
                </div>
            ` : ''}
            <div class="${isAdmin ? 'bg-white rounded-tl-none' : 'bg-pink-600 text-white rounded-tr-none'} rounded-lg py-2 px-4 max-w-xs lg:max-w-md shadow-sm">
                <p class="text-sm mb-1 ${isAdmin ? 'text-gray-600' : 'text-pink-100'}">
                    ${isAdmin ? 'แอดมิน' : 'คุณ'}
                </p>
                <p class="${isAdmin ? 'text-gray-800' : 'text-white'}">${message.message || message}</p>
                <p class="text-xs mt-1 text-right ${isAdmin ? 'text-gray-400' : 'text-pink-200'}">${timeString}</p>
            </div>
            ${!isAdmin ? `
                <div class="flex-shrink-0 ml-3">
                    <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-700 font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
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
        fetch('{{ route('chat.messages', $rental->id) }}', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.messages || !Array.isArray(data.messages)) return;
            const messages = data.messages;
            const emptyMessageDiv = chatBox.querySelector('.flex.justify-center.mb-4');
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

        fetch('{{ route('chat.send') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                rental_id: rentalId,
                message: message,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.message) {
                addMessage(data.message, false, data.message.id, data.message.created_at);
                messageInput.value = '';
                const emptyMessageDiv = chatBox.querySelector('.flex.justify-center.mb-4');
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
});
</script>
@endpush
@endsection