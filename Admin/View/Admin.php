<?php
include "../../model/DatabaseConnection.php";
session_start();

if(!isset($_SESSION["user_id"]) || $_SESSION["role"]!="admin")
{
    Header("Location:Login.php");
    exit();
}

$database=new DatabaseConnection();
$connection=$database->OpenCon();
$action=$_GET["action"] ?? "dashboard";
$id=(int)$_SESSION["user_id"];
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Panel - EduQuiz</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<header>
    <a class="brand" href="../MainPage.php">EduQuiz</a>
    <nav>
        <a href="../MainPage.php">Dashboard</a>
        <a href="Profile.php">Profile</a>
        <a href="Password.php">Password</a>
        <a href="../Control/logout.php">Logout</a>
    </nav>
</header>
<main class="container">
    <div class="page-title"><h1>Admin Panel</h1></div>

    <div class="tabs">
        <a <?php if($action=="dashboard") echo 'class="active"'; ?> href="Admin.php?action=dashboard">Dashboard</a>
        <a <?php if($action=="users") echo 'class="active"'; ?> href="Admin.php?action=users">User Management</a>
        <a <?php if($action=="courses") echo 'class="active"'; ?> href="Admin.php?action=courses">Course Control</a>
        <a <?php if($action=="reports") echo 'class="active"'; ?> href="Admin.php?action=reports">System Report</a>
    </div>

    <?php
    if($action=="dashboard")
    {
        $users=$connection->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc();
        $courses=$connection->query("SELECT COUNT(*) AS total FROM courses")->fetch_assoc();
        $attempts=$connection->query("SELECT COUNT(*) AS total FROM attempts")->fetch_assoc();
    ?>
        <p class="welcome">Welcome, <b><?php echo htmlspecialchars($_SESSION["name"]); ?></b></p>
        <table class="summary-table">
            <tr><th>Users</th><td><?php echo $users["total"]; ?></td></tr>
            <tr><th>Courses</th><td><?php echo $courses["total"]; ?></td></tr>
            <tr><th>Quiz Attempts</th><td><?php echo $attempts["total"]; ?></td></tr>
        </table>
    <?php
    }
    else if($action=="users")
    {
        $result=$connection->query("SELECT * FROM users ORDER BY id DESC");
    ?>
        <section class="content-section">
            <h2>All Users</h2>
            <div class="table-wrap">
                <table>
                    <tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th></tr>
                    <?php while($user=$result->fetch_assoc()){ ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user["name"]); ?><div class="muted">@<?php echo htmlspecialchars($user["username"]); ?></div></td>
                        <td><?php echo htmlspecialchars($user["email"]); ?></td>
                        <td><?php echo htmlspecialchars($user["role"]); ?></td>
                        <td><?php if($user["is_active"]==1) echo "Active"; else echo "Inactive"; ?></td>
                        <td class="inline">
                            <?php if($user["id"]!=$id){ ?>
                            <form method="post" action="../Control/AdminControl.php">
                                <input type="hidden" name="mode" value="toggle_user">
                                <input type="hidden" name="user_id" value="<?php echo $user["id"]; ?>">
                                <button class="btn secondary small" type="submit">Toggle</button>
                            </form>
                            <form method="post" action="../Control/AdminControl.php" onsubmit="return confirm('Delete user?')">
                                <input type="hidden" name="mode" value="delete_user">
                                <input type="hidden" name="user_id" value="<?php echo $user["id"]; ?>">
                                <button class="btn danger small" type="submit">Delete</button>
                            </form>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </section>
    <?php
    }
    else if($action=="courses")
    {
        $result=$connection->query("SELECT c.id,c.code,c.title,u.name AS instructor FROM courses c,users u WHERE c.instructor_id=u.id ORDER BY c.id DESC");
    ?>
        <section class="content-section">
            <h2>Course Control</h2>
            <div class="table-wrap">
                <table>
                    <tr><th>Code</th><th>Title</th><th>Instructor</th><th>Students</th><th>Action</th></tr>
                    <?php while($course=$result->fetch_assoc()){
                        $course_id=(int)$course["id"];
                        $count=$connection->query("SELECT COUNT(*) AS total FROM enrollments WHERE course_id=$course_id")->fetch_assoc();
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course["code"]); ?></td>
                        <td><?php echo htmlspecialchars($course["title"]); ?></td>
                        <td><?php echo htmlspecialchars($course["instructor"]); ?></td>
                        <td><?php echo $count["total"]; ?></td>
                        <td>
                            <form method="post" action="../Control/AdminControl.php" onsubmit="return confirm('Remove course?')">
                                <input type="hidden" name="mode" value="delete_course">
                                <input type="hidden" name="course_id" value="<?php echo $course["id"]; ?>">
                                <button class="btn danger small" type="submit">Remove</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </section>
    <?php
    }
    else
    {
        $students=$connection->query("SELECT COUNT(*) AS total FROM users WHERE role='student'")->fetch_assoc();
        $instructors=$connection->query("SELECT COUNT(*) AS total FROM users WHERE role='instructor'")->fetch_assoc();
        $courses=$connection->query("SELECT COUNT(*) AS total FROM courses")->fetch_assoc();
        $quizzes=$connection->query("SELECT COUNT(*) AS total FROM quizzes")->fetch_assoc();
        $attempts=$connection->query("SELECT COUNT(*) AS total FROM attempts")->fetch_assoc();
        $recent=$connection->query("SELECT u.name,q.title,a.score,a.total,a.attempted_at FROM attempts a,users u,quizzes q WHERE a.student_id=u.id AND a.quiz_id=q.id ORDER BY a.id DESC LIMIT 10");
    ?>
        <table class="summary-table">
            <tr><th>Total Students</th><td><?php echo $students["total"]; ?></td></tr>
            <tr><th>Total Instructors</th><td><?php echo $instructors["total"]; ?></td></tr>
            <tr><th>Total Courses</th><td><?php echo $courses["total"]; ?></td></tr>
            <tr><th>Total Quizzes</th><td><?php echo $quizzes["total"]; ?></td></tr>
            <tr><th>Total Attempts</th><td><?php echo $attempts["total"]; ?></td></tr>
        </table>

        <section class="content-section">
            <h2>Recent Quiz Attempts</h2>
            <div class="table-wrap">
                <table>
                    <tr><th>Student</th><th>Quiz</th><th>Score</th><th>Date</th></tr>
                    <?php while($row=$recent->fetch_assoc()){ ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["title"]); ?></td>
                        <td><?php echo htmlspecialchars($row["score"]."/".$row["total"]); ?></td>
                        <td><?php echo htmlspecialchars($row["attempted_at"]); ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </section>
    <?php } ?>
</main>
</body>
</html>
