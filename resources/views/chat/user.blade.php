@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <span class="flex items-center">
            <svg class="w-8 h-8 mr-3 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            แชทกับแอดมิน <span class="text-pink-600">(คำสั่งเช่า #{{ $rental->id }})</span>
        </span>
    </h1>

    <div class="bg-white shadow-lg rounded-xl overflow-hidden h-full flex flex-col">
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                แชทกับแอดมิน
            </h2>
        </div>
        
        <!-- Chat messages -->
        <div id="chat-box" class="flex-grow p-4 overflow-y-auto bg-gray-50 min-h-[400px]">
            @if(count($messages) == 0)
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
                @foreach ($messages as $message)
                    <div class="flex mb-4 items-start {{ $message->is_admin ? 'justify-start' : 'justify-end' }}">
                        @if ($message->is_admin)
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center text-white font-bold">
                                A
                            </div>
                        </div>
                        @endif
                        
                        <div class="{{ $message->is_admin ? 'bg-white rounded-tl-none' : 'bg-pink-600 text-white rounded-tr-none' }} rounded-lg py-2 px-4 max-w-xs lg:max-w-md shadow-sm">
                            <p class="text-sm mb-1 {{ $message->is_admin ? 'text-purple-600' : 'text-pink-100' }}">
                                {{ $message->is_admin ? 'แอดมิน' : $message->user->name }}
                            </p>
                            <p>{{ $message->message }}</p>
                            <p class="text-xs mt-1 text-right {{ $message->is_admin ? 'text-gray-400' : 'text-pink-200' }}">
                                {{ \Carbon\Carbon::parse($message->created_at)->format('H:i') }}
                            </p>
                        </div>
                        
                        @if (!$message->is_admin)
                        <div class="flex-shrink-0 ml-3">
                            <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-700 font-bold">
                                {{ substr($message->user->name, 0, 1) }}
                            </div>
                        </div>
                        @endif
                    </div>
                @endforeach
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
                        placeholder="พิมพ์ข้อความถึงแอดมิน..." 
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
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;
    
    function addUserMessage(message) {
        const now = new Date();
        const timeString = now.getHours().toString().padStart(2, '0') + ':' + 
                         now.getMinutes().toString().padStart(2, '0');
        
        const messageElement = document.createElement('div');
        messageElement.className = 'flex mb-4 items-start justify-end';
        
        const messageContent = `
            <div class="bg-pink-600 text-white rounded-lg rounded-tr-none py-2 px-4 max-w-xs lg:max-w-md shadow-sm">
                <p class="text-sm mb-1 text-pink-100">{{ auth()->user()->name }}</p>
                <p>${message}</p>
                <p class="text-xs mt-1 text-right text-pink-200">${timeString}</p>
            </div>
            <div class="flex-shrink-0 ml-3">
                <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-700 font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        `;
        
        messageElement.innerHTML = messageContent;
        chatBox.appendChild(messageElement);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

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
                is_admin: false, // ผู้ใช้ทั่วไป
            }),
        })
        .then(response => {
            if (!response.ok) throw new Error('Server error: ' + response.status);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                addUserMessage(message);
                messageInput.value = '';
                const emptyMessageDiv = chatBox.querySelector('div.flex.justify-center.py-10');
                if (emptyMessageDiv) emptyMessageDiv.remove();
            } else {
                alert('เกิดข้อผิดพลาด: ' + (data.error || 'ไม่ทราบสาเหตุ'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('เกิดข้อผิดพลาดในการส่งข้อความ: ' + error.message);
        });
    });
});
</script>
@endpush
@endsection