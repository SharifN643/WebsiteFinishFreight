<?php
session_start();
require_once '../db_connect.php';

$email = '';

// Check if the form is submitted via POST or if email is provided via GET
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the full form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $service_type = $_POST['service_type'] ?? '';
    $message = $_POST['message'] ?? '';

    // Validate and sanitize input data
    $name = htmlspecialchars(strip_tags($name));
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(strip_tags($phone));
    $service_type = htmlspecialchars(strip_tags($service_type));
    $message = htmlspecialchars(strip_tags($message));

    // Check if user is logged in
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Prepare SQL statement
    $sql = "INSERT INTO get_quote (user_id, name, email, phone, service_type, message) VALUES (?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isssss", $user_id, $name, $email, $phone, $service_type, $message);
        
        if ($stmt->execute()) {
            // Redirect to a thank you page or show a success message
            $_SESSION['quote_success'] = true;
            header("Location: thank_you.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['email'])) {
    // If email is provided via GET, pre-fill the email field
    $email = $_GET['email'];
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Get a Quote - TruckLogix</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="../assets/css/main.css" />
</head>
<body class="is-preload">
    <div id="page-wrapper">
        <!-- Header -->
        <header id="header">
            <h1><a href="../index.php">TruckLogix</a></h1>
            <nav id="nav">
                <ul>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../index.php#services">Services</a></li>
                    <li><a href="get_quote.php" class="button">Get a Quote</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main -->
        <section id="main" class="container">
            <header>
                <h2>Get a Quote</h2>
                <p>Fill out the form below and we'll get back to you with a custom quote.</p>
            </header>
            <div class="box">
                <form method="post" action="get_quote.php">
                    <div class="row gtr-50 gtr-uniform">
                        <div class="col-6 col-12-mobilep">
                            <input type="text" name="name" id="name" value="" placeholder="Name" required />
                        </div>
                        <div class="col-6 col-12-mobilep">
                            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Email" required />
                        </div>
                        <div class="col-6 col-12-mobilep">
                            <input type="tel" name="phone" id="phone" value="" placeholder="Phone" />
                        </div>
                        <div class="col-6 col-12-mobilep">
                            <select name="service_type" id="service_type" required>
                                <option value="">- Select a Service -</option>
                                <option value="freight_transport">Freight Transport</option>
                                <option value="warehousing">Warehousing</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <textarea name="message" id="message" placeholder="Enter your message" rows="6" required></textarea>
                            <p class="note">You can refer to our <a href="../services.php">pricing page</a> for further information on our services.</p>
                        </div>
                        <div class="col-12">
                            <ul class="actions special">
                                <li><input type="submit" value="Submit Quote Request" /></li>
                            </ul>
                        </div>
                    </div>
                </form>
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
