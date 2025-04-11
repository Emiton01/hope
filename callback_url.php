<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection credentials
$servername = "localhost";
$username = "root";
$password = "";
$database = "cfms_db";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}

// Get the M-PESA response
$mpesaResponse = file_get_contents('php://input');
$data = json_decode($mpesaResponse, true);

// Log the full M-PESA response for debugging
$logFile = "M_PESAConfirmationResponse.txt";
file_put_contents($logFile, json_encode($data, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);

// Extract transaction details from M-PESA response
$amount = $data['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'] ?? 0;
$transaction_code = $data['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'] ?? '';
$transaction_date = $data['Body']['stkCallback']['CallbackMetadata']['Item'][3]['Value'] ?? '';
$phone_number = $data['Body']['stkCallback']['CallbackMetadata']['Item'][4]['Value'] ?? '';

// Convert transaction date format (YYYYMMDDHHMMSS → YYYY-MM-DD HH:MM:SS)
if (!empty($transaction_date)) {
    $date = DateTime::createFromFormat('YmdHis', $transaction_date);
    $formattedDate = $date ? $date->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'); // Default to current timestamp if conversion fails
} else {
    $formattedDate = date('Y-m-d H:i:s'); // Default value if transaction date is missing
}

// Default values for donation
$user_id = 1; // Replace with logic to get actual user ID
$group_id = 1; // Replace with logic to get actual group ID
$type = 'donation'; // Change based on context (tithe, offering, donation)
$payment_method = 'Mpesa';

// Insert transaction into the donations table using prepared statement
$sql = "INSERT INTO donations (user_id, group_id, amount, type, payment_method, transaction_code, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iidssss", $user_id, $group_id, $amount, $type, $payment_method, $transaction_code, $formattedDate);

if ($stmt->execute()) {
    file_put_contents("M_PESAInsertSuccess.txt", "Transaction Saved: $transaction_code" . PHP_EOL, FILE_APPEND);
} else {
    file_put_contents("M_PESAInsertError.txt", "Error: " . $stmt->error . PHP_EOL, FILE_APPEND);
}

// Close the database connection
$stmt->close();
$conn->close();

// Respond to Safaricom (ACK Response)
header("Content-Type: application/json");
$response = [
    "ResultCode" => 0,
    "ResultDesc" => "Confirmation Received Successfully"
];
echo json_encode($response);
?>