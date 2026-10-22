<?php
// Controller for the Change Password page
include_once "../../model/DatabaseConnection.php";
include_once "Session.php";
session_start();
RequireLogin();

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
    $user=$database->GetUserById($connection,$id);

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
        $database->UpdatePassword($connection,$id,$connection->real_escape_string($new));
        $success="Password changed successfully";
    }
}

$home=HomePage($_SESSION["role"]);
?>
