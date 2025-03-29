<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Montserrat', sans-serif; background-color: rgb(243, 243, 243); text-align: center;">
    <main style="width: 100%; padding: 3rem 0;">
        <!-- LOGO SECTION -->
        <section style="width: 130px; margin: auto;">
            <img src="{{ asset('logo/long.png') }}" alt="My power logo" style="width: 100%; height: auto;">
            <div style="width: 80px; height: 6px; background-color: #fecd07; margin: auto;"></div>
        </section>
        <!-- LOGO SECTION -->

        <!-- CARD SECTION -->
        <section style="width: 95%; max-width: 500px; background-color: white; margin: 2rem auto; padding: 20px 15px; border-radius: 5px;">
            <h1 style="font-weight: 500; font-size: 36px; text-transform: capitalize;">Need a New Password? Let’s Fix That!</h1>
            <p style="max-width: 350px; margin: auto;">Forgot your password? No worries! Click the button below to reset it and get back into your <span style="color: #fecd07; font-weight: 500;">My Power</span> account.</p>
            <a href="{{ $resetUrl }}" style="display: inline-block; background-color: #fecd07; padding: 12px 15px; text-decoration: none; color: black; font-weight: bold; border-radius: 5px; margin: 15px 0;">Reset Password</a>
            <p style="word-wrap: break-word; overflow-wrap: break-word;">If the button did not work, please click the following link or copy it in your browser: <br>
                <a href="{{ $resetUrl }}" style="color: #fecd07; text-decoration: none;">{{ $resetUrl }}</a>
            </p>
            <p style="font-size: 14px; color: #fecd07;"><em>If you did not try to reset your password, you may ignore this email.</em></p>
        </section>
        <!-- CARD SECTION -->

        <!-- CARD FOOTER -->
        <div style="background-color: rgb(61, 61, 61); padding: 20px 0;">
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; justify-content: center; gap: 15px;">
                <li><a href="https://www.facebook.com/profile.php?id=61557147477761" target="_blank"><img src="../assets/social_logos/facebook.svg" alt="Facebook" width="24px"></a></li>
                <li><a href="https://www.instagram.com/mypowernepal/" target="_blank"><img src="../assets/social_logos/instagram.svg" alt="Instagram" width="24px"></a></li>
                <li><a href="https://www.tiktok.com/@mypowernepal" target="_blank"><img src="../assets/social_logos/tiktok.svg" alt="Tiktok" width="24px"></a></li>
                <li><a href="https://www.youtube.com/@mypowernepal" target="_blank"><img src="../assets/social_logos/youtube.svg" alt="YouTube" width="24px"></a></li>
                <li><a href="https://x.com/mypowernepal" target="_blank"><img src="../assets/social_logos/x.svg" alt="X" width="24px"></a></li>
                <li><a href="https://www.pinterest.com/Mypowernepal/" target="_blank"><img src="../assets/social_logos/pinterest.svg" alt="Pinterest" width="24px"></a></li>
            </ul>
            <p style="color: white; font-size: 12px; margin: 5px 0;">&copy; 2025 MyPower, All Rights Reserved</p>
            <p style="color: white; font-size: 12px;">Reply to this email to contact us!</p>
        </div>
        <!-- CARD FOOTER -->
    </main>
</body>
</html>
