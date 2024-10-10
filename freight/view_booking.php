<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../db_connect.php'; // Ensure this path is correct

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>View Bookings - TruckLogix</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="../assets/css/main.css" />
</head>
<body class="is-preload">
    <div id="page-wrapper">
        <!-- Header -->
        <header id="header" style="height: 4em; display: flex; align-items: center;">
            <h1 style="margin: 0; padding: 0;">
                <a href="../index.php" class="logo" style="display: flex; align-items: center; height: 100%;">
                    <img src="../images/PEN_Logo-removebg-preview (2).png" alt="TruckLogix" style="max-height: 3em; width: auto; vertical-align: middle;">
                </a>
            </h1>
            <nav id="nav" style="margin-left: auto;">
                <ul>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="freight.php">Freight Transport</a></li>
                    <li><a href="../logout.php" class="button alt">Logout</a></li>
                </ul>
            </nav>
        </header>

        <section id="main" class="container">
            <header>
                <h2>Booking History</h2>
                <p>Here are your recent bookings.</p>
            </header>

            <div class="box">
                <?php
                if (isset($conn)) {
                    $sql = "SELECT fr.request_id, fr.pickup_address, fr.pickup_date, fr.delivery_address, fr.delivery_date, 
                                   i.item_type, i.item_description, i.item_quantity, i.item_weight,
                                   s.insurance, s.packaging
                            FROM freight_requests fr
                            LEFT JOIN items i ON fr.request_id = i.request_id
                            LEFT JOIN services s ON fr.request_id = s.request_id
                            WHERE fr.user_id = ?
                            ORDER BY fr.created_at DESC";
                    
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result && $result->num_rows > 0) {
                        echo "<table border='1'>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Pickup Address</th>
                                    <th>Pickup Date</th>
                                    <th>Delivery Address</th>
                                    <th>Delivery Date</th>
                                    <th>Item Type</th>
                                    <th>Item Description</th>
                                    <th>Quantity</th>
                                    <th>Weight</th>
                                    <th>Insurance</th>
                                    <th>Packaging</th>
                                </tr>";
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row["request_id"]) . "</td>
                                    <td>" . htmlspecialchars($row["pickup_address"]) . "</td>
                                    <td>" . htmlspecialchars($row["pickup_date"]) . "</td>
                                    <td>" . htmlspecialchars($row["delivery_address"]) . "</td>
                                    <td>" . htmlspecialchars($row["delivery_date"]) . "</td>
                                    <td>" . htmlspecialchars($row["item_type"]) . "</td>
                                    <td>" . htmlspecialchars($row["item_description"]) . "</td>
                                    <td>" . htmlspecialchars($row["item_quantity"]) . "</td>
                                    <td>" . htmlspecialchars($row["item_weight"]) . "</td>
                                    <td>" . (($row["insurance"]) ? 'Yes' : 'No') . "</td>
                                    <td>" . (($row["packaging"]) ? 'Yes' : 'No') . "</td>
                                  </tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "No bookings found.";
                    }

                    $stmt->close();
                    $conn->close();
                } else {
                    echo "Database connection error. Please try again later.";
                }
                ?>
            </div>
        </section>

        <!-- Footer -->
        <footer id="footer">
            <ul class="icons">
                <li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
                <li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
                <li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
                <li><a href="#" class="icon brands fa-linkedin"><span class="label">LinkedIn</span></a></li>
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