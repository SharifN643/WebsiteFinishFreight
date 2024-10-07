<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
require_once '../db_connect.php'; // Adjust the path as necessary

// Start a transaction
$conn->begin_transaction();

try {
    // Insert into freight_requests
    $stmt = $conn->prepare("INSERT INTO freight_requests (user_id, pickup_address, pickup_date, pickup_time, pickup_contact, pickup_contact_number, delivery_address, delivery_date, delivery_time, special_instructions, additional_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // Assume user_id is obtained from session
    session_start();
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        throw new Exception("User not logged in.");
    }

    $stmt->bind_param("issssssssss", $user_id, $pickupAddress, $pickupDate, $pickupTime, $pickupContact, $pickupContactNumber, $deliveryAddress, $deliveryDate, $deliveryTime, $specialInstructions, $additionalInfo);

    // Set parameters
    $pickupAddress = $_POST['pickupAddress'];
    $pickupDate = $_POST['pickupDate'];
    $pickupTime = $_POST['pickupTime'];
    $pickupContact = $_POST['pickupContact'] ?? null;
    $pickupContactNumber = $_POST['pickupContactNumber'] ?? null;
    $deliveryAddress = $_POST['deliveryAddress'];
    $deliveryDate = $_POST['deliveryDate'] ?? null;
    $deliveryTime = $_POST['deliveryTime'] ?? null;
    $specialInstructions = $_POST['specialInstructions'] ?? null;
    $additionalInfo = $_POST['additionalInfo'] ?? null;

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    // Get the last inserted request_id
    $request_id = $conn->insert_id;

    // Insert into items
    $stmt = $conn->prepare("INSERT INTO items (request_id, item_type, item_description, item_quantity, item_weight, item_dimensions) VALUES (?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("issids", $request_id, $itemType, $itemDescription, $itemQuantity, $itemWeight, $itemDimensions);

    // Set item parameters
    $itemType = $_POST['itemType'];
    $itemDescription = $_POST['itemDescription'] ?? null;
    $itemQuantity = $_POST['itemQuantity'];
    $itemWeight = $_POST['itemWeight'];
    $itemDimensions = $_POST['itemDimensions'] ?? null;

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    // Insert into services
    $stmt = $conn->prepare("INSERT INTO services (request_id, insurance, packaging) VALUES (?, ?, ?)");

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("iii", $request_id, $insurance, $packaging);

    // Set service parameters
    $insurance = isset($_POST['insurance']) ? 1 : 0;
    $packaging = isset($_POST['packaging']) ? 1 : 0;

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    // Commit transaction
    $conn->commit();

    // Redirect to thank you page after successful insertion
    header("Location: thank_you.php");
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo "Error: " . $e->getMessage() . "<br>";
}

$stmt->close();
$conn->close();
?>