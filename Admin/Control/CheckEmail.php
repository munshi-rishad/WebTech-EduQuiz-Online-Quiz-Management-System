<?php
include "../../model/DatabaseConnection.php";
$email=$_POST["email"] ?? "";

if(!$email)
{
    echo "Email Required";
}
else
{
    $database=new DatabaseConnection();
    $connection=$database->OpenCon();
    $email=$connection->real_escape_string($email);
    $result=$database->CheckEmail($connection,$email);

    if($result->num_rows>0)
    {
        echo "Email Already Taken";
    }
    else
    {
        echo "Email Available";
    }
}
?>
