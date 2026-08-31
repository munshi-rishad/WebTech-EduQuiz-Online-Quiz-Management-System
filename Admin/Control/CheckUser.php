<?php
include "../../model/DatabaseConnection.php";
$username=$_POST["username"] ?? "";

if(!$username)
{
    echo "Username Required";
}
else
{
    $database=new DatabaseConnection();
    $connection=$database->OpenCon();
    $username=$connection->real_escape_string($username);
    $result=$database->CheckUsername($connection,$username);

    if($result->num_rows>0)
    {
        echo "UserName Already Taken";
    }
    else
    {
        echo "User Name Available";
    }
}
?>
