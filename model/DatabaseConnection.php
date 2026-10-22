<?php
// Model: database connection + every query about user accounts
// (login, registration, profile, password).
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

    // Puts all rows of a query result into an array
    function GetRows($result)
    {
        $rows=array();
        while($row=$result->fetch_assoc())
        {
            $rows[]=$row;
        }
        return $rows;
    }

    // Returns the "total" column of a COUNT(*) query
    function GetTotal($conn,$sql)
    {
        $row=$conn->query($sql)->fetch_assoc();
        return $row["total"];
    }

    // ---------- Login ----------

    function CheckUser($conn,$email,$password)
    {
        $result=$conn->query("SELECT * FROM users WHERE email='".$email."' AND password='".$password."' AND is_active=1");
        return $result;
    }

    // ---------- Registration (also used by the AJAX checks) ----------

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

    function AddUser($conn,$name,$username,$email,$password,$phone,$department,$role)
    {
        $sql="INSERT INTO users(name,username,email,password,phone,department,role) VALUES('$name','$username','$email','$password','$phone','$department','$role')";
        return $conn->query($sql);
    }

    // ---------- Profile ----------

    function GetUserById($conn,$id)
    {
        $result=$conn->query("SELECT * FROM users WHERE id=$id");
        return $result->fetch_assoc();
    }

    function UpdateProfile($conn,$id,$name,$phone,$department)
    {
        return $conn->query("UPDATE users SET name='$name',phone='$phone',department='$department' WHERE id=$id");
    }

    function DeleteUser($conn,$id)
    {
        return $conn->query("DELETE FROM users WHERE id=$id");
    }

    // ---------- Password ----------

    function UpdatePassword($conn,$id,$password)
    {
        return $conn->query("UPDATE users SET password='$password' WHERE id=$id");
    }

    function FindUserForReset($conn,$email,$username)
    {
        $result=$conn->query("SELECT * FROM users WHERE email='$email' AND username='$username' AND is_active=1");
        return $result;
    }
}
?>
