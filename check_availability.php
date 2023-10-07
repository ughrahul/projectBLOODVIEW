<?php
include("connect.php"); // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $bloodType = $_GET["bloodType"];
    $bloodPints = $_GET["bloodPints"];

    // Check if the blood type does not have any sign (+ or -)
    if (strpos($bloodType, '+') === false && strpos($bloodType, '-') === false) {
        // Add a positive sign (+) if it doesn't have any sign
        $bloodType = $bloodType . '+';
    }

    // Query to calculate the available blood units
    $availableQuery = "SELECT SUM(blood_pints) AS available_pints FROM blood_stock WHERE blood_type = '$bloodType'";
    
    $result = mysqli_query($con, $availableQuery);

    if (!$result) {
        // Handle database query error
        echo 0; // Return 0 to indicate an error
    } else {
        $row = mysqli_fetch_assoc($result);
        $availablePints = (int)$row['available_pints'];
        echo $availablePints;
    }
}

mysqli_close($con);
?>
