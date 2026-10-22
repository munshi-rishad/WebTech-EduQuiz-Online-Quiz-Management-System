<?php include "../Control/InstructorControl.php"; ?>

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
        <a href="../../Auth/View/Profile.php">Profile</a>
        <a href="../../Auth/View/Password.php">Password</a>
        <a href="../../Auth/Control/logout.php">Logout</a>
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

    <?php if($action=="dashboard"){ ?>
        <p class="welcome">Welcome, <b><?php echo htmlspecialchars($_SESSION["name"]); ?></b></p>
        <table class="summary-table">
            <tr><th>My Courses</th><td><?php echo $totalCourses; ?></td></tr>
            <tr><th>My Quizzes</th><td><?php echo $totalQuizzes; ?></td></tr>
            <tr><th>Quiz Attempts</th><td><?php echo $totalAttempts; ?></td></tr>
        </table>

    <?php } else if($action=="courses"){ ?>
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
                        <?php foreach($courses as $course){ ?>
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

    <?php } else if($action=="quizzes"){ ?>
        <div class="layout-two">
            <div class="content-section">
                <h2>Create Quiz</h2>
                <?php if(count($courses)==0){ ?>
                    <p class="muted">Create a course first, then you can add quizzes to it.</p>
                <?php } else { ?>
                <form method="post" action="../Control/InstructorControl.php">
                    <input type="hidden" name="mode" value="create_quiz">
                    <label>Course</label>
                    <select name="course_id" required>
                        <?php foreach($courses as $course){ ?>
                        <option value="<?php echo $course["id"]; ?>"><?php echo htmlspecialchars($course["code"]." - ".$course["title"]); ?></option>
                        <?php } ?>
                    </select><br><br>
                    <label>Quiz title</label><input name="title" required><br><br>
                    <button class="btn" type="submit">Create Quiz</button>
                </form>
                <?php } ?>
            </div>

            <div class="content-section">
                <h2>My Quizzes</h2>
                <div class="table-wrap">
                    <table>
                        <tr><th>Course</th><th>Quiz</th><th>Questions</th><th>Status</th><th>Action</th></tr>
                        <?php foreach($quizzes as $quiz){ ?>
                        <tr>
                            <td><?php echo htmlspecialchars($quiz["code"]); ?></td>
                            <td><?php echo htmlspecialchars($quiz["title"]); ?></td>
                            <td><?php echo $quiz["questions"]; ?></td>
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

        <?php if($selectedQuiz){ ?>
        <div class="content-section">
            <h2>Questions of <?php echo htmlspecialchars($selectedQuiz["code"]." - ".$selectedQuiz["title"]); ?></h2>
            <?php if(count($questions)==0){ ?>
                <p class="muted">No questions yet. Add the first question below.</p>
            <?php } else { ?>
            <div class="table-wrap">
                <table>
                    <tr><th>#</th><th>Question</th><th>Correct Answer</th><th>Mark</th></tr>
                    <?php $number=1; foreach($questions as $question){ ?>
                    <tr>
                        <td><?php echo $number; ?></td>
                        <td><?php echo htmlspecialchars($question["question_text"]); ?></td>
                        <td><?php echo $question["correct_option"].". ".htmlspecialchars($question["option_".strtolower($question["correct_option"])]); ?></td>
                        <td><?php echo $question["mark"]+0; ?></td>
                    </tr>
                    <?php $number++; } ?>
                </table>
            </div>
            <?php } ?>
        </div>

        <div class="content-section">
            <h2>Add Question</h2>
            <form method="post" action="../Control/InstructorControl.php" class="form-grid">
                <input type="hidden" name="mode" value="add_question">
                <input type="hidden" name="quiz_id" value="<?php echo $selectedQuiz["id"]; ?>">
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

    <?php } else { ?>
        <div class="content-section">
            <h2>Student Quiz Results</h2>
            <div class="table-wrap">
                <table>
                    <tr><th>Student</th><th>Course</th><th>Quiz</th><th>Score</th><th>Status</th><th>Date</th></tr>
                    <?php foreach($results as $row){
                        $percent=$row["total"]>0 ? $row["score"]/$row["total"]*100 : 0;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["student"]); ?></td>
                        <td><?php echo htmlspecialchars($row["code"]); ?></td>
                        <td><?php echo htmlspecialchars($row["quiz"]); ?></td>
                        <td><?php echo htmlspecialchars(($row["score"]+0)." / ".($row["total"]+0)); ?></td>
                        <td><?php if($percent>=$passPercent) echo '<span class="badge">Pass</span>'; else echo '<span class="badge fail">Fail</span>'; ?></td>
                        <td><?php echo htmlspecialchars($row["attempted_at"]); ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <p class="muted">Pass mark: <?php echo $passPercent; ?>% of the total marks.</p>
        </div>
    <?php } ?>
</main>
</body>
</html>
