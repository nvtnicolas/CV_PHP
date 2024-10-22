<?php
// Include the database connection
include 'db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    // Fetch all CVs from the 'cvs' table
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
    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="test.css">
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
                <a href="logout.php" class="btn btn-danger">Log out</a>
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
                    <!-- Nom de l'utilisateur, cliquable pour afficher la carte -->
                    <div class="cv-name p-3 bg-light border rounded text-center" onclick="toggleCard(this)">
                        <?php echo htmlspecialchars($cv['fullname']); ?>
                    </div>
                    <!-- La carte est cachée par défaut et ne s'affiche qu'au clic -->
                    <div class="cv-card card mt-3">
                        <div class="card-body">
                            <h3 class="card-title"><?php echo htmlspecialchars($cv['fullname']); ?> (<?php echo htmlspecialchars($cv['username']); ?>)</h3>
                            <!-- Détails cachés par défaut qui apparaissent après le clic -->
                            <div class="cv-details">
                                <p><strong>Education:</strong> <?php echo nl2br(htmlspecialchars($cv['education'])); ?></p>
                                <p><strong>Skills:</strong> <?php echo nl2br(htmlspecialchars($cv['skills'])); ?></p>
                                <p><strong>Experience:</strong> <?php echo nl2br(htmlspecialchars($cv['experience'])); ?></p>
                                <p><strong>Contact:</strong> <?php echo nl2br(htmlspecialchars($cv['contact'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Add Pagination and Navigation buttons -->
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>

<!-- Footer -->
<footer class="container-fluid bg-dark text-white text-center py-3 mt-5">
    <p>© 2024 All rights reserved.</p>
    <p>Contact us at <a class="btn btn-light" href="contact.php">nicolas.nguyenvanthnah@ynov.com</a></p>
</footer>

<!-- Bootstrap JS and Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script>
    // Fonction pour afficher ou cacher la carte au clic
    function toggleCard(nameElement) {
        const card = nameElement.nextElementSibling; // Sélectionner la carte associée
        if (card.classList.contains('active')) {
            card.classList.remove('active'); // Cacher la carte si elle est déjà visible
        } else {
            card.classList.add('active'); // Afficher la carte
        }
    }

    const swiper = new Swiper('.swiper-container', {
        slidesPerView: 3,
        spaceBetween: 30,
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        autoplay: {
            delay: 3000,
        },
    });

    // Script pour basculer entre les thèmes
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
