<?php
include "../../model/DatabaseConnection.php";
$phone=$_POST["phone"] ?? "";

if(!$phone)
{
    echo "";
}
else
{
    $database=new DatabaseConnection();
    $connection=$database->OpenCon();
    $phone=$connection->real_escape_string($phone);
    $result=$database->CheckPhone($connection,$phone);

    if($result->num_rows>0)
    {
        echo "Phone Number Already Taken";
    }
    else
    {
        echo "Phone Number Available";
    }
}
?>
