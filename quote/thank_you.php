<?php
session_start();
if (!isset($_SESSION['quote_success'])) {
    header("Location: ../index.php");
    exit();
}
unset($_SESSION['quote_success']);
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Thank You - TruckLogix</title>
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
                <h2>Thank You!</h2>
                <p>We've received your quote request and will get back to you soon.</p>
            </header>
            <div class="box">
                <p>Thank you for your interest in TruckLogix services. Our team will review your request and contact you with a custom quote as soon as possible.</p>
                <ul class="actions special">
                    <li><a href="../index.php" class="button">Return to Home</a></li>
                </ul>
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

