<?php include "../Control/AdminControl.php"; ?>

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
        <a href="../../Auth/View/Profile.php">Profile</a>
        <a href="../../Auth/View/Password.php">Password</a>
        <a href="../../Auth/Control/logout.php">Logout</a>
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

    <?php if($action=="dashboard"){ ?>
        <p class="welcome">Welcome, <b><?php echo htmlspecialchars($_SESSION["name"]); ?></b></p>
        <table class="summary-table">
            <tr><th>Users</th><td><?php echo $totalUsers; ?></td></tr>
            <tr><th>Courses</th><td><?php echo $totalCourses; ?></td></tr>
            <tr><th>Quiz Attempts</th><td><?php echo $totalAttempts; ?></td></tr>
        </table>

    <?php } else if($action=="users"){ ?>
        <section class="content-section">
            <h2>All Users</h2>
            <div class="table-wrap">
                <table>
                    <tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th></tr>
                    <?php foreach($users as $user){ ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user["name"]); ?><div class="muted">@<?php echo htmlspecialchars($user["username"]); ?></div></td>
                        <td><?php echo htmlspecialchars($user["email"]); ?></td>
                        <td><?php echo htmlspecialchars($user["role"]); ?></td>
                        <td><?php if($user["is_active"]==1) echo "Active"; else echo "Inactive"; ?></td>
                        <td class="inline">
                            <?php if($user["id"]!=$me){ ?>
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

    <?php } else if($action=="courses"){ ?>
        <section class="content-section">
            <h2>Course Control</h2>
            <div class="table-wrap">
                <table>
                    <tr><th>Code</th><th>Title</th><th>Instructor</th><th>Students</th><th>Action</th></tr>
                    <?php foreach($courses as $course){ ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course["code"]); ?></td>
                        <td><?php echo htmlspecialchars($course["title"]); ?></td>
                        <td><?php echo htmlspecialchars($course["instructor"]); ?></td>
                        <td><?php echo $course["students"]; ?></td>
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

    <?php } else { ?>
        <table class="summary-table">
            <tr><th>Total Students</th><td><?php echo $totalStudents; ?></td></tr>
            <tr><th>Total Instructors</th><td><?php echo $totalInstructors; ?></td></tr>
            <tr><th>Total Courses</th><td><?php echo $totalCourses; ?></td></tr>
            <tr><th>Total Quizzes</th><td><?php echo $totalQuizzes; ?></td></tr>
            <tr><th>Total Attempts</th><td><?php echo $totalAttempts; ?></td></tr>
        </table>

        <section class="content-section">
            <h2>Recent Quiz Attempts</h2>
            <div class="table-wrap">
                <table>
                    <tr><th>Student</th><th>Quiz</th><th>Score</th><th>Date</th></tr>
                    <?php foreach($recent as $row){ ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["title"]); ?></td>
                        <td><?php echo htmlspecialchars(($row["score"]+0)."/".($row["total"]+0)); ?></td>
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
