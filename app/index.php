<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './db/db.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? '';

try {
    $stmt = $pdo->prepare('SELECT * FROM cvs WHERE user_id = :user_id');
    $stmt->execute(['user_id' => $user_id]);
    $cv = $stmt->fetch(PDO::FETCH_ASSOC);
    $display_name = $cv && !empty($cv['fullname']) ? $cv['fullname'] : 'Edit your CV in profile';
} catch (PDOException $e) {
    echo '<p style="color:red;">Error fetching CV data: ' . $e->getMessage() . '</p>';
    $display_name = 'Edit your CV in profile';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/test.css">
    <style>
        .dark-mode {
            background-color: #333;
            color: #fff;
        }
        /* Dark mode styles */
        body.dark-mode {
            background-color: #333;
            color: #f4f4f4;
        }

        body.dark-mode .cv-card {
            background-color: #222; /* Darker background for the CV card */
            color: #f4f4f4; /* Light text for contrast */
        }

        body.dark-mode .card-body {
            background-color: #222; /* Darker background for the card body */
            color: #f4f4f4; /* Light text for contrast */
        }
    </style>
</head>
<body>

<?php if (!empty($username)): ?>

    <header class="container-fluid bg-dark text-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h4">Welcome, <?php echo htmlspecialchars($display_name); ?>!</h1>
            <nav>
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link text-white" href="router.php?page=index">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="router.php?page=profile">Profile</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="router.php?page=project">Projects</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="router.php?page=contact">Contact</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="auth/logout.php">Log out</a></li>
                </ul>
            </nav>
            <button id="theme-toggle" class="btn btn-light">Dark Mode</button>
        </div>
    </header>

    <main class="container my-5">
        <h2>Welcome to the homepage!</h2>
        <p>This is the content section of your homepage.</p>

        <?php if ($cv): ?>
            <div class="cv-card card mt-4">
                <div class="card-body">
                    <h3 class="card-title"><?php echo htmlspecialchars($cv['fullname']); ?></h3>
                    <p><strong>Education:</strong> <?php echo nl2br(htmlspecialchars($cv['education'])); ?></p>
                    <p><strong>Skills:</strong> <?php echo nl2br(htmlspecialchars($cv['skills'])); ?></p>
                    <p><strong>Experience:</strong> <?php echo nl2br(htmlspecialchars($cv['experience'])); ?></p>
                    <p><strong>Contact:</strong> <?php echo nl2br(htmlspecialchars($cv['contact'])); ?></p>
                    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($cv['description'])); ?></p>
                </div>
            </div>
        <?php else: ?>
            <p>No CV found. Please <a href="router.php?page=profile">edit your profile</a> to add your CV.</p>
        <?php endif; ?>
    </main>

    <footer class="container-fluid bg-dark text-white text-center py-3">
        <p>© 2024 All rights reserved.</p>
        <p>Contact us at <a class="btn btn-light" href="contact.php">nicolas.nguyenvanthnah@ynov.com</a></p>
    </footer>

<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('theme-toggle').addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
        const buttons = document.querySelectorAll('button, .nav-link');
        buttons.forEach(button => button.classList.toggle('dark-mode'));

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

