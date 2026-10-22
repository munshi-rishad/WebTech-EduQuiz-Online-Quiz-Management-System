<?php
// Home of the Instructor panel: only a logged-in instructor can open it
session_start();
if(!isset($_SESSION["user_id"]) || $_SESSION["role"]!="instructor")
{
    Header("Location:../Auth/View/Login.php");
    exit();
}
Header("Location:View/Instructor.php?action=dashboard");
?>
