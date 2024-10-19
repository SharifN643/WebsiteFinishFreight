<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Normal Storage Form - TruckLogix</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
		<link rel="stylesheet" href="../assets/css/fontawesome-all.min.css" />
		<script src="../assets/js/jquery.min.js"></script>
	</head>
	<body class="is-preload">
		<div id="page-wrapper">

			<!-- Header -->
			<header id="header" style="height: 4em; display: flex; align-items: center;">
				<h1 style="margin: 0; padding: 0;">
					<a href="../index.php" class="logo" style="display: flex; align-items: center; height: 100%;">
						<img src="../images/PEN_Logo-removebg-preview (2).png" alt="TruckLogix" style="max-height: 3em; width: auto; vertical-align: middle;">
					</a>
				</h1>
				<nav id="nav" style="margin-left: auto;">
					<ul>
						<li><a href="../index.php">Home</a></li>
						<li>
							<a href="#" class="icon solid fa-angle-down">Services</a>
							<ul>
								<li><a href="../freight.php">Freight Transport</a></li>
								<li><a href="warehousing.php">Warehousing</a></li>
								<li><a href="../logistics.php">Logistics Solutions</a></li>
							</ul>
						</li>
						<li><a href="../contact.php">Contact</a></li>
					</ul>
				</nav>
			</header>

			<!-- Main -->
			<section id="main" class="container">
				<header>
					<h2>Normal Storage Request Form</h2>
					<p>Request storage for your goods</p>
				</header>
				<div class="box">
					<form method="post" action="../warehouse/process_normal_storage.php">
							<h3>Basic Information</h3>
							<div class="row gtr-50 gtr-uniform">
								<div class="col-6 col-12-mobilep">
									<input type="text" name="customerName" id="customerName" value="" placeholder="Customer Name" required />
								</div>
								<div class="col-6 col-12-mobilep">
									<input type="text" name="companyName" id="companyName" value="" placeholder="Company Name" />
								</div>
								<div class="col-6 col-12-mobilep">
									<input type="email" name="email" id="email" value="" placeholder="Email" required />
								</div>
								<div class="col-6 col-12-mobilep">
									<input type="tel" name="phoneNumber" id="phoneNumber" value="" placeholder="Phone Number" required />
								</div>
								<div class="col-12">
									<textarea name="billingAddress" id="billingAddress" placeholder="Billing Address" rows="3" required></textarea>
								</div>
								<div class="col-12">
									<textarea name="deliveryAddress" id="deliveryAddress" placeholder="Delivery Address" rows="3" required></textarea>
								</div>
							</div>

							<h3>Item Information</h3>
							<div class="row gtr-50 gtr-uniform">
								<div class="col-12">
									<input type="text" name="itemDescription" id="itemDescription" value="" placeholder="Item Description" required />
								</div>
								<div class="col-4 col-12-mobilep">
									<input type="number" name="quantity" id="quantity" value="" placeholder="Quantity" required />
								</div>
								<div class="col-4 col-12-mobilep">
									<input type="number" name="weight" id="weight" value="" placeholder="Weight" required />
								</div>
								<div class="col-4 col-12-mobilep">
									<input type="text" name="dimensions" id="dimensions" value="" placeholder="Dimensions (L x W x H)" required />
								</div>
							</div>

							<h3>Service Options</h3>
							<div class="row gtr-50 gtr-uniform">
								<div class="col-12">
									<input type="text" name="storageDuration" id="storageDuration" value="" placeholder="Desired Storage Duration" required />
								</div>
								<div class="col-12">
									<label>Additional Services Needed:</label>
									<ul class="actions special">
										<li><input type="checkbox" id="packing" name="additionalServices[]" value="Packing"><label for="packing">Packing</label></li>
										<li><input type="checkbox" id="labeling" name="additionalServices[]" value="Labeling"><label for="labeling">Labeling</label></li>
										<li><input type="checkbox" id="otherServices" name="additionalServices[]" value="Other"><label for="otherServices">Other</label></li>
									</ul>
								</div>
								<div class="col-12">
									<input type="text" name="otherServices" id="otherServices" value="" placeholder="Other Services" />
								</div>
							</div>

							<h3>Delivery/Pickup Information</h3>
							<div class="row gtr-50 gtr-uniform">
								<div class="col-12">
									<select name="deliveryMethod" id="deliveryMethod" required>
										<option value="">- Preferred Delivery Method -</option>
										<option value="Standard">Standard</option>
										<option value="Express">Express</option>
										<option value="Other">Other</option>
									</select>
								</div>
								<div class="col-6 col-12-mobilep">
									<label for="deliveryDate">Delivery Date:</label>
									<input type="date" name="deliveryDate" id="deliveryDate" value="" required />
								</div>
								<div class="col-6 col-12-mobilep">
									<label for="pickupDate">Pickup Date:</label>
									<input type="date" name="pickupDate" id="pickupDate" value="" required />
								</div>
							</div>

							<h3>Additional Notes/Comments</h3>
							<div class="row gtr-50 gtr-uniform">
								<div class="col-12">
									<textarea name="additionalNotes" id="additionalNotes" placeholder="Enter any additional notes or comments" rows="6"></textarea>
								</div>
								<div class="col-12">
									<ul class="actions special">
										<li><input type="submit" value="Submit Request" /></li>
									</ul>
								</div>
							</div>
						</form>
					</div>
				</section>

			<!-- Footer -->
			<footer id="footer">
				<ul class="icons">
					<li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
					<li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
					<li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
					<li><a href="#" class="icon brands fa-github"><span class="label">Github</span></a></li>
					<li><a href="#" class="icon brands fa-dribbble"><span class="label">Dribbble</span></a></li>
					<li><a href="#" class="icon brands fa-google-plus"><span class="label">Google+</span></a></li>
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

	</body>
</html>
