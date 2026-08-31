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
$action=$_GET["action"] ?? "dashboard";
$id=(int)$_SESSION["user_id"];
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Instructor Panel - EduQuiz</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<header>
    <a class="brand" href="../MainPage.php">EduQuiz</a>
    <nav>
        <a href="../MainPage.php">Dashboard</a>
        <a href="../../Admin/View/Profile.php">Profile</a>
        <a href="../../Admin/View/Password.php">Password</a>
        <a href="../../Admin/Control/logout.php">Logout</a>
    </nav>
</header>
<main class="container">
    <div class="page-title"><h1>Instructor Panel</h1></div>

    <div class="tabs">
        <a <?php if($action=="dashboard") echo 'class="active"'; ?> href="Instructor.php?action=dashboard">Dashboard</a>
        <a <?php if($action=="courses") echo 'class="active"'; ?> href="Instructor.php?action=courses">Manage Courses</a>
        <a <?php if($action=="quizzes") echo 'class="active"'; ?> href="Instructor.php?action=quizzes">Manage Quizzes</a>
        <a <?php if($action=="results") echo 'class="active"'; ?> href="Instructor.php?action=results">Student Results</a>
    </div>

    <?php
    if($action=="dashboard")
    {
        $courses=$connection->query("SELECT COUNT(*) AS total FROM courses WHERE instructor_id=$id")->fetch_assoc();
        $quizzes=$connection->query("SELECT COUNT(*) AS total FROM quizzes q,courses c WHERE q.course_id=c.id AND c.instructor_id=$id")->fetch_assoc();
        $attempts=$connection->query("SELECT COUNT(*) AS total FROM attempts a,quizzes q,courses c WHERE a.quiz_id=q.id AND q.course_id=c.id AND c.instructor_id=$id")->fetch_assoc();
    ?>
        <p class="welcome">Welcome, <b><?php echo htmlspecialchars($_SESSION["name"]); ?></b></p>
        <table class="summary-table">
            <tr><th>My Courses</th><td><?php echo $courses["total"]; ?></td></tr>
            <tr><th>My Quizzes</th><td><?php echo $quizzes["total"]; ?></td></tr>
            <tr><th>Quiz Attempts</th><td><?php echo $attempts["total"]; ?></td></tr>
        </table>
    <?php
    }
    else if($action=="courses")
    {
        $result=$connection->query("SELECT * FROM courses WHERE instructor_id=$id ORDER BY id DESC");
    ?>
        <div class="layout-two">
            <div class="content-section">
                <h2>Create Course</h2>
                <form method="post" action="../Control/InstructorControl.php">
                    <input type="hidden" name="mode" value="create_course">
                    <label>Course code</label><input name="code" required><br><br>
                    <label>Title</label><input name="title" required><br><br>
                    <label>Description</label><textarea name="description"></textarea><br><br>
                    <button class="btn" type="submit">Create Course</button>
                </form>
            </div>

            <div class="content-section">
                <h2>My Courses</h2>
                <div class="table-wrap">
                    <table>
                        <tr><th>Code</th><th>Title</th><th>Action</th></tr>
                        <?php while($course=$result->fetch_assoc()){ ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course["code"]); ?></td>
                            <td><?php echo htmlspecialchars($course["title"]); ?></td>
                            <td>
                                <form method="post" action="../Control/InstructorControl.php" onsubmit="return confirm('Delete course?')">
                                    <input type="hidden" name="mode" value="delete_course">
                                    <input type="hidden" name="course_id" value="<?php echo $course["id"]; ?>">
                                    <button class="btn danger small" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    <?php
    }
    else if($action=="quizzes")
    {
        $courses=$connection->query("SELECT * FROM courses WHERE instructor_id=$id ORDER BY id DESC");
        $quizzes=$connection->query("SELECT q.id,q.title,q.is_published,c.code FROM quizzes q,courses c WHERE q.course_id=c.id AND c.instructor_id=$id ORDER BY q.id DESC");
        $quiz_id=(int)($_GET["quiz_id"] ?? 0);
    ?>
        <div class="layout-two">
            <div class="content-section">
                <h2>Create Quiz</h2>
                <form method="post" action="../Control/InstructorControl.php">
                    <input type="hidden" name="mode" value="create_quiz">
                    <label>Course</label>
                    <select name="course_id" required>
                        <?php while($course=$courses->fetch_assoc()){ ?>
                        <option value="<?php echo $course["id"]; ?>"><?php echo htmlspecialchars($course["code"]." - ".$course["title"]); ?></option>
                        <?php } ?>
                    </select><br><br>
                    <label>Quiz title</label><input name="title" required><br><br>
                    <button class="btn" type="submit">Create Quiz</button>
                </form>
            </div>

            <div class="content-section">
                <h2>My Quizzes</h2>
                <div class="table-wrap">
                    <table>
                        <tr><th>Course</th><th>Quiz</th><th>Questions</th><th>Status</th><th>Action</th></tr>
                        <?php while($quiz=$quizzes->fetch_assoc()){
                            $qid=(int)$quiz["id"];
                            $count=$connection->query("SELECT COUNT(*) AS total FROM questions WHERE quiz_id=$qid")->fetch_assoc();
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($quiz["code"]); ?></td>
                            <td><?php echo htmlspecialchars($quiz["title"]); ?></td>
                            <td><?php echo $count["total"]; ?></td>
                            <td><?php if($quiz["is_published"]==1) echo "Published"; else echo "Draft"; ?></td>
                            <td class="inline">
                                <a class="btn small" href="Instructor.php?action=quizzes&quiz_id=<?php echo $quiz["id"]; ?>">Questions</a>
                                <form method="post" action="../Control/InstructorControl.php">
                                    <input type="hidden" name="mode" value="toggle_quiz">
                                    <input type="hidden" name="quiz_id" value="<?php echo $quiz["id"]; ?>">
                                    <button class="btn secondary small" type="submit">Toggle</button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>

        <?php if($quiz_id!=0){ ?>
        <div class="content-section">
            <h2>Add Question</h2>
            <form method="post" action="../Control/InstructorControl.php" class="form-grid">
                <input type="hidden" name="mode" value="add_question">
                <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
                <div class="full"><label>Question</label><textarea name="question" required></textarea></div>
                <div><label>Option A</label><input name="a" required></div>
                <div><label>Option B</label><input name="b" required></div>
                <div><label>Option C</label><input name="c" required></div>
                <div><label>Option D</label><input name="d" required></div>
                <div><label>Correct option</label><select name="correct"><option>A</option><option>B</option><option>C</option><option>D</option></select></div>
                <div><label>Mark</label><input type="number" name="mark" value="1" min="1"></div>
                <div class="full"><button class="btn success" type="submit">Add Question</button></div>
            </form>
        </div>
        <?php } ?>
    <?php
    }
    else
    {
        $result=$connection->query("SELECT u.name AS student,c.code,q.title AS quiz,a.score,a.total,a.attempted_at FROM attempts a,users u,quizzes q,courses c WHERE a.student_id=u.id AND a.quiz_id=q.id AND q.course_id=c.id AND c.instructor_id=$id ORDER BY a.id DESC");
    ?>
        <div class="content-section">
            <h2>Student Quiz Results</h2>
            <div class="table-wrap">
                <table>
                    <tr><th>Student</th><th>Course</th><th>Quiz</th><th>Score</th><th>Date</th></tr>
                    <?php while($row=$result->fetch_assoc()){ ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["student"]); ?></td>
                        <td><?php echo htmlspecialchars($row["code"]); ?></td>
                        <td><?php echo htmlspecialchars($row["quiz"]); ?></td>
                        <td><?php echo htmlspecialchars($row["score"]." / ".$row["total"]); ?></td>
                        <td><?php echo htmlspecialchars($row["attempted_at"]); ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    <?php } ?>
</main>
</body>
</html>
