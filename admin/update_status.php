<?php
session_start();
require_once '../db_connect.php';

// Check if user is logged in and has admin role
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Determine which table to update based on the type
    $table = ($type == 'normal') ? 'normal_storage_requests' : 'temperature_controlled_storage_requests';

    // Update the status in the database
    $sql = "UPDATE $table SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);
    
    if ($stmt->execute()) {
        // Redirect back to the admin warehouse page with a success message
        header("Location: admin_warehouse.php?message=Status updated successfully");
    } else {
        // Redirect back to the admin warehouse page with an error message
        header("Location: admin_warehouse.php?error=Failed to update status");
    }

    $stmt->close();
} else {
    // If accessed directly without POST data, redirect to the admin warehouse page
    header("Location: admin_warehouse.php");
}

$conn->close();
?>

