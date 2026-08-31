<?php include "../Control/ForgotPasswordValidation.php"; ?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Reset Password - EduQuiz</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="../JavaScript/Validation.js"></script>
</head>
<body>
<header>
    <a class="brand" href="../../index.php">EduQuiz</a>
    <nav><a href="Login.php">Login</a><a href="Registration.php">Register</a></nav>
</header>
<main class="container">
    <div class="auth-area">
        <h1>Reset Password</h1>
        <?php if($message!=""){ ?><div class="alert error"><?php echo htmlspecialchars($message); ?></div><?php } ?>
        <form method="post" action="" class="basic-form form-box" onsubmit="return validateForgotPassword()">
            <div class="form-group">
                <label>Email</label><input id="forgotEmail" type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Username</label><input id="forgotUsername" name="username" required>
            </div>
            <div class="form-group">
                <label>New password</label><input id="forgotPassword" type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Confirm new password</label><input id="forgotConfirm" type="password" name="confirm" required>
            </div>
            <button class="btn" type="submit">Reset Password</button>
        </form>
    </div>
</main>
</body>
</html>
