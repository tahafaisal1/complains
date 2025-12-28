/**
 * AI Chatbot Widget
 * نظام المساعد الذكي
 */

(function() {
    // Create chatbot HTML
    const chatbotHTML = `
        <div id="chatbot-container">
            <!-- Chat Toggle Button -->
            <button id="chatbot-toggle" class="chatbot-btn" title="المساعد الذكي">
                <i class="fas fa-robot"></i>
                <span class="chatbot-badge" style="display: none;">1</span>
            </button>
            
            <!-- Chat Window -->
            <div id="chatbot-window" class="chatbot-window" style="display: none;">
                <div class="chatbot-header">
                    <div class="chatbot-header-info">
                        <i class="fas fa-robot"></i>
                        <span>المساعد الذكي</span>
                    </div>
                    <button id="chatbot-close" class="chatbot-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div id="chatbot-messages" class="chatbot-messages">
                    <div class="chat-message bot">
                        <div class="message-avatar">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="message-content">
                            مرحباً! 👋 أنا المساعد الذكي للمنصة.<br>
                            كيف يمكنني مساعدتك اليوم؟
                        </div>
                    </div>
                </div>
                
                <div class="chatbot-input-area">
                    <form id="chatbot-form">
                        <input type="text" id="chatbot-input" placeholder="اكتب سؤالك هنا..." autocomplete="off">
                        <button type="submit" id="chatbot-send">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    `;

    // Create chatbot styles
    const chatbotStyles = `
        <style>
            #chatbot-container {
                position: fixed;
                bottom: 20px;
                left: 20px;
                z-index: 9999;
                font-family: 'Cairo', sans-serif;
            }
            
            .chatbot-btn {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: linear-gradient(135deg, #4f46e5, #7c3aed);
                border: none;
                color: white;
                font-size: 24px;
                cursor: pointer;
                box-shadow: 0 4px 20px rgba(79, 70, 229, 0.4);
                transition: all 0.3s ease;
                position: relative;
            }
            
            .chatbot-btn:hover {
                transform: scale(1.1);
                box-shadow: 0 6px 25px rgba(79, 70, 229, 0.5);
            }
            
            .chatbot-badge {
                position: absolute;
                top: -5px;
                right: -5px;
                background: #ef4444;
                color: white;
                font-size: 12px;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .chatbot-window {
                position: absolute;
                bottom: 70px;
                left: 0;
                width: 380px;
                height: 500px;
                background: white;
                border-radius: 16px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                display: flex;
                flex-direction: column;
                overflow: hidden;
                animation: slideUp 0.3s ease;
            }
            
            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .chatbot-header {
                background: linear-gradient(135deg, #4f46e5, #7c3aed);
                color: white;
                padding: 15px 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .chatbot-header-info {
                display: flex;
                align-items: center;
                gap: 10px;
                font-weight: 600;
            }
            
            .chatbot-header-info i {
                font-size: 20px;
            }
            
            .chatbot-close {
                background: rgba(255,255,255,0.2);
                border: none;
                color: white;
                width: 30px;
                height: 30px;
                border-radius: 50%;
                cursor: pointer;
                transition: all 0.3s;
            }
            
            .chatbot-close:hover {
                background: rgba(255,255,255,0.3);
            }
            
            .chatbot-messages {
                flex: 1;
                overflow-y: auto;
                padding: 20px;
                display: flex;
                flex-direction: column;
                gap: 15px;
                background: #f8fafc;
            }
            
            .chat-message {
                display: flex;
                gap: 10px;
                max-width: 85%;
            }
            
            .chat-message.user {
                align-self: flex-end;
                flex-direction: row-reverse;
            }
            
            .message-avatar {
                width: 35px;
                height: 35px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            
            .chat-message.bot .message-avatar {
                background: linear-gradient(135deg, #4f46e5, #7c3aed);
                color: white;
            }
            
            .chat-message.user .message-avatar {
                background: #10b981;
                color: white;
            }
            
            .message-content {
                background: white;
                padding: 12px 16px;
                border-radius: 16px;
                line-height: 1.5;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            }
            
            .chat-message.user .message-content {
                background: linear-gradient(135deg, #4f46e5, #7c3aed);
                color: white;
            }
            
            .chat-message.bot .message-content {
                border-top-right-radius: 4px;
            }
            
            .chat-message.user .message-content {
                border-top-left-radius: 4px;
            }
            
            .chatbot-input-area {
                padding: 15px;
                background: white;
                border-top: 1px solid #e5e7eb;
            }
            
            #chatbot-form {
                display: flex;
                gap: 10px;
            }
            
            #chatbot-input {
                flex: 1;
                padding: 12px 16px;
                border: 2px solid #e5e7eb;
                border-radius: 25px;
                font-size: 14px;
                font-family: 'Cairo', sans-serif;
                direction: rtl;
                transition: all 0.3s;
            }
            
            #chatbot-input:focus {
                outline: none;
                border-color: #4f46e5;
            }
            
            #chatbot-send {
                width: 45px;
                height: 45px;
                border-radius: 50%;
                background: linear-gradient(135deg, #4f46e5, #7c3aed);
                border: none;
                color: white;
                cursor: pointer;
                transition: all 0.3s;
            }
            
            #chatbot-send:hover {
                transform: scale(1.05);
            }
            
            #chatbot-send:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }
            
            .typing-indicator {
                display: flex;
                gap: 5px;
                padding: 12px 16px;
            }
            
            .typing-indicator span {
                width: 8px;
                height: 8px;
                background: #94a3b8;
                border-radius: 50%;
                animation: typing 1.4s infinite;
            }
            
            .typing-indicator span:nth-child(2) {
                animation-delay: 0.2s;
            }
            
            .typing-indicator span:nth-child(3) {
                animation-delay: 0.4s;
            }
            
            @keyframes typing {
                0%, 60%, 100% {
                    transform: translateY(0);
                }
                30% {
                    transform: translateY(-10px);
                }
            }
            
            @media (max-width: 480px) {
                .chatbot-window {
                    width: calc(100vw - 40px);
                    height: 70vh;
                    bottom: 70px;
                    left: 0;
                    right: 20px;
                }
            }
        </style>
    `;

    // Initialize chatbot when DOM is ready
    function initChatbot() {
        // Inject styles
        document.head.insertAdjacentHTML('beforeend', chatbotStyles);
        
        // Inject HTML
        document.body.insertAdjacentHTML('beforeend', chatbotHTML);
        
        // Get elements
        const toggleBtn = document.getElementById('chatbot-toggle');
        const chatWindow = document.getElementById('chatbot-window');
        const closeBtn = document.getElementById('chatbot-close');
        const form = document.getElementById('chatbot-form');
        const input = document.getElementById('chatbot-input');
        const messagesContainer = document.getElementById('chatbot-messages');
        
        // Toggle chat window
        toggleBtn.addEventListener('click', () => {
            const isVisible = chatWindow.style.display !== 'none';
            chatWindow.style.display = isVisible ? 'none' : 'flex';
            if (!isVisible) {
                input.focus();
            }
        });
        
        // Close chat window
        closeBtn.addEventListener('click', () => {
            chatWindow.style.display = 'none';
        });
        
        // Handle form submission
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const message = input.value.trim();
            if (!message) return;
            
            // Add user message
            addMessage(message, 'user');
            input.value = '';
            
            // Show typing indicator
            showTypingIndicator();
            
            try {
                // Send to server
                const response = await fetch('/chatbot/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'message=' + encodeURIComponent(message)
                });
                
                const data = await response.json();
                
                // Remove typing indicator
                removeTypingIndicator();
                
                if (data.success && data.response) {
                    addMessage(data.response, 'bot');
                } else {
                    addMessage('عذراً، حدث خطأ. يرجى المحاولة مرة أخرى.', 'bot');
                }
            } catch (error) {
                removeTypingIndicator();
                addMessage('عذراً، لا يمكن الاتصال بالخادم حالياً.', 'bot');
            }
        });
        
        // Add message to chat
        function addMessage(text, sender) {
            const messageHTML = `
                <div class="chat-message ${sender}">
                    <div class="message-avatar">
                        <i class="fas fa-${sender === 'bot' ? 'robot' : 'user'}"></i>
                    </div>
                    <div class="message-content">${formatMessage(text)}</div>
                </div>
            `;
            messagesContainer.insertAdjacentHTML('beforeend', messageHTML);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        
        // Format message (convert newlines to <br>)
        function formatMessage(text) {
            return text.replace(/\n/g, '<br>');
        }
        
        // Show typing indicator
        function showTypingIndicator() {
            const typingHTML = `
                <div class="chat-message bot" id="typing-indicator">
                    <div class="message-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="message-content typing-indicator">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            `;
            messagesContainer.insertAdjacentHTML('beforeend', typingHTML);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        
        // Remove typing indicator
        function removeTypingIndicator() {
            const indicator = document.getElementById('typing-indicator');
            if (indicator) {
                indicator.remove();
            }
        }
    }
    
    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initChatbot);
    } else {
        initChatbot();
    }
})();
