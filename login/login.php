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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($username) || empty($password)) {
        $error_message = 'Please fill in both username and password.';
        logError($error_message);
        header("Location: ../index.php?error=" . urlencode($error_message));
        exit();
    }

    // Fetch user data using prepared statements
    $stmt = $conn->prepare("SELECT user_id, password, role FROM users WHERE username = ?");
    if (!$stmt) {
        logError("Prepare Statement Failed: " . $conn->error);
        header("Location: ../index.php?error=Database error. Please try again.");
        exit();
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $hashed_password, $role);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            $stmt->close();
            header("Location: ../index.php");
            exit();
        } else {
            // Incorrect password
            $error_message = 'Invalid username or password.';
            logError("Invalid password for user: $username");
            $stmt->close();
            header("Location: ../index.php?error=" . urlencode($error_message));
            exit();
        }
    } else {
        // User not found
        $error_message = 'Invalid username or password.';
        logError("User not found: $username");
        $stmt->close();
        header("Location: ../index.php?error=" . urlencode($error_message));
        exit();
    }
} else {
    // Invalid request method
    header("Location: ../index.php");
    exit();
}
?>