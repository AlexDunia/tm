<!DOCTYPE html>
<html>
<head>
    <title>Security Alert: New Login Detected | Tixdemand</title>
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
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 20px;
        }
        h2 {
            color: #f746a5;
            font-size: 20px;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 20px;
            font-size: 16px;
        }
        .alert {
            background-color: #fff8f8;
            border-left: 4px solid #f746a5;
            padding: 15px;
            margin-bottom: 20px;
            color: #333;
        }
        .info-box {
            background-color: #f9f9f9;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .info-item {
            display: flex;
            border-bottom: 1px solid #eaeaea;
            padding: 12px 0;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-item strong {
            width: 140px;
            color: #555;
        }
        .info-item span {
            flex: 1;
            word-break: break-word;
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
        .support {
            text-align: center;
            margin-top: 30px;
            color: #777;
            font-size: 14px;
        }
        .support a {
            color: #f746a5;
            text-decoration: none;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eaeaea;
            color: #999;
            font-size: 12px;
        }
        .security-tips {
            background-color: #f0f9ff;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin: 25px 0;
        }
        .security-tips ul {
            margin: 10px 0 0 0;
            padding-left: 20px;
        }
        .security-tips li {
            margin-bottom: 8px;
        }
        @media only screen and (max-width: 600px) {
            .container {
                padding: 20px 15px;
            }
            h1 {
                font-size: 22px;
            }
            h2 {
                font-size: 18px;
            }
            .info-item {
                flex-direction: column;
            }
            .info-item strong {
                width: 100%;
                margin-bottom: 5px;
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
            <h1>Hello {{$firstname}},</h1>

            <div class="alert">
                <p><strong>Important Security Alert:</strong> We've detected a new login to your Tixdemand account from a device or location we don't recognize.</p>
            </div>

            <p>Your account security is our priority. We're reaching out to confirm this activity was authorized.</p>

            <h2>Login Details</h2>

            <div class="info-box">
                <div class="info-item">
                    <strong>Date & Time:</strong>
                    <span>{{ $deviceInfo['time'] ?? now()->format('F j, Y \a\t g:i a') }}</span>
                </div>
                <div class="info-item">
                    <strong>Device Type:</strong>
                    <span>{{ $deviceInfo['device_type'] ?? 'Unknown' }}</span>
                </div>
                <div class="info-item">
                    <strong>Browser:</strong>
                    <span>{{ $deviceInfo['browser'] ?? 'Unknown' }}</span>
                </div>
                <div class="info-item">
                    <strong>IP Address:</strong>
                    <span>{{ $locationData->ip }}</span>
                </div>
                <div class="info-item">
                    <strong>Location:</strong>
                    <span>{{ $locationData->cityName }}, {{ $locationData->regionCode }}, {{ $locationData->countryName }}</span>
                </div>
            </div>

            <p><strong>If this was you:</strong> No action is needed. Your account is secure, and this email is just to keep you informed about account activity.</p>

            <p><strong>If you did not initiate this login:</strong> Someone may be trying to access your account. We recommend you take immediate action to secure your account.</p>

            <div class="action">
                <a href="{{ url('forgotpassword') }}" class="button">Reset Your Password</a>
            </div>

            <div class="security-tips">
                <h3>Security Recommendations:</h3>
                <ul>
                    <li>Use a strong, unique password that you don't use for other services</li>
                    <li>Change your password regularly (every 3-6 months)</li>
                    <li>Be cautious of phishing emails asking for your login details</li>
                    <li>Log out when using shared or public devices</li>
                    <li>Keep your email account secure, as it's linked to your Tixdemand account</li>
                </ul>
            </div>

            <div class="support">
                <p>If you need assistance or didn't recognize this login, please contact our support team immediately at <a href="mailto:support@tixdemand.com">support@tixdemand.com</a> or visit our <a href="https://www.tixdemand.com/contact">Help Center</a>.</p>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Tixdemand. All rights reserved.</p>
            <p>This is an automated security notification. Please do not reply to this email.</p>
            <p><small>Reference ID: {{ Str::random(8) }}</small></p>
        </div>
    </div>
</body>
</html>
