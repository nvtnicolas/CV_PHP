<?php

include __DIR__ . '/./db/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $stmt = $pdo->prepare('SELECT cvs.*, users.username FROM cvs JOIN users ON cvs.user_id = users.id');
    $stmt->execute();
    $cvs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Error fetching CVs: ' . $e->getMessage();
}

$username = $_SESSION['username'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projects - View CVs</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/project.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

</head>
<body>
<header class="bg-dark text-white py-3">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <form method="GET" action="router.php">
                <button class="btn btn-light me-2" name="page" value="index">Home</button>
                <button class="btn btn-light me-2" name="page" value="profile">Profile</button>
                <button class="btn btn-light me-2" name="page" value="project">Projects</button>
                <button class="btn btn-light" name="page" value="contact">Contact</button>
            </form>
            <div class="d-flex align-items-center">
                <button id="theme-toggle" class="btn btn-light me-2">Dark Mode</button>
                <?php if ($username): ?>
                    <span class="me-3">Here you can see other projects, <?php echo htmlspecialchars($username); ?></span>
                <?php endif; ?>
                <a href="auth/logout.php" class="btn btn-danger">Log out</a>
            </div>
        </div>
    </div>
</header>

<div class="container mt-4">
    <h2 class="text-center mb-4">View CVs of Users</h2>

    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php foreach ($cvs as $cv): ?>
                <div class="swiper-slide">
                    <div class="cv-name p-3 bg-light border rounded text-center" onclick="toggleCard(this)">
                        <?php echo htmlspecialchars($cv['fullname']); ?>
                        <?php
                        $imagePath = $cv['profile_image'];
                        ?>
                        <?php if (!empty($cv['profile_image']) && file_exists(__DIR__ . '/' . $imagePath)): ?>
                            <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Profile Image" class="profile-image">
                        <?php else: ?>
                            <img src="./uploads/profil.png" alt="Default Profile Image" class="profile-image">
                        <?php endif; ?>
                    </div>
                    <div class="cv-card card mt-3">
                        <div class="card-body">
                            <h3 class="card-title"><?php echo htmlspecialchars($cv['fullname']); ?> (<?php echo htmlspecialchars($cv['username']); ?>)</h3>
                            <div class="cv-details">
                                <p><strong>Education:</strong> <?php echo nl2br(htmlspecialchars($cv['education'])); ?></p>
                                <p><strong>Skills:</strong> <?php echo nl2br(htmlspecialchars($cv['skills'])); ?></p>
                                <p><strong>Experience:</strong> <?php echo nl2br(htmlspecialchars($cv['experience'])); ?></p>
                                <p><strong>Contact:</strong> <?php echo nl2br(htmlspecialchars($cv['contact'])); ?></p>
                                <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($cv['description'])); ?></p>
                            </div>
                            <button class="btn btn-primary mt-3" onclick="downloadCV('<?php echo htmlspecialchars($cv['fullname']); ?>', '<?php echo htmlspecialchars($cv['education']); ?>', '<?php echo htmlspecialchars($cv['skills']); ?>', '<?php echo htmlspecialchars($cv['experience']); ?>', '<?php echo htmlspecialchars($cv['contact']); ?>', '<?php echo htmlspecialchars($cv['description']); ?>')">Download CV as PDF</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>

<footer class="container-fluid bg-dark text-white text-center py-3 mt-5">
    <p>© 2024 All rights reserved.</p>
    <p>Contact us at <a class="btn btn-light" href="contact.php">nicolas.nguyenvanthnah@ynov.com</a></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="assets/js/swiper.js"></script>
<script src="assets/js/dark-mode.js"></script>
<script src="assets/js/download-cv.js"></script>
</body>
</html>