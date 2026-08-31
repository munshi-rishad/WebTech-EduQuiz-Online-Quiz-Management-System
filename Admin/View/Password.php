<?php include "../Control/PasswordValidation.php"; ?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Change Password - EduQuiz</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="../JavaScript/Validation.js"></script>
</head>
<body>
<header>
    <a class="brand" href="<?php echo $home; ?>">EduQuiz</a>
    <nav>
        <a href="<?php echo $home; ?>">Dashboard</a>
        <a href="Profile.php">Profile</a>
        <a href="Password.php">Password</a>
        <a href="../Control/logout.php">Logout</a>
    </nav>
</header>
<main class="container">
    <div class="auth-area">
        <h1>Change Password</h1>
        <?php if($message!=""){ ?><div class="alert error"><?php echo htmlspecialchars($message); ?></div><?php } ?>
        <?php if($success!=""){ ?><div class="alert success"><?php echo htmlspecialchars($success); ?></div><?php } ?>
        <form method="post" action="" class="basic-form form-box" onsubmit="return validatePassword()">
            <div class="form-group">
                <label>Current password</label><input id="oldPassword" type="password" name="old" required>
            </div>
            <div class="form-group">
                <label>New password</label><input id="newPassword" type="password" name="new" required>
            </div>
            <div class="form-group">
                <label>Confirm new password</label><input id="confirmPassword" type="password" name="confirm" required>
            </div>
            <button class="btn" type="submit">Change Password</button>
        </form>
    </div>
</main>
</body>
</html>
