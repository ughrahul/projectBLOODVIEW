<?php
include("connect.php"); // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bloodType = $_POST["bloodType"];

    // Check if the blood type does not have any sign (+ or -)
    if (strpos($bloodType, '+') === false && strpos($bloodType, '-') === false) {
        // Add a positive sign (+) if it doesn't have any sign
        $bloodType = $bloodType . '+';
    }

    $recordDate = $_POST["recordDate"];
    $bloodPints = $_POST["bloodPints"];

    // You can perform validations here to ensure data integrity

    // Insert the data into withdrawal_records table
    $withdrawalQuery = "INSERT INTO withdrawal_records (blood_type, record_date, withdrawn_pints) VALUES ('$bloodType', '$recordDate', '$bloodPints')";
    
    if (mysqli_query($con, $withdrawalQuery)) {
        echo 'Withdrawal successful!';
    } else {
        echo 'Failed to add withdrawal record: ' . mysqli_error($con);
    }
}

mysqli_close($con);
?>
