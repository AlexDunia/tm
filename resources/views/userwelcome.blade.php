<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Tixdemand</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body {
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    line-height: 1.6;
    color: #333;
    margin: 0;
    padding: 0;
    background-color: #f8f8f8;
}
.container {
    max-width: 600px;
    margin: 0 auto;
    padding: 30px 20px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
.header {
    text-align: center;
    margin-bottom: 30px;
}
.header img {
    max-width: 200px;
    height: auto;
}
.content {
    padding: 0 10px;
}
h1 {
    color: #333;
    font-size: 28px;
    margin-top: 0;
    margin-bottom: 20px;
    text-align: center;
}
h2 {
    color: #f746a5;
    font-size: 22px;
    margin-top: 30px;
    margin-bottom: 20px;
    text-align: center;
}
p {
    margin-bottom: 20px;
    font-size: 16px;
    color: #555;
}
.welcome-banner {
    background-color: #fcf2f7;
    border-radius: 8px;
    padding: 25px;
    margin: 30px 0;
    text-align: center;
    border-left: 4px solid #f746a5;
}
.welcome-message {
    text-align: center;
    margin: 30px 0;
}
.feature-list {
    background-color: #f9f9f9;
    border-radius: 6px;
    padding: 20px;
    margin: 25px 0;
}
.feature-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 20px;
}
.feature-icon {
    flex: 0 0 30px;
    font-size: 20px;
    color: #f746a5;
    margin-right: 15px;
}
.feature-text {
    flex: 1;
}
.feature-text h3 {
    margin-top: 0;
    margin-bottom: 8px;
    font-size: 18px;
    color: #444;
}
.action {
    text-align: center;
    margin: 30px 0;
}
.button {
    display: inline-block;
    background-color: #f746a5;
    color: white;
    font-weight: 600;
    text-decoration: none;
    padding: 12px 25px;
    border-radius: 4px;
    font-size: 16px;
}
.button:hover {
    background-color: #e03b8b;
}
.next-steps {
    margin: 30px 0;
    background-color: #f0f9ff;
    border-radius: 6px;
    padding: 20px;
}
.next-steps h3 {
    margin-top: 0;
    color: #2980b9;
    font-size: 18px;
}
.next-steps ol {
    margin: 15px 0 0;
    padding-left: 25px;
}
.next-steps li {
    margin-bottom: 10px;
}
.social {
    text-align: center;
    margin: 30px 0;
}
.social a {
    display: inline-block;
    margin: 0 10px;
    text-decoration: none;
}
.social img {
    width: 30px;
    height: 30px;
}
.help-info {
    background-color: #f9f9f9;
    border-radius: 6px;
    padding: 15px;
    margin: 25px 0;
    text-align: center;
}
.help-info p {
    margin: 0;
    font-size: 15px;
}
.footer {
    text-align: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eaeaea;
    color: #999;
    font-size: 12px;
}
@media only screen and (max-width: 600px) {
    .container {
        padding: 20px 15px;
    }
    h1 {
        font-size: 24px;
    }
    h2 {
        font-size: 20px;
    }
}
</style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="https://www.tixdemand.com/" target="_blank">
                <img src="https://fextellar.com/images/ft.png" alt="Tixdemand Logo">
            </a>
        </div>

        <div class="content">
            <div class="welcome-banner">
                <h1>Welcome to Tixdemand, {{$firstname}}!</h1>
                <p>Your account has been successfully created.</p>
            </div>

            <div class="welcome-message">
                <h2>Your best moments, on demand!</h2>
                <p>Thank you for joining Tixdemand. We're thrilled to have you as part of our community and look forward to helping you discover amazing events and experiences. With your new account, you're all set to explore the world of entertainment at your fingertips.</p>
            </div>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon">🎭</div>
                    <div class="feature-text">
                        <h3>Discover Events</h3>
                        <p>Browse through our curated collection of music, movies, theatre, comedy, sports events and more. Filter by category, date, or location to find exactly what you're looking for.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">🎟️</div>
                    <div class="feature-text">
                        <h3>Secure Tickets</h3>
                        <p>Purchase tickets securely and easily for all your favorite events. Our platform ensures your transactions are protected and your tickets are guaranteed.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">📱</div>
                    <div class="feature-text">
                        <h3>Mobile Access</h3>
                        <p>Access your tickets anytime, anywhere from any device. No need to print - just show your digital tickets at the venue for seamless entry.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">🔔</div>
                    <div class="feature-text">
                        <h3>Event Alerts</h3>
                        <p>Never miss out on your favorite events. Set up alerts for artists, venues, or event types you love and be the first to know when tickets are available.</p>
                    </div>
                </div>
            </div>

            <div class="next-steps">
                <h3>Getting Started with Tixdemand</h3>
                <ol>
                    <li>Complete your profile to personalize your experience</li>
                    <li>Browse events and save your favorites</li>
                    <li>Invite friends to join you at upcoming events</li>
                    <li>Download our mobile app for on-the-go access</li>
                </ol>
            </div>

            <div class="action">
                <a href="https://www.tixdemand.com/" class="button">Start Exploring</a>
            </div>

            <div class="help-info">
                <p>If you have any questions or need assistance, our support team is here to help.</p>
                <p>Contact us at <a href="mailto:support@tixdemand.com">support@tixdemand.com</a></p>
            </div>

            <div class="social">
                <p>Follow us on social media for the latest updates and exclusive offers:</p>
                <a href="https://www.facebook.com/" target="_blank"><img src="https://cdn-icons-png.flaticon.com/512/145/145802.png" alt="Facebook"></a>
                <a href="https://www.twitter.com/" target="_blank"><img src="https://cdn-icons-png.flaticon.com/512/145/145812.png" alt="Twitter"></a>
                <a href="https://www.instagram.com/" target="_blank"><img src="https://cdn-icons-png.flaticon.com/512/145/145805.png" alt="Instagram"></a>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Tixdemand. All rights reserved.</p>
            <p>You're receiving this email because you've created an account with Tixdemand.</p>
            <p><small>Account creation reference: {{ Str::random(8) }}</small></p>
        </div>
</div>
</body>
</html>
