<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db_connect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    $additionalServices = isset($_POST['additionalServices']) ? implode(", ", $_POST['additionalServices']) : '';
    $otherServices = $_POST['otherServices'];
    $deliveryMethod = $_POST['deliveryMethod'];
    $deliveryDate = $_POST['deliveryDate'];
    $pickupDate = $_POST['pickupDate'];
    $additionalNotes = $_POST['additionalNotes'];

    // Prepare SQL statement
    $sql = "INSERT INTO temperature_controlled_storage_requests (
        customer_name, company_name, email, phone_number, billing_address, delivery_address,
        item_description, quantity, weight, dimensions, temperature_range, special_handling,
        storage_duration, additional_services, other_services, delivery_method, delivery_date,
        pickup_date, additional_notes
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssidssssssssss", 
            $customerName, $companyName, $email, $phoneNumber, $billingAddress, $deliveryAddress,
            $itemDescription, $quantity, $weight, $dimensions, $temperatureRange, $specialHandling,
            $storageDuration, $additionalServices, $otherServices, $deliveryMethod, $deliveryDate,
            $pickupDate, $additionalNotes
        );
        $stmt->execute();

        echo "Data inserted successfully";

        // Redirect to a success page
        // header("Location: submission_success.php");
        // exit();
    } catch(Exception $e) {
        echo "Error inserting data: " . $e->getMessage();
    }
}

$conn->close();
?>
