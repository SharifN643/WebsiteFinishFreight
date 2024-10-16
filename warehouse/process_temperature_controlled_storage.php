<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Create connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

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
    ) VALUES (
        :customerName, :companyName, :email, :phoneNumber, :billingAddress, :deliveryAddress,
        :itemDescription, :quantity, :weight, :dimensions, :temperatureRange, :specialHandling,
        :storageDuration, :additionalServices, :otherServices, :deliveryMethod, :deliveryDate,
        :pickupDate, :additionalNotes
    )";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':customerName' => $customerName,
            ':companyName' => $companyName,
            ':email' => $email,
            ':phoneNumber' => $phoneNumber,
            ':billingAddress' => $billingAddress,
            ':deliveryAddress' => $deliveryAddress,
            ':itemDescription' => $itemDescription,
            ':quantity' => $quantity,
            ':weight' => $weight,
            ':dimensions' => $dimensions,
            ':temperatureRange' => $temperatureRange,
            ':specialHandling' => $specialHandling,
            ':storageDuration' => $storageDuration,
            ':additionalServices' => $additionalServices,
            ':otherServices' => $otherServices,
            ':deliveryMethod' => $deliveryMethod,
            ':deliveryDate' => $deliveryDate,
            ':pickupDate' => $pickupDate,
            ':additionalNotes' => $additionalNotes
        ]);

        // Redirect to a success page
        header("Location: submission_success.php");
        exit();
    } catch(PDOException $e) {
        die("Error inserting data: " . $e->getMessage());
    }
}
?>

