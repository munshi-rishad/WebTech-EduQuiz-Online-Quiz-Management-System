<?php
// Start page: logged-in users go to their dashboard, others to the Login page
session_start();

if(!isset($_SESSION["user_id"]))
{
    Header("Location:Auth/View/Login.php");
}
else if($_SESSION["role"]=="admin")
{
    Header("Location:Admin/MainPage.php");
}
else if($_SESSION["role"]=="instructor")
{
    Header("Location:Instructor/MainPage.php");
}
else
{
    Header("Location:Student/MainPage.php");
}
?>
