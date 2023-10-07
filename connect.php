 <?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$database = "mydatabase"; 

$con = mysqli_connect($servername, $db_username, $db_password, $database);

if (!$con) {
    die("Unable to connect to the database: " . mysqli_connect_error());
}
?>
