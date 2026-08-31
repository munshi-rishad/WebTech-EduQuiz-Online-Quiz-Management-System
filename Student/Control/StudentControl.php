<?php
include "../../model/DatabaseConnection.php";
session_start();

if(!isset($_SESSION["user_id"]) || $_SESSION["role"]!="student")
{
    Header("Location:../../Admin/View/Login.php");
    exit();
}

$database=new DatabaseConnection();
$connection=$database->OpenCon();
$mode=$_POST["mode"] ?? "";
$id=(int)$_SESSION["user_id"];

if($mode=="enroll")
{
    $course=(int)($_POST["course_id"] ?? 0);
    $check=$connection->query("SELECT * FROM enrollments WHERE student_id=$id AND course_id=$course");

    if($check->num_rows==0)
    {
        $connection->query("INSERT INTO enrollments(student_id,course_id) VALUES($id,$course)");
    }
    Header("Location:../View/Student.php?action=courses");
}
else if($mode=="submit_quiz")
{
    $quiz=(int)($_POST["quiz_id"] ?? 0);
    $check=$connection->query("SELECT q.id FROM quizzes q,enrollments e WHERE q.course_id=e.course_id AND q.id=$quiz AND q.is_published=1 AND e.student_id=$id");

    if($check->num_rows==0)
    {
        echo "Quiz unavailable";
        exit();
    }

    $questions=$connection->query("SELECT * FROM questions WHERE quiz_id=$quiz ORDER BY id");
    $score=0;
    $total=0;

    while($row=$questions->fetch_assoc())
    {
        $total=$total+$row["mark"];
        $answer=$_POST["q".$row["id"]] ?? "";

        if($answer==$row["correct_option"])
        {
            $score=$score+$row["mark"];
        }
    }

    $connection->query("INSERT INTO attempts(quiz_id,student_id,score,total) VALUES($quiz,$id,$score,$total)");
    Header("Location:../View/Student.php?action=results");
}
else
{
    Header("Location:../View/Student.php?action=dashboard");
}
?>
