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
$action=$_GET["action"] ?? "dashboard";
$id=(int)$_SESSION["user_id"];
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Student Panel - EduQuiz</title>
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
    <div class="page-title"><h1>Student Panel</h1></div>

    <div class="tabs">
        <a <?php if($action=="dashboard") echo 'class="active"'; ?> href="Student.php?action=dashboard">Dashboard</a>
        <a <?php if($action=="courses") echo 'class="active"'; ?> href="Student.php?action=courses">Browse & Enroll</a>
        <a <?php if($action=="quizzes") echo 'class="active"'; ?> href="Student.php?action=quizzes">Take Quiz</a>
        <a <?php if($action=="results") echo 'class="active"'; ?> href="Student.php?action=results">My Results</a>
    </div>

    <?php
    if($action=="dashboard")
    {
        $courses=$connection->query("SELECT COUNT(*) AS total FROM enrollments WHERE student_id=$id")->fetch_assoc();
        $attempts=$connection->query("SELECT COUNT(*) AS total FROM attempts WHERE student_id=$id")->fetch_assoc();
        $average=$connection->query("SELECT AVG(score/total*100) AS average FROM attempts WHERE student_id=$id AND total>0")->fetch_assoc();
        $avg=$average["average"]==null ? 0 : round($average["average"],1);
    ?>
        <p class="welcome">Welcome, <b><?php echo htmlspecialchars($_SESSION["name"]); ?></b></p>
        <table class="summary-table">
            <tr><th>Enrolled Courses</th><td><?php echo $courses["total"]; ?></td></tr>
            <tr><th>Quiz Attempts</th><td><?php echo $attempts["total"]; ?></td></tr>
            <tr><th>Average Score %</th><td><?php echo $avg; ?></td></tr>
        </table>
    <?php
    }
    else if($action=="courses")
    {
        $courses=$connection->query("SELECT c.id,c.code,c.title,c.description,u.name AS instructor FROM courses c,users u WHERE c.instructor_id=u.id ORDER BY c.id DESC");
    ?>
        <div class="content-section">
            <h2>Available Courses</h2>
            <div class="table-wrap">
                <table>
                    <tr><th>Code</th><th>Course</th><th>Instructor</th><th>Action</th></tr>
                    <?php while($course=$courses->fetch_assoc()){
                        $course_id=(int)$course["id"];
                        $enrolled=$connection->query("SELECT * FROM enrollments WHERE student_id=$id AND course_id=$course_id");
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course["code"]); ?></td>
                        <td><b><?php echo htmlspecialchars($course["title"]); ?></b><div class="muted"><?php echo htmlspecialchars($course["description"]); ?></div></td>
                        <td><?php echo htmlspecialchars($course["instructor"]); ?></td>
                        <td>
                            <?php if($enrolled->num_rows==1){ ?>
                                <span class="badge">Enrolled</span>
                            <?php } else { ?>
                                <form method="post" action="../Control/StudentControl.php">
                                    <input type="hidden" name="mode" value="enroll">
                                    <input type="hidden" name="course_id" value="<?php echo $course["id"]; ?>">
                                    <button class="btn small" type="submit">Enroll</button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    <?php
    }
    else if($action=="quizzes")
    {
        $take=(int)($_GET["quiz_id"] ?? 0);

        if($take!=0)
        {
            $quiz_result=$connection->query("SELECT q.id,q.title FROM quizzes q,enrollments e WHERE q.course_id=e.course_id AND q.id=$take AND e.student_id=$id AND q.is_published=1");

            if($quiz_result->num_rows==1)
            {
                $quiz=$quiz_result->fetch_assoc();
                $questions=$connection->query("SELECT * FROM questions WHERE quiz_id=$take ORDER BY id");
    ?>
                <div class="content-section">
                    <h2><?php echo htmlspecialchars($quiz["title"]); ?></h2>
                    <form method="post" action="../Control/StudentControl.php">
                        <input type="hidden" name="mode" value="submit_quiz">
                        <input type="hidden" name="quiz_id" value="<?php echo $take; ?>">
                        <?php $number=1; while($question=$questions->fetch_assoc()){ ?>
                        <div class="question">
                            <b><?php echo $number.". ".htmlspecialchars($question["question_text"]); ?></b>
                            <label><input style="width:auto" type="radio" name="q<?php echo $question["id"]; ?>" value="A" required> <?php echo htmlspecialchars($question["option_a"]); ?></label>
                            <label><input style="width:auto" type="radio" name="q<?php echo $question["id"]; ?>" value="B" required> <?php echo htmlspecialchars($question["option_b"]); ?></label>
                            <label><input style="width:auto" type="radio" name="q<?php echo $question["id"]; ?>" value="C" required> <?php echo htmlspecialchars($question["option_c"]); ?></label>
                            <label><input style="width:auto" type="radio" name="q<?php echo $question["id"]; ?>" value="D" required> <?php echo htmlspecialchars($question["option_d"]); ?></label>
                        </div>
                        <?php $number++; } ?>
                        <button class="btn success" type="submit">Submit Quiz</button>
                    </form>
                </div>
    <?php
            }
            else
            {
                echo '<div class="alert error">Quiz is unavailable.</div>';
            }
        }
        else
        {
            $quizzes=$connection->query("SELECT q.id,q.title,c.code,c.title AS course_title FROM quizzes q,courses c,enrollments e WHERE q.course_id=c.id AND e.course_id=c.id AND e.student_id=$id AND q.is_published=1 ORDER BY q.id DESC");
    ?>
            <div class="content-section">
                <h2>Published Quizzes</h2>
                <div class="table-wrap">
                    <table>
                        <tr><th>Course</th><th>Quiz</th><th>Questions</th><th>Action</th></tr>
                        <?php while($quiz=$quizzes->fetch_assoc()){
                            $qid=(int)$quiz["id"];
                            $count=$connection->query("SELECT COUNT(*) AS total FROM questions WHERE quiz_id=$qid")->fetch_assoc();
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($quiz["code"]." - ".$quiz["course_title"]); ?></td>
                            <td><?php echo htmlspecialchars($quiz["title"]); ?></td>
                            <td><?php echo $count["total"]; ?></td>
                            <td><a class="btn small" href="Student.php?action=quizzes&quiz_id=<?php echo $quiz["id"]; ?>">Take</a></td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
    <?php
        }
    }
    else
    {
        $result=$connection->query("SELECT a.score,a.total,a.attempted_at,q.title AS quiz,c.code FROM attempts a,quizzes q,courses c WHERE a.quiz_id=q.id AND q.course_id=c.id AND a.student_id=$id ORDER BY a.id DESC");
    ?>
        <div class="content-section">
            <h2>Quiz Result History</h2>
            <div class="table-wrap">
                <table>
                    <tr><th>Course</th><th>Quiz</th><th>Score</th><th>Percent</th><th>Date</th></tr>
                    <?php while($row=$result->fetch_assoc()){
                        $percent=$row["total"]>0 ? round($row["score"]/$row["total"]*100,1) : 0;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["code"]); ?></td>
                        <td><?php echo htmlspecialchars($row["quiz"]); ?></td>
                        <td><?php echo htmlspecialchars($row["score"]." / ".$row["total"]); ?></td>
                        <td><?php echo $percent; ?>%</td>
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
