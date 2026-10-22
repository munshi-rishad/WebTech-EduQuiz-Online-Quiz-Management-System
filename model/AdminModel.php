<?php
// Model: every database query used by the Admin panel
include_once __DIR__."/DatabaseConnection.php";

class AdminModel extends DatabaseConnection
{
    // ---------- Dashboard and System Report ----------

    function CountUsers($conn,$role="")
    {
        if($role=="")
        {
            return $this->GetTotal($conn,"SELECT COUNT(*) AS total FROM users");
        }
        return $this->GetTotal($conn,"SELECT COUNT(*) AS total FROM users WHERE role='$role'");
    }

    function CountCourses($conn)
    {
        return $this->GetTotal($conn,"SELECT COUNT(*) AS total FROM courses");
    }

    function CountQuizzes($conn)
    {
        return $this->GetTotal($conn,"SELECT COUNT(*) AS total FROM quizzes");
    }

    function CountAttempts($conn)
    {
        return $this->GetTotal($conn,"SELECT COUNT(*) AS total FROM attempts");
    }

    function GetRecentAttempts($conn)
    {
        $result=$conn->query("SELECT u.name,q.title,a.score,a.total,a.attempted_at FROM attempts a,users u,quizzes q WHERE a.student_id=u.id AND a.quiz_id=q.id ORDER BY a.id DESC LIMIT 10");
        return $this->GetRows($result);
    }

    // ---------- User Management ----------

    function GetAllUsers($conn)
    {
        $result=$conn->query("SELECT * FROM users ORDER BY id DESC");
        return $this->GetRows($result);
    }

    // Active -> Inactive, Inactive -> Active
    function ToggleUser($conn,$id)
    {
        return $conn->query("UPDATE users SET is_active=1-is_active WHERE id=$id");
    }

    // ---------- Course Control ----------

    // All courses with the instructor name and how many students joined
    function GetAllCourses($conn)
    {
        $result=$conn->query("SELECT c.id,c.code,c.title,u.name AS instructor,(SELECT COUNT(*) FROM enrollments e WHERE e.course_id=c.id) AS students FROM courses c,users u WHERE c.instructor_id=u.id ORDER BY c.id DESC");
        return $this->GetRows($result);
    }

    function DeleteCourse($conn,$id)
    {
        return $conn->query("DELETE FROM courses WHERE id=$id");
    }
}
?>
