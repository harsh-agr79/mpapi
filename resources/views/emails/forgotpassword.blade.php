<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background-color: #ffcc00;
            color: #000;
            padding: 12px 20px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
        .social-icons {
            margin-top: 10px;
        }
        .social-icons img {
            width: 30px;
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://your-logo-url.com/logo.png" alt="My Power Logo" class="logo" width="150">
        <h2>Need A New Password? Let’s Fix That!</h2>
        <p>Forgot your password? No worries! Click the button below to reset it and get back into your <b>My Power</b> account.</p>
        <a href="{{ $resetUrl }}" class="button">Reset Password</a>
        <p>If the button did not work, please click the following link or copy it in your browser:</p>
        <p><a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>
        <div class="footer">
            <p>If you did not try to reset your password, you may ignore this email.</p>
            <p>&copy; 2025 MyPower, All Rights Reserved</p>
            <div class="social-icons">
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook"></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733579.png" alt="Instagram"></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733558.png" alt="Twitter"></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/1384/1384060.png" alt="YouTube"></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" alt="LinkedIn"></a>
            </div>
        </div>
    </div>
</body>
</html>
