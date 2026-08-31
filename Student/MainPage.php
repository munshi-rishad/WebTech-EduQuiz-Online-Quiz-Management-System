<?php
session_start();
if(!isset($_SESSION["user_id"]) || $_SESSION["role"]!="student")
{
    Header("Location:../Admin/View/Login.php");
    exit();
}
Header("Location:View/Student.php?action=dashboard");
?>
