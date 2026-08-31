<?php
include "../../model/DatabaseConnection.php";
session_start();

if(!isset($_SESSION["user_id"]))
{
    Header("Location:Login.php");
    exit();
}

$message="";
$success="";
$database=new DatabaseConnection();
$connection=$database->OpenCon();
$id=(int)$_SESSION["user_id"];

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $old=trim($_POST["old"] ?? "");
    $new=trim($_POST["new"] ?? "");
    $confirm=trim($_POST["confirm"] ?? "");
    $result=$connection->query("SELECT password FROM users WHERE id=$id");
    $user=$result->fetch_assoc();

    if($old!=$user["password"])
    {
        $message="Current password is incorrect";
    }
    else if(strlen($new)<4)
    {
        $message="New password must be at least 4 characters";
    }
    else if($new!=$confirm)
    {
        $message="New passwords do not match";
    }
    else
    {
        $new=$connection->real_escape_string($new);
        $connection->query("UPDATE users SET password='$new' WHERE id=$id");
        $success="Password changed successfully";
    }
}

if($_SESSION["role"]=="admin") $home="../MainPage.php";
else if($_SESSION["role"]=="instructor") $home="../../Instructor/MainPage.php";
else $home="../../Student/MainPage.php";
?>
