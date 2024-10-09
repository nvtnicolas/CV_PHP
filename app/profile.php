<?php
// Include the database connection
include 'db.php';

// Hardcoded user ID for now (replace with session user ID in the future)
$user_id = 1;

// Initialize variables for CV fields
$fullname = '';
$education = '';
$skills = '';
$experience = '';
$contact = '';

// Retrieve the existing CV data for the user if it exists
try {
    $stmt = $pdo->prepare('SELECT * FROM cvs WHERE user_id = :user_id');
    $stmt->execute(['user_id' => $user_id]);
    $cv = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cv) {
        // If a CV exists, populate the form fields with the data
        $fullname = $cv['fullname'];
        $education = $cv['education'];
        $skills = $cv['skills'];
        $experience = $cv['experience'];
        $contact = $cv['contact'];
    }
} catch (PDOException $e) {
    echo '<p style="color:red;">Error loading CV data: ' . $e->getMessage() . '</p>';
}

// Handle form submission (creating or updating the CV)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize CV data from the form
    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $education = htmlspecialchars(trim($_POST['education']));
    $skills = htmlspecialchars(trim($_POST['skills']));
    $experience = htmlspecialchars(trim($_POST['experience']));
    $contact = htmlspecialchars(trim($_POST['contact']));

    try {
        // Check if the user already has a CV
        $stmt = $pdo->prepare('SELECT id FROM cvs WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $user_id]);
        $cv = $stmt->fetch();

        if ($cv) {
            // If CV exists, update the existing record
            $stmt = $pdo->prepare('UPDATE cvs 
                                   SET fullname = :fullname, education = :education, skills = :skills, experience = :experience, contact = :contact
                                   WHERE user_id = :user_id');
            $stmt->execute([
                'fullname' => $fullname,
                'education' => $education,
                'skills' => $skills,
                'experience' => $experience,
                'contact' => $contact,
                'user_id' => $user_id
            ]);

            echo '<p style="color:green;">CV updated successfully!</p>';
        } else {
            // If no CV exists, insert a new record
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
        }
    } catch (PDOException $e) {
        echo '<p style="color:red;">Error saving CV: ' . $e->getMessage() . '</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create or Update CV</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h2>Create or Update Your CV</h2>
<header >
    <form method="GET" action="router.php">
        <button class="nav-btn" name="page" value="admin">Admin</button>
        <button class="nav-btn" name="page" value="profile">Profile</button>
        <button class="nav-btn" name="page" value="project">Projects</button>
        <button class="nav-btn" name="page" value="contact">Contact</button>
    </form>
    <a href="logout.php">Log out</a>
</header>
<form method="POST" action="">
    <label for="fullname">Full Name:</label>
    <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required><br><br>

    <label for="education">Education:</label><br>
    <textarea id="education" name="education" rows="4" cols="50" required><?php echo htmlspecialchars($education); ?></textarea><br><br>

    <label for="skills">Skills:</label><br>
    <textarea id="skills" name="skills" rows="4" cols="50" required><?php echo htmlspecialchars($skills); ?></textarea><br><br>

    <label for="experience">Experience:</label><br>
    <textarea id="experience" name="experience" rows="4" cols="50" required><?php echo htmlspecialchars($experience); ?></textarea><br><br>

    <label for="contact">Contact:</label><br>
    <textarea id="contact" name="contact" rows="4" cols="50" required><?php echo htmlspecialchars($contact); ?></textarea><br><br>

    <button type="submit">Save CV</button>
</form>

</body>
</html>
