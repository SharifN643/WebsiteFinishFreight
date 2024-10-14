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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form submission
    $fullName = $_POST['fullName'];
    $itemType = $_POST['itemType'];
    $pickupDate = $_POST['pickupDate'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE deliveries SET fullName = ?, itemType = ?, pickupDate = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $fullName, $itemType, $pickupDate, $status, $delivery_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Delivery updated successfully.";
        header("Location: manage_deliveries.php");
        exit();
    } else {
        $error_message = "Error updating delivery: " . $conn->error;
    }
} else {
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
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Edit Delivery - TruckLogix Admin</title>
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
					<h2>Edit Delivery</h2>
				</header>
				
				<div class="box">
					<?php if (isset($error_message)): ?>
						<p style="color: red;"><?php echo $error_message; ?></p>
					<?php endif; ?>

					<form method="post">
						<div class="row gtr-uniform gtr-50">
							<div class="col-6 col-12-mobilep">
								<label for="fullName">Full Name:</label>
								<input type="text" name="fullName" id="fullName" value="<?php echo htmlspecialchars($delivery['fullName']); ?>" required />
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="itemType">Item Type:</label>
								<input type="text" name="itemType" id="itemType" value="<?php echo htmlspecialchars($delivery['itemType']); ?>" required />
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="pickupDate">Pickup Date:</label>
								<input type="date" name="pickupDate" id="pickupDate" value="<?php echo $delivery['pickupDate']; ?>" required />
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="status">Status:</label>
								<select name="status" id="status" required>
									<option value="Pending" <?php echo $delivery['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
									<option value="In Transit" <?php echo $delivery['status'] === 'In Transit' ? 'selected' : ''; ?>>In Transit</option>
									<option value="Delivered" <?php echo $delivery['status'] === 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
								</select>
							</div>
							<div class="col-12">
								<ul class="actions">
									<li><input type="submit" value="Update Delivery" /></li>
									<li><a href="manage_deliveries.php" class="button alt">Cancel</a></li>
								</ul>
							</div>
						</div>
					</form>
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

