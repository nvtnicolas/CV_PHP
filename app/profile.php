<?php
// Include the database connection
include 'db.php';


// Retrieve the user ID from the session


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize CV data
    $user_id = 1; // Hardcoded user ID for now
    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $education = htmlspecialchars(trim($_POST['education']));
    $skills = htmlspecialchars(trim($_POST['skills']));
    $experience = htmlspecialchars(trim($_POST['experience']));
    $contact = htmlspecialchars(trim($_POST['contact']));

    // Debugging: Print out the CV data
    echo "<pre>";
    print_r([
        'user_id' => $user_id,
        'fullname' => $fullname,
        'education' => $education,
        'skills' => $skills,
        'experience' => $experience,
        'contact' => $contact,
    ]);
    echo "</pre>";

    try {
        // Insert CV data into the 'cvs' table
        $stmt = $pdo->prepare('INSERT INTO cvs (user_id, fullname, education, skills, experience, contact)
                               VALUES (:user_id, :fullname, :education, :skills, :experience, :contact)');
        $stmt->execute([
            'user_id' => $user_id,
            'fullname' => $fullname,
            'education' => $education,
            'skills' => $skills,
            'experience' => $experience,
            'contact' => $contact
        ]);

        echo '<p style="color:green;">CV created successfully!</p>';
    } catch (PDOException $e) {
        echo '<p style="color:red;">Error creating CV: ' . $e->getMessage() . '</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create CV</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h2>Create Your CV</h2>

<form method="POST" action="">
    <label for="fullname">Full Name:</label>
    <input type="text" id="fullname" name="fullname" required><br><br>

    <label for="education">Education:</label><br>
    <textarea id="education" name="education" rows="4" cols="50" required></textarea><br><br>

    <label for="skills">Skills:</label><br>
    <textarea id="skills" name="skills" rows="4" cols="50" required></textarea><br><br>

    <label for="experience">Experience:</label><br>
    <textarea id="experience" name="experience" rows="4" cols="50" required></textarea><br><br>

    <label for="contact">contact:</label><br>
    <textarea id="contact" name="contact" rows="4" cols="50" required></textarea><br><br>

    <button type="submit">Create CV</button>
</form>

</body>
</html>
