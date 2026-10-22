<?php
// Home of the Admin panel: only a logged-in admin can open it
session_start();
if(!isset($_SESSION["user_id"]) || $_SESSION["role"]!="admin")
{
    Header("Location:../Auth/View/Login.php");
    exit();
}
Header("Location:View/Admin.php?action=dashboard");
?>
