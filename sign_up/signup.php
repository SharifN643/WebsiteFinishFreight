<?php
session_start();
require_once '../db_connect.php'; // Adjust the path if necessary

// Enable error reporting for development (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to log errors
function logError($message) {
    error_log(date('[Y-m-d H:i:s] ') . "Signup Error: " . $message . "\n", 3, "../error.log");
}

// Check database connection
if ($conn->connect_error) {
    logError("Database connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    logError("Received POST data: Username - $username, Email - $email"); // Log received data

    // Validate input
    $errors = [];
    if (empty($username)) {
        $errors[] = 'Username is required.';
    }
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }
    if (empty($password)) {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if (!empty($errors)) {  
        // Redirect back with errors
        $error_message = implode(' ', $errors);
        logError($error_message);
        header("Location: ../index.php?signup_error=" . urlencode($error_message));
        exit();
    }

    // Check if username or email already exists using prepared statements
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
    if (!$stmt) {
        logError("Prepare Statement Failed: " . $conn->error);
        die("Prepare failed: " . $conn->error); // Output error for debugging
    }
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Username or email already taken
        logError("Username or Email already taken: Username - $username, Email - $email");
        $stmt->close();
        header("Location: ../index.php?signup_error=Username or email already taken.");
        exit();
    }
    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert the new user using prepared statements
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'customer')");
    if (!$stmt) {
        logError("Prepare Statement Failed: " . $conn->error);
        die("Prepare failed: " . $conn->error); // Output error for debugging
    }
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        // Registration successful, set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'customer';
        $stmt->close();
        logError("Registration successful for user: $username");
        header("Location: ../index.php?signup_success=1");
        exit();
    } else {
        // Error during insertion
        logError("Error during registration: " . $stmt->error);
        die("Execute failed: " . $stmt->error); // Output error for debugging
    }
} else {
    // Invalid request method
    header("Location: ../index.php");
    exit();
}
?>
