<?php
session_start();
if (!isset($_SESSION['userID']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 0) {
    // Redirect to login page if not an admin
    header("Location: ../../logins/logout.php"); // Change the path as needed
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sugar Regulatory Administration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="traderdashboard.css">

</head>
<body>
    <header>
    <div class="logo-container">
        <img src="../../resources/SRA_thumbnail.png" alt="SRA Logo" class="logo">
        <img src="../../resources/REPUBLICOFPH.png" alt="SRA Logo" class="logo secondLogo">
    </div>
    <nav>
        <a href="#" class="active">HOME</a>
        <a href="reportdraft.php">DRAFTS</a>
        <div class="user-icon-container">
            <i class="fas fa-user-circle"></i>
        </div>
    </nav>
    </header>

    <div class="password-modal" id="passwordModal">
        <div class="password-modal-content">
            <div class="password-modal-header">
                <h3>Change Password</h3>
                <span class="password-modal-close">&times;</span>
            </div>
            <div class="password-modal-body">
                <form id="changePasswordForm">
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <div class="password-input-container">
                            <input type="password" id="currentPassword" name="currentPassword" required>
                            <span class="password-toggle"><i class="fas fa-eye"></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <div class="password-input-container">
                            <input type="password" id="newPassword" name="newPassword" required>
                            <span class="password-toggle"><i class="fas fa-eye"></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <div class="password-input-container">
                            <input type="password" id="confirmPassword" name="confirmPassword" required>
                            <span class="password-toggle"><i class="fas fa-eye"></i></span>
                        </div>
                    </div>
                    <button type="submit" class="update-password-btn">Update Password</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Side Panel -->
    <div class="side-panel">
        <div class="user-info">
            <div class="user-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="user-name"><?php echo htmlspecialchars($_SESSION['name']); ?></div>
            <div class="user-email"><?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'username@gmail.com'; ?></div>
        </div>
        <div class="side-panel-menu">
            <div class="menu-item" id="change-password-btn">
                <i class="fas fa-lock"></i>
                <span>Change password</span>
            </div>
            <div class="menu-item" id="log-out-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Log out</span>
            </div>
        </div>
    </div>
    
    <!-- Overlay for side panel -->
    <div class="side-panel-overlay"></div>
    
    <main>
    <div class="trader-info">
    Logged in as: <span><?php echo htmlspecialchars($_SESSION['name']); ?></span> </div>
        <div class="welcome-section">
            <div class="title-container">
                <h1>Welcome to the</h1>
                <span class="green-highlight">Sugar Regulatory Administration</span>
            </div>  
            <p>Monitoring System: Efficiently track and manage trader reports with real-time data and compliance updates.</p>    
            <button class="create-report-btn" onclick="window.location.href='createreport.php'">Create report</button>
        </div>
        
        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-file"></i>
                </div>
                <h3>Easy Reporting</h3>
                <p>Submit and manage reports effortlessly with our smooth, trader-friendly system built for regulatory compliance.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <h3>Real-time Updates</h3>
                <p>Stay updated with real-time notifications, market trends, and instant chats for seamless communication.</p>
            </div>
        </div>
        
        <div class="spacer"></div>
    </main>
    
    <footer id="footer">
        <div class="footer-content">
            <div class="footer-description">
                The Sugar Regulatory Administration (SRA) monitors and regulates the sugar industry to ensure fair trade practices and market stability.
            </div>
            
            <div class="footer-links">
                <i class="fa-solid fa-phone"></i>
                <i class="fa-regular fa-envelope"></i>
                <i class="fa-brands fa-facebook"></i>
            </div>
            
            <div class="footer-bottom">
                SRA © 2025, All Rights Reserved.
            </div>
        </div>
    </footer>
    
    <!-- Overlay div for blur effect -->
    <div class="overlay" id="overlay"></div>

    <!-- Chat Bubble -->
    <div class="chat-bubble">
        <i class="fa-solid fa-comment"></i>
        <span class="notification-dot" style="display: none;"></span>
    </div>

    <!-- Chat Window -->
    <div class="chat-window">
        <div class="chat-header">
            <h3><i class="fa-solid fa-circle"></i> SRA Chat Support</h3>
            <div class="chat-close">
                <i class="fa-solid fa-xmark"></i>
            </div>
        </div>
        <div class="chat-box-body" id="chat-box-body">
            <!-- Chat messages will be loaded here -->

        </div>
        <form class="chat-input" id="chat-form">
            <input type="hidden" id="sender" value="<?php echo $_SESSION['name']; ?>">
            <input type="hidden" id="receiver" value="<?php echo $_SESSION['receiverName']; ?>">
            <input type="text" id="message" placeholder="Type your message..." required>
            <button type="submit" class="send-btn"><i class="fa-solid fa-paper-plane"></i></button>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        // Document ready function
        $(document).ready(function() {
            // User icon and side panel functionality
            const userIcon = $('.user-icon-container');
            const sidePanel = $('.side-panel');
            const sidePanelOverlay = $('.side-panel-overlay');
            
            userIcon.on('click', function() {
                sidePanel.toggleClass('open');
                sidePanelOverlay.toggleClass('active');
            });
            
            sidePanelOverlay.on('click', function() {
                sidePanel.removeClass('open');
                sidePanelOverlay.removeClass('active');
            });
            
            // Change password functionality
            const changePasswordBtn = $('#change-password-btn');
            const passwordModal = $('#passwordModal');
            const passwordModalClose = $('.password-modal-close');
            
            changePasswordBtn.on('click', function() {
                passwordModal.css('display', 'flex');
                $('#currentPassword').focus();
                sidePanel.removeClass('open');
                sidePanelOverlay.removeClass('active');
            });
            
            passwordModalClose.on('click', function() {
                passwordModal.css('display', 'none');
            });
            
            // Close modal when clicking outside
            $(window).on('click', function(event) {
                if (event.target === passwordModal[0]) {
                    passwordModal.css('display', 'none');
                }
            });
            
            // Toggle password visibility
            $('.password-toggle').on('click', function() {
                const inputField = $(this).prev();
                const icon = $(this).find('i');
                
                if (inputField.attr('type') === 'password') {
                    inputField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    inputField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            
            // Handle password form submission
            $('#changePasswordForm').on('submit', function(e) {
                e.preventDefault();
                
                const currentPassword = $('#currentPassword').val();
                const newPassword = $('#newPassword').val();
                const confirmPassword = $('#confirmPassword').val();
                
                // Basic validation
                if (newPassword !== confirmPassword) {
                    alert('New password and confirmation do not match');
                    return;
                }
                
                // Here you would normally send an AJAX request to update the password
                // For demonstration, we'll just show an alert
                alert('Password updated successfully!');
                passwordModal.css('display', 'none');
                
                // Reset form
                this.reset();
            });

            // Sign out functionality
            $('#log-out-btn').on('click', function(event) {
                event.preventDefault();
                const userConfirmed = confirm("Are you sure you want to log out?");
                
                if (userConfirmed) {
                    window.location.href = "../../logins/logout.php";
                }
            });
            
            // Show footer only when scrolling down
            $(window).on('scroll', function() {
                const footer = $('#footer');
                const scrollPosition = $(window).scrollTop();
                
                // Show footer when scrolled enough
                if (scrollPosition > 100) {
                    footer.css('display', 'block');
                } else {
                    footer.css('display', 'none');
                }
            });
            
            // Force a small scroll event to check if scrollbar is available
            $(window).on('load', function() {
                // Add enough content to make page scrollable if it isn't already
                const body = document.body;
                const html = document.documentElement;
                const height = Math.max(
                    body.scrollHeight, body.offsetHeight,
                    html.clientHeight, html.scrollHeight, html.offsetHeight
                );
                
                if (height <= window.innerHeight) {
                    // Page isn't tall enough to scroll, add some padding to main
                    $('main').css('paddingBottom', '300px');
                }
            });

            // Chat functionality
            const chatBubble = $('.chat-bubble');
            const chatWindow = $('.chat-window');
            const chatClose = $('.chat-close');
            const overlay = $('#overlay');

            // Toggle chat window visibility
            chatBubble.on('click', function() {
                chatWindow.css('display', 'flex');
                chatBubble.css('display', 'none');
                overlay.css('display', 'block');
                $('#message').focus();

                $('.notification-dot').hide(); // Hide the dot when chat opens
                autoScrollOnOpen();
            });

            // Close chat window
            chatClose.on('click', function() {
                chatWindow.css('display', 'none');
                chatBubble.css('display', 'flex');
                overlay.css('display', 'none');
            });

            // Also close chat when clicking overlay
            overlay.on('click', function(e) {
                if (e.target === overlay[0]) {
                    chatWindow.css('display', 'none');
                    chatBubble.css('display', 'flex');
                    overlay.css('display', 'none');
                }
            });

            // Function to fetch messages
            function fetchMessages() {
                var sender = $('#sender').val();
                var receiver = $('#receiver').val();
                var chatBox = $('#chat-box-body');
                var chatWindow = $('.chat-window');
                var notificationDot = $('.notification-dot');

                $.ajax({
                    url: '../../backend/messages/fetch_message.php',
                    type: 'POST',
                    data: { sender: sender, receiver: receiver, markAsRead: true },

                    success: function(data) {
                        // Compare new data with old data
                        var prevContent = chatBox.html();
                        chatBox.html(data);

                        // Check for latest message sender if content changed
                        if (data !== prevContent) {
                            scrollChatToBottom();

                            // Use jQuery to find the last message container
                            var lastSender = $('#chat-box-body .message-container:last .sender-name').text().trim();

                            // If it's not the current user, and chat is closed, show the dot
                            if (lastSender === receiver && !chatWindow.is(':visible')) {
                                notificationDot.show();
                            }
                        }
                    }
                });
            }

            // Function to scroll the chat box to the bottom
            function scrollChatToBottom() {
                var chatBox = $('#chat-box-body');
                chatBox.scrollTop(chatBox.prop("scrollHeight"));
            }

            // Function to auto-scroll chat to bottom when opening
            function autoScrollOnOpen() {
                var chatBox = $('#chat-box-body');
                chatBox.scrollTop(chatBox.prop("scrollHeight"));
            }

            // Initial fetch and set interval
            fetchMessages();
            setInterval(fetchMessages, 3000);

            // Submit the chat message
            $('#chat-form').submit(function(e) {
                e.preventDefault();
                var sender = $('#sender').val();
                var receiver = $('#receiver').val();
                var message = $('#message').val();

                var sendBtn = $('.send-btn');
                sendBtn.prop('disabled', true); // Disable button

                $.ajax({
                    url: '../../backend/messages/submit_message.php',
                    type: 'POST',
                    data: { sender: sender, receiver: receiver, message: message },
                    success: function() {
                        $('#message').val('');
                        fetchMessages(); // Fetch after sending
                    },
                    complete: function() {
                        sendBtn.prop('disabled', false); // Re-enable button
                    }
                });
            });
        });
    </script>
</body>
</html>
