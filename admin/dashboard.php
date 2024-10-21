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
    $stats = [
        'pending_requests' => 0,
        'in_transit_requests' => 0,
        'completed_requests' => 0
    ];
    
    $sql = "SELECT 
        SUM(CASE WHEN delivery_date IS NULL THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN delivery_date IS NOT NULL AND delivery_date >= CURDATE() THEN 1 ELSE 0 END) as in_transit,
        SUM(CASE WHEN delivery_date IS NOT NULL AND delivery_date < CURDATE() THEN 1 ELSE 0 END) as completed
        FROM freight_requests";
    
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $stats['pending_requests'] = (int)$row['pending'];
        $stats['in_transit_requests'] = (int)$row['in_transit'];
        $stats['completed_requests'] = (int)$row['completed'];
    } else {
        echo "Error executing query: " . $conn->error;
    }
    
    return $stats;
}

// Function to get warehouse request statistics
function getWarehouseRequestStats($conn) {
    $stats = [];
    
    // Normal storage requests
    $sql = "SELECT COUNT(*) as total, 
            SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) as in_progress,
            SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed
            FROM normal_storage_requests";
    $result = $conn->query($sql);
    $stats['normal'] = $result->fetch_assoc();
    
    // Temperature-controlled storage requests
    $sql = "SELECT COUNT(*) as total, 
            SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) as in_progress,
            SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed
            FROM temperature_controlled_storage_requests";
    $result = $conn->query($sql);
    $stats['temp'] = $result->fetch_assoc();
    
    return $stats;
}

$recentRequests = getRecentFreightRequests($conn);
$freightStats = getFreightRequestStats($conn);
$warehouseStats = getWarehouseRequestStats($conn);

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Admin Dashboard - TruckLogix</title>
		<link rel="stylesheet" href="../assets/css/main.css">
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	</head>
	<body class="is-preload">
		<div id="page-wrapper">

			<!-- Header -->
			<header id="header">
				<h1><a href="dashboard.php">TruckLogix Admin</a></h1>
				<nav id="nav">
					<ul>
						<li><a href="dashboard.php">Dashboard</a></li>
						<li><a href="manage_request.php">Manage Freight</a></li>
						<li><a href="admin_warehouse.php">Manage Warehouse</a></li>
						<li><a href="manage_users.php">Manage Users</a></li>
						<li><a href="../logout.php" class="button">Logout</a></li>
					</ul>
				</nav>
			</header>

			<!-- Main content starts here -->
			<section id="main" class="container">
				<header>
					<h2>Admin Dashboard</h2>
					<p>Manage freight requests and operations</p>
				</header>

				<div class="row">
					<div class="col-4 col-12-narrower">
						<section class="box">
							<h3>Freight Request Statistics</h3>
							<canvas id="freightChart"></canvas>
						</section>
					</div>
					<div class="col-4 col-12-narrower">
						<section class="box">
							<h3>Normal Storage Statistics</h3>
							<canvas id="normalStorageChart"></canvas>
						</section>
					</div>
					<div class="col-4 col-12-narrower">
						<section class="box">
							<h3>Temperature-Controlled Storage Statistics</h3>
							<canvas id="tempStorageChart"></canvas>
						</section>
					</div>
				</div>

				<div class="row">
					<div class="col-12">
						<section class="box">
							<h3>Freight Management</h3>
							<p>Manage freight requests and operations</p>
							<ul class="actions">
								<li><a href="manage_request.php" class="button">Manage Freight Requests</a></li>
							</ul>
						</section>
					</div>
				</div>

				<div class="row">
					<div class="col-12">
						<section class="box">
							<h3>Warehouse Management</h3>
							<p>Manage warehouse storage requests and operations</p>
							<ul class="actions">
								<li><a href="admin_warehouse.php" class="button">Manage Warehouse</a></li>
							</ul>
						</section>
					</div>
				</div>

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

				// Freight Chart
				var freightCtx = document.getElementById('freightChart').getContext('2d');
				var freightData = {
					pending: <?php echo json_encode($freightStats['pending_requests']); ?>,
					inTransit: <?php echo json_encode($freightStats['in_transit_requests']); ?>,
					completed: <?php echo json_encode($freightStats['completed_requests']); ?>
				};
				console.log('Freight Data:', freightData);

				if (freightData.pending !== null && freightData.inTransit !== null && freightData.completed !== null) {
					var freightChart = new Chart(freightCtx, {
						type: 'pie',
						data: {
							labels: ['Pending', 'In Transit', 'Completed'],
							datasets: [{
								data: [freightData.pending, freightData.inTransit, freightData.completed],
								backgroundColor: [
									'rgba(255, 206, 86, 0.8)',
									'rgba(54, 162, 235, 0.8)',
									'rgba(75, 192, 192, 0.8)'
								]
							}]
						},
						options: {
							responsive: true,
							plugins: {
								title: {
									display: true,
									text: 'Freight Requests'
								},
								legend: {
									position: 'bottom'
								}
							}
						}
					});
				} else {
					console.error('Invalid freight data');
					document.getElementById('freightChart').insertAdjacentHTML('afterend', '<p>Error: Unable to load freight data</p>');
				}

				// Normal Storage Chart
				var normalStorageCtx = document.getElementById('normalStorageChart').getContext('2d');
				var normalStorageChart = new Chart(normalStorageCtx, {
					type: 'pie',
					data: {
						labels: ['Pending', 'Approved', 'In Progress', 'Completed'],
						datasets: [{
							data: [
								<?php echo $warehouseStats['normal']['pending']; ?>,
								<?php echo $warehouseStats['normal']['approved']; ?>,
								<?php echo $warehouseStats['normal']['in_progress']; ?>,
								<?php echo $warehouseStats['normal']['completed']; ?>
							],
							backgroundColor: [
								'rgba(255, 99, 132, 0.8)',
								'rgba(54, 162, 235, 0.8)',
								'rgba(255, 206, 86, 0.8)',
								'rgba(75, 192, 192, 0.8)'
							]
						}]
					},
					options: {
						responsive: true,
						title: {
							display: true,
							text: 'Normal Storage Requests'
						}
					}
				});

				// Temperature-Controlled Storage Chart
				var tempStorageCtx = document.getElementById('tempStorageChart').getContext('2d');
				var tempStorageChart = new Chart(tempStorageCtx, {
					type: 'pie',
					data: {
						labels: ['Pending', 'Approved', 'In Progress', 'Completed'],
						datasets: [{
							data: [
								<?php echo $warehouseStats['temp']['pending']; ?>,
								<?php echo $warehouseStats['temp']['approved']; ?>,
								<?php echo $warehouseStats['temp']['in_progress']; ?>,
								<?php echo $warehouseStats['temp']['completed']; ?>
							],
							backgroundColor: [
								'rgba(255, 99, 132, 0.8)',
								'rgba(54, 162, 235, 0.8)',
								'rgba(255, 206, 86, 0.8)',
								'rgba(75, 192, 192, 0.8)'
							]
						}]
					},
					options: {
						responsive: true,
						title: {
							display: true,
							text: 'Temperature-Controlled Storage Requests'
						}
					}
				});

				// Debug output for charts
				console.log('Freight Stats:', <?php echo json_encode($freightStats); ?>);
				console.log('Warehouse Stats:', <?php echo json_encode($warehouseStats); ?>);
			</script>
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

			// Freight Chart
			var freightCtx = document.getElementById('freightChart').getContext('2d');
			var freightData = {
				pending: <?php echo json_encode($freightStats['pending_requests']); ?>,
				inTransit: <?php echo json_encode($freightStats['in_transit_requests']); ?>,
				completed: <?php echo json_encode($freightStats['completed_requests']); ?>
			};
			console.log('Freight Data:', freightData);

			if (freightData.pending !== null && freightData.inTransit !== null && freightData.completed !== null) {
				var freightChart = new Chart(freightCtx, {
					type: 'pie',
					data: {
						labels: ['Pending', 'In Transit', 'Completed'],
						datasets: [{
							data: [freightData.pending, freightData.inTransit, freightData.completed],
							backgroundColor: [
								'rgba(255, 206, 86, 0.8)',
								'rgba(54, 162, 235, 0.8)',
								'rgba(75, 192, 192, 0.8)'
							]
						}]
					},
					options: {
						responsive: true,
						plugins: {
							title: {
								display: true,
								text: 'Freight Requests'
							},
							legend: {
								position: 'bottom'
							}
						}
					}
				});
			} else {
				console.error('Invalid freight data');
				document.getElementById('freightChart').insertAdjacentHTML('afterend', '<p>Error: Unable to load freight data</p>');
			}

			// Normal Storage Chart
			var normalStorageCtx = document.getElementById('normalStorageChart').getContext('2d');
			var normalStorageChart = new Chart(normalStorageCtx, {
				type: 'pie',
				data: {
					labels: ['Pending', 'Approved', 'In Progress', 'Completed'],
					datasets: [{
						data: [
							<?php echo $warehouseStats['normal']['pending']; ?>,
							<?php echo $warehouseStats['normal']['approved']; ?>,
							<?php echo $warehouseStats['normal']['in_progress']; ?>,
							<?php echo $warehouseStats['normal']['completed']; ?>
						],
						backgroundColor: [
							'rgba(255, 99, 132, 0.8)',
							'rgba(54, 162, 235, 0.8)',
							'rgba(255, 206, 86, 0.8)',
							'rgba(75, 192, 192, 0.8)'
						]
					}]
				},
				options: {
					responsive: true,
					title: {
						display: true,
						text: 'Normal Storage Requests'
					}
				}
			});

			// Temperature-Controlled Storage Chart
			var tempStorageCtx = document.getElementById('tempStorageChart').getContext('2d');
			var tempStorageChart = new Chart(tempStorageCtx, {
				type: 'pie',
				data: {
					labels: ['Pending', 'Approved', 'In Progress', 'Completed'],
					datasets: [{
						data: [
							<?php echo $warehouseStats['temp']['pending']; ?>,
							<?php echo $warehouseStats['temp']['approved']; ?>,
							<?php echo $warehouseStats['temp']['in_progress']; ?>,
							<?php echo $warehouseStats['temp']['completed']; ?>
						],
						backgroundColor: [
							'rgba(255, 99, 132, 0.8)',
							'rgba(54, 162, 235, 0.8)',
							'rgba(255, 206, 86, 0.8)',
							'rgba(75, 192, 192, 0.8)'
						]
					}]
				},
				options: {
					responsive: true,
					title: {
						display: true,
						text: 'Temperature-Controlled Storage Requests'
					}
				}
			});

			// Debug output for charts
			console.log('Freight Stats:', <?php echo json_encode($freightStats); ?>);
			console.log('Warehouse Stats:', <?php echo json_encode($warehouseStats); ?>);
		</script>
	</body>
</html>
