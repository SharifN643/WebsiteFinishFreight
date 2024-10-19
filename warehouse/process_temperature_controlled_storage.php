<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');

require_once '../db_connect.php';

// Start the session
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have a user authentication system and the user_id is stored in the session
    $userId = $_SESSION['user_id']; // Make sure to start the session at the beginning of your script

    // Collect form data
    $customerName = $_POST['customerName'];
    $companyName = $_POST['companyName'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $billingAddress = $_POST['billingAddress'];
    $deliveryAddress = $_POST['deliveryAddress'];
    $itemDescription = $_POST['itemDescription'];
    $quantity = $_POST['quantity'];
    $weight = $_POST['weight'];
    $dimensions = $_POST['dimensions'];
    $temperatureRange = $_POST['temperatureRange'];
    $specialHandling = $_POST['specialHandling'];
    $storageDuration = $_POST['storageDuration'];
    $deliveryMethod = $_POST['deliveryMethod'];
    $deliveryDate = $_POST['deliveryDate'];
    $pickupDate = $_POST['pickupDate'];
    $additionalNotes = $_POST['additionalNotes'];

    try {
        $conn->begin_transaction();

        // Prepare SQL statement for main request (updated to include user_id)
        $sql = "INSERT INTO temperature_controlled_storage_requests (
            user_id, customer_name, company_name, email, phone_number, billing_address, delivery_address,
            item_description, quantity, weight, dimensions, temperature_range, special_handling,
            storage_duration, delivery_method, delivery_date, pickup_date, additional_notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssssssidssssssss", 
            $userId, $customerName, $companyName, $email, $phoneNumber, $billingAddress, $deliveryAddress,
            $itemDescription, $quantity, $weight, $dimensions, $temperatureRange, $specialHandling,
            $storageDuration, $deliveryMethod, $deliveryDate, $pickupDate, $additionalNotes
        );
        $stmt->execute();

        $requestId = $conn->insert_id;

        // Insert additional services
        if (isset($_POST['additionalServices']) && is_array($_POST['additionalServices'])) {
            $additionalServicesSql = "INSERT INTO storage_additional_services (request_id, service_name) VALUES (?, ?)";
            $additionalServicesStmt = $conn->prepare($additionalServicesSql);
            foreach ($_POST['additionalServices'] as $service) {
                $additionalServicesStmt->bind_param("is", $requestId, $service);
                $additionalServicesStmt->execute();
            }
        }

        // Insert other services
        if (!empty($_POST['otherServices'])) {
            $otherServicesSql = "INSERT INTO storage_other_services (request_id, service_description) VALUES (?, ?)";
            $otherServicesStmt = $conn->prepare($otherServicesSql);
            $otherServicesStmt->bind_param("is", $requestId, $_POST['otherServices']);
            $otherServicesStmt->execute();
        }

        $conn->commit();

        // Redirect to success page
        header("Location: submission_success.php");
        exit();
    } catch(Exception $e) {
        $conn->rollback();
        // Log the error
        error_log("Error inserting data: " . $e->getMessage());
        // Redirect to an error page
        header("Location: submission_error.php");
        exit();
    }
} else {
    // Redirect to the form page if accessed directly
    header("Location: temperature_controlled_storage_form.php");
    exit();
}

$conn->close();
?>
