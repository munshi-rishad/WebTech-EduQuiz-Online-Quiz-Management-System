<?php
// Controller for the Student panel.
// 1) POST (Enroll / Submit Quiz): save the data, then go back to the page.
// 2) GET (included by View/Student.php): load the data that the page shows.
include_once "../../model/StudentModel.php";
session_start();

if(!isset($_SESSION["user_id"]) || $_SESSION["role"]!="student")
{
    Header("Location:../../Auth/View/Login.php");
    exit();
}

$model=new StudentModel();
$connection=$model->OpenCon();
$id=(int)$_SESSION["user_id"];

// ---------- 1) Button actions ----------
if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $mode=$_POST["mode"] ?? "";

    if($mode=="enroll")
    {
        $course=(int)($_POST["course_id"] ?? 0);

        if($model->CourseExists($connection,$course) && !$model->IsEnrolled($connection,$id,$course))
        {
            $model->Enroll($connection,$id,$course);
        }
        Header("Location:../View/Student.php?action=courses");
    }
    else if($mode=="submit_quiz")
    {
        $quiz=(int)($_POST["quiz_id"] ?? 0);
        $questions=$model->GetQuestions($connection,$quiz);

        // the quiz must be published, from my course, and have questions
        if(!$model->GetQuizForStudent($connection,$id,$quiz) || count($questions)==0)
        {
            Header("Location:../View/Student.php?action=quizzes");
            exit();
        }

        // Check every answer and add up the marks
        $score=0;
        $total=0;
        foreach($questions as $row)
        {
            $total=$total+$row["mark"];
            $answer=$_POST["q".$row["id"]] ?? "";

            if($answer==$row["correct_option"])
            {
                $score=$score+$row["mark"];
            }
        }

        $model->SaveAttempt($connection,$quiz,$id,$score,$total);
        Header("Location:../View/Student.php?action=results");
    }
    else
    {
        Header("Location:../View/Student.php?action=dashboard");
    }
    exit();
}

// ---------- 2) Data for the page ----------
$action=$_GET["action"] ?? "dashboard";

if($action=="dashboard")
{
    $totalCourses=$model->CountMyCourses($connection,$id);
    $totalAttempts=$model->CountMyAttempts($connection,$id);
    $average=$model->GetAverage($connection,$id);
}
else if($action=="courses")
{
    $courses=$model->GetCourses($connection,$id);
}
else if($action=="quizzes")
{
    $takeId=(int)($_GET["quiz_id"] ?? 0);

    if($takeId!=0)
    {
        // "Take" button: open one quiz
        $quiz=$model->GetQuizForStudent($connection,$id,$takeId);
        $questions=$quiz ? $model->GetQuestions($connection,$takeId) : array();
    }
    else
    {
        $quizzes=$model->GetMyQuizzes($connection,$id);
    }
}
else
{
    $action="results";
    $results=$model->GetMyResults($connection,$id);
}
?>
