<?php include "../Control/StudentControl.php"; ?>

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
        <a href="../../Auth/View/Profile.php">Profile</a>
        <a href="../../Auth/View/Password.php">Password</a>
        <a href="../../Auth/Control/logout.php">Logout</a>
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

    <?php if($action=="dashboard"){ ?>
        <p class="welcome">Welcome, <b><?php echo htmlspecialchars($_SESSION["name"]); ?></b></p>
        <table class="summary-table">
            <tr><th>Enrolled Courses</th><td><?php echo $totalCourses; ?></td></tr>
            <tr><th>Quiz Attempts</th><td><?php echo $totalAttempts; ?></td></tr>
            <tr><th>Average Score %</th><td><?php echo $average; ?></td></tr>
        </table>

    <?php } else if($action=="courses"){ ?>
        <div class="content-section">
            <h2>Available Courses</h2>
            <div class="table-wrap">
                <table>
                    <tr><th>Code</th><th>Course</th><th>Instructor</th><th>Action</th></tr>
                    <?php foreach($courses as $course){ ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course["code"]); ?></td>
                        <td><b><?php echo htmlspecialchars($course["title"]); ?></b><div class="muted"><?php echo htmlspecialchars($course["description"]); ?></div></td>
                        <td><?php echo htmlspecialchars($course["instructor"]); ?></td>
                        <td>
                            <?php if($course["enrolled"]>0){ ?>
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

    <?php } else if($action=="quizzes" && $takeId!=0){ ?>
        <?php if(!$quiz){ ?>
            <div class="alert error">Quiz is unavailable.</div>
        <?php } else if(count($questions)==0){ ?>
            <div class="alert error">This quiz has no questions yet.</div>
        <?php } else { ?>
        <div class="content-section">
            <h2><?php echo htmlspecialchars($quiz["title"]); ?></h2>
            <form method="post" action="../Control/StudentControl.php">
                <input type="hidden" name="mode" value="submit_quiz">
                <input type="hidden" name="quiz_id" value="<?php echo $takeId; ?>">
                <?php $number=1; foreach($questions as $question){ ?>
                <div class="question">
                    <b><?php echo $number.". ".htmlspecialchars($question["question_text"]); ?></b>
                    <?php foreach(array("A","B","C","D") as $letter){ ?>
                    <label><input style="width:auto" type="radio" name="q<?php echo $question["id"]; ?>" value="<?php echo $letter; ?>" required> <?php echo htmlspecialchars($question["option_".strtolower($letter)]); ?></label>
                    <?php } ?>
                </div>
                <?php $number++; } ?>
                <button class="btn success" type="submit">Submit Quiz</button>
            </form>
        </div>
        <?php } ?>

    <?php } else if($action=="quizzes"){ ?>
        <div class="content-section">
            <h2>Published Quizzes</h2>
            <div class="table-wrap">
                <table>
                    <tr><th>Course</th><th>Quiz</th><th>Questions</th><th>Action</th></tr>
                    <?php foreach($quizzes as $row){ ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["code"]." - ".$row["course_title"]); ?></td>
                        <td><?php echo htmlspecialchars($row["title"]); ?></td>
                        <td><?php echo $row["questions"]; ?></td>
                        <td><a class="btn small" href="Student.php?action=quizzes&quiz_id=<?php echo $row["id"]; ?>">Take</a></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>

    <?php } else { ?>
        <div class="content-section">
            <h2>Quiz Result History</h2>
            <div class="table-wrap">
                <table>
                    <tr><th>Course</th><th>Quiz</th><th>Score</th><th>Percent</th><th>Date</th></tr>
                    <?php foreach($results as $row){
                        $percent=$row["total"]>0 ? round($row["score"]/$row["total"]*100,1) : 0;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["code"]); ?></td>
                        <td><?php echo htmlspecialchars($row["quiz"]); ?></td>
                        <td><?php echo htmlspecialchars(($row["score"]+0)." / ".($row["total"]+0)); ?></td>
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
