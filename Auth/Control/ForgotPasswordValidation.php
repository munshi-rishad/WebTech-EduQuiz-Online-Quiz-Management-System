<?php
// Controller for the Reset Password page:
// the user proves who they are with email + username and sets a new password.
include_once "../../model/DatabaseConnection.php";
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
    else if(strlen($password)<4)
    {
        $message="Password must be at least 4 characters";
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
        $result=$database->FindUserForReset($connection,$email,$username);

        if($result->num_rows==1)
        {
            $user=$result->fetch_assoc();
            $database->UpdatePassword($connection,(int)$user["id"],$password);
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
