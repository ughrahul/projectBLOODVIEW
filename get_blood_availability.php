<?php
// Include the database connection
include("connect.php");

// Initialize an array to store blood availability data
$bloodAvailability = [];

// Code to retrieve data from the database
$bloodStockQuery = "SELECT * FROM blood_stock";
$withdrawalRecordsQuery = "SELECT * FROM withdrawal_records";

$bloodStockResult = mysqli_query($con, $bloodStockQuery);
$withdrawalRecordsResult = mysqli_query($con, $withdrawalRecordsQuery);

if (!$bloodStockResult || !$withdrawalRecordsResult) {
    // Handle database query errors
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Database query error"]);
    exit;
}

$bloodStockData = [];
$withdrawalRecordsData = [];

while ($row = mysqli_fetch_assoc($bloodStockResult)) {
    $bloodStockData[] = $row;
}

while ($row = mysqli_fetch_assoc($withdrawalRecordsResult)) {
    $withdrawalRecordsData[] = $row;
}

// Calculate and prepare the data for JSON response
$bloodGroups = ['A +', 'A-', 'B +', 'B-', 'O +', 'O-', 'AB +', 'AB-'];

foreach ($bloodGroups as $bloodType) {
    $netAvailability = calculateNetAvailability($bloodType, $bloodStockData, $withdrawalRecordsData);
    $bloodAvailability[$bloodType] = $netAvailability;
}

// Send the data as JSON response
header('Content-Type: application/json');
echo json_encode($bloodAvailability);

// Close the database connection
mysqli_close($con);

// Function to calculate net availability
function calculateNetAvailability($bloodType, $records, $withdrawals) {
    $depositedPints = 0;
    $withdrawnPints = 0;

    // Calculate deposited pints
    foreach ($records as $record) {
        if ($record['blood_type'] === $bloodType) {
            $depositedPints += $record['blood_pints'];
        }
    }

    // Calculate withdrawn pints
    foreach ($withdrawals as $withdrawal) {
        if ($withdrawal['blood_type'] === $bloodType) {
            $withdrawnPints += $withdrawal['withdrawn_pints'];
        }
    }

    return $depositedPints - $withdrawnPints;
}
?>
