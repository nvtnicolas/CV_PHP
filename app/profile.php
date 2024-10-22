<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';  // Include database connection

// Retrieve the user ID and username from the session
$user_id = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? null;

if ($user_id) {
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
        echo '<div class="alert alert-danger">Error loading CV data: ' . $e->getMessage() . '</div>';
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
                $stmt = $pdo->prepare('UPDATE cvs SET fullname = :fullname, education = :education, skills = :skills, experience = :experience, contact = :contact WHERE user_id = :user_id');
                $stmt->execute([
                    'fullname' => $fullname,
                    'education' => $education,
                    'skills' => $skills,
                    'experience' => $experience,
                    'contact' => $contact,
                    'user_id' => $user_id
                ]);
                echo '<div class="alert alert-success">CV updated successfully!</div>';
            } else {
                // If no CV exists, insert a new record
                $stmt = $pdo->prepare('INSERT INTO cvs (user_id, fullname, education, skills, experience, contact) VALUES (:user_id, :fullname, :education, :skills, :experience, :contact)');
                $stmt->execute([
                    'user_id' => $user_id,
                    'fullname' => $fullname,
                    'education' => $education,
                    'skills' => $skills,
                    'experience' => $experience,
                    'contact' => $contact
                ]);
                echo '<div class="alert alert-success">CV created successfully!</div>';
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Error saving CV: ' . $e->getMessage() . '</div>';
        }
    }
} else {
    echo '<div class="alert alert-danger">User not logged in.</div>';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create or Update CV</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="test.css">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CV Manager</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="router.php?page=index">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="router.php?page=admin">Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="router.php?page=profile">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="router.php?page=project">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="router.php?page=contact">Contact</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <button id="theme-toggle" class="btn btn-light me-2">Dark Mode</button>
                    <span class="navbar-text">
                        <?php if ($username): ?>
                            Hello, <?php echo htmlspecialchars($username); ?>
                        <?php endif; ?>
                        <a href="logout.php" class="btn btn-outline-light">Log out</a>
                    </span>
                </div>
            </div>
        </div>
    </nav>
</header>

<div class="container my-5">
    <h2 class="mb-4">Create or Update Your CV</h2>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="fullname" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required>
        </div>

        <div class="mb-3">
            <label for="education" class="form-label">Education</label>
            <textarea class="form-control" id="education" name="education" rows="4" required><?php echo htmlspecialchars($education); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="skills" class="form-label">Skills</label>
            <textarea class="form-control" id="skills" name="skills" rows="4" required><?php echo htmlspecialchars($skills); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="experience" class="form-label">Experience</label>
            <textarea class="form-control" id="experience" name="experience" rows="4" required><?php echo htmlspecialchars($experience); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="contact" class="form-label">Contact</label>
            <textarea class="form-control" id="contact" name="contact" rows="4" required><?php echo htmlspecialchars($contact); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save CV</button>
    </form>
</div>

<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2024 All rights reserved.</p>
    <p>Contact us at <a href="contact.php" class="btn btn-light">nicolas.nguyenvanthnah@ynov.com</a></p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('theme-toggle').addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
        const buttons = document.querySelectorAll('button, .nav-btn');
        buttons.forEach(button => button.classList.toggle('dark-mode'));

        // Change button text based on the current theme
        if (document.body.classList.contains('dark-mode')) {
            this.textContent = 'Light Mode';
            this.classList.remove('btn-light');
            this.classList.add('btn-dark');
        } else {
            this.textContent = 'Dark Mode';
            this.classList.remove('btn-dark');
            this.classList.add('btn-light');
        }
    });
</script>


</body>
</html>
