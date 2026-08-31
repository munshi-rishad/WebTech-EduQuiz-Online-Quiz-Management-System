<?php
class DatabaseConnection
{
    function OpenCon()
    {
        $dbhost="localhost";
        $dbuser="root";
        $dbpass="";
        $db="eduquiz_short";

        $conn=new mysqli($dbhost,$dbuser,$dbpass,$db);

        if($conn->connect_error)
        {
            die("Please Connect The Database");
        }

        return $conn;
    }

    function CheckUser($conn,$email,$password)
    {
        $result=$conn->query("SELECT * FROM users WHERE email='".$email."' AND password='".$password."' AND is_active=1");
        return $result;
    }

    function CheckUsername($conn,$username)
    {
        $result=$conn->query("SELECT * FROM users WHERE username='".$username."'");
        return $result;
    }

    function CheckEmail($conn,$email)
    {
        $result=$conn->query("SELECT * FROM users WHERE email='".$email."'");
        return $result;
    }

    function CheckPhone($conn,$phone)
    {
        $result=$conn->query("SELECT * FROM users WHERE phone='".$phone."' AND phone!=''");
        return $result;
    }
}
?>
