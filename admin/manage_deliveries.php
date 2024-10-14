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

// Function to get all deliveries
function getAllDeliveries($conn) {
    $sql = "SELECT id, fullName, itemType, pickupDate, status FROM deliveries ORDER BY pickupDate DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

$allDeliveries = getAllDeliveries($conn);

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Manage Deliveries - TruckLogix Admin</title>
		<meta charset="utf-8" />	
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
		<script src="../assets/js/jquery.min.js"></script>
		<style>
			.admin-table {
				width: 100%;
				border-collapse: collapse;
			}
			.admin-table th, .admin-table td {
				padding: 10px;
				border: 1px solid #ddd;
			}
			.admin-table th {
				background-color: #f2f2f2;
				font-weight: bold;
			}
		</style>
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
					<h2>Manage Deliveries</h2>
					<p>View and manage all deliveries</p>
				</header>
				
				<div class="box">
					<h3>All Deliveries</h3>
					<table class="admin-table">
						<thead>
							<tr>
								<th>ID</th>
								<th>Customer</th>
								<th>Item Type</th>
								<th>Pickup Date</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($allDeliveries as $delivery): ?>
							<tr>
								<td><?php echo $delivery['id']; ?></td>
								<td><?php echo htmlspecialchars($delivery['fullName']); ?></td>
								<td><?php echo htmlspecialchars($delivery['itemType']); ?></td>
								<td><?php echo $delivery['pickupDate']; ?></td>
								<td><?php echo htmlspecialchars($delivery['status']); ?></td>
								<td>
									<a href="edit_delivery.php?id=<?php echo $delivery['id']; ?>" class="button small">Edit</a>
									<a href="view_delivery.php?id=<?php echo $delivery['id']; ?>" class="button small alt">View</a>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</section>

			<!-- Footer -->
			<footer id="footer">
				<ul class="icons">
					<li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
					<li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
					<li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
					<li><a href="#" class="icon brands fa-github"><span class="label">Github</span></a></li>
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
