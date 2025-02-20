<?php
session_start();
require_once '../db_connect.php'; // Adjust the path if necessary

// Enable error reporting for development (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to log errors
function logError($message) {
    error_log(date('[Y-m-d H:i:s] ') . "Login Error: " . $message . "\n", 3, "../error.log");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        logError("Login attempt with empty username or password");
        $_SESSION['login_error'] = "Please enter both username and password.";
        header("Location: ../index.php");
        exit();
    }

    // Perform login logic here
    $stmt = $conn->prepare("SELECT user_id, username, password, role FROM users WHERE username = ?");
    if (!$stmt) {
        logError("Prepare failed: " . $conn->error);
        $_SESSION['login_error'] = "An error occurred. Please try again later.";
        header("Location: ../index.php");
        exit();
    }
    
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            logError("Successful login for user: $username");
            
            // Redirect based on user role
            if ($_SESSION['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../index.php");
            }
            exit();
        } else {
            logError("Failed login attempt for user: $username (incorrect password)");
            $_SESSION['login_error'] = "Invalid username or password.";
        }
    } else {
        logError("Failed login attempt for non-existent user: $username");
        $_SESSION['login_error'] = "Invalid username or password.";
    }

    header("Location: ../index.php");
    exit();
} else {
    // Invalid request method
    header("Location: ../index.php");
    exit();
}
?>
