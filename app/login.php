<?php
session_start();
include 'db.php';  // Include database connection

// Handling login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the query to check if the user exists
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify the password if user exists
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header('Location: index.php');  // Redirect to the home page after login
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}

// Handling registration
if (isset($_POST['register'])) {
    $username = $_POST['reg_username'];
    $email = $_POST['reg_email'];
    $password = $_POST['reg_password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hashing password

    // Check if username or email already exists
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
    $stmt->execute(['username' => $username, 'email' => $email]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        $error = "Username or email already taken!";
    } else {
        // Insert new user into the database
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashedPassword]);
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
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h2>Login</h2>

<!-- Login Form -->
<form method="POST" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit" name="login">Login</button>
</form>

<?php if (isset($error)) { echo '<p style="color:red;">' . $error . '</p>'; } ?>
<?php if (isset($success)) { echo '<p style="color:green;">' . $success . '</p>'; } ?>

<hr>

<h2>Create an Account</h2>

<!-- Registration Form -->
<form method="POST" action="">
    <label for="reg_username">Username:</label>
    <input type="text" id="reg_username" name="reg_username" required><br><br>

    <label for="reg_email">Email:</label>
    <input type="email" id="reg_email" name="reg_email" required><br><br>

    <label for="reg_password">Password:</label>
    <input type="password" id="reg_password" name="reg_password" required><br><br>

    <button type="submit" name="register">Register</button>
</form>

</body>
</html>
