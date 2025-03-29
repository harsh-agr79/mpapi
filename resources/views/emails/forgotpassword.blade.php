<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Reset Password</title>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet" />
	</head>
	<body style="margin: 0; padding: 0; font-family: 'Montserrat', sans-serif; background-color: #f3f3f3; text-align: center;">
		<main style="width: 100%; max-width: 500px; margin: 3rem auto; background-color: #fff; padding: 20px; border-radius: 8px;">
			<!-- LOGO SECTION -->
			<div style="margin-bottom: 20px;">
				<img src="{{ asset('logo/long.png') }}" alt="My power logo" style="width: 130px; display: block; margin: 0 auto;" />
				<div style="width: 80px; height: 6px; background-color: #fecd07; margin: 10px auto;"></div>
			</div>

			<!-- CARD BODY -->
			<h1 style="font-size: 24px; font-weight: 600;">Need a New Password? Let’s Fix That!</h1>
			<p style="font-size: 14px; color: #3d3d3d; max-width: 350px; margin: 0 auto;">
				Forgot your password? No worries! Click the button below to reset it and get back into your <strong style="color: #fecd07;">My Power</strong> account.
			</p>
			<a href="{{ $resetUrl }}" style="display: inline-block; background-color: #fecd07; color: #000; padding: 12px 20px; text-decoration: none; font-weight: 600; border-radius: 5px; margin: 20px 0;">Reset Password</a>
			<p style="font-size: 12px; color: #3d3d3d;">
				If the button did not work, please click the following link or copy it in your browser:
				<a href="{{ $resetUrl }}" style="color: #fecd07; text-decoration: none; word-wrap: break-word; display: block;">{{ $resetUrl }}</a>
			</p>
			<p style="font-size: 12px; color: #3d3d3d; font-style: italic;">If you did not try to reset your password, you may ignore this email.</p>
		</main>

		<!-- FOOTER -->
		<footer style="background-color: #3d3d3d; padding: 15px; color: white; text-align: center;">
			<p style="font-size: 12px; margin-bottom: 10px;">&copy; 2025 MyPower, All Rights Reserved</p>
			<p style="font-size: 12px;">Reply to this email to contact us!</p>
		</footer>
	</body>
</html>
