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

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process user deletion
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "User deleted successfully.";
        header("Location: manage_users.php");
        exit();
    } else {
        $error_message = "Error deleting user: " . $conn->error;
    }
} else {
    // Fetch user details
    $stmt = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $_SESSION['error_message'] = "User not found.";
        header("Location: manage_users.php");
        exit();
    }
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Delete User - TruckLogix Admin</title>
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
					<h2>Delete User</h2>
				</header>
				
				<div class="box">
					<?php if (isset($error_message)): ?>
						<p style="color: red;"><?php echo $error_message; ?></p>
					<?php else: ?>
						<h3>Are you sure you want to delete the user: <?php echo htmlspecialchars($user['username']); ?>?</h3>
						<form method="post">
							<div class="row gtr-uniform gtr-50">
								<div class="col-12">
									<ul class="actions">
										<li><input type="submit" value="Delete User" class="primary" /></li>
										<li><a href="manage_users.php" class="button alt">Cancel</a></li>
									</ul>
								</div>
							</div>
						</form>
					<?php endif; ?>
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
