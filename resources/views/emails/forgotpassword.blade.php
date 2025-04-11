<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f3f3f3">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
        style="background-color: #f3f3f3; margin-top: 60px; width: 100%">
        <tr>
            <td align="center">
                <!-- Wrapper Table -->
                <table role="presentation" width="100%" max-width="600" cellspacing="0" cellpadding="0" border="0"
                    style="
							background-color: white;
							padding: 20px;
							border-radius: 8px;
							width: 100%;
							max-width: 600px;
						">
                    <!-- Logo -->
                    <tr>
                        <td align="center">
                            <img src="{{ asset('logo/long.png') }}alt="My Power Logo" width="120"
                                style="display: block; max-width: 100%; height: auto" />
                            <hr
                                style="
										border: none;
										height: 4px;
										background-color: #fecd07;
										width: 80px;
										margin: 10px auto;
									" />
                        </td>
                    </tr>

                    <!-- Heading -->
                    <tr>
                        <td align="center">
                            <h1
                                style="
										font-family: Arial, sans-serif;
										font-size: 24px;
										font-weight: bold;
										color: #333;
										text-align: center;
									">
                                Need A New Password? Let’s Fix That!
                            </h1>
                            <p
                                style="
										font-family: Arial, sans-serif;
										font-size: 16px;
										color: #555;
										text-align: center;
									">
                                Forgot your password? No worries! Click the button below to
                                reset it and get back into your
                                <span style="color: #fecd07; font-weight: bold">My Power</span>
                                account.
                            </p>
                        </td>
                    </tr>

                    <!-- Button -->
                    <tr>
                        <td align="center">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td bgcolor="#fecd07" style="border-radius: 5px; text-align: center">
                                        <a href="{{ $resetUrl }}"
                                            style="
													display: inline-block;
													font-family: Arial, sans-serif;
													font-size: 16px;
													color: black;
													text-decoration: none;
													background-color: #fecd07;
													padding: 12px 20px;
													border-radius: 5px;
													width: 100%;
													max-width: 200px;
													text-align: center;
												">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Backup Link -->
                    <tr>
                        <td align="center" style="padding: 15px 0; word-wrap: break-word">
                            <p
                                style="
										font-family: Arial, sans-serif;
										font-size: 14px;
										color: #555;
									">
                                If the button did not work, please click the following link or
                                copy it in your browser:
                                <br />
                                <a href="{{ $resetUrl }}"
                                    style="
											color: blue;
											word-break: break-all;
											display: block;
											max-width: 100%;
											overflow-wrap: break-word;
										">
                                    https://www.mypower.com.np/reset-pwd?token=your_token
                                </a>
                            </p>
                        </td>
                    </tr>

                    <!-- Warning Message -->
                    <tr>
                        <td align="center">
                            <p
                                style="
										font-family: Arial, sans-serif;
										font-size: 14px;
										color: #fecd07;
										font-style: italic;
                    text-align: center;
									">
                                If you did not try to reset your password, you may ignore this
                                email.
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Footer -->
                <table role="presentation" width="100%" max-width="600" cellspacing="0" cellpadding="0" border="0"
                    style="
							background-color: #3d3d3d;
							padding: 20px;
							border-radius: 0 0 8px 8px;
							width: 100%;
							max-width: 600px;
						">
                    <tr>
                        <td align="center">
                            <!-- Social Icons -->
                            <table role="presentation" cellspacing="0" cellpadding="5" border="0"
                                style="text-align: center">
                                <tr>
                                    <td style="padding: 0 5px;">
                                        <a href="https://www.facebook.com/profile.php?id=61557147477761"
                                            target="_blank"><img src="{{ asset('social_logos/facebook.png') }}"
                                                width="24" alt="Facebook" /></a>
                                    </td>
                                    <td style="padding: 0 5px;">
                                        <a href="https://www.instagram.com/mypowernepal/" target="_blank"><img
                                                src="{{ asset('social_logos/instagram.png') }}" width="24"
                                                alt="Instagram" /></a>
                                    </td>
                                    <td style="padding: 0 5px;">
                                        <a href="https://www.tiktok.com/@mypowernepal" target="_blank"><img
                                                src="{{ asset('social_logos/tiktok.png') }}" width="24"
                                                alt="Tiktok" /></a>
                                    </td>
                                    <td style="padding: 0 5px;">
                                        <a href="https://www.youtube.com/@mypowernepal" target="_blank"><img
                                                src="{{ asset('social_logos/youtube.png') }}" width="24"
                                                alt="Youtube" /></a>
                                    </td>
                                    <td style="padding: 0 5px;">
                                        <a href="https://x.com/mypowernepal" target="_blank"><img
                                                src="{{ asset('social_logos/x.png') }}" width="24"
                                                alt="Twitter" /></a>
                                    </td>
                                    <td style="padding: 0 5px;">
                                        <a href="https://www.pinterest.com/Mypowernepal/" target="_blank"><img
                                                src="{{ asset('social_logos/pinterest.png') }}" width="24"
                                                alt="Pinterest" /></a>
                                    </td>
                                </tr>
                            </table>
                            <br />
                            <p style="color: white; font-size: 12px; text-align: center">
                                © 2025 MyPower, All Rights Reserved
                            </p>
                            <p style="color: white; font-size: 12px; text-align: center">
                                Reply to this email to contact us!
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
