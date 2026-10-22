<?php include "../Control/ProfileValidation.php"; ?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>My Profile - EduQuiz</title>
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
    <div class="page-title"><h1>My Profile</h1></div>
    <?php if($message!=""){ ?><div class="alert error"><?php echo htmlspecialchars($message); ?></div><?php } ?>
    <?php if($success!=""){ ?><div class="alert success"><?php echo htmlspecialchars($success); ?></div><?php } ?>

    <div class="layout-two">
        <section class="content-section">
            <h2>Profile Information</h2>
            <ul class="profile-list">
                <li><b>Name:</b> <?php echo htmlspecialchars($user["name"]); ?></li>
                <li><b>Username:</b> <?php echo htmlspecialchars($user["username"]); ?></li>
                <li><b>Email:</b> <?php echo htmlspecialchars($user["email"]); ?></li>
                <li><b>Role:</b> <?php echo htmlspecialchars(ucfirst($user["role"])); ?></li>
                <li><b>Joined:</b> <?php echo htmlspecialchars($user["created_at"]); ?></li>
            </ul>
        </section>

        <section class="content-section">
            <h2>Edit Profile</h2>
            <form method="post" action="" class="basic-form form-box" onsubmit="return validateProfile()">
                <input type="hidden" name="mode" value="update">
                <div class="form-group">
                    <label>Name</label><input id="profileName" name="name" value="<?php echo htmlspecialchars($user["name"]); ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone</label><input name="phone" value="<?php echo htmlspecialchars($user["phone"]); ?>">
                </div>
                <div class="form-group">
                    <label>Department</label><input name="department" value="<?php echo htmlspecialchars($user["department"]); ?>">
                </div>
                <button class="btn" type="submit">Save Changes</button>
            </form>
        </section>
    </div>

    <section class="content-section">
        <h2>Delete Account</h2>
        <p class="muted">This permanently deletes your profile and related data.</p>
        <form method="post" action="" class="basic-form form-box" onsubmit="return confirm('Delete your account permanently?')">
            <input type="hidden" name="mode" value="delete">
            <div class="inline">
                <input type="password" name="password" placeholder="Enter password to confirm" required>
                <button class="btn danger" type="submit">Delete Account</button>
            </div>
        </form>
    </section>
</main>
</body>
</html>
