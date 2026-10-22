<?php
// Model: every database query used by the Student panel
include_once __DIR__."/DatabaseConnection.php";

class StudentModel extends DatabaseConnection
{
    // ---------- Dashboard ----------

    function CountMyCourses($conn,$student)
    {
        return $this->GetTotal($conn,"SELECT COUNT(*) AS total FROM enrollments WHERE student_id=$student");
    }

    function CountMyAttempts($conn,$student)
    {
        return $this->GetTotal($conn,"SELECT COUNT(*) AS total FROM attempts WHERE student_id=$student");
    }

    // Average score in percent, 0 if no quiz was taken yet
    function GetAverage($conn,$student)
    {
        $row=$conn->query("SELECT AVG(score/total*100) AS average FROM attempts WHERE student_id=$student AND total>0")->fetch_assoc();
        if($row["average"]==null)
        {
            return 0;
        }
        return round($row["average"],1);
    }

    // ---------- Courses ----------

    // All courses, with "enrolled" = 1 if this student already joined
    function GetCourses($conn,$student)
    {
        $result=$conn->query("SELECT c.id,c.code,c.title,c.description,u.name AS instructor,(SELECT COUNT(*) FROM enrollments e WHERE e.course_id=c.id AND e.student_id=$student) AS enrolled FROM courses c,users u WHERE c.instructor_id=u.id ORDER BY c.id DESC");
        return $this->GetRows($result);
    }

    function IsEnrolled($conn,$student,$course)
    {
        $result=$conn->query("SELECT id FROM enrollments WHERE student_id=$student AND course_id=$course");
        return $result->num_rows>0;
    }

    function CourseExists($conn,$course)
    {
        $result=$conn->query("SELECT id FROM courses WHERE id=$course");
        return $result->num_rows==1;
    }

    function Enroll($conn,$student,$course)
    {
        return $conn->query("INSERT INTO enrollments(student_id,course_id) VALUES($student,$course)");
    }

    // ---------- Quizzes ----------

    // Published quizzes of my courses, with the number of questions
    function GetMyQuizzes($conn,$student)
    {
        $result=$conn->query("SELECT q.id,q.title,c.code,c.title AS course_title,(SELECT COUNT(*) FROM questions x WHERE x.quiz_id=q.id) AS questions FROM quizzes q,courses c,enrollments e WHERE q.course_id=c.id AND e.course_id=c.id AND e.student_id=$student AND q.is_published=1 ORDER BY q.id DESC");
        return $this->GetRows($result);
    }

    // Returns the quiz only if it is published and the student is enrolled, otherwise null
    function GetQuizForStudent($conn,$student,$quiz)
    {
        $result=$conn->query("SELECT q.id,q.title FROM quizzes q,enrollments e WHERE q.course_id=e.course_id AND q.id=$quiz AND e.student_id=$student AND q.is_published=1");
        return $result->fetch_assoc();
    }

    function GetQuestions($conn,$quiz)
    {
        $result=$conn->query("SELECT * FROM questions WHERE quiz_id=$quiz ORDER BY id");
        return $this->GetRows($result);
    }

    function SaveAttempt($conn,$quiz,$student,$score,$total)
    {
        return $conn->query("INSERT INTO attempts(quiz_id,student_id,score,total) VALUES($quiz,$student,$score,$total)");
    }

    // ---------- Results ----------

    function GetMyResults($conn,$student)
    {
        $result=$conn->query("SELECT a.score,a.total,a.attempted_at,q.title AS quiz,c.code FROM attempts a,quizzes q,courses c WHERE a.quiz_id=q.id AND q.course_id=c.id AND a.student_id=$student ORDER BY a.id DESC");
        return $this->GetRows($result);
    }
}
?>
