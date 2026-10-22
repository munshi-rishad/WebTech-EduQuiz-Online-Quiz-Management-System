<?php
// Controller for the Admin panel.
// 1) POST (Toggle / Delete / Remove buttons): change the data, then go back to the page.
// 2) GET (included by View/Admin.php): load the data that the page shows.
include_once "../../model/AdminModel.php";
session_start();

if(!isset($_SESSION["user_id"]) || $_SESSION["role"]!="admin")
{
    Header("Location:../../Auth/View/Login.php");
    exit();
}

$model=new AdminModel();
$connection=$model->OpenCon();
$me=(int)$_SESSION["user_id"];

// ---------- 1) Button actions ----------
if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $mode=$_POST["mode"] ?? "";

    if($mode=="toggle_user" || $mode=="delete_user")
    {
        $userId=(int)($_POST["user_id"] ?? 0);

        // the admin cannot block or delete their own account here
        if($userId!=0 && $userId!=$me)
        {
            if($mode=="toggle_user") $model->ToggleUser($connection,$userId);
            else $model->DeleteUser($connection,$userId);
        }
        Header("Location:../View/Admin.php?action=users");
    }
    else if($mode=="delete_course")
    {
        $courseId=(int)($_POST["course_id"] ?? 0);
        if($courseId!=0)
        {
            $model->DeleteCourse($connection,$courseId);
        }
        Header("Location:../View/Admin.php?action=courses");
    }
    else
    {
        Header("Location:../View/Admin.php?action=dashboard");
    }
    exit();
}

// ---------- 2) Data for the page ----------
$action=$_GET["action"] ?? "dashboard";

if($action=="dashboard")
{
    $totalUsers=$model->CountUsers($connection);
    $totalCourses=$model->CountCourses($connection);
    $totalAttempts=$model->CountAttempts($connection);
}
else if($action=="users")
{
    $users=$model->GetAllUsers($connection);
}
else if($action=="courses")
{
    $courses=$model->GetAllCourses($connection);
}
else
{
    $action="reports";
    $totalStudents=$model->CountUsers($connection,"student");
    $totalInstructors=$model->CountUsers($connection,"instructor");
    $totalCourses=$model->CountCourses($connection);
    $totalQuizzes=$model->CountQuizzes($connection);
    $totalAttempts=$model->CountAttempts($connection);
    $recent=$model->GetRecentAttempts($connection);
}
?>
