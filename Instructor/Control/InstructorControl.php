<?php
// Controller for the Instructor panel.
// 1) POST (Create / Delete / Toggle / Add Question): change the data, then go back to the page.
// 2) GET (included by View/Instructor.php): load the data that the page shows.
include_once "../../model/InstructorModel.php";
session_start();

if(!isset($_SESSION["user_id"]) || $_SESSION["role"]!="instructor")
{
    Header("Location:../../Auth/View/Login.php");
    exit();
}

$model=new InstructorModel();
$connection=$model->OpenCon();
$id=(int)$_SESSION["user_id"];

$passPercent=40;   // a student passes a quiz with 40% or more

// ---------- 1) Button actions ----------
if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $mode=$_POST["mode"] ?? "";

    if($mode=="create_course")
    {
        $code=$connection->real_escape_string(trim($_POST["code"] ?? ""));
        $title=$connection->real_escape_string(trim($_POST["title"] ?? ""));
        $description=$connection->real_escape_string(trim($_POST["description"] ?? ""));

        if($code!="" && $title!="")
        {
            $model->AddCourse($connection,$id,$code,$title,$description);
        }
        Header("Location:../View/Instructor.php?action=courses");
    }
    else if($mode=="delete_course")
    {
        $course=(int)($_POST["course_id"] ?? 0);
        $model->DeleteCourse($connection,$id,$course);
        Header("Location:../View/Instructor.php?action=courses");
    }
    else if($mode=="create_quiz")
    {
        $course=(int)($_POST["course_id"] ?? 0);
        $title=$connection->real_escape_string(trim($_POST["title"] ?? ""));

        if($title!="" && $model->IsMyCourse($connection,$id,$course))
        {
            $model->AddQuiz($connection,$course,$title);
        }
        Header("Location:../View/Instructor.php?action=quizzes");
    }
    else if($mode=="toggle_quiz")
    {
        $quiz=(int)($_POST["quiz_id"] ?? 0);

        if($model->GetMyQuiz($connection,$id,$quiz))
        {
            $model->ToggleQuiz($connection,$quiz);
        }
        Header("Location:../View/Instructor.php?action=quizzes");
    }
    else if($mode=="add_question")
    {
        $quiz=(int)($_POST["quiz_id"] ?? 0);
        $question=$connection->real_escape_string(trim($_POST["question"] ?? ""));
        $a=$connection->real_escape_string(trim($_POST["a"] ?? ""));
        $b=$connection->real_escape_string(trim($_POST["b"] ?? ""));
        $c=$connection->real_escape_string(trim($_POST["c"] ?? ""));
        $d=$connection->real_escape_string(trim($_POST["d"] ?? ""));
        $correct=$_POST["correct"] ?? "A";
        $mark=(float)($_POST["mark"] ?? 1);

        $allFilled=$question!="" && $a!="" && $b!="" && $c!="" && $d!="";
        $validAnswer=in_array($correct,array("A","B","C","D"));

        if($allFilled && $validAnswer && $mark>=1 && $model->GetMyQuiz($connection,$id,$quiz))
        {
            $model->AddQuestion($connection,$quiz,$question,$a,$b,$c,$d,$correct,$mark);
        }
        Header("Location:../View/Instructor.php?action=quizzes&quiz_id=$quiz");
    }
    else
    {
        Header("Location:../View/Instructor.php?action=dashboard");
    }
    exit();
}

// ---------- 2) Data for the page ----------
$action=$_GET["action"] ?? "dashboard";

if($action=="dashboard")
{
    $totalCourses=$model->CountMyCourses($connection,$id);
    $totalQuizzes=$model->CountMyQuizzes($connection,$id);
    $totalAttempts=$model->CountMyAttempts($connection,$id);
}
else if($action=="courses")
{
    $courses=$model->GetMyCourses($connection,$id);
}
else if($action=="quizzes")
{
    $courses=$model->GetMyCourses($connection,$id);
    $quizzes=$model->GetMyQuizzes($connection,$id);

    // "Questions" button: show the questions of one of my quizzes
    $selectedQuiz=$model->GetMyQuiz($connection,$id,(int)($_GET["quiz_id"] ?? 0));
    if($selectedQuiz)
    {
        $questions=$model->GetQuestions($connection,(int)$selectedQuiz["id"]);
    }
}
else
{
    $action="results";
    $results=$model->GetStudentResults($connection,$id);
}
?>
