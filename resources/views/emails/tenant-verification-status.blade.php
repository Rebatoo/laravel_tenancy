<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .content {
            padding: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Tenant Account Status Update</h2>
    </div>

    <div class="content">
        <p>Dear {{ $emailData['tenant_name'] }},</p>

        @if($emailData['status'] === 'verified')
            <p>Congratulations! Your account is verified.</p>
        @else
            <p>Your account was rejected.</p>
        @endif

        @if($emailData['status'] === 'verified')
            <p>Congratulations! Your tenant account has been verified and approved. You can now access your account using the following URL:</p>
            <p><a href="https://{{ $emailData['login_url'] }}" class="button">Access Your Account</a></p>
            <p>Your account is now active and you can start using all the features of our platform.</p>
        @else
            <p>We regret to inform you that your tenant account registration has been rejected.</p>
            <p>If you believe this is an error or would like to submit a new application, please contact our support team.</p>
        @endif

        <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
    </div>

    <div class="footer">
        <p>This is an automated message, please do not reply directly to this email.</p>
    </div>
</body>
</html> 