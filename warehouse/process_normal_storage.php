<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');

echo "Script started<br>";

require_once 'db_connect.php';

echo "Database connected<br>";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "POST data received<br>";
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
    $storageDuration = $_POST['storageDuration'];
    $deliveryMethod = $_POST['deliveryMethod'];
    $deliveryDate = $_POST['deliveryDate'];
    $pickupDate = $_POST['pickupDate'];
    $additionalNotes = $_POST['additionalNotes'];

    try {
        $conn->begin_transaction();

        // Insert main request
        $sql = "INSERT INTO normal_storage_requests (
            customer_name, company_name, email, phone_number, billing_address, delivery_address,
            item_description, quantity, weight, dimensions, storage_duration,
            delivery_method, delivery_date, pickup_date, additional_notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssidssssss", 
            $customerName, $companyName, $email, $phoneNumber, $billingAddress, $deliveryAddress,
            $itemDescription, $quantity, $weight, $dimensions, $storageDuration,
            $deliveryMethod, $deliveryDate, $pickupDate, $additionalNotes
        );
        $stmt->execute();

        $requestId = $conn->insert_id;
        echo "Main request inserted successfully. ID: $requestId<br>";

        // Insert additional services
        if (isset($_POST['additionalServices']) && is_array($_POST['additionalServices'])) {
            $additionalServicesSql = "INSERT INTO normal_storage_additional_services (request_id, service_name) VALUES (?, ?)";
            $additionalServicesStmt = $conn->prepare($additionalServicesSql);
            foreach ($_POST['additionalServices'] as $service) {
                $additionalServicesStmt->bind_param("is", $requestId, $service);
                $additionalServicesStmt->execute();
                echo "Additional service inserted: $service<br>";
            }
        }

        // Insert other services
        if (!empty($_POST['otherServices'])) {
            $otherServicesSql = "INSERT INTO normal_storage_other_services (request_id, service_description) VALUES (?, ?)";
            $otherServicesStmt = $conn->prepare($otherServicesSql);
            $otherServicesStmt->bind_param("is", $requestId, $_POST['otherServices']);
            $otherServicesStmt->execute();
            echo "Other services inserted<br>";
        }

        $conn->commit();
        echo "Transaction committed successfully<br>";

        // Redirect to success page (uncomment when ready)
        // header("Location: submission_success.php");
        // exit();
    } catch(Exception $e) {
        $conn->rollback();
        echo "Error inserting data: " . $e->getMessage() . "<br>";
    }
} else {
    echo "No POST data received. This page should be accessed from a form submission.<br>";
}

echo "Script ended<br>";

$conn->close();
?>
