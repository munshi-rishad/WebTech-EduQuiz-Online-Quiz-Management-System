<?php
// Controller for the Login page
include_once "../../model/DatabaseConnection.php";
include_once "Session.php";
session_start();

// Already logged in? Go straight to the dashboard
if(isset($_SESSION["user_id"]))
{
    Header("Location:".HomePage($_SESSION["role"]));
    exit();
}

$email="";
$message="";

// "Remember Me" fills the email box
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

        $safeEmail=$connection->real_escape_string($email);
        $safePassword=$connection->real_escape_string($password);
        $result=$database->CheckUser($connection,$safeEmail,$safePassword);

        if($result->num_rows==1)
        {
            $user=$result->fetch_assoc();
            $_SESSION["user_id"]=$user["id"];
            $_SESSION["name"]=$user["name"];
            $_SESSION["role"]=$user["role"];

            if($remember)
            {
                setcookie("remember_email",$email,time()+60*60*24*7,"/");   // 7 days
            }
            else
            {
                setcookie("remember_email","",time()-3600,"/");             // delete the cookie
            }

            Header("Location:".HomePage($user["role"]));
            exit();
        }
        else
        {
            $message="Invalid email or password";
        }
    }
}
?>
