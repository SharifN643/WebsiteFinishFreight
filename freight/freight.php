<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit();
}

// Include database connection
require_once '../db_connect.php';

// Function to get recent deliveries (for admin view)
function getRecentDeliveries($conn, $limit = 5) {
    $sql = "SELECT id, fullName, itemType, pickupDate, status FROM deliveries ORDER BY pickupDate DESC LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get recent deliveries if user is admin
$recentDeliveries = $isAdmin ? getRecentDeliveries($conn) : [];
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Freight Transport - TruckLogix</title>
		<meta charset="utf-8" />	
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" /> <!-- Ensure the path is correct -->
		<script src="../assets/js/jquery.min.js"></script> <!-- Ensure the path is correct -->
		<style>
			.service-options .checkbox-wrapper {
				display: flex;
				align-items: center;
				margin-bottom: 10px;
			}
			.service-options input[type="checkbox"] {
				appearance: auto;
				-webkit-appearance: checkbox;
				width: 20px;
				height: 20px;
				margin-right: 10px;
			}
			.service-options label {
				display: inline;
				margin-bottom: 0;
			}
			.admin-panel {
				background-color: #f8f8f8;
				border: 1px solid #ddd;
				padding: 20px;
				margin-bottom: 20px;
				border-radius: 5px;
			}
			.admin-panel h3 {
				margin-top: 0;
			}
			.admin-panel table {
				width: 100%;
			}
			.admin-panel th, .admin-panel td {
				padding: 10px;
				text-align: left;
				border-bottom: 1px solid #ddd;
			}
			.admin-panel th {
				background-color: #f2f2f2;
			}
		</style>
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
								<li><a href="freight.php">Freight Transport</a></li>
								<li><a href="../warehousing.php">Warehousing</a></li>
								<li><a href="../logistics.php">Logistics Solutions</a></li>
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
					<h2>Freight Transport Services</h2>
					<p>Reliable and efficient freight solutions for your business</p>
				</header>

				<div class="box">
					<h3>Request Delivery</h3>
					<form action="process_delivery.php" method="post">
						<!-- Customer Information -->
						<h4>Customer Information</h4>
						<label for="fullName">Full Name:</label>
						<input type="text" id="fullName" name="fullName" required>
						
						<label for="companyName">Company Name (Optional):</label>
						<input type="text" id="companyName" name="companyName">
						
						<label for="email">Email Address:</label>
						<input type="email" id="email" name="email" required>
						
						<label for="phone">Phone Number:</label>
						<input type="tel" id="phone" name="phone" required>

						<!-- Pickup Information -->
						<h4>Pickup Information</h4>
						<label for="pickupAddress">Pickup Address:</label>
						<textarea id="pickupAddress" name="pickupAddress" required></textarea>
						
						<label for="pickupDate">Pickup Date:</label>
						<input type="date" id="pickupDate" name="pickupDate" required>
						
						<label for="pickupTime">Preferred Pickup Time:</label>
						<select id="pickupTime" name="pickupTime">
							<option value="9-12">9 AM to 12 PM</option>
							<option value="12-3">12 PM to 3 PM</option>
							<option value="3-6">3 PM to 6 PM</option>
						</select>
						
						<label for="pickupContact">Contact Person at Pickup Location:</label>
						<input type="text" id="pickupContact" name="pickupContact">
						
						<label for="pickupContactNumber">Contact Number for Pickup Location:</label>
						<input type="tel" id="pickupContactNumber" name="pickupContactNumber">

						<!-- Delivery Information -->
						<h4>Delivery Information</h4>
						<label for="deliveryAddress">Delivery Address:</label>
						<textarea id="deliveryAddress" name="deliveryAddress" required></textarea>
						
						<label for="deliveryDate">Delivery Date (Optional):</label>
						<input type="date" id="deliveryDate" name="deliveryDate">
						
						<label for="deliveryTime">Preferred Delivery Time (Optional):</label>
						<select id="deliveryTime" name="deliveryTime">
							<option value="9-12">9 AM to 12 PM</option>
							<option value="12-3">12 PM to 3 PM</option>
							<option value="3-6">3 PM to 6 PM</option>
						</select>

						<!-- Item Information -->
						<h4>Item Information</h4>
						<label for="itemType">Item Type:</label>
						<select id="itemType" name="itemType">
							<option value="documents">Documents</option>
							<option value="electronics">Electronics</option>
							<option value="furniture">Furniture</option>
							<option value="fragile">Fragile items</option>
						</select>
						
						<label for="itemDescription">Item Description:</label>
						<textarea id="itemDescription" name="itemDescription"></textarea>
						
						<label for="itemQuantity">Item Quantity:</label>
						<input type="number" id="itemQuantity" name="itemQuantity" required>
						
						<label for="itemWeight">Weight of Item (kg):</label>
						<input type="number" id="itemWeight" name="itemWeight" required>
						
						<label for="itemDimensions">Dimensions of Item (cm):</label>
						<input type="text" id="itemDimensions" name="itemDimensions" placeholder="L x W x H">
						
						<label for="specialInstructions">Special Handling Instructions:</label>
						<textarea id="specialInstructions" name="specialInstructions"></textarea>

						<!-- Service Options -->
						<h4>Service Options</h4>
						<div class="service-options">		
							<div class="checkbox-wrapper">
								<input type="checkbox" id="insurance" name="insurance" value="yes">
								<label for="insurance">Add insurance for this delivery</label>
							</div>
							<div class="checkbox-wrapper">
								<input type="checkbox" id="packaging" name="packaging" value="yes">
								<label for="packaging">Require packaging services</label>
							</div>
						</div>

						<!-- Additional Information -->
						<h4>Additional Information</h4>
						<textarea name="additionalInfo" placeholder="Any other specific notes"></textarea>

						<!-- Submit Button -->
						<input type="submit" value="Request Delivery" class="primary">
					</form>

					<!-- View Booking Button -->
					<div style="margin-top: 20px;">
						<a href="view_booking.php" class="button">View Booking</a>
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
				</ul>
				<ul class="copyright">
					<li>&copy; TruckLogix. All rights reserved.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
				</ul>
			</footer>

		</div>

		<!-- Scripts -->
		<script src="../assets/js/jquery.min.js"></script> <!-- Ensure the path is correct -->
		<script src="../assets/js/jquery.dropotron.min.js"></script>
		<script src="../assets/js/jquery.scrollex.min.js"></script>
		<script src="../assets/js/browser.min.js"></script>
		<script src="../assets/js/breakpoints.min.js"></script>
		<script src="../assets/js/util.js"></script>
		<script src="../assets/js/main.js"></script>

	</body>
</html>
