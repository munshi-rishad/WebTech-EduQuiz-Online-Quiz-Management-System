<?php
include "../../model/DatabaseConnection.php";
session_start();

if(!isset($_SESSION["user_id"]))
{
    Header("Location:Login.php");
    exit();
}

$database=new DatabaseConnection();
$connection=$database->OpenCon();
$id=(int)$_SESSION["user_id"];
$message="";
$success="";

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $mode=$_POST["mode"] ?? "update";

    if($mode=="update")
    {
        $name=trim($_POST["name"] ?? "");
        $phone=trim($_POST["phone"] ?? "");
        $department=trim($_POST["department"] ?? "");

        if(empty($name))
        {
            $message="Name is required";
        }
        else
        {
            $name=$connection->real_escape_string($name);
            $phone=$connection->real_escape_string($phone);
            $department=$connection->real_escape_string($department);
            $connection->query("UPDATE users SET name='$name',phone='$phone',department='$department' WHERE id=$id");
            $_SESSION["name"]=$name;
            $success="Profile updated";
        }
    }

    if($mode=="delete")
    {
        $password=trim($_POST["password"] ?? "");
        $password=$connection->real_escape_string($password);
        $check=$connection->query("SELECT * FROM users WHERE id=$id AND password='$password'");

        if($check->num_rows==1)
        {
            $connection->query("DELETE FROM users WHERE id=$id");
            session_unset();
            session_destroy();
            Header("Location:Login.php");
            exit();
        }
        else
        {
            $message="Password is incorrect. Account was not deleted";
        }
    }
}

$result=$connection->query("SELECT * FROM users WHERE id=$id");
$user=$result->fetch_assoc();

if($_SESSION["role"]=="admin") $home="../MainPage.php";
else if($_SESSION["role"]=="instructor") $home="../../Instructor/MainPage.php";
else $home="../../Student/MainPage.php";
?>
