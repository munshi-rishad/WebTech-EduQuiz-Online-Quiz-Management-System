<?php
include "../../model/DatabaseConnection.php";
$message="";

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $email=trim($_POST["email"] ?? "");
    $username=trim($_POST["username"] ?? "");
    $password=trim($_POST["password"] ?? "");
    $confirm=trim($_POST["confirm"] ?? "");

    if(empty($email) || empty($username) || empty($password))
    {
        $message="All fields are required";
    }
    else if($password!=$confirm)
    {
        $message="Passwords do not match";
    }
    else
    {
        $database=new DatabaseConnection();
        $connection=$database->OpenCon();
        $email=$connection->real_escape_string($email);
        $username=$connection->real_escape_string($username);
        $password=$connection->real_escape_string($password);
        $result=$connection->query("SELECT * FROM users WHERE email='$email' AND username='$username' AND is_active=1");

        if($result->num_rows==1)
        {
            $connection->query("UPDATE users SET password='$password' WHERE email='$email' AND username='$username'");
            Header("Location:Login.php");
            exit();
        }
        else
        {
            $message="User not found";
        }
    }
}
?>
