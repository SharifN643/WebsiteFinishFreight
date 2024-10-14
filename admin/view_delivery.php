<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in and has admin role
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Include database connection
require_once '../db_connect.php';

$delivery_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch delivery details
$stmt = $conn->prepare("SELECT * FROM deliveries WHERE id = ?");
$stmt->bind_param("i", $delivery_id);
$stmt->execute();
$result = $stmt->get_result();
$delivery = $result->fetch_assoc();

if (!$delivery) {
    $_SESSION['error_message'] = "Delivery not found.";
    header("Location: manage_deliveries.php");
    exit();
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>View Delivery - TruckLogix Admin</title>
		<meta charset="utf-8" />	
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
	</head>
	<body class="is-preload">
		<div id="page-wrapper">

			<!-- Header -->
			<header id="header">
				<h1><a href="dashboard.php">TruckLogix Admin</a></h1>
				<nav id="nav">
					<ul>
						<li><a href="dashboard.php">Dashboard</a></li>
						<li><a href="manage_deliveries.php">Manage Deliveries</a></li>
						<li><a href="manage_users.php">Manage Users</a></li>
						<li><a href="../logout.php" class="button">Logout</a></li>
					</ul>
				</nav>
			</header>

			<!-- Main -->
			<section id="main" class="container">
				<header>
					<h2>View Delivery</h2>
				</header>
				
				<div class="box">
					<h3>Delivery Details</h3>
					<table class="alt">
						<tbody>
							<tr>
								<th>ID:</th>
								<td><?php echo $delivery['id']; ?></td>
							</tr>
							<tr>
								<th>Full Name:</th>
								<td><?php echo htmlspecialchars($delivery['fullName']); ?></td>
							</tr>
							<tr>
								<th>Item Type:</th>
								<td><?php echo htmlspecialchars($delivery['itemType']); ?></td>
							</tr>
							<tr>
								<th>Pickup Date:</th>
								<td><?php echo $delivery['pickupDate']; ?></td>
							</tr>
							<tr>
								<th>Status:</th>
								<td><?php echo htmlspecialchars($delivery['status']); ?></td>
							</tr>
							<!-- Add more fields as needed -->
						</tbody>
					</table>
					<ul class="actions">
						<li><a href="edit_delivery.php?id=<?php echo $delivery['id']; ?>" class="button">Edit Delivery</a></li>
						<li><a href="manage_deliveries.php" class="button alt">Back to Deliveries</a></li>
					</ul>
				</div>
			</section>

			<!-- Footer -->
			<footer id="footer">
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
