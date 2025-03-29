{{-- <!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h1>Hello, {{ $name }}</h1>
    <p>You requested to reset your password. Please click the link below to reset it:</p>
    <a href="{{ $resetUrl }}" style="padding: 10px; background: rgb(0, 140, 255); color: white; text-decoration: none; font-weight: 600;">Reset Password</a>
    <br>
    <br>
    <p>If you did not request this, please ignore this email.</p>
</body>
</html> --}}


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Reset Password</title>
		<!-- FONT -->
		<link
			href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap"
			rel="stylesheet"
		/>

		<style>
			/* CSS COLOR VARIABLES */
			:root {
				--primary-color: #fecd07;
				--secondary-color: #fecd07cf;
				--main-gray: rgb(243, 243, 243);
				--secondary-gray: rgb(61, 61, 61);
			}
			* {
				padding: 0;
				margin: 0;
				box-sizing: border-box;
				font-family: 'Montserrat', sans-serif;
			}
			body {
				overflow-x: hidden;
			}

			/* ONLY FOR HOMEPAGE (NOT TEMPLATE) */
			.homepage {
				display: flex;
				flex-direction: column;
				gap: 20px;
				height: 100vh;
				align-items: center;
				justify-content: center;
			}
			.links {
				text-align: center;
				display: flex;
				flex-direction: column;
				gap: 1rem;
			}
			/* ONLY FOR HOMEPAGE */

			.page-container {
				display: flex;
				flex-direction: column;
				justify-content: center;
				align-items: center;
				width: 100%;
				padding: 3rem 0;
				background-color: var(--main-gray);
				min-height: 100vh;
			}

			/* LOGO */
			.logo-wrapper {
				width: 130px;
				display: flex;
				flex-direction: column;
				align-items: center;
				gap: 10px;
			}
			.logo {
				width: 100%;
				height: 100%;
			}
			.yellow-span {
				width: 80px;
				height: 6px;
				background-color: var(--primary-color);
			}

			/* CARD */
			.card-wrapper {
				width: 95%;
				max-width: 500px;
				background-color: white;
				margin: 2rem auto;
			}
			.card-body {
				padding: 20px 15px;
				display: flex;
				flex-direction: column;
				align-items: center;
				gap: 2rem;
			}
			.card-body h1 {
				font-weight: 500;
				font-size: 36px;
				text-align: center;
			}
			.card-body p {
				text-align: center;
				max-width: 350px;
			}
			.card-body span.card-link-text {
				text-align: left;
				display: block;
				word-wrap: break-word;
				overflow-wrap: break-word;
				max-width: 100%;
			}
			.card-body span.card-link-text a:hover {
				text-decoration: none;
			}
			.yellow-text {
				color: var(--primary-color);
				font-weight: 500;
			}
			.card-body button {
				background-color: var(--primary-color);
				border: none;
				outline: none;
				padding: 12px 15px;
				cursor: pointer;
			}
			.card-body button:hover {
				background-color: var(--secondary-color);
			}

			/* CARD FOOTER */
			.card-footer {
				background-color: var(--secondary-gray);
				padding: 20px 0;
				display: flex;
				flex-direction: column;
				align-items: center;
			}
			.social-links {
				display: flex;
				list-style: none;
				justify-content: center;
				gap: 15px;
				margin-bottom: 5px;
			}
			.social-links li a:hover {
				opacity: 0.8;
			}
			.copyright,
			.reply-text {
				color: white;
				font-size: 12px;
			}
		</style>
	</head>
	<body>
		<main class="page-container">
			<!-- LOGO SECTION -->
			<section class="logo-wrapper">
				<img
					src="{{ asset('logo/long.png') }}"
					alt="My power logo"
					class="logo"
				/>
				<div class="yellow-span" />
			</section>
			<!-- LOGO SECTION -->

			<!-- CARD SECTION -->
			<section class="card-wrapper">
				<div class="card-body">
					<h1 style="text-transform: capitalize;">Need a New Password? Let’s Fix That!</h1>
					<p>
						Forgot your password? No worries! Click the button below to reset it
						and get back into your
						<span class="yellow-text">My Power</span> account. <br />
					</p>
					<button href="{{ $resetUrl }}">Reset Password</button>
					<span class="card-link-text"
						>If the button did not work, please click the following link or copy
						it in your browser: <br />
						<a href="{{ $resetUrl }}"
							>{{ $resetUrl }}</a
						>
					</span>
					<span>
						<em class="yellow-text" style="font-size: 14px"
							>If you did not try to reset your password, you may ignore this
							email.</em
						>
					</span>
				</div>
				<!-- CARD SECTION -->

				<!-- CARD FOOTER -->
				<div class="card-footer">
					<ul class="social-links">
						<li>
							<a
								href="https://www.facebook.com/profile.php?id=61557147477761"
								target="_blank"
							>
								<img
									src="../assets/social_logos/facebook.svg"
									alt="Facebook logo"
									width="24px"
								/>
							</a>
						</li>
						<li>
							<a href="https://www.instagram.com/mypowernepal/" target="_blank"
								><img
									src="../assets/social_logos/instagram.svg"
									alt="Instagram logo"
									width="24px"
								/>
							</a>
						</li>
						<li>
							<a href="https://www.tiktok.com/@mypowernepal" target="_blank"
								><img
									src="../assets/social_logos/tiktok.svg"
									alt="Tiktok logo"
									width="24px"
								/>
							</a>
						</li>
						<li>
							<a href="https://www.youtube.com/@mypowernepal" target="_blank"
								><img
									src="../assets/social_logos/youtube.svg"
									alt="Youtube logo"
									width="24px"
								/>
							</a>
						</li>
						<li>
							<a href="https://x.com/mypowernepal" target="_blank"
								><img
									src="../assets/social_logos/x.svg"
									alt="X logo"
									width="24px"
								/>
							</a>
						</li>
						<li>
							<a href="https://www.pinterest.com/Mypowernepal/" target="_blank"
								><img
									src="../assets/social_logos/pinterest.svg"
									alt="Pinterest logo"
									width="24px"
								/>
							</a>
						</li>
					</ul>

					<span class="copyright"
						>&copy; 2025 &nbsp;MyPower, All Rights Reserved</span
					>
					<span class="reply-text">Reply to this email to contact us!</span>
				</div>
				<!-- CARD FOOTER -->
			</section>
		</main>
	</body>
</html>
