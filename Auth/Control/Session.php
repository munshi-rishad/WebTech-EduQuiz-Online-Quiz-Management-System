<?php
// Small helper for the Auth pages (Login, Profile, Password ...).
// All Auth pages are inside Auth/View, so these paths start from there.

// Home page of each type of user
function HomePage($role)
{
    if($role=="admin") return "../../Admin/MainPage.php";
    if($role=="instructor") return "../../Instructor/MainPage.php";
    return "../../Student/MainPage.php";
}

// Sends a user who is not logged in to the Login page
function RequireLogin()
{
    if(!isset($_SESSION["user_id"]))
    {
        Header("Location:Login.php");
        exit();
    }
}
?>
