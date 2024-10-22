<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering
ob_start();

session_start();
require_once '../db_connect.php';

// Remove or comment out these debug lines
// Debug: Check session variables
// echo "Session variables: ";
// print_r($_SESSION);

// Check if user is logged in and has admin role
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "User not authorized. Redirecting...";
    header("Location: ../login.php");
    exit();
}

// Function to get all freight requests
function getAllFreightRequests($conn) {
    $sql = "SELECT fr.*, u.username 
            FROM freight_requests fr 
            LEFT JOIN users u ON fr.user_id = u.user_id 
            ORDER BY fr.created_at DESC";
    $result = $conn->query($sql);
    if (!$result) {
        echo "Query error: " . $conn->error;
        return [];
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Handle form submission for updating or deleting
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'update') {
            // Update request
            $sql = "UPDATE freight_requests SET 
                    pickup_address = ?, pickup_date = ?, pickup_time = ?, 
                    pickup_contact = ?, pickup_contact_number = ?,
                    delivery_address = ?, delivery_date = ?, delivery_time = ?,
                    special_instructions = ?, additional_info = ?, status = ?
                    WHERE request_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssssi", 
                $_POST['pickup_address'], $_POST['pickup_date'], $_POST['pickup_time'],
                $_POST['pickup_contact'], $_POST['pickup_contact_number'],
                $_POST['delivery_address'], $_POST['delivery_date'], $_POST['delivery_time'],
                $_POST['special_instructions'], $_POST['additional_info'], $_POST['status'],
                $_POST['request_id']
            );
            $stmt->execute();
        } elseif ($_POST['action'] === 'delete') {
            // Delete request
            $sql = "DELETE FROM freight_requests WHERE request_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $_POST['request_id']);
            $stmt->execute();
        }
    }
    // Redirect to refresh the page after update/delete
    header("Location: manage_request.php");
    exit();
}

// Get all freight requests
$freightRequests = getAllFreightRequests($conn);

// Remove or comment out these debug lines
// Debug: Print freight requests and table structure
// echo "Freight Requests: ";
// print_r($freightRequests);

// Get table structure
$tableStructure = $conn->query("DESCRIBE freight_requests");
// echo "Table Structure: ";
// print_r($tableStructure->fetch_all(MYSQLI_ASSOC));

?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Manage Freight Requests - TruckLogix</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
		<style>
			.edit-form { display: none; }
			.edit-form.active { display: block; }
			.view-row.hidden { display: none; }
			.request-table { width: 100%; border-collapse: collapse; }
			.request-table th, .request-table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
			.request-table th { background-color: #f2f2f2; }
			.request-table tr:hover { background-color: #f5f5f5; }
			.edit-form form { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; padding: 20px; background-color: #f9f9f9; border-radius: 5px; }
			.edit-form label { display: block; margin-bottom: 5px; }
			.edit-form input[type="text"], .edit-form input[type="date"], .edit-form select, .edit-form textarea { width: 100%; padding: 5px; }
			.edit-form button { margin-top: 10px; }
			.action-buttons { display: flex; gap: 5px; }
			.action-buttons .button {
				padding: 0 1em;
				height: 2.75em;
				line-height: 2.75em;
				background-color: #737373; /* Changed to the requested color */
				color: #ffffff !important;
				text-decoration: none;
				border: 0;
				cursor: pointer;
				text-align: center;
				white-space: nowrap;
			}
			.action-buttons .button:hover {
				background-color: #5a5a5a; /* A slightly darker shade for hover effect */
			}
			.action-buttons .button.small {
				font-size: 0.8em;
			}
			#edit-forms-container { display: none; }
			.edit-form { display: none; background-color: #f9f9f9; padding: 20px; border-radius: 5px; margin-top: 20px; }
			.edit-form.active { display: block; }
			.edit-form form {
				display: grid;
				grid-template-columns: 1fr 1fr;
				gap: 15px;
			}
			.edit-form form > div {
				display: flex;
				flex-direction: column;
			}
			.edit-form form label {
				margin-bottom: 5px;
			}
			.edit-form form input,
			.edit-form form select,
			.edit-form form textarea {
				width: 100%;
				padding: 5px;
			}
			.edit-form form button {
				margin-top: 10px;
			}
			/* Add this to ensure all buttons use the same color */
			.button {
				background-color: #737373 !important;
				color: #ffffff !important;
			}
			.button:hover {
				background-color: #5a5a5a !important;
			}
		</style>
	</head>
	<body class="is-preload">
		<div id="page-wrapper">

			<!-- Header -->
			<header id="header">
				<h1><a href="dashboard.php">TruckLogix</a> Admin</h1>
				<nav id="nav">
					<ul>
						<li><a href="dashboard.php">Dashboard</a></li>
						<li><a href="manage_request.php" class="active">Manage Requests</a></li>
						<li><a href="../logout.php" class="button">Log Out</a></li>
					</ul>
				</nav>
			</header>

			<!-- Main -->
			<section id="main" class="container">
				<header>
					<h2>Manage Freight Requests</h2>
				</header>
				<div class="box">
					<h3>All Freight Requests</h3>
					<div id="table-container">
						<table class="request-table">
							<thead>
								<tr>
									<th>ID</th>
									<th>Customer</th>
									<th>Pickup Address</th>
									<th>Delivery Address</th>
									<th>Pickup Date</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($freightRequests as $request): ?>
								<tr class="view-row" id="view-<?php echo $request['request_id']; ?>">
									<td><?php echo $request['request_id']; ?></td>
									<td><?php echo htmlspecialchars($request['username']) . ' (ID: ' . $request['user_id'] . ')'; ?></td>
									<td><?php echo htmlspecialchars($request['pickup_address']); ?></td>
									<td><?php echo htmlspecialchars($request['delivery_address']); ?></td>
									<td><?php echo $request['pickup_date']; ?></td>
									<td><?php echo htmlspecialchars($request['status']); ?></td>
									<td class="action-buttons">
										<button class="button small" onclick="showEditForm(<?php echo $request['request_id']; ?>)">Edit</button>
										<form method="POST" onsubmit="return confirm('Are you sure you want to delete this request?');">
											<input type="hidden" name="action" value="delete">
											<input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
											<button type="submit" class="button small">Delete</button>
										</form>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>

					<div id="edit-forms-container" style="display: none;">
						<?php foreach ($freightRequests as $request): ?>
						<div class="edit-form" id="edit-<?php echo $request['request_id']; ?>">
							<h4>Edit Request #<?php echo $request['request_id']; ?></h4>
							<form method="POST">
								<input type="hidden" name="action" value="update">
								<input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
								
								<div>
									<label for="pickup_address_<?php echo $request['request_id']; ?>">Pickup Address:</label>
									<input type="text" id="pickup_address_<?php echo $request['request_id']; ?>" name="pickup_address" value="<?php echo htmlspecialchars($request['pickup_address']); ?>">
								</div>
								
								<div>
									<label for="pickup_date_<?php echo $request['request_id']; ?>">Pickup Date:</label>
									<input type="date" id="pickup_date_<?php echo $request['request_id']; ?>" name="pickup_date" value="<?php echo $request['pickup_date']; ?>">
								</div>
								
								<div>
									<label for="pickup_time_<?php echo $request['request_id']; ?>">Pickup Time:</label>
									<select id="pickup_time_<?php echo $request['request_id']; ?>" name="pickup_time">
										<option value="9am to 12pm" <?php echo $request['pickup_time'] == '9am to 12pm' ? 'selected' : ''; ?>>9am to 12pm</option>
										<option value="12pm to 3pm" <?php echo $request['pickup_time'] == '12pm to 3pm' ? 'selected' : ''; ?>>12pm to 3pm</option>
										<option value="3pm to 6pm" <?php echo $request['pickup_time'] == '3pm to 6pm' ? 'selected' : ''; ?>>3pm to 6pm</option>
									</select>
								</div>
								
								<div>
									<label for="pickup_contact_<?php echo $request['request_id']; ?>">Pickup Contact:</label>
									<input type="text" id="pickup_contact_<?php echo $request['request_id']; ?>" name="pickup_contact" value="<?php echo htmlspecialchars($request['pickup_contact']); ?>">
								</div>
								
								<div>
									<label for="pickup_contact_number_<?php echo $request['request_id']; ?>">Pickup Contact Number:</label>
									<input type="text" id="pickup_contact_number_<?php echo $request['request_id']; ?>" name="pickup_contact_number" value="<?php echo htmlspecialchars($request['pickup_contact_number']); ?>">
								</div>
								
								<div>
									<label for="delivery_address_<?php echo $request['request_id']; ?>">Delivery Address:</label>
									<input type="text" id="delivery_address_<?php echo $request['request_id']; ?>" name="delivery_address" value="<?php echo htmlspecialchars($request['delivery_address']); ?>">
								</div>
								
								<div>
									<label for="delivery_date_<?php echo $request['request_id']; ?>">Delivery Date:</label>
									<input type="date" id="delivery_date_<?php echo $request['request_id']; ?>" name="delivery_date" value="<?php echo $request['delivery_date']; ?>">
								</div>
								
								<div>
									<label for="delivery_time_<?php echo $request['request_id']; ?>">Delivery Time:</label>
									<select id="delivery_time_<?php echo $request['request_id']; ?>" name="delivery_time">
										<option value="9am to 12pm" <?php echo $request['delivery_time'] == '9am to 12pm' ? 'selected' : ''; ?>>9am to 12pm</option>
										<option value="12pm to 3pm" <?php echo $request['delivery_time'] == '12pm to 3pm' ? 'selected' : ''; ?>>12pm to 3pm</option>
										<option value="3pm to 6pm" <?php echo $request['delivery_time'] == '3pm to 6pm' ? 'selected' : ''; ?>>3pm to 6pm</option>
									</select>
								</div>
								
								<div>
									<label for="special_instructions_<?php echo $request['request_id']; ?>">Special Instructions:</label>
									<textarea id="special_instructions_<?php echo $request['request_id']; ?>" name="special_instructions"><?php echo htmlspecialchars($request['special_instructions']); ?></textarea>
								</div>
								
								<div>
									<label for="additional_info_<?php echo $request['request_id']; ?>">Additional Info:</label>
									<textarea id="additional_info_<?php echo $request['request_id']; ?>" name="additional_info"><?php echo htmlspecialchars($request['additional_info']); ?></textarea>
								</div>
								
								<div>
									<label for="status_<?php echo $request['request_id']; ?>">Status:</label>
									<select id="status_<?php echo $request['request_id']; ?>" name="status">
										<option value="pending" <?php echo $request['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
										<option value="approved" <?php echo $request['status'] == 'approved' ? 'selected' : ''; ?>>Approved</option>
										<option value="in_transit" <?php echo $request['status'] == 'in_transit' ? 'selected' : ''; ?>>In Transit</option>
										<option value="delivered" <?php echo $request['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
									</select>
								</div>
								
								<div>
									<button type="submit" class="button">Update</button>
									<button type="button" class="button" onclick="hideEditForm(<?php echo $request['request_id']; ?>)">Cancel</button>
								</div>
							</form>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>

			<!-- Footer -->
			<footer id="footer">
				<ul class="copyright">
					<li>&copy; TruckLogix. All rights reserved.</li>
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
		<script>
			function showEditForm(id) {
				console.log('Showing edit form for ID:', id); // Debug log
				document.getElementById('table-container').style.display = 'none';
				document.getElementById('edit-forms-container').style.display = 'block';
				document.querySelectorAll('.edit-form').forEach(form => form.style.display = 'none');
				let editForm = document.getElementById('edit-' + id);
				if (editForm) {
					editForm.style.display = 'block';
				} else {
					console.error('Edit form not found for ID:', id); // Debug log
				}
			}
			function hideEditForm(id) {
				console.log('Hiding edit form for ID:', id); // Debug log
				document.getElementById('table-container').style.display = 'block';
				document.getElementById('edit-forms-container').style.display = 'none';
				let editForm = document.getElementById('edit-' + id);
				if (editForm) {
					editForm.style.display = 'none';
				}
			}
			// Debug: Log when the script runs
			console.log('Script loaded');
		</script>

	</body>
</html>

// End of file
$output = ob_get_clean();
echo $output;
?>
