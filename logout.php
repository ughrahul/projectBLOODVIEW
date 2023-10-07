<?php
session_start();
session_destroy();
header("Location: loginexample.php"); 
exit();
?>
