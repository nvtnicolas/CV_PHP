<?php
include __DIR__ . '/../db/db.php'; // Ensure the correct path to the db.php file
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? null; // Ensure $username is defined
$profileImagePath = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $education = $_POST['education'];
    $skills = $_POST['skills'];
    $experience = $_POST['experience'];
    $contact = $_POST['contact'];
    $description = $_POST['description'];

    // Ensure the uploads directory exists
    $targetDir = __DIR__ . '/../uploads/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (!empty($_FILES['profile_image']['name'])) {
        $profileImagePath = $targetDir . basename($_FILES['profile_image']['name']);
        if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $profileImagePath)) {
            echo '<div class="alert alert-danger">Error uploading image.</div>';
            $profileImagePath = null; // Set to null if upload fails
        } else {
            // Store the relative path to the image
            $profileImagePath = 'uploads/' . basename($_FILES['profile_image']['name']);
        }
    } else {
        $profileImagePath = $_POST['existing_profile_image'] ?? null; // Keep existing image if no new upload
    }

    try {
        // Check if the CV already exists
        $stmt = $pdo->prepare('SELECT id FROM cvs WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $user_id]);
        $cv = $stmt->fetch();

        if ($cv) {
            // Update the CV
            $stmt = $pdo->prepare('
                UPDATE cvs SET fullname = :fullname, education = :education, skills = :skills,
                experience = :experience, contact = :contact, description = :description,
                profile_image = :profile_image WHERE user_id = :user_id
            ');
            $stmt->execute([
                'fullname' => $fullname,
                'education' => $education,
                'skills' => $skills,
                'experience' => $experience,
                'contact' => $contact,
                'description' => $description,
                'profile_image' => $profileImagePath, // Save image path
                'user_id' => $user_id
            ]);
            echo '<div class="alert alert-success">CV updated successfully!</div>';
        } else {
            // Insert a new CV
            $stmt = $pdo->prepare('
                INSERT INTO cvs (user_id, fullname, education, skills, experience, contact, description, profile_image)
                VALUES (:user_id, :fullname, :education, :skills, :experience, :contact, :description, :profile_image)
            ');
            $stmt->execute([
                'user_id' => $user_id,
                'fullname' => $fullname,
                'education' => $education,
                'skills' => $skills,
                'experience' => $experience,
                'contact' => $contact,
                'description' => $description,
                'profile_image' => $profileImagePath
            ]);
            echo '<div class="alert alert-success">CV created successfully!</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Error saving CV: ' . $e->getMessage() . '</div>';
    }
} else {
    // Fetch existing CV data
    $stmt = $pdo->prepare('SELECT * FROM cvs WHERE user_id = :user_id');
    $stmt->execute(['user_id' => $user_id]);
    $cv = $stmt->fetch();
    if ($cv) {
        $fullname = $cv['fullname'];
        $education = $cv['education'];
        $skills = $cv['skills'];
        $experience = $cv['experience'];
        $contact = $cv['contact'];
        $description = $cv['description'];
        $profileImagePath = $cv['profile_image'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create or Update CV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/test.css">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CV Manager</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../router.php?page=index">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../router.php?page=admin">Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../router.php?page=profile">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../router.php?page=project">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../router.php?page=contact">Contact</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <button id="theme-toggle" class="btn btn-light me-2">Dark Mode</button>
                    <span class="navbar-text">
                        <?php if ($username): ?>
                            This is your profile page <?php echo htmlspecialchars($username); ?>
                        <?php endif; ?>
                        <a href="../auth/logout.php" class="btn btn-outline-light">Log out</a>
                    </span>
                </div>
            </div>
        </div>
    </nav>
</header>

<div class="container my-5">
    <h2 class="mb-4">Create or Update Your CV</h2>

    <form method="POST" action="" enctype="multipart/form-data">
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

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($description); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="profile_image" class="form-label">Profile Image</label>
            <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
            <input type="hidden" name="existing_profile_image" value="<?php echo htmlspecialchars($profileImagePath); ?>">
            <?php if (!empty($profileImagePath) && file_exists(__DIR__ . '/../' . $profileImagePath)): ?>
                <div class="mt-3">
                    <p>Current Image:</p>
                    <img src="<?php echo htmlspecialchars($profileImagePath); ?>" alt="Profile Image" style="width: 150px; height: auto;">
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Save CV</button>
    </form>
</div>

<footer class="bg-dark text-white text-center py-3">
    <p>© 2024 All rights reserved.</p>
    <p>Contact us at <a href="contact.php" class="btn btn-light">nicolas.nguyenvanthnah@ynov.com</a></p>
</footer>

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