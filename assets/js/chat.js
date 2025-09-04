document.addEventListener('DOMContentLoaded', function () {
  // Conversation item click
  const conversationItems = document.querySelectorAll('.conversation-item');
  conversationItems.forEach(item => {
    item.addEventListener('click', function () {
      conversationItems.forEach(i => i.classList.remove('active'));
      this.classList.add('active');

      // In a real app, this would load the selected conversation
      const chatId = this.getAttribute('data-chat');
      console.log('Loading chat', chatId);

      // For mobile view
      document.querySelector('.conversations-sidebar').classList.remove('active');
      document.querySelector('.chat-main').classList.add('active');
    });
  });

  // Mobile back button
  const mobileBack = document.querySelector('.mobile-back');
  if (mobileBack) {
    mobileBack.addEventListener('click', function () {
      document.querySelector('.conversations-sidebar').classList.add('active');
      document.querySelector('.chat-main').classList.remove('active');
    });
  }

  // Send message
  const chatInput = document.querySelector('.chat-input textarea');
  const sendButton = document.querySelector('.chat-input button');

  sendButton.addEventListener('click', function () {
    if (chatInput.value.trim()) {
      const messagesContainer = document.querySelector('.chat-messages');

      const newMessage = document.createElement('div');
      newMessage.className = 'message sent';
      newMessage.innerHTML = `
                <div class="message-content">${chatInput.value}</div>
                <div class="message-time">Just now</div>
            `;

      messagesContainer.appendChild(newMessage);
      chatInput.value = '';
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
  });

  // Also send on Enter key (but allow Shift+Enter for new lines)
  chatInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      sendButton.click();
    }
  });
});