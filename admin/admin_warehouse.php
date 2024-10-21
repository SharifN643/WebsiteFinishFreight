<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Debug output
echo "Script is running.<br>";
var_dump($_SESSION);

// Check if user is logged in and has admin role
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "User is not logged in or not an admin. Redirecting...<br>";
    header("Location: ../login.php");
    exit();
}

// If we get here, the user is logged in and is an admin
echo "User is logged in and is an admin.<br>";

// Include database connection
require_once '../db_connect.php';

// Fetch all storage requests
$sql_normal = "SELECT * FROM normal_storage_requests ORDER BY created_at DESC";
$sql_temp = "SELECT * FROM temperature_controlled_storage_requests ORDER BY created_at DESC";

$result_normal = $conn->query($sql_normal);
$result_temp = $conn->query($sql_temp);
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Admin Warehouse - TruckLogix</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <style>
        .admin-actions {
            margin-top: 1em;
        }
        .admin-actions .button {
            margin-right: 0.5em;
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
                    <li><a href="admin_warehouse.php">Warehouse</a></li>
                    <li><a href="manage_request.php">Manage Requests</a></li>
                    <li><a href="manage_users.php">Manage Users</a></li>
                    <li><a href="../logout.php" class="button">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main -->
        <section id="main" class="container">
            <header>
                <h2>Warehouse Admin Dashboard</h2>
            </header>
            
            <!-- Normal Storage Requests -->
            <div class="box">
                <h3>Normal Storage Requests</h3>
                <?php if ($result_normal && $result_normal->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Item Description</th>
                                <th>Quantity</th>
                                <th>Storage Duration</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result_normal->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['customer_name'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['item_description'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['quantity'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['storage_duration'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['status'] ?? 'Pending'); ?></td>
                                    <td class="admin-actions">
                                        <a href="view_request.php?type=normal&id=<?php echo $row['id']; ?>" class="button small">View</a>
                                        <form action="update_status.php" method="post" style="display: inline;">
                                            <input type="hidden" name="type" value="normal">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <select name="status" onchange="this.form.submit()">
                                                <option value="">Update Status</option>
                                                <option value="Pending" <?php echo ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                <option value="Approved" <?php echo ($row['status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                                                <option value="Rejected" <?php echo ($row['status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                                                <option value="Completed" <?php echo ($row['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No normal storage requests at this time.</p>
                <?php endif; ?>
            </div>

            <!-- Temperature-Controlled Storage Requests -->
            <div class="box">
                <h3>Temperature-Controlled Storage Requests</h3>
                <?php if ($result_temp && $result_temp->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Item Description</th>
                                <th>Quantity</th>
                                <th>Temperature Range</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result_temp->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['customer_name'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['item_description'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['quantity'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['temperature_range'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['status'] ?? 'Pending'); ?></td>
                                    <td class="admin-actions">
                                        <a href="view_request.php?type=temp&id=<?php echo $row['id']; ?>" class="button small">View</a>
                                        <form action="update_status.php" method="post" style="display: inline;">
                                            <input type="hidden" name="type" value="temp">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <select name="status" onchange="this.form.submit()">
                                                <option value="">Update Status</option>
                                                <option value="Pending" <?php echo ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                <option value="Approved" <?php echo ($row['status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                                                <option value="Rejected" <?php echo ($row['status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                                                <option value="Completed" <?php echo ($row['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No temperature-controlled storage requests at this time.</p>
                <?php endif; ?>
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
</body>
</html>

<?php
$conn->close();
?>
