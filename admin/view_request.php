<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Check if user is logged in and has admin role
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Include database connection
require_once '../db_connect.php';

// Check if type and id are set
if (!isset($_GET['type']) || !isset($_GET['id'])) {
    die("Invalid request");
}

$type = $_GET['type'];
$id = $_GET['id'];

// Prepare the query based on the type
if ($type === 'normal') {
    $table = 'normal_storage_requests';
} elseif ($type === 'temp') {
    $table = 'temperature_controlled_storage_requests';
} else {
    die("Invalid request type");
}

$sql = "SELECT * FROM $table WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Request not found");
}

$request = $result->fetch_assoc();

// Fetch additional services
$sql_additional = "SELECT id, request_id, service_name FROM storage_additional_services WHERE request_id = ?";
$stmt_additional = $conn->prepare($sql_additional);
$stmt_additional->bind_param("i", $id);
$stmt_additional->execute();
$result_additional = $stmt_additional->get_result();
$additional_services = $result_additional->fetch_all(MYSQLI_ASSOC);

// Fetch other services
$stmt_other = $conn->prepare("SELECT id, request_id, service_description FROM storage_other_services WHERE request_id = ?");
if ($stmt_other->execute([$id])) {
    $result_other = $stmt_other->get_result();
    $other_services = [];
    while ($row = $result_other->fetch_assoc()) {
        $other_services[] = $row['service_description'];
    }
    // Now $other_services is an array of other service descriptions for this request
} else {
    // Handle error
}

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>View Request - TruckLogix</title>
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
                <h2>View Request Details</h2>
            </header>
            
            <div class="box">
                <h3><?php echo ucfirst($type); ?> Storage Request #<?php echo $request['id']; ?></h3>
                <table>
                    <?php foreach ($request as $key => $value): ?>
                        <?php if ($key !== 'id' && $key !== 'user_id'): ?>
                            <tr>
                                <th><?php echo ucwords(str_replace('_', ' ', $key)); ?></th>
                                <td><?php echo htmlspecialchars($value); ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </table>

                <h4>Additional Services</h4>
                <?php if (!empty($additional_services)): ?>
                    <ul>
                        <?php foreach ($additional_services as $service): ?>
                            <li><?php echo htmlspecialchars($service['service_name']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No additional services requested.</p>
                <?php endif; ?>

                <h4>Other Services</h4>
                <?php if (!empty($other_services)): ?>
                    <ul>
                        <?php foreach ($other_services as $service): ?>
                            <li><?php echo htmlspecialchars($service); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No other services requested.</p>
                <?php endif; ?>

                <a href="admin_warehouse.php" class="button">Back to Warehouse</a>
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
