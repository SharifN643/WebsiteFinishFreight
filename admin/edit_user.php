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
        <li>&copy; TruckLogix. All rights reserved.</li>
        <li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
    </ul>
</footer>
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
    // Process form submission
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $username, $email, $role, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "User updated successfully.";
        header("Location: manage_users.php");
        exit();
    } else {
        $error_message = "Error updating user: " . $conn->error;
    }
} else {
    // Fetch user details
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
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
		<title>Edit User - TruckLogix Admin</title>
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
					<h2>Edit User</h2>
				</header>
				
				<div class="box">
					<?php if (isset($error_message)): ?>
						<p style="color: red;"><?php echo $error_message; ?></p>
					<?php endif; ?>

					<form method="post">
						<div class="row gtr-uniform gtr-50">
							<div class="col-6 col-12-mobilep">
								<label for="username">Username:</label>
								<input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required />
							</div>
							<div class="col-6 col-12-mobilep">
								<label for="email">Email:</label>
								<input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required />
							</div>
							<div class="col-12">
								<label for="role">Role:</label>
								<select name="role" id="role" required>
									<option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
									<option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
								</select>
							</div>
							<div class="col-12">
								<ul class="actions">
									<li><input type="submit" value="Update User" /></li>
									<li><a href="manage_users.php" class="button alt">Cancel</a></li>
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
