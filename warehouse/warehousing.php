<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Warehousing Services - TruckLogix</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
		<link rel="stylesheet" href="../assets/css/fontawesome-all.min.css" />
		<script src="../assets/js/jquery.min.js"></script>
		<style>
			@media screen and (max-width: 1280px) {
				.actions.special {
					flex-direction: column;
				}
				.actions.special li {
					width: 100%;
					margin-bottom: 1em;
				}
				.button.large {
					width: 100%;
					text-align: center;
				}
			}

			@media screen and (max-width: 980px) {
				.col-6 {
					width: 100%;
					margin-bottom: 2em;
				}
			}

			@media screen and (max-width: 736px) {
				#header {
					flex-direction: column;
					height: auto;
					padding: 1em 0;
				}
				#header h1 {
					margin-bottom: 1em;
				}
				#nav {
					margin-left: 0;
				}
				.col-6 {
					width: 100%;
					margin-bottom: 1em;
				}
				.actions.special {
					flex-direction: column;
				}
				.actions.special li {
					margin-bottom: 1em;
				}
				.button.large {
					width: 100%;
					text-align: center;
				}
			}

			.button.large {
				white-space: normal;
				height: auto;
				line-height: 1.5em;
				padding: 0.75em 1.5em;
			}
		</style>
	</head>
	<body class="is-preload">
		<div id="page-wrapper">

			<!-- Header -->
			<header id="header" style="display: flex; align-items: center; flex-wrap: wrap; padding: 1em;">
				<h1 style="margin: 0; padding: 0;">
					<a href="index.php" class="logo" style="display: flex; align-items: center;">
						<img src="images/PEN_Logo-removebg-preview (2).png" alt="TruckLogix" style="max-height: 3em; width: auto; vertical-align: middle;">
					</a>
				</h1>
				<nav id="nav" style="margin-left: auto;">
					<ul>
						<li><a href="index.php">Home</a></li>
						<li>
							<a href="#" class="icon solid fa-angle-down">Services</a>
							<ul>
								<li><a href="freight.php">Freight Transport</a></li>
								<li><a href="warehouse/warehousing.php">Warehousing</a></li>
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
						<li><a href="#" class="button">Get a Quote</a></li>
						<li><a href="#" class="button alt">Login</a></li>
					</ul>
				</nav>
			</header>

			<!-- Main -->
			<section id="main" class="container">
				<header>
					<h2>Warehousing Services</h2>
					<p>Efficient storage and inventory management solutions</p>
				</header>
				<div class="box">
					<h3>Our Warehousing Solutions</h3>
					<p>At TruckLogix, we offer state-of-the-art warehousing services designed to optimize your supply chain and reduce operational costs. Our facilities are equipped with the latest technology to ensure the safety and efficient management of your inventory.</p>
					
					<!-- Functional buttons for Storage Forms and Viewing Bookings -->
					<div class="row">
						<div class="col-6 col-12-mobilep">
							<h4>Normal Storage</h4>
							<ul class="actions special" style="display: flex; flex-direction: column; align-items: flex-start;">
								<li style="width: 100%; margin-bottom: 1em;">
									<a href="normal_storage_form.php" class="button primary large" style="width: auto; min-width: 60%;">Request Normal Storage</a>
								</li>
								<li style="width: 100%;">
									<a href="view_normal_storage_bookings.php" class="button large" style="width: auto; min-width: 60%;">View Normal Bookings</a>
								</li>
							</ul>
						</div>
						<div class="col-6 col-12-mobilep">
							<h4>Temperature-Controlled Storage</h4>
							<ul class="actions special" style="display: flex; flex-direction: column; align-items: flex-start;">
								<li style="width: 100%; margin-bottom: 1em;">
									<a href="temperature_controlled_storage_form.php" class="button primary large" style="width: auto; min-width: 60%;">Request Temp-Controlled</a>
								</li>
								<li style="width: 100%;">
									<a href="view_temperature_controlled_bookings.php" class="button large" style="width: auto; min-width: 60%;">View Temp-Controlled Bookings</a>
								</li>
							</ul>
						</div>
					</div>
					
					<div class="row">
						<div class="col-6 col-12-mobilep">
							<h4>Storage Solutions</h4>
							<ul>
								<li>Short-term and long-term storage options</li>
								<li>Climate-controlled facilities</li>
								<li>Secure storage for high-value items</li>
								<li>Bulk storage capabilities</li>
							</ul>
						</div>
						<div class="col-6 col-12-mobilep">
							<h4>Inventory Management</h4>
							<ul>
								<li>Real-time inventory tracking</li>
								<li>Barcode and RFID technology</li>
								<li>Cycle counting and reconciliation</li>
								<li>Customized reporting and analytics</li>
							</ul>
						</div>
					</div>
				</div>
			</section>

			<!-- Footer -->
			<footer id="footer">
				<ul class="icons">
					<li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
					<li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
					<li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
					<li><a href="#" class="icon brands fa-linkedin"><span class="label">LinkedIn</span></a></li>
					<li><a href="#" class="icon brands fa-github"><span class="label">GitHub</span></a></li>
				</ul>
				<ul class="copyright">
					<li>&copy; TruckLogix. All rights reserved.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
				</ul>
			</footer>

		</div>

		<!-- Scripts -->
		<script src="../assets/js/jquery.min.js"></script>
		<script src="../assets/js/jquery.dropotron.min.js"></script>
		<script src="../assets/js/jquery.scrollex.min.js"></script>
		<script src="../assets/js/browser.min.js"></script>
		<script src="../assets/js/breakpoints.min.js"></script>
		<script src="../assets/js/util.js"></script>
		<script src="../assets/js/main.js"></script>

		<script>
			$(document).ready(function() {
				$('#nav > ul').dropotron({
					alignment: 'right',
					hideDelay: 350
				});
			});
		</script>

	</body>
</html>
