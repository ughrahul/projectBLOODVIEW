<?php
include("connect.php"); // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"];
    $bloodType = $_POST["bloodType"];
    
    // Check if the blood type does not have any sign (+ or -)
    if (strpos($bloodType, '+') === false && strpos($bloodType, '-') === false) {
        // Add a positive sign (+) if it doesn't have any sign
        $bloodType = $bloodType . '+';
    }
    
    $recordDate = $_POST["recordDate"];
    $bloodPints = $_POST["bloodPints"];

    if ($action == "deposit") {
        // Deposit action
        $sql = "INSERT INTO blood_stock (blood_type, record_date, blood_pints) VALUES ('$bloodType', '$recordDate', '$bloodPints')";

        if (mysqli_query($con, $sql)) {
            echo "Success"; // You can return a success message if needed
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } elseif ($action == "withdraw") {
        // Withdraw action
        // Redirect to withdraw.php with POST data
        echo "<form id='withdrawForm' action='withdraw.php' method='post'>";
        echo "<input type='hidden' name='action' value='withdraw'>";
        echo "<input type='hidden' name='bloodType' value='$bloodType'>";
        echo "<input type='hidden' name='recordDate' value='$recordDate'>";
        echo "<input type='hidden' name='bloodPints' value='$bloodPints'>";
        echo "</form>";
        echo "<script>document.getElementById('withdrawForm').submit();</script>";
    } else {
        // Invalid action
        echo "Invalid action";
    }
}
?>
