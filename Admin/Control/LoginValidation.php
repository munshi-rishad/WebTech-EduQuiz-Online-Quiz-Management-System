<?php
include "../../model/DatabaseConnection.php";
session_start();

if(isset($_SESSION["user_id"]))
{
    if($_SESSION["role"]=="admin") Header("Location:../MainPage.php");
    else if($_SESSION["role"]=="instructor") Header("Location:../../Instructor/MainPage.php");
    else Header("Location:../../Student/MainPage.php");
    exit();
}

$email="";
$password="";
$message="";
$remember=false;

if(isset($_COOKIE["remember_email"]))
{
    $email=$_COOKIE["remember_email"];
}

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $email=trim($_POST["email"] ?? "");
    $password=trim($_POST["password"] ?? "");
    $remember=isset($_POST["remember"]);

    if(empty($email) || empty($password))
    {
        $message="Email and Password are required";
    }
    else
    {
        $database=new DatabaseConnection();
        $connection=$database->OpenCon();

        $email=$connection->real_escape_string($email);
        $password=$connection->real_escape_string($password);
        $result=$database->CheckUser($connection,$email,$password);

        if($result->num_rows==1)
        {
            $user=$result->fetch_assoc();
            $_SESSION["user_id"]=$user["id"];
            $_SESSION["name"]=$user["name"];
            $_SESSION["role"]=$user["role"];

            if($remember)
            {
                setcookie("remember_email",$email,time()+60*60*24*7,"/");
            }
            else
            {
                setcookie("remember_email","",time()-3600,"/");
            }

            if($user["role"]=="admin") Header("Location:../MainPage.php");
            else if($user["role"]=="instructor") Header("Location:../../Instructor/MainPage.php");
            else Header("Location:../../Student/MainPage.php");
            exit();
        }
        else
        {
            $message="Invalid email or password";
        }
    }
}
?>
