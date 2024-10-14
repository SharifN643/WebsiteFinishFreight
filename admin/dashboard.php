<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Dashboard is loading..."; // This line will help you determine if PHP is executing

// Check if user is logged in and has admin role
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Include database connection
require_once '../db_connect.php';

// Function to get recent freight requests
function getRecentFreightRequests($conn, $limit = 10) {
    $sql = "SELECT fr.request_id, u.username, fr.pickup_date, fr.created_at, 
            CASE 
                WHEN fr.delivery_date IS NULL THEN 'Pending'
                WHEN fr.delivery_date < CURDATE() THEN 'Delivered'
                ELSE 'In Transit'
            END AS status
            FROM freight_requests fr
            JOIN users u ON fr.user_id = u.user_id
            ORDER BY fr.created_at DESC LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to get freight request statistics
function getFreightRequestStats($conn) {
    $stats = [];
    
    // Total requests
    $sql = "SELECT COUNT(*) as total FROM freight_requests";
    $result = $conn->query($sql);
    $stats['total_requests'] = $result->fetch_assoc()['total'];
    
    // Pending requests
    $sql = "SELECT COUNT(*) as pending FROM freight_requests WHERE delivery_date IS NULL";
    $result = $conn->query($sql);
    $stats['pending_requests'] = $result->fetch_assoc()['pending'];
    
    // In-transit requests
    $sql = "SELECT COUNT(*) as in_transit FROM freight_requests WHERE delivery_date >= CURDATE()";
    $result = $conn->query($sql);
    $stats['in_transit_requests'] = $result->fetch_assoc()['in_transit'];
    
    // Completed requests
    $sql = "SELECT COUNT(*) as completed FROM freight_requests WHERE delivery_date < CURDATE()";
    $result = $conn->query($sql);
    $stats['completed_requests'] = $result->fetch_assoc()['completed'];
    
    return $stats;
}

$recentRequests = getRecentFreightRequests($conn);
$requestStats = getFreightRequestStats($conn);

// Add debugging information
echo "<pre>";
print_r($recentRequests);
print_r($requestStats);
echo "</pre>";

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Admin Dashboard - TruckLogix</title>
		<!-- Add console log to check if JavaScript is running -->
		<script>console.log("JavaScript is running");</script>
		<!-- Check if CSS is loading properly -->
		<link rel="stylesheet" href="../assets/css/main.css">
		<style>
			/* Add a temporary style to make sure CSS is applied */
			body { background-color: #f0f0f0; }
		</style>
	</head>
	<body class="is-preload">
		<!-- Add more debug information -->
		<div style="background-color: yellow; padding: 10px;">
			Debug: Body of the dashboard is starting to render
		</div>

		<div id="page-wrapper">

			<!-- Header -->
			<header id="header">
				<h1><a href="dashboard.php">TruckLogix Admin</a></h1>
				<nav id="nav">
					<ul>
						<li><a href="dashboard.php">Dashboard</a></li>
						<li><a href="manage_request.php">Manage Requests</a></li>
						<li><a href="manage_users.php">Manage Users</a></li>
						<li><a href="../logout.php" class="button">Logout</a></li>
					</ul>
				</nav>
			</header>

			<!-- Main -->
			<section id="main" class="container">
				<header>
					<h2>Admin Dashboard</h2>
					<p>Manage freight requests and operations</p>
				</header>
				
				<div class="row">
					<div class="col-12">
						<section class="box">
							<h3>Freight Request Statistics</h3>
							<ul>
								<li>Total Requests: <?php echo $requestStats['total_requests']; ?></li>
								<li>Pending Requests: <?php echo $requestStats['pending_requests']; ?></li>
								<li>In-Transit Requests: <?php echo $requestStats['in_transit_requests']; ?></li>
								<li>Completed Requests: <?php echo $requestStats['completed_requests']; ?></li>
							</ul>
						</section>
					</div>
				</div>

				<div class="row">
					<div class="col-12">
						<section class="box">
							<h3>Recent Freight Requests</h3>
							<table>
								<thead>
									<tr>
										<th>ID</th>
										<th>Customer</th>
										<th>Pickup Date</th>
										<th>Created At</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($recentRequests as $request): ?>
									<tr>
										<td><?php echo $request['request_id']; ?></td>
										<td><?php echo htmlspecialchars($request['username']); ?></td>
										<td><?php echo $request['pickup_date']; ?></td>
										<td><?php echo $request['created_at']; ?></td>
										<td><?php echo htmlspecialchars($request['status']); ?></td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</section>
					</div>
				</div>
			</section>

			<!-- Footer -->
			<footer id="footer">
				<p>&copy; 2023 TruckLogix. All rights reserved.</p>
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
		<!-- Add a script to check if all scripts are loaded -->
		<script>
			console.log("All scripts have been included");
			$(document).ready(function() {
				console.log("Document is ready");
			});
		</script>
	</body>
</html>
