<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'db_connect.php';

// Handle display messages
$login_error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
$signup_error = $_SESSION['signup_error'] ?? '';
$signup_success = isset($_SESSION['signup_success']) ? true : false;
$welcome = isset($_GET['welcome']) ? true : false;
$username = $_SESSION['username'] ?? '';

// Clear session variables after use
unset($_SESSION['signup_error'], $_SESSION['signup_success']);

// Check if user is logged in and get their role
$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$user_role = $_SESSION['role'] ?? '';

// If user is logged in and is an admin, redirect to admin dashboard
if ($is_logged_in && $user_role === 'admin') {
    header("Location: admin/dashboard.php");
    exit();
}
?><!DOCTYPE HTML>
<html>
	<head>
		<title>TruckLogix</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="stylesheet" href="assets/css/fontawesome-all.min.css" />
		<script src="assets/js/jquery.min.js"></script>
		<style>
			/* Styles for Login and Sign Up Forms */
			#loginForm, #signupForm {
				display: none;
				position: fixed;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				background: white;
				padding: 2em;
				border-radius: 5px;
				box-shadow: 0 0 10px rgba(0,0,0,0.1);
				z-index: 1000;
				width: 300px;
			}
			#loginForm h3, #signupForm h3 {
				margin-bottom: 1em;
				text-align: center;
			}
			#loginForm .error, #signupForm .error {
				color: red;
				margin-top: 1em;
				text-align: center;
			}
			#signupForm .success {
				color: green;
				margin-top: 1em;
				text-align: center;
			}
			/* Form inputs and buttons */
			#loginForm input, #signupForm input {
				width: 100%;
				margin-bottom: 1em;
			}
			#loginForm input[type="submit"], #signupForm input[type="submit"],
			#loginForm input[type="button"], #signupForm input[type="button"] {
				width: 48%;
				display: inline-block;
			}
			#loginForm input[type="button"], #signupForm input[type="button"] {
				float: right;
			}
			/* Overlay */
			#formOverlay {
				display: none;
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background: rgba(0, 0, 0, 0.5);
				z-index: 999;
			}
			#signupForm, #formOverlay {
				display: none;
			}
			/* Add this to your existing styles */
			.welcome-message {
				background-color: #4CAF50;
				color: white;
				padding: 10px;
				margin-bottom: 20px;
				border-radius: 5px;
				text-align: center;
			}
		</style>
	</head>
	<body class="landing is-preload">
		<div id="page-wrapper">
			<!-- Header -->
			<header id="header" style="height: 4em; display: flex; align-items: center;">
				<h1 style="margin: 0; padding: 0;">
					<a href="index.php" class="logo" style="display: flex; align-items: center; height: 100%;">
						<img src="images/PEN_Logo-removebg-preview (2).png" alt="TruckLogix" style="max-height: 3em; width: auto; vertical-align: middle;">
					</a>
				</h1>
				<nav id="nav" style="margin-left: auto;">
					<ul>
						<li><a href="index.php">Home</a></li>
						<li>
							<a href="#" class="icon solid fa-angle-down">Servicese</a>
							<ul>
								<li><a href="freight/freight.php">Freight Transport</a></li>
								<li><a href="warehousing.php">Warehousing</a></li>
								<li><a href="logistics.php">Logistics Solutions</a></li>
								<li>
									<a href="#">Specialized Services</a>
									<ul>
										<li><a href="#">Temperature-Controlled</a></li>
										<li><a href="#">Hazardous Materials</a></li>
										<li><a href="#">Oversized Loads</a></li>
										<li><a href="#">Express Delivery</a></li>
									</ul>
								</li>
							</ul>
						</li>
						<?php if ($is_logged_in): ?>
							<li><a href="#" class="button">Welcome, <?php echo htmlspecialchars($username); ?></a></li>
							<li><a href="logout.php" class="button alt">Logout</a></li>
						<?php else: ?>
							<li><a href="#" class="button alt" id="loginButton">Login</a></li>
							<li><a href="#" class="button alt" id="signupButton">Sign Up</a></li>
						<?php endif; ?>
					</ul>
				</nav>
			</header>

			<!-- Overlay for Forms -->
			<div id="formOverlay"></div>

			<!-- Login Form -->
			<div id="loginForm">
				<form action="login/login.php" method="post">
					<h3>Login</h3>
					<div>
						<input type="text" name="username" id="loginUsername" placeholder="Username" required />
					</div>
					<div>
						<input type="password" name="password" id="loginPassword" placeholder="Password" required />
					</div>
					<div>
						<input type="submit" value="Login" class="primary" />
						<input type="button" value="Close" id="closeLogin" />
					</div>
					<?php if (!empty($login_error)): ?>
						<p class="error"><?php echo htmlspecialchars($login_error); ?></p>
					<?php endif; ?>
				</form>
			</div>

			<!-- Sign Up Form -->
			<div id="signupForm">
				<form action="sign_up/signup.php" method="post" id="signupFormElement">
					<h3>Sign Up</h3>
					<div>
						<input type="text" name="username" id="signupUsername" placeholder="Username" required />
					</div>
					<div>
						<input type="email" name="email" id="signupEmail" placeholder="Email" required />
					</div>
					<div>
						<input type="password" name="password" id="signupPassword" placeholder="Password" required />
						<p id="passwordError" class="error" style="display: none;"></p>
					</div>
					<div>
						<input type="submit" value="Sign Up" class="primary" />
						<input type="button" value="Close" id="closeSignup" />
					</div>
					<?php if (!empty($signup_error)): ?>
						<p class="error"><?php echo htmlspecialchars($signup_error); ?></p>
					<?php endif; ?>
					<?php if ($signup_success): ?>
						<p class="success">Signup successful! You can now log in.</p>
					<?php endif; ?>
				</form>
			</div>

			<!-- Banner -->
			<section id="banner">
				<h2>Pen Express Your Dynamic Logistics Partner</h2>
				<p>Efficient Solutions for All Your Trucking and Shipping Needs</p>
				<ul class="actions special">
					<li><a href="#" class="button primary">Get a Quote</a></li>
					<li><a href="#" class="button">Our Services</a></li>
				</ul>
			</section>

			<!-- Main Content -->
			<section id="main" class="container">
				<section class="box special">
					<header class="major">
						<h2>Revolutionizing Trucking and Logistics
						<br />
						with Cutting-Edge Technology</h2>
						<p>Experience seamless shipping and real-time tracking with our state-of-the-art logistics platform.<br />
						Optimizing routes, reducing costs, and ensuring on-time deliveries.</p>
					</header>
					<span class="image featured">
						<img src="images\Untitled design (4).png" alt="Truck Fleet" style="object-fit: cover; width: 100%; max-height: 400px;" />
					</span>
				</section>

				<section class="box special features">
					<div class="features-row">
						<section>
							<span class="icon solid major fa-truck accent2"></span>
							<h3>Nationwide Coverage</h3>
							<p>Our extensive network of trucks and drivers ensures reliable transportation services across the country, meeting your shipping needs with precision and care.</p>
						</section>
						<section>
							<span class="icon solid major fa-chart-line accent3"></span>
							<h3>Real-Time Tracking</h3>
							<p>Stay informed about your shipments with our advanced tracking system, providing real-time updates and estimated arrival times for complete peace of mind.</p>
						</section>
					</div>
					<div class="features-row">
						<section>
							<span class="icon solid major fa-warehouse accent4"></span>
							<h3>Warehousing Solutions</h3>
							<p>Our state-of-the-art warehouses offer secure storage and efficient inventory management, streamlining your supply chain and reducing operational costs.</p>
						</section>
						<section>
							<span class="icon solid major fa-shield-alt accent5"></span>
							<h3>Secure Transport</h3>
							<p>We prioritize the safety of your cargo with our rigorous security measures, experienced drivers, and well-maintained fleet, ensuring your goods arrive intact.</p>
						</section>
					</div>
				</section>

				<div class="row">
					<div class="col-6 col-12-narrower">
						<section class="box special">
							<span class="image featured">
								<img src="images\MultipleTransport.png" alt="Intermodal Transport" style="object-fit: cover; width: 100%; height: 200px;" />
							</span>
							<h3>Intermodal Transport Solutions</h3>
							<p>Optimize your supply chain with our efficient intermodal transport services. We combine road, rail, and sea shipping to provide cost-effective and environmentally friendly logistics solutions for your business.</p>
							<ul class="actions special">
								<li><a href="#" class="button alt">Learn More</a></li>
							</ul>
						</section>
					</div>
					<div class="col-6 col-12-narrower">
						<section class="box special">
							<span class="image featured">
								<img src="images\Untitled design (9).png" alt="Custom Logistics" style="object-fit: cover; width: 100%; height: 200px;" />
							</span>
							<h3>Custom Logistics Solutions</h3>
							<p>Our team of experts designs tailored logistics solutions to meet your unique business needs. From specialized handling to complex supply chain management, we've got you covered.</p>
							<ul class="actions special">
								<li><a href="#" class="button alt">Learn More</a></li>
							</ul>
						</section>
					</div>
				</div>
			</section>

			<!-- CTA Section -->
			<section id="cta">
				<h2>Request a Free Quote</h2>
				<p>Get in touch with our logistics experts for a customized shipping solution tailored to your needs.</p>

				<form action="quote/quote.php" method="post">
					<div class="row gtr-50 gtr-uniform">
						<div class="col-8 col-12-mobilep">
							<input type="email" name="email" id="quoteEmail" placeholder="Email Address" required />
						</div>
						<div class="col-4 col-12-mobilep">
							<input type="submit" value="Get Quote" class="fit" />
						</div>
					</div>
				</form>
			</section>

			<!-- Footer -->
			<footer id="footer">
				<ul class="icons">
					<li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
					<li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
					<li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
					<li><a href="#" class="icon brands fa-linkedin"><span class="label">LinkedIn</span></a></li>
				</ul>
				<ul class="copyright">
					<li>&copy; TruckLogix. All rights reserved.</li>
					<li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
				</ul>
			</footer>
		</div>

		<!-- Scripts -->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/jquery.dropotron.min.js"></script>
		<script src="assets/js/jquery.scrollex.min.js"></script>
		<script src="assets/js/browser.min.js"></script>
		<script src="assets/js/breakpoints.min.js"></script>
		<script src="assets/js/util.js"></script>
		<script src="assets/js/main.js"></script>
		<script>
			$(document).ready(function() {
				console.log('Document ready');
				// Open Login Form
				$('#loginButton').click(function(e) {
					e.preventDefault();
					$('#formOverlay').fadeIn(300);
					$('#loginForm').fadeIn(300);
				});

				// Open Sign Up Form
				$('#signupButton').click(function(e) {
					e.preventDefault();
					$('#formOverlay').fadeIn(300);
					$('#signupForm').fadeIn(300);
					console.log('Sign-up button clicked'); // Add this line for debugging
				});

				// Close Login Form
				$('#closeLogin').click(function() {
					$('#loginForm').fadeOut(300);
					$('#formOverlay').fadeOut(300);
				});

				// Close Sign Up Form
				$('#closeSignup').click(function() {
					$('#signupForm').fadeOut(300);
					$('#formOverlay').fadeOut(300);
				});

				// Close forms when clicking outside
				$('#formOverlay').click(function() {
					$('#loginForm, #signupForm').fadeOut(300);
					$(this).fadeOut(300);
				});

				// Password validation for sign up
				$('#signupPassword').on('input', function() {
					var password = $(this).val();
					var $passwordError = $('#passwordError');
					
					if (password.length < 6) {
						$passwordError.text('Password must be at least 6 characters long').show();
					} else if (!/[A-Z]/.test(password)) {
						$passwordError.text('Password must contain at least one uppercase letter').show();
					} else if (!/[a-z]/.test(password)) {
						$passwordError.text('Password must contain at least one lowercase letter').show();
					} else if (!/[0-9]/.test(password)) {
						$passwordError.text('Password must contain at least one number').show();
					} else {
						$passwordError.hide();
					}
				});

				// Form submission
				$('#signupFormElement').submit(function(e) {
					e.preventDefault();
					var username = $('#signupUsername').val().trim();
					var email = $('#signupEmail').val().trim();
					var password = $('#signupPassword').val();

					if (username === '' || email === '' || password === '') {
						alert('Please fill in all fields');
						return;
					}

					if ($('#passwordError').is(':visible')) {
						alert('Please correct the password errors before submitting');
						return;
					}

					// If all validations pass, submit the form
					this.submit();
				});

				<?php if (!$is_logged_in): ?>
				// Login form submission
				$('#loginForm form').submit(function(e) {
					var username = $('#loginUsername').val().trim();
					var password = $('#loginPassword').val();

					if (username === '' || password === '') {
						e.preventDefault();
						alert('Please enter both username and password.');
					}
				});
				<?php endif; ?>

				<?php if ($welcome || $signup_success): ?>
				// Automatically open the login form if the user just signed up
				$('#formOverlay').fadeIn(300);
				$('#loginForm').fadeIn(300);
				<?php endif; ?>
			});
		</script>
	</body>
</html>