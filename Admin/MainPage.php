<?php
session_start();

if(!isset($_SESSION["user_id"]))
{
    Header("Location:View/Login.php");
}
else if($_SESSION["role"]=="admin")
{
    Header("Location:View/Admin.php?action=dashboard");
}
else if($_SESSION["role"]=="instructor")
{
    Header("Location:../Instructor/MainPage.php");
}
else
{
    Header("Location:../Student/MainPage.php");
}
?>
