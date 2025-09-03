<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - ServiceHub</title>
    <style>
        :root {
            --primary: #15b625;
            --secondary: #f8f9fa;
            --dark: #343a40;
            --light: #ffffff;
            --success: #28a745;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        header {
            background-color: var(--light);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 5%;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }
        
        .nav-links {
            display: flex;
            gap: 2rem;
        }
        
        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: var(--primary);
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #eee;
            cursor: pointer;
        }
        
        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
        }
        
        .btn-outline:hover {
            background-color: var(--primary);
            color: var(--light);
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: var(--light);
        }
        
        .btn-primary:hover {
            background-color: #3a5bef;
        }
        
        .chat-container {
            display: flex;
            flex: 1;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
            height: calc(100vh - 80px);
        }
        
        .conversations-sidebar {
            width: 350px;
            background-color: var(--light);
            border-right: 1px solid #eee;
            overflow-y: auto;
        }
        
        .conversations-header {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .conversations-header h2 {
            font-size: 1.2rem;
        }
        
        .conversation-search {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }
        
        .conversation-search input {
            width: 100%;
            padding: 0.6rem 1rem;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        
        .conversation-list {
            list-style: none;
        }
        
        .conversation-item {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .conversation-item:hover {
            background-color: #f9f9f9;
        }
        
        .conversation-item.active {
            background-color: #f0f4ff;
        }
        
        .conversation-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        
        .conversation-user {
            font-weight: 600;
        }
        
        .conversation-time {
            font-size: 0.8rem;
            color: #666;
        }
        
        .conversation-preview {
            display: flex;
            align-items: center;
        }
        
        .conversation-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #eee;
            margin-right: 1rem;
        }
        
        .conversation-message {
            font-size: 0.9rem;
            color: #666;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex: 1;
        }
        
        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: var(--light);
        }
        
        .chat-header {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }
        
        .chat-user {
            display: flex;
            align-items: center;
            flex: 1;
        }
        
        .chat-user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #eee;
            margin-right: 1rem;
        }
        
        .chat-user-info h3 {
            margin-bottom: 0.2rem;
        }
        
        .chat-user-info p {
            color: #666;
            font-size: 0.9rem;
        }
        
        .chat-actions {
            display: flex;
            gap: 1rem;
        }
        
        .chat-messages {
            flex: 1;
            padding: 1rem;
            overflow-y: auto;
            background-color: #f9f9f9;
        }
        
        .message {
            margin-bottom: 1rem;
            max-width: 70%;
        }
        
        .message.received {
            margin-right: auto;
        }
        
        .message.sent {
            margin-left: auto;
            text-align: right;
        }
        
        .message-content {
            display: inline-block;
            padding: 0.8rem 1rem;
            border-radius: 18px;
            font-size: 0.95rem;
            line-height: 1.4;
        }
        
        .message.received .message-content {
            background-color: var(--light);
            border: 1px solid #eee;
            border-bottom-left-radius: 5px;
        }
        
        .message.sent .message-content {
            background-color: var(--primary);
            color: var(--light);
            border-bottom-right-radius: 5px;
        }
        
        .message-time {
            font-size: 0.7rem;
            color: #666;
            margin-top: 0.3rem;
        }
        
        .chat-input {
            padding: 1rem;
            border-top: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .chat-input textarea {
            flex: 1;
            padding: 0.8rem 1rem;
            border: 1px solid #ddd;
            border-radius: 20px;
            resize: none;
            font-size: 0.95rem;
            max-height: 120px;
        }
        
        .chat-input textarea:focus {
            outline: none;
            border-color: var(--primary);
        }
        
        .chat-input button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            color: var(--light);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .job-details {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            background-color: #f9f9f9;
        }
        
        .job-details h4 {
            margin-bottom: 0.5rem;
        }
        
        .job-details p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .job-price {
            font-weight: 600;
            color: var(--dark);
        }
        
        footer {
            background-color: var(--dark);
            color: var(--light);
            padding: 3rem 5%;
        }
        
        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }
        
        .footer-column h3 {
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
        }
        
        .footer-column ul {
            list-style: none;
        }
        
        .footer-column ul li {
            margin-bottom: 0.8rem;
        }
        
        .footer-column ul li a {
            color: #adb5bd;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-column ul li a:hover {
            color: var(--light);
        }
        
        .copyright {
            text-align: center;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid #495057;
            color: #adb5bd;
        }
        
        @media (max-width: 992px) {
            .conversations-sidebar {
                width: 300px;
            }
        }
        
        @media (max-width: 768px) {
            .conversations-sidebar {
                width: 100%;
                display: none;
            }
            
            .conversations-sidebar.active {
                display: block;
            }
            
            .chat-main {
                display: none;
            }
            
            .chat-main.active {
                display: flex;
            }
            
            .mobile-back {
                display: block;
                margin-right: 1rem;
                cursor: pointer;
            }
        }
        
        .mobile-back {
            display: none;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <a href="index.html" class="logo"><img src="servo.jpg" width="100px"></a>
            <div class="nav-links">
                <a href="#">Home</a>
                <a href="#">Services</a>
                <a href="#">Providers</a>
                <a href="#">Messages</a>
                <a href="#">My Jobs</a>
            </div>
            <diV>

            </diV>
        </nav>
    </header>
    
    <div class="chat-container">
        <div class="conversations-sidebar active">
            <div class="conversations-header">
                <h2>Messages</h2>
            </div>
            <div class="conversation-search">
                <input type="text" placeholder="Search conversations...">
            </div>
            <ul class="conversation-list">
                <li class="conversation-item active" data-chat="1">
                    <div class="conversation-header">
                        <div class="conversation-user">Sarah Johnson</div>
                        <div class="conversation-time">2h ago</div>
                    </div>
                    <div class="conversation-preview">
                        <div class="conversation-avatar"></div>
                        <div class="conversation-message">
                            Hi there! I've completed the initial design mockups for your website. Let me know what you think...
                        </div>
                    </div>
                </li>
                <li class="conversation-item" data-chat="2">
                    <div class="conversation-header">
                        <div class="conversation-user">Michael Chen</div>
                        <div class="conversation-time">1d ago</div>
                    </div>
                    <div class="conversation-preview">
                        <div class="conversation-avatar"></div>
                        <div class="conversation-message">
                            Thanks for accepting my bid! When would you like to schedule the home repair?
                        </div>
                    </div>
                </li>
                <li class="conversation-item" data-chat="3">
                    <div class="conversation-header">
                        <div class="conversation-user">David Wilson</div>
                        <div class="conversation-time">3d ago</div>
                    </div>
                    <div class="conversation-preview">
                        <div class="conversation-avatar"></div>
                        <div class="conversation-message">
                            I've sent you the first draft of the article. Please review and let me know if you'd like any changes.
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        
        <div class="chat-main active">
            <div class="chat-header">
                <span class="mobile-back">←</span>
                <div class="chat-user">
                    <div class="chat-user-avatar"></div>
                    <div class="chat-user-info">
                        <h3>Sarah Johnson</h3>
                        <p>Web Developer & Designer</p>
                    </div>
                </div>
                <div class="chat-actions">
                    <button class="btn btn-outline">View Job</button>
                    <button class="btn btn-primary">Complete Job</button>
                </div>
            </div>
            <!--
            <div class="job-details">
                <h4>Website Development for My Business</h4>
                <p>Status: In Progress</p>
                <p>Deadline: June 15, 2023</p>
                <p class="job-price">Price: $1,200 (50% paid upfront)</p>
            </div>
            -->
            <div class="chat-messages">
                <div class="message received">
                    <div class="message-content">
                        Hi there! I've completed the initial design mockups for your website. Let me know what you think about the overall layout and color scheme.
                    </div>
                    <div class="message-time">June 2, 10:15 AM</div>
                </div>
                
                <div class="message sent">
                    <div class="message-content">
                        Thanks Sarah! I just checked them out and they look great. I really like the color scheme you chose. Can we make the header section a bit taller though?
                    </div>
                    <div class="message-time">June 2, 11:30 AM</div>
                </div>
                
                <div class="message received">
                    <div class="message-content">
                        Absolutely! I'll adjust the header height and send you updated mockups by tomorrow. Also, do you have the content ready for the About Us page?
                    </div>
                    <div class="message-time">June 2, 12:45 PM</div>
                </div>
                
                <div class="message sent">
                    <div class="message-content">
                        Not yet, but I'll have it to you by the end of the week. In the meantime, let's focus on the homepage layout.
                    </div>
                    <div class="message-time">June 2, 1:20 PM</div>
                </div>
                
                <div class="message received">
                    <div class="message-content">
                        Sounds good. I've attached the updated mockup with the taller header. I also added some subtle animations to the call-to-action buttons. Let me know if you'd like to keep them or make them more/less prominent.
                    </div>
                    <div class="message-time">June 3, 9:15 AM</div>
                </div>
            </div>
            
            <div class="chat-input">
                <textarea placeholder="Type your message..."></textarea>
                <button>→</button>
            </div>
        </div>
    </div>
    
    <footer>
        <div class="footer-content">
            <div class="footer-column">
                <h3>ServiceHub</h3>
                <p>Connecting clients with skilled professionals worldwide through our secure platform.</p>
            </div>
            <div class="footer-column">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Providers</a></li>
                    <li><a href="#">How It Works</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Support</h3>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Safety Tips</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">FAQs</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Legal</h3>
                <ul>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Cookie Policy</a></li>
                    <li><a href="#">Dispute Resolution</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2023 ServiceHub. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Conversation item click
            const conversationItems = document.querySelectorAll('.conversation-item');
            conversationItems.forEach(item => {
                item.addEventListener('click', function() {
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
                mobileBack.addEventListener('click', function() {
                    document.querySelector('.conversations-sidebar').classList.add('active');
                    document.querySelector('.chat-main').classList.remove('active');
                });
            }
            
            // Send message
            const chatInput = document.querySelector('.chat-input textarea');
            const sendButton = document.querySelector('.chat-input button');
            
            sendButton.addEventListener('click', function() {
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
            chatInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendButton.click();
                }
            });
        });
    </script>
</body>
</html>