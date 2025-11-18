@extends('admin.layouts.app')

@section('title', 'Quản lý Chat')
@section('page-title', 'Chat Hỗ Trợ Khách Hàng')

@push('styles')
<style>
.chat-container {
    display: flex;
    height: calc(100vh - 200px);
    gap: 20px;
}

.chat-list {
    width: 350px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
}

.chat-list-header {
    padding: 20px;
    border-bottom: 2px solid #f1f1f1;
}

.chat-list-body {
    flex: 1;
    overflow-y: auto;
}

.chat-item {
    padding: 15px 20px;
    border-bottom: 1px solid #f1f1f1;
    cursor: pointer;
    transition: background 0.3s;
}

.chat-item:hover {
    background: #f8f9fa;
}

.chat-item.active {
    background: #e7f3ff;
    border-left: 4px solid #667eea;
}

.chat-item .unread-badge {
    background: #dc3545;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}

.chat-window {
    flex: 1;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
}

.chat-header {
    padding: 20px;
    border-bottom: 2px solid #f1f1f1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #f8f9fa;
    max-height: calc(100vh - 400px); 
    min-height: 400px;
}

.message {
    margin-bottom: 15px;
    display: flex;
}

.message.user {
    justify-content: flex-start;
}

.message.admin {
    justify-content: flex-end;
}

.message-bubble {
    max-width: 70%;
    padding: 12px 16px;
    border-radius: 18px;
    word-wrap: break-word;
}

.message.user .message-bubble {
    background: white;
    border: 1px solid #e0e0e0;
}

.message.admin .message-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.message-time {
    font-size: 11px;
    color: #999;
    margin-top: 5px;
}

.chat-input {
    padding: 20px;
    border-top: 2px solid #f1f1f1;
}

.chat-input-group {
    display: flex;
    gap: 10px;
}

.chat-input-group input {
    flex: 1;
    padding: 12px 20px;
    border: 1px solid #e0e0e0;
    border-radius: 25px;
}

.chat-input-group button {
    padding: 12px 30px;
    border-radius: 25px;
}

.no-chat-selected {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #999;
    font-size: 18px;
}

@media (max-width: 768px) {
    .chat-container {
        flex-direction: column;
        height: auto;
    }
    
    .chat-list {
        width: 100%;
        max-height: 300px;
    }  
}

</style>
@endpush

@section('content')
<div class="chat-container">
    <!-- Chat List -->
    <div class="chat-list">
        <div class="chat-list-header">
            <h5 class="mb-0"><i class="fas fa-comments"></i> Danh sách Chat</h5>
            <small class="text-muted">{{ $chats->total() }} cuộc trò chuyện</small>
        </div>
        <div class="chat-list-body">
            @forelse($chats as $chat)
            <div class="chat-item" data-chat-id="{{ $chat->id }}" onclick="loadChat({{ $chat->id }})">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong>{{ $chat->name }}</strong>
                        <br>
                        <small class="text-muted">{{ $chat->email }}</small>
                        @if($chat->lastMessage)
                        <br>
                        <small class="text-muted">
                            {{ Str::limit($chat->lastMessage->message, 30) }}
                        </small>
                        @endif
                    </div>
                    <div class="text-end">
                        @if($chat->unread_count > 0)
                        <div class="unread-badge">{{ $chat->unread_count }}</div>
                        @endif
                        @if($chat->last_message_at)
                        <br>
                        <small class="text-muted">{{ $chat->last_message_at->diffForHumans() }}</small>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="p-4 text-center text-muted">
                <i class="fas fa-comments fa-3x mb-3"></i>
                <p>Chưa có cuộc trò chuyện nào</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Chat Window -->
    <div class="chat-window">
        <div id="noChatSelected" class="no-chat-selected">
            <div class="text-center">
                <i class="fas fa-comments fa-4x mb-3"></i>
                <p>Chọn một cuộc trò chuyện để bắt đầu</p>
            </div>
        </div>

        <div id="chatContent" style="display: none; flex: 1; display: flex; flex-direction: column;">
            <div class="chat-header">
                <div>
                    <h5 class="mb-0" id="chatUserName">-</h5>
                    <small class="text-muted" id="chatUserEmail">-</small>
                </div>
                <div>
                    <button class="btn btn-sm btn-danger" onclick="closeChat()" title="Đóng chat">
                        <i class="fas fa-times"></i> Đóng
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteChat()" title="Xóa cuộc trò chuyện">
                        <i class="fas fa-trash"></i> Xóa
                    </button>
                </div>
            </div>

            <div class="chat-messages" id="chatMessages">
                <!-- Messages will be loaded here -->
            </div>

            <div class="chat-input">
                <form id="messageForm" onsubmit="sendAdminMessage(event)">
                    <div class="chat-input-group">
                        <input type="text" id="messageInput" placeholder="Nhập tin nhắn..." required>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Gửi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentChatId = null;
let messageLoadInterval = null;

function loadChat(chatId) {
    currentChatId = chatId;
    
    // Update UI
    document.querySelectorAll('.chat-item').forEach(item => {
        item.classList.remove('active');
    });
    document.querySelector(`.chat-item[data-chat-id="${chatId}"]`).classList.add('active');
    
    // Show chat window
    document.getElementById('noChatSelected').style.display = 'none';
    document.getElementById('chatContent').style.display = 'flex';
    
    // Load messages
    fetch(`/admin/chat/${chatId}/messages`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update header
                document.getElementById('chatUserName').textContent = data.chat.name;
                document.getElementById('chatUserEmail').textContent = data.chat.email;
                
                // Display messages
                displayMessages(data.messages);
                
                // Clear unread badge
                const badge = document.querySelector(`.chat-item[data-chat-id="${chatId}"] .unread-badge`);
                if (badge) {
                    badge.remove();
                }
            }
        })
        .catch(error => console.error('Error:', error));
    
    // Start auto-refresh messages
    if (messageLoadInterval) {
        clearInterval(messageLoadInterval);
    }
    messageLoadInterval = setInterval(() => {
        loadMessages(chatId);
    }, 3000);
}

function loadMessages(chatId) {
    fetch(`/admin/chat/${chatId}/messages`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayMessages(data.messages);
            }
        })
        .catch(error => console.error('Error:', error));
}

function displayMessages(messages) {
    const container = document.getElementById('chatMessages');
    container.innerHTML = '';
    
    messages.forEach(msg => {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${msg.sender}`;
        messageDiv.innerHTML = `
            <div class="message-bubble">
                <div>${msg.text}</div>
                <div class="message-time">${msg.time}</div>
            </div>
        `;
        container.appendChild(messageDiv);
    });
        if (shouldScroll || messages.length > 0) {
        container.scrollTop = container.scrollHeight;
    }
    
    // Scroll to bottom
    container.scrollTop = container.scrollHeight;
}

function sendAdminMessage(event) {
    event.preventDefault();
    
    if (!currentChatId) return;
    
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    fetch(`/admin/chat/${currentChatId}/send`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            loadMessages(currentChatId);
        }
    })
    .catch(error => console.error('Error:', error));
}

function closeChat() {
    if (!currentChatId || !confirm('Bạn có chắc muốn đóng cuộc trò chuyện này?')) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    fetch(`/admin/chat/${currentChatId}/close`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Đã đóng cuộc trò chuyện');
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
function deleteChat() {
    if (!currentChatId) return;
    
    if (!confirm('Bạn có chắc muốn XÓA VĨNH VIỄN cuộc trò chuyện này?\n\nHành động này không thể hoàn tác!')) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    fetch(`/admin/chat/${currentChatId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Đã xóa cuộc trò chuyện thành công!');
            location.reload();
        } else {
            alert('Có lỗi xảy ra: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi xóa!');
    });
}

// Cleanup interval on page unload
window.addEventListener('beforeunload', function() {
    if (messageLoadInterval) {
        clearInterval(messageLoadInterval);
    }
});
</script>
@endpush