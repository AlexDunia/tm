<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Reset</title>
</head>
<body>
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;">
        <h2>Reset Your Password</h2>
        <p>You are receiving this email because we received a password reset request for your account.</p>
        <p>Click the button below to reset your password:</p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('password.reset', $token) }}" style="background-color: #4CAF50; color: white; padding: 12px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">Reset Password</a>
        </div>
        <p>If you did not request a password reset, no further action is required.</p>
        <p>This password reset link will expire in 60 minutes.</p>
        <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:</p>
        <p style="word-break: break-all;">{{ route('password.reset', $token) }}</p>
        <p>Regards,<br>Your Application</p>
    </div>
</body>
</html>
