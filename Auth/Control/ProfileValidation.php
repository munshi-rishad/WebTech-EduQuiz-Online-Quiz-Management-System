<?php
// Controller for the Profile page (view, edit and delete account)
include_once "../../model/DatabaseConnection.php";
include_once "Session.php";
session_start();
RequireLogin();

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
            $safePhone=$connection->real_escape_string($phone);
            $samePhone=$database->CheckPhone($connection,$safePhone);
            $phoneOwner=$samePhone->num_rows>0 ? (int)$samePhone->fetch_assoc()["id"] : 0;

            if($phone!="" && $phoneOwner!=0 && $phoneOwner!=$id)
            {
                $message="Phone number already exists";
            }
            else
            {
                $database->UpdateProfile($connection,$id,$connection->real_escape_string($name),$safePhone,$connection->real_escape_string($department));
                $_SESSION["name"]=$name;
                $success="Profile updated";
            }
        }
    }
    else if($mode=="delete")
    {
        $password=trim($_POST["password"] ?? "");
        $user=$database->GetUserById($connection,$id);

        if($user["password"]==$password)
        {
            $database->DeleteUser($connection,$id);
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

$user=$database->GetUserById($connection,$id);
$home=HomePage($_SESSION["role"]);
?>
