<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - ServiceHub</title>
    <link rel="stylesheet" href="assets/css/chat.css">
</head>
<body>
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
    <script src="assets/js/chat.js" defer></script>
</body>
</html>