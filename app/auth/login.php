<?php
session_start();
include '../db/db.php';


if (isset($_POST['login'])) {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (!empty($username) && !empty($password)) {

        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header('Location: ../index.php');
            exit();
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Please fill in all fields!";
    }
}


if (isset($_POST['register'])) {
    $username = htmlspecialchars(trim($_POST['reg_username']));
    $email = filter_var(trim($_POST['reg_email']), FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars(trim($_POST['reg_password']));
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
    $stmt->execute(['username' => $username, 'email' => $email]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        $error = "Username or email already taken!";
    } else {

        $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)');
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => 'user'
        ]);
        $success = "Account successfully created! You can now log in.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/test.css">
</head>
<body>
<header class="bg-dark text-white py-3">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="h3">Login & Register</h1>
        <button id="theme-toggle" class="btn btn-light">Dark Mode</button>
    </div>
</header>

<div class="container my-5">

    <div class="row">
        <div class="col-md-6">
            <h2>Login</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>

                </div>
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </form>
            <?php if (isset($error)) { echo '<div class="alert alert-danger mt-3">' . $error . '</div>'; } ?>
        </div>


        <div class="col-md-6">
            <h2>Create an Account</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="reg_username">Username:</label>
                    <input type="text" class="form-control" id="reg_username" name="reg_username" required>
                </div>
                <div class="form-group">
                    <label for="reg_email">Email:</label>
                    <input type="email" class="form-control" id="reg_email" name="reg_email" required>
                </div>
                <div class="form-group">
                    <label for="reg_password">Password:</label>
                    <input type="password" class="form-control" id="reg_password" name="reg_password" required>

                </div>
                <button type="submit" name="register" class="btn btn-success">Register</button>
            </form>
            <?php if (isset($success)) { echo '<div class="alert alert-success mt-3">' . $success . '</div>'; } ?>
        </div>
    </div>


    <div class="text-center mt-5">
        <a href="../Index_no_log.php" class="btn btn-secondary">Continue as Guest</a>
        <input type="checkbox" onclick="togglePassword()"> Show Password
    </div>
</div>


<footer class="bg-dark text-white text-center py-3">
    <p>© 2024 All rights reserved.</p>
    <p>Contact us at <a class="text-light" href="../contact.php">nicolas.nguyenvanthnah@ynov.com</a></p>
</footer>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/togglePassword.js"></script>
<script src="../assets/js/dark-mode.js"></script>
</body>
</html>
