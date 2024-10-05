<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// require_once 'db_connect.php';  // Uncomment this line when you have set up your database
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>TruckLogix</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="stylesheet" href="assets/css/fontawesome-all.min.css" />
		<script src="assets/js/jquery.min.js"></script>
		<style>
			#header h1 { margin: 0; padding: 0; }
			#header h1 a.logo { display: flex; align-items: center; height: 100%; }
			#header h1 a.logo img { max-height: 3em; width: auto; vertical-align: middle; }
			#header nav { display: flex; align-items: center; }
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
		</style>
	</head>
	<body class="landing is-preload">
		<div id="page-wrapper">
			<!-- Header -->
			<header id="header" style="height: 4em; display: flex; align-items: center;">
				<h1>
					<a href="index.php" class="logo">
						<img src="images/PEN_Logo-removebg-preview (2).png" alt="TruckLogix">
					</a>
				</h1>
				<nav id="nav" style="margin-left: auto;">
					<ul>
						<li><a href="index.php">Home</a></li>
						<li>
							<a href="#" class="icon solid fa-angle-down">Services</a>
							<ul>
								<li><a href="freight.php">Freight Transport</a></li>
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
						<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
							<li><a href="#" class="button">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
							<li><a href="logout.php" class="button alt">Logout</a></li>
						<?php else: ?>
							<li><a href="#" class="button alt" id="loginButton">Login</a></li>
							<li><a href="#" class="button alt" id="signupButton">Sign Up</a></li> <!-- Ensure this line is present -->
						<?php endif; ?>
					</ul>
				</nav>
			</header>

			<!-- Login Form -->
			<div id="loginForm">
				<form action="login.php" method="post">
					<h3>Login</h3>
					<div class="row gtr-50 gtr-uniform">
						<div class="col-12">
							<input type="text" name="username" id="username" value="" placeholder="Username" required />
						</div>
						<div class="col-12">
							<input type="password" name="password" id="password" value="" placeholder="Password" required />
						</div>
						<div class="col-12">
							<ul class="actions">
								<li><input type="submit" value="Login" class="primary" /></li>
								<li><input type="button" value="Close" id="closeLogin" /></li>
							</ul>
						</div>
					</div>
				</form>
				<?php
				if (isset($_GET['error'])) {
					echo '<p class="error">' . htmlspecialchars($_GET['error']) . '</p>';
				}
				?>
			</div>

			<!-- Sign Up Form -->
			<div id="signupForm" style="display: none;">
				<form action="sign_up/signup.php" method="post"> <!-- Updated action path -->
					<h3>Sign Up</h3>
					<div class="row gtr-50 gtr-uniform">
						<div class="col-12">
							<input type="text" name="username" id="signupUsername" value="" placeholder="Username" required />
						</div>
						<div class="col-12">
							<input type="email" name="email" id="signupEmail" value="" placeholder="Email" required />
						</div>
						<div class="col-12">
							<input type="password" name="password" id="signupPassword" value="" placeholder="Password" required />
						</div>
						<div class="col-12">
							<ul class="actions">
								<li><input type="submit" value="Sign Up" class="primary" /></li>
								<li><input type="button" value="Close" id="closeSignup" /></li>
							</ul>
						</div>
					</div>
				</form>
				<?php
				if (isset($_GET['signup_error'])) {
					echo '<p class="error">' . htmlspecialchars($_GET['signup_error']) . '</p>';
				}
				?>
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

			<!-- Main -->
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
								<img src="images\Untitled design (3).png" alt="Custom Logistics Solutions" style="object-fit: cover; width: 100%; height: 200px;" />
							</span>
							<h3>Custom Logistics Solutions</h3>
							<p>Our team of experts designs tailored logistics strategies to meet your unique business needs. From inventory management to last-mile delivery, we provide end-to-end solutions that drive efficiency and reduce costs.</p>
							<ul class="actions special">
								<li><a href="#" class="button alt">Learn More</a></li>
							</ul>
						</section>

					</div>
				</div>
			</section>

			<!-- CTA -->
			<section id="cta">
				<h2>Request a Free Quote</h2>
				<p>Get in touch with our logistics experts for a customized shipping solution tailored to your needs.</p>

				<form action="login.php" method="post">
					<div class="row gtr-50 gtr-uniform">
						<div class="col-8 col-12-mobilep">
							<input type="email" name="email" id="email" placeholder="Email Address" />
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
					<li>&copy; TruckLogix. All rights reserved.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
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
				$('#loginButton').click(function(e) {
					e.preventDefault();
					$('#loginForm').fadeIn(300);
				});

				$('#closeLogin').click(function() {
					$('#loginForm').fadeOut(300);
				});

				$('#signupButton').click(function(e) {
					e.preventDefault();
					$('#signupForm').fadeIn(300);
				});

				$('#closeSignup').click(function() {
					$('#signupForm').fadeOut(300);
				});

				// Close forms when clicking outside
				$(document).mouseup(function(e) {
					var loginContainer = $("#loginForm");
					var signupContainer = $("#signupForm");
					if (!loginContainer.is(e.target) && loginContainer.has(e.target).length === 0) {
						loginContainer.fadeOut(300);
					}
					if (!signupContainer.is(e.target) && signupContainer.has(e.target).length === 0) {
						signupContainer.fadeOut(300);
					}
				});

				// Prevent form from closing on submission
				$('#loginForm form, #signupForm form').submit(function() {
					return true; // Allow form submission
				});
			});
		</script>
	</body>
</html>

<?php
?>