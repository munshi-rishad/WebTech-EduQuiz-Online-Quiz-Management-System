<?php
include "../../model/DatabaseConnection.php";
session_start();

if(!isset($_SESSION["user_id"]) || $_SESSION["role"]!="instructor")
{
    Header("Location:../../Admin/View/Login.php");
    exit();
}

$database=new DatabaseConnection();
$connection=$database->OpenCon();
$mode=$_POST["mode"] ?? "";
$id=(int)$_SESSION["user_id"];

if($mode=="create_course")
{
    $code=$connection->real_escape_string(trim($_POST["code"] ?? ""));
    $title=$connection->real_escape_string(trim($_POST["title"] ?? ""));
    $description=$connection->real_escape_string(trim($_POST["description"] ?? ""));

    if($code!="" && $title!="")
    {
        $connection->query("INSERT INTO courses(instructor_id,code,title,description) VALUES($id,'$code','$title','$description')");
    }
    Header("Location:../View/Instructor.php?action=courses");
}
else if($mode=="delete_course")
{
    $course=(int)($_POST["course_id"] ?? 0);
    $connection->query("DELETE FROM courses WHERE id=$course AND instructor_id=$id");
    Header("Location:../View/Instructor.php?action=courses");
}
else if($mode=="create_quiz")
{
    $course=(int)($_POST["course_id"] ?? 0);
    $title=$connection->real_escape_string(trim($_POST["title"] ?? ""));

    $check=$connection->query("SELECT * FROM courses WHERE id=$course AND instructor_id=$id");
    if($title!="" && $check->num_rows==1)
    {
        $connection->query("INSERT INTO quizzes(course_id,title) VALUES($course,'$title')");
    }
    Header("Location:../View/Instructor.php?action=quizzes");
}
else if($mode=="toggle_quiz")
{
    $quiz=(int)($_POST["quiz_id"] ?? 0);
    $check=$connection->query("SELECT q.id FROM quizzes q,courses c WHERE q.course_id=c.id AND q.id=$quiz AND c.instructor_id=$id");

    if($check->num_rows==1)
    {
        $connection->query("UPDATE quizzes SET is_published=1-is_published WHERE id=$quiz");
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

    $check=$connection->query("SELECT q.id FROM quizzes q,courses c WHERE q.course_id=c.id AND q.id=$quiz AND c.instructor_id=$id");

    if($question!="" && $a!="" && $b!="" && $c!="" && $d!="" && $check->num_rows==1)
    {
        $connection->query("INSERT INTO questions(quiz_id,question_text,option_a,option_b,option_c,option_d,correct_option,mark) VALUES($quiz,'$question','$a','$b','$c','$d','$correct',$mark)");
    }
    Header("Location:../View/Instructor.php?action=quizzes&quiz_id=$quiz");
}
else
{
    Header("Location:../View/Instructor.php?action=dashboard");
}
?>
