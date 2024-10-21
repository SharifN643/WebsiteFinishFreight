<?php
session_start();
require_once 'db_connect.php';

$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$user_role = $_SESSION['role'] ?? '';
$username = $_SESSION['username'] ?? '';
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Our Services - TruckLogix</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="is-preload">
		<div id="page-wrapper">
			<!-- Header -->
			<header id="header">
				<h1><a href="index.php">TruckLogix</a></h1>
				<nav id="nav">
					<ul>
						<li><a href="index.php">Home</a></li>
						<li><a href="services.php">Services</a></li>
						<?php if ($is_logged_in): ?>
							<li><a href="#" class="button">Welcome, <?php echo htmlspecialchars($username); ?></a></li>
							<li><a href="logout.php" class="button alt">Logout</a></li>
						<?php else: ?>
							<li><a href="index.php#loginForm" class="button alt">Login</a></li>
							<li><a href="index.php#signupForm" class="button alt">Sign Up</a></li>
						<?php endif; ?>
					</ul>
				</nav>
			</header>

			<!-- Main -->
			<section id="main" class="container">
				<header>
					<h2>Our Services</h2>
					<p>Comprehensive logistics solutions for your business needs</p>
				</header>
				<div class="box">
					<h3>Freight Transport</h3>
					<p>Our nationwide network of trucks ensures reliable and efficient transportation of your goods across the country.</p>
				</div>
				<div class="box">
					<h3>Warehousing</h3>
					<p>State-of-the-art warehouses offering secure storage and efficient inventory management to streamline your supply chain.</p>
				</div>
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
	</body>
</html>
