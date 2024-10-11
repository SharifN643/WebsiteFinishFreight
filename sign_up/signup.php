<?php
session_start();
require_once '../db_connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to sanitize user input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Function to log errors and set session messages
function set_error($message) {
    error_log("Signup Error: " . $message);
    $_SESSION['signup_error'] = $message;
    header("Location: ../index.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate user input
    $username = sanitize_input($_POST['username'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate username
    if (empty($username)) {
        set_error("Username is required");
    }

    // Validate email
    if (empty($email)) {
        set_error("Email is required");
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_error("Invalid email format");
    }

    // Validate password
    if (empty($password)) {
        set_error("Password is required");
    } elseif (strlen($password) < 6) {
        set_error("Password must be at least 6 characters long");
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    if (!$stmt) {
        set_error("Database error: " . $conn->error);
    }
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt->close();
        set_error("Username or email already exists");
    }
    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        set_error("Database error: " . $conn->error);
    }
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['signup_success'] = true;
        $_SESSION['username'] = $username; // Store username for welcome message
        error_log("User registered successfully: " . $username);
        header("Location: ../index.php?welcome=1");
        exit();
    } else {
        set_error("Registration failed: " . $stmt->error);
    }

    $stmt->close();
} else {
    // If someone tries to access this page directly, redirect them to the homepage
    header("Location: ../index.php");
    exit();
}

$conn->close();
?>
