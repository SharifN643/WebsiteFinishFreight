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

// Function to get recent deliveries
function getRecentDeliveries($conn, $limit = 10) {
    $sql = "SELECT id, fullName, itemType, pickupDate, status FROM deliveries ORDER BY pickupDate DESC LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to get delivery statistics
function getDeliveryStats($conn) {
    $stats = [];
    
    // Total deliveries
    $sql = "SELECT COUNT(*) as total FROM deliveries";
    $result = $conn->query($sql);
    $stats['total_deliveries'] = $result->fetch_assoc()['total'];
    
    // Pending deliveries
    $sql = "SELECT COUNT(*) as pending FROM deliveries WHERE status = 'Pending'";
    $result = $conn->query($sql);
    $stats['pending_deliveries'] = $result->fetch_assoc()['pending'];
    
    // In-transit deliveries
    $sql = "SELECT COUNT(*) as in_transit FROM deliveries WHERE status = 'In Transit'";
    $result = $conn->query($sql);
    $stats['in_transit_deliveries'] = $result->fetch_assoc()['in_transit'];
    
    return $stats;
}

$recentDeliveries = getRecentDeliveries($conn);
$deliveryStats = getDeliveryStats($conn);

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Admin Freight Management - TruckLogix</title>
		<meta charset="utf-8" />	
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
		<script src="../assets/js/jquery.min.js"></script>
		<style>
			.admin-dashboard {
				display: flex;
				flex-wrap: wrap;
				gap: 20px;
			}
			.admin-card {
				flex: 1 1 300px;
				background-color: #f8f8f8;
				border: 1px solid #ddd;
				padding: 20px;
				border-radius: 5px;
			}
			.stat-number {
				font-size: 2em;
				font-weight: bold;
				color: #4a4a4a;
			}
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
				<h1><a href="admin_dashboard.php">TruckLogix Admin</a></h1>
				<nav id="nav">
					<ul>
						<li><a href="admin_dashboard.php">Dashboard</a></li>
						<li><a href="admin_freight.php">Freight Management</a></li>
						<li><a href="admin_users.php">User Management</a></li>
						<li><a href="../logout.php" class="button">Logout</a></li>
					</ul>
				</nav>
			</header>

			<!-- Main -->
			<section id="main" class="container">
				<header>
					<h2>Freight Management</h2>
					<p>Manage deliveries and freight operations</p>
				</header>
				
				<div class="box">
					<div class="admin-dashboard">
						<div class="admin-card">
							<h3>Delivery Statistics</h3>
							<p>Total Deliveries: <span class="stat-number"><?php echo $deliveryStats['total_deliveries']; ?></span></p>
							<p>Pending Deliveries: <span class="stat-number"><?php echo $deliveryStats['pending_deliveries']; ?></span></p>
							<p>In-Transit Deliveries: <span class="stat-number"><?php echo $deliveryStats['in_transit_deliveries']; ?></span></p>
						</div>
						<div class="admin-card">
							<h3>Quick Actions</h3>
							<ul>
								<li><a href="create_delivery.php" class="button">Create New Delivery</a></li>
								<li><a href="manage_deliveries.php" class="button">Manage All Deliveries</a></li>
								<li><a href="generate_report.php" class="button">Generate Report</a></li>
							</ul>
						</div>
					</div>
					
					<h3>Recent Deliveries</h3>
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
							<?php foreach ($recentDeliveries as $delivery): ?>
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
