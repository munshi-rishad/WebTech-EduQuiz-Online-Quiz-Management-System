<?php
// Logs the user out and goes back to the Login page
session_start();
session_unset();
session_destroy();
Header("Location:../View/Login.php");
?>
