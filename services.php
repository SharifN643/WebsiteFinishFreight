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
		<title>Our Pricing - PEN Express</title>
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
					<h2>Our Pricing</h2>
					<p>Comprehensive logistics solutions for your business needs</p>
				</header>
				<div class="box">
					<h3>Freight Transport</h3>
					<h4>Basic Transport Fees:</h4>
					<h5>Local Transport (within city limits):</h5>
					<ul>
						<li>Up to 10 kg: RM 50</li>
						<li>10 kg to 50 kg: RM 80</li>
						<li>Over 50 kg: RM 120</li>
					</ul>
					<h5>Regional Transport (outside city limits but within state):</h5>
					<ul>
						<li>Up to 10 kg: RM 100</li>
						<li>10 kg to 50 kg: RM 150</li>
						<li>Over 50 kg: RM 200</li>
					</ul>
					<h5>Interstate Transport:</h5>
					<ul>
						<li>Up to 10 kg: RM 150</li>
						<li>10 kg to 50 kg: RM 200</li>
						<li>Over 50 kg: RM 250</li>
					</ul>
					<h4>Pickup and Delivery Time Slots:</h4>
					<ul>
						<li>Early Morning (9 AM to 12 PM): RM 20</li>
						<li>Afternoon (12 PM to 3 PM): RM 10</li>
						<li>Evening (3 PM to 6 PM): RM 15</li>
					</ul>
					<h4>Item Type Additional Charges:</h4>
					<ul>
						<li>Documents: Included in the standard fee</li>
						<li>Electronics: RM 30 additional</li>
						<li>Furniture: RM 50 additional</li>
						<li>Fragile Items: RM 50 additional (plus special handling)</li>
					</ul>
					<h4>Special Handling Charges:</h4>
					<ul>
						<li>Special Handling Instructions: RM 30 (if specified)</li>
					</ul>
					<h4>Additional Services:</h4>
					<ul>
						<li>Add Insurance for Delivery: RM 20 (flat fee)</li>
						<li>Require Packaging Services: RM 15 per item</li>
					</ul>
					<p><strong>Important Notes:</strong> Prices may vary based on specific service requests and additional needs. For personalized quotes or further inquiries, please contact us directly.</p>
				</div>
				<div class="box">
					<h3>Freight Transport Cost Calculator</h3>
					<form id="freightCalculator">
						<div class="row gtr-uniform gtr-50">
							<div class="col-6 col-12-mobilep">
								<label for="transportType">Transport Type:</label>
								<select name="transportType" id="transportType">
									<option value="local">Local (within city limits)</option>
									<option value="regional">Regional (within state)</option>
									<option value="interstate">Interstate</option>
								</select>
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="weight">Weight (kg):</label>
								<input type="number" name="weight" id="weight" value="0" min="0" step="0.1" required>
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="timeSlot">Time Slot:</label>
								<select name="timeSlot" id="timeSlot">
									<option value="early">Early Morning (9 AM to 12 PM)</option>
									<option value="afternoon">Afternoon (12 PM to 3 PM)</option>
									<option value="evening">Evening (3 PM to 6 PM)</option>
								</select>
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="itemType">Item Type:</label>
								<select name="itemType" id="itemType">
									<option value="documents">Documents</option>
									<option value="electronics">Electronics</option>
									<option value="furniture">Furniture</option>
									<option value="fragile">Fragile Items</option>
								</select>
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="specialHandling">Special Handling:</label>
								<input type="checkbox" id="specialHandling" name="specialHandling">
								<label for="specialHandling">Yes</label>
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="insurance">Add Insurance:</label>
								<input type="checkbox" id="insurance" name="insurance">
								<label for="insurance">Yes</label>
							</div>
							<div class="col-12">
								<label for="packaging">Packaging Services:</label>
								<input type="number" name="packaging" id="packaging" value="0" min="0" step="1">
							</div>
							<div class="col-12">
								<ul class="actions">
									<li><input type="submit" value="Calculate Cost" class="primary" /></li>
								</ul>
							</div>
						</div>
					</form>
					<div id="calculationResult" style="display: none;">
						<h4>Estimated Cost: <span id="totalCost"></span></h4>
					</div>
				</div>
				<div class="box">
					<h3>Normal Storage</h3>
					<h4>Base Pricing (per day):</h4>
					<ul>
						<li>Small Items (up to 10kg): RM 5</li>
						<li>Medium Items (10kg to 50kg): RM 10</li>
						<li>Large Items (50kg and above): RM 20</li>
					</ul>
					<h4>Additional Services:</h4>
					<ul>
						<li>Packing: RM 10 per item</li>
						<li>Labeling: RM 5 per item</li>
						<li>Other Services: Pricing varies (please inquire)</li>
					</ul>
					<h4>Discounts:</h4>
					<ul>
						<li>Long-Term Storage (over 30 days): 5% off</li>
						<li>Long-Term Storage (over 60 days): 10% off</li>
					</ul>
					<h4>Delivery/Pickup:</h4>
					<ul>
						<li>Within city limits: RM 50</li>
						<li>Outside city limits: RM 100</li>
					</ul>
					<p><strong>Important Notes:</strong> Prices may vary based on specific service requests and additional needs. For personalized quotes or further inquiries, please contact us directly.</p>
				</div>
				<div class="box">
					<h3>Normal Storage Cost Calculator</h3>
					<form id="normalStorageCalculator">
						<div class="row gtr-uniform gtr-50">
							<div class="col-6 col-12-mobilep">
								<label for="normalItemSize">Item Size:</label>
								<select name="normalItemSize" id="normalItemSize">
									<option value="small">Small (up to 10kg)</option>
									<option value="medium">Medium (10kg to 50kg)</option>
									<option value="large">Large (50kg and above)</option>
								</select>
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="normalStorageDays">Number of Days:</label>
								<input type="number" name="normalStorageDays" id="normalStorageDays" value="1" min="1" step="1" required>
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="normalNumberOfItems">Number of Items:</label>
								<input type="number" name="normalNumberOfItems" id="normalNumberOfItems" value="1" min="1" step="1" required>
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="normalPacking">Packing:</label>
								<input type="checkbox" id="normalPacking" name="normalPacking">
								<label for="normalPacking">Yes</label>
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="normalLabeling">Labeling:</label>
								<input type="checkbox" id="normalLabeling" name="normalLabeling">
								<label for="normalLabeling">Yes</label>
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="normalDelivery">Delivery:</label>
								<select name="normalDelivery" id="normalDelivery">
									<option value="none">No Delivery</option>
									<option value="within">Within City Limits</option>
									<option value="outside">Outside City Limits</option>
								</select>
							</div>
							<div class="col-12">
								<ul class="actions">
									<li><input type="submit" value="Calculate Cost" class="primary" /></li>
								</ul>
							</div>
						</div>
					</form>
					<div id="normalStorageCalculationResult" style="display: none;">
						<h4>Estimated Cost: <span id="normalStorageTotalCost"></span></h4>
					</div>
				</div>
				<div class="box">
					<h3>Temperature-Controlled Storage</h3>
					<h4>Base Pricing (per day):</h4>
					<ul>
						<li>Small Items (up to 10kg): RM 15</li>
						<li>Medium Items (10kg to 50kg): RM 30</li>
						<li>Large Items (50kg and above): RM 60</li>
					</ul>
					<h4>Temperature Control:</h4>
					<ul>
						<li>Standard Range (2°C to 8°C): Included</li>
						<li>Custom Temperature: RM 10 per day</li>
					</ul>
					<h4>Additional Services:</h4>
					<ul>
						<li>Packing: RM 15 per item</li>
						<li>Labeling: RM 7 per item</li>
						<li>Other Services: Pricing varies (please inquire)</li>
					</ul>
					<h4>Discounts:</h4>
					<ul>
						<li>Long-Term Storage (over 30 days): 5% off</li>
						<li>Long-Term Storage (over 60 days): 10% off</li>
					</ul>
					<h4>Delivery/Pickup:</h4>
					<ul>
						<li>Within city limits: RM 80</li>
						<li>Outside city limits: RM 150</li>
					</ul>
					<p><strong>Important Notes:</strong> Prices may vary based on specific service requests and additional needs. For personalized quotes or further inquiries, please contact us directly.</p>
				</div>
				<div class="box">
					<h3>Temperature-Controlled Storage Cost Calculator</h3>
					<form id="storageCalculator">
						<div class="row gtr-uniform gtr-50">
							<div class="col-6 col-12-mobilep">
								<label for="itemSize">Item Size:</label>
								<select name="itemSize" id="itemSize">
									<option value="small">Small (up to 10kg)</option>
									<option value="medium">Medium (10kg to 50kg)</option>
									<option value="large">Large (50kg and above)</option>
								</select>
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="storageDays">Number of Days:</label>
								<input type="number" name="storageDays" id="storageDays" value="1" min="1" step="1" required>
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="numberOfItems">Number of Items:</label>
								<input type="number" name="numberOfItems" id="numberOfItems" value="1" min="1" step="1" required>
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="customTemp">Custom Temperature:</label>
								<input type="checkbox" id="customTemp" name="customTemp">
								<label for="customTemp">Yes</label>
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="packing">Packing:</label>
								<input type="checkbox" id="packing" name="packing">
								<label for="packing">Yes</label>
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="labeling">Labeling:</label>
								<input type="checkbox" id="labeling" name="labeling">
								<label for="labeling">Yes</label>
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="delivery">Delivery:</label>
								<select name="delivery" id="delivery">
									<option value="none">No Delivery</option>
									<option value="within">Within City Limits</option>
									<option value="outside">Outside City Limits</option>
								</select>
							</div>
							<div class="col-12">
								<ul class="actions">
									<li><input type="submit" value="Calculate Cost" class="primary" /></li>
								</ul>
							</div>
						</div>
					</form>
					<div id="storageCalculationResult" style="display: none;">
						<h4>Estimated Cost: <span id="storageTotalCost"></span></h4>
					</div>
				</div>
				<div class="box">
					<h3>Contact Us</h3>
					<p>For more information, personalized quotes, or to request our services, please use our quote request form.</p>
					<a href="quote/get_quote.php" class="button primary">Get a Quote</a>
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
		<script>
		document.getElementById('freightCalculator').addEventListener('submit', function(e) {
			e.preventDefault();
			
			const transportType = document.getElementById('transportType').value;
			const weight = parseFloat(document.getElementById('weight').value);
			const timeSlot = document.getElementById('timeSlot').value;
			const itemType = document.getElementById('itemType').value;
			const specialHandling = document.getElementById('specialHandling').checked;
			const insurance = document.getElementById('insurance').checked;
			const packaging = parseInt(document.getElementById('packaging').value);

			let totalCost = 0;

			// Basic transport fee
			if (transportType === 'local') {
				if (weight <= 10) totalCost += 50;
				else if (weight <= 50) totalCost += 80;
				else totalCost += 120;
			} else if (transportType === 'regional') {
				if (weight <= 10) totalCost += 100;
				else if (weight <= 50) totalCost += 150;
				else totalCost += 200;
			} else if (transportType === 'interstate') {
				if (weight <= 10) totalCost += 150;
				else if (weight <= 50) totalCost += 200;
				else totalCost += 250;
			}

			// Time slot fee
			if (timeSlot === 'early') totalCost += 20;
			else if (timeSlot === 'afternoon') totalCost += 10;
			else if (timeSlot === 'evening') totalCost += 15;

			// Item type additional charges
			if (itemType === 'electronics') totalCost += 30;
			else if (itemType === 'furniture') totalCost += 50;
			else if (itemType === 'fragile') totalCost += 50;

			// Special handling
			if (specialHandling) totalCost += 30;

			// Insurance
			if (insurance) totalCost += 20;

			// Packaging
			totalCost += packaging * 15;

			document.getElementById('totalCost').textContent = 'RM ' + totalCost.toFixed(2);
			document.getElementById('calculationResult').style.display = 'block';
		});

		document.getElementById('storageCalculator').addEventListener('submit', function(e) {
			e.preventDefault();
			
			const itemSize = document.getElementById('itemSize').value;
			const storageDays = parseInt(document.getElementById('storageDays').value);
			const customTemp = document.getElementById('customTemp').checked;
			const packing = document.getElementById('packing').checked;
			const labeling = document.getElementById('labeling').checked;
			const delivery = document.getElementById('delivery').value;

			// New input for number of items
			const numberOfItems = parseInt(document.getElementById('numberOfItems').value) || 1;

			let totalCost = 0;

			// Base pricing per day
			if (itemSize === 'small') totalCost += 15 * storageDays;
			else if (itemSize === 'medium') totalCost += 30 * storageDays;
			else if (itemSize === 'large') totalCost += 60 * storageDays;

			// Custom temperature
			if (customTemp) totalCost += 10 * storageDays;

			// Additional services (per item)
			if (packing) totalCost += 15 * numberOfItems;
			if (labeling) totalCost += 7 * numberOfItems;

			// Delivery
			if (delivery === 'within') totalCost += 80;
			else if (delivery === 'outside') totalCost += 150;

			// Apply discounts
			if (storageDays > 60) totalCost *= 0.9; // 10% off
			else if (storageDays > 30) totalCost *= 0.95; // 5% off

			document.getElementById('storageTotalCost').textContent = 'RM ' + totalCost.toFixed(2);
			document.getElementById('storageCalculationResult').style.display = 'block';
		});

		document.getElementById('normalStorageCalculator').addEventListener('submit', function(e) {
			e.preventDefault();
			
			const itemSize = document.getElementById('normalItemSize').value;
			const storageDays = parseInt(document.getElementById('normalStorageDays').value);
			const numberOfItems = parseInt(document.getElementById('normalNumberOfItems').value);
			const packing = document.getElementById('normalPacking').checked;
			const labeling = document.getElementById('normalLabeling').checked;
			const delivery = document.getElementById('normalDelivery').value;

			let totalCost = 0;

			// Base pricing per day
			if (itemSize === 'small') totalCost += 5 * storageDays * numberOfItems;
			else if (itemSize === 'medium') totalCost += 10 * storageDays * numberOfItems;
			else if (itemSize === 'large') totalCost += 20 * storageDays * numberOfItems;

			// Additional services (per item)
			if (packing) totalCost += 10 * numberOfItems;
			if (labeling) totalCost += 5 * numberOfItems;

			// Delivery
			if (delivery === 'within') totalCost += 50;
			else if (delivery === 'outside') totalCost += 100;

			// Apply discounts
			if (storageDays > 60) totalCost *= 0.9; // 10% off
			else if (storageDays > 30) totalCost *= 0.95; // 5% off

			document.getElementById('normalStorageTotalCost').textContent = 'RM ' + totalCost.toFixed(2);
			document.getElementById('normalStorageCalculationResult').style.display = 'block';
		});
		</script>
	</body>
</html>
