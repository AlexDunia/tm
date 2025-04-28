<!DOCTYPE html>
<html>
<head>
    <title>Reset Your Password | Tixdemand</title>
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
            text-align: center;
        }
        p {
            margin-bottom: 20px;
            font-size: 16px;
            color: #555;
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
        .info-box {
            background-color: #f9f9f9;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
            color: #555;
            font-size: 14px;
        }
        .security-note {
            border-left: 4px solid #f746a5;
            padding: 10px 15px;
            background-color: #fff8f8;
            margin: 20px 0;
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
                font-size: 22px;
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
            <h1>Reset Your Password</h1>

            <p>Hello,</p>

            <p>You are receiving this email because we received a password reset request for your Tixdemand account.</p>

            <div class="action">
                <a href="{{ route('rp', $token) }}" class="button">Reset Password</a>
            </div>

            <p>This password reset link will expire in 60 minutes.</p>

            <div class="security-note">
                <p><strong>Important:</strong> If you did not request a password reset, no further action is required. However, you may want to review your account security.</p>
            </div>

            <div class="info-box">
                <p>For security reasons, this password reset request was initiated from:</p>
                <p>• Date and Time: {{ now()->format('F j, Y \a\t g:i a') }}</p>
                <p>• IP Address: {{ request()->ip() }}</p>
            </div>

            <p>If you have any questions or concerns, please <a href="https://www.tixdemand.com/contact" style="color: #f746a5; text-decoration: none;">contact our support team</a>.</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Tixdemand. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
