<?php
// Home of the Student panel: only a logged-in student can open it
session_start();
if(!isset($_SESSION["user_id"]) || $_SESSION["role"]!="student")
{
    Header("Location:../Auth/View/Login.php");
    exit();
}
Header("Location:View/Student.php?action=dashboard");
?>
