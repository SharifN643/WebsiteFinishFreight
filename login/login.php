<?php
session_start();
require_once '../db_connect.php'; // Adjust the path to point to the correct location

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Username and password are required";
    } else {
        $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // Regenerate session ID for security
                session_regenerate_id(true);
                
                header("Location: ../index.php");
                exit;
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "Invalid username or password";
        }

        $stmt->close();
    }

    $conn->close();

    if (isset($error)) {
        error_log("Login error: " . $error); // Log the error
        header("Location: ../index.php?error=" . urlencode($error));
        exit;
    }
} else {
    // If not a POST request, redirect to the index page
    header("Location: ../index.php");
    exit;
}
?>
