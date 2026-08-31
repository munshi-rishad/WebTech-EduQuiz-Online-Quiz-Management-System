<?php include "../Control/RegistrationValidation.php"; ?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Create Account - EduQuiz</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="../JavaScript/CheckUser.js"></script>
    <script src="../JavaScript/Validation.js"></script>
</head>
<body>
<header>
    <a class="brand" href="../../index.php">EduQuiz</a>
    <nav>
        <a href="Login.php">Login</a>
        <a href="Registration.php">Register</a>
    </nav>
</header>
<main class="container">
    <div class="auth-area">
        <h1>Create Account</h1>
        <?php if($message!=""){ ?><div class="alert error"><?php echo htmlspecialchars($message); ?></div><?php } ?>
        <form method="post" action="" class="basic-form form-box form-grid" onsubmit="return validateRegistration()">
            <div>
                <label>Full name</label>
                <input id="name" name="name" required>
            </div>
            <div>
                <label>Username</label>
                <input id="username" name="username" onkeyup="CheckUser()" required>
                <span id="userresponse" class="check-response"></span>
            </div>
            <div>
                <label>Email</label>
                <input id="email" type="email" name="email" onkeyup="CheckEmail()" required>
                <span id="emailresponse" class="check-response"></span>
            </div>
            <div>
                <label>Phone</label>
                <input id="phone" name="phone" onkeyup="CheckPhone()">
                <span id="phoneresponse" class="check-response"></span>
            </div>
            <div>
                <label>Department</label>
                <input name="department">
            </div>
            <div>
                <label>Account type</label>
                <select name="role">
                    <option value="student">Student</option>
                    <option value="admin">Admin</option>
                    <option value="instructor">Instructor</option>
                </select>
            </div>
            <div>
                <label>Password</label>
                <input id="password" type="password" name="password" required>
            </div>
            <div>
                <label>Confirm password</label>
                <input id="confirm" type="password" name="confirm" required>
            </div>
            <div class="full">
                <button class="btn" type="submit">Register</button>
            </div>
        </form>
    </div>
</main>
</body>
</html>
