<?php
// Model: every database query used by the Instructor panel
include_once __DIR__."/DatabaseConnection.php";

class InstructorModel extends DatabaseConnection
{
    // ---------- Dashboard ----------

    function CountMyCourses($conn,$instructor)
    {
        return $this->GetTotal($conn,"SELECT COUNT(*) AS total FROM courses WHERE instructor_id=$instructor");
    }

    function CountMyQuizzes($conn,$instructor)
    {
        return $this->GetTotal($conn,"SELECT COUNT(*) AS total FROM quizzes q,courses c WHERE q.course_id=c.id AND c.instructor_id=$instructor");
    }

    function CountMyAttempts($conn,$instructor)
    {
        return $this->GetTotal($conn,"SELECT COUNT(*) AS total FROM attempts a,quizzes q,courses c WHERE a.quiz_id=q.id AND q.course_id=c.id AND c.instructor_id=$instructor");
    }

    // ---------- Courses ----------

    function GetMyCourses($conn,$instructor)
    {
        $result=$conn->query("SELECT * FROM courses WHERE instructor_id=$instructor ORDER BY id DESC");
        return $this->GetRows($result);
    }

    function AddCourse($conn,$instructor,$code,$title,$description)
    {
        return $conn->query("INSERT INTO courses(instructor_id,code,title,description) VALUES($instructor,'$code','$title','$description')");
    }

    function DeleteCourse($conn,$instructor,$course)
    {
        return $conn->query("DELETE FROM courses WHERE id=$course AND instructor_id=$instructor");
    }

    function IsMyCourse($conn,$instructor,$course)
    {
        $result=$conn->query("SELECT id FROM courses WHERE id=$course AND instructor_id=$instructor");
        return $result->num_rows==1;
    }

    // ---------- Quizzes ----------

    // My quizzes with the course code and the number of questions
    function GetMyQuizzes($conn,$instructor)
    {
        $result=$conn->query("SELECT q.id,q.title,q.is_published,c.code,(SELECT COUNT(*) FROM questions x WHERE x.quiz_id=q.id) AS questions FROM quizzes q,courses c WHERE q.course_id=c.id AND c.instructor_id=$instructor ORDER BY q.id DESC");
        return $this->GetRows($result);
    }

    function AddQuiz($conn,$course,$title)
    {
        return $conn->query("INSERT INTO quizzes(course_id,title) VALUES($course,'$title')");
    }

    // Returns the quiz only if it belongs to this instructor, otherwise null
    function GetMyQuiz($conn,$instructor,$quiz)
    {
        $result=$conn->query("SELECT q.id,q.title,c.code FROM quizzes q,courses c WHERE q.course_id=c.id AND q.id=$quiz AND c.instructor_id=$instructor");
        return $result->fetch_assoc();
    }

    // Published -> Draft, Draft -> Published
    function ToggleQuiz($conn,$quiz)
    {
        return $conn->query("UPDATE quizzes SET is_published=1-is_published WHERE id=$quiz");
    }

    // ---------- Questions ----------

    function GetQuestions($conn,$quiz)
    {
        $result=$conn->query("SELECT * FROM questions WHERE quiz_id=$quiz ORDER BY id");
        return $this->GetRows($result);
    }

    function AddQuestion($conn,$quiz,$question,$a,$b,$c,$d,$correct,$mark)
    {
        return $conn->query("INSERT INTO questions(quiz_id,question_text,option_a,option_b,option_c,option_d,correct_option,mark) VALUES($quiz,'$question','$a','$b','$c','$d','$correct',$mark)");
    }

    // ---------- Student Results ----------

    function GetStudentResults($conn,$instructor)
    {
        $result=$conn->query("SELECT u.name AS student,c.code,q.title AS quiz,a.score,a.total,a.attempted_at FROM attempts a,users u,quizzes q,courses c WHERE a.student_id=u.id AND a.quiz_id=q.id AND q.course_id=c.id AND c.instructor_id=$instructor ORDER BY a.id DESC");
        return $this->GetRows($result);
    }
}
?>
