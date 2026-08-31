<?php
include "../../model/DatabaseConnection.php";
session_start();

if(!isset($_SESSION["user_id"]) || $_SESSION["role"]!="admin")
{
    Header("Location:../View/Login.php");
    exit();
}

$database=new DatabaseConnection();
$connection=$database->OpenCon();
$mode=$_POST["mode"] ?? "";
$me=(int)$_SESSION["user_id"];

if($mode=="toggle_user")
{
    $id=(int)($_POST["user_id"] ?? 0);
    if($id!=0 && $id!=$me)
    {
        $connection->query("UPDATE users SET is_active=1-is_active WHERE id=$id");
    }
    Header("Location:../View/Admin.php?action=users");
}
else if($mode=="delete_user")
{
    $id=(int)($_POST["user_id"] ?? 0);
    if($id!=0 && $id!=$me)
    {
        $connection->query("DELETE FROM users WHERE id=$id");
    }
    Header("Location:../View/Admin.php?action=users");
}
else if($mode=="delete_course")
{
    $id=(int)($_POST["course_id"] ?? 0);
    if($id!=0)
    {
        $connection->query("DELETE FROM courses WHERE id=$id");
    }
    Header("Location:../View/Admin.php?action=courses");
}
else
{
    Header("Location:../View/Admin.php?action=dashboard");
}
?>
