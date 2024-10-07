<?php
// Include your database connection file
include 'db.php'; // Assuming db.php contains your PDO connection as $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize the input data to avoid XSS or code injection
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate email format
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            // Prepare the SQL statement using PDO to insert contact data into the 'contacts' table
            $stmt = $pdo->prepare('INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)');
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'message' => $message
            ]);

            echo '<p style="color:green;">Message sent successfully!</p>';
        } catch (PDOException $e) {
            // Handle any potential database errors
            echo '<p style="color:red;">Error sending message: ' . $e->getMessage() . '</p>';
        }
    } else {
        // Email is not valid
        echo '<p style="color:red;">Invalid email address.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h2>Contact Admin</h2>

<form method="POST" action="">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="message">Message:</label><br>
    <textarea id="message" name="message" rows="4" cols="50" required></textarea><br><br>

    <button type="submit">Send</button>
</form>

</body>
</html>
