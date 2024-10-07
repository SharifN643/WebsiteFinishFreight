<?php
session_start();
require_once '../db_connect.php'; // Adjust the path to db_connect.php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input
    $full_name = trim($_POST['username']); // Change variable name to $full_name
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate input
    if (empty($full_name) || empty($email) || empty($password)) {
        header("Location: ../index.php?signup_error=Please fill in all fields.");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../index.php?signup_error=Invalid email format.");
        exit();
    }

    // Check if full_name or email already exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE full_name = ? OR email = ?"); // Use 'full_name' instead of 'username'
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("ss", $full_name, $email); // Bind $full_name instead of $username
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: ../index.php?signup_error=Username or email already taken.");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user into the database
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'customer')"); // Use 'full_name' instead of 'username'
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("sss", $full_name, $email, $hashed_password); // Bind $full_name instead of $username

    if ($stmt->execute()) {
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $conn->insert_id; // Store the new user's ID
        $_SESSION['username'] = $full_name;
        $_SESSION['role'] = 'customer'; // Default role
        header("Location: ../index.php");
    } else {
        header("Location: ../index.php?signup_error=Error during registration. Please try again.");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../index.php");
    exit();
}
?>
