<?php
// Controller for the Registration page
include_once "../../model/DatabaseConnection.php";
$message="";

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $name=trim($_POST["name"] ?? "");
    $username=trim($_POST["username"] ?? "");
    $email=trim($_POST["email"] ?? "");
    $phone=trim($_POST["phone"] ?? "");
    $department=trim($_POST["department"] ?? "");
    $role=$_POST["role"] ?? "student";
    $password=trim($_POST["password"] ?? "");
    $confirm=trim($_POST["confirm"] ?? "");

    if(empty($name) || empty($username) || empty($email) || empty($password))
    {
        $message="Required fields cannot be empty";
    }
    else if($role!="student" && $role!="instructor")
    {
        // Only students and instructors can sign up. Admin accounts are added in the database.
        $message="Invalid account type";
    }
    else if(strlen($username)<3)
    {
        $message="Username must be at least 3 characters";
    }
    else if(!filter_var($email,FILTER_VALIDATE_EMAIL))
    {
        $message="Enter a valid email";
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

        $name=$connection->real_escape_string($name);
        $username=$connection->real_escape_string($username);
        $email=$connection->real_escape_string($email);
        $phone=$connection->real_escape_string($phone);
        $department=$connection->real_escape_string($department);
        $password=$connection->real_escape_string($password);

        if($database->CheckUsername($connection,$username)->num_rows>0)
        {
            $message="Username already exists";
        }
        else if($database->CheckEmail($connection,$email)->num_rows>0)
        {
            $message="Email already exists";
        }
        else if($phone!="" && $database->CheckPhone($connection,$phone)->num_rows>0)
        {
            $message="Phone number already exists";
        }
        else if($database->AddUser($connection,$name,$username,$email,$password,$phone,$department,$role))
        {
            Header("Location:Login.php");
            exit();
        }
        else
        {
            $message="Registration failed";
        }
    }
}
?>
