<?php

include __DIR__ . '/./db/db.php'; // Ensure the correct path to the db.php file
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
    <link rel="stylesheet" href="assets/css/test.css">
    <style>
        .swiper-container {
            width: 100%;
            padding: 20px 0;
        }
        .swiper-slide {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .cv-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            max-width: 300px;
            text-align: center;
            display: none;
            transition: all 0.3s ease;
        }
        .cv-card.active {
            display: block;
        }
        .cv-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .cv-details {
            display: none;
        }
        .cv-card.active .cv-details {
            display: block;
        }
        .cv-name {
            cursor: pointer;
            font-size: 1.2em;
            font-weight: bold;
            padding: 10px;
            background-color: #eee;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        body.dark-mode .cv-name {
            background-color: #444; /* Change this to match the card background */
            color: #f4f4f4; /* Light text color */
        }
        body.dark-mode .cv-card {
            background-color: #444;
            color: #f4f4f4;
            border-color: #555;
        }
        body.dark-mode .cv-card:hover {
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.2);
        }
        body.dark-mode .cv-card .cv-details {
            background-color: #666; /* Fond gris pour les détails ouverts */
            color: #f4f4f4; /* Texte blanc pour les détails ouverts */
        }
        body.dark-mode .cv-card:not(.active) {
            background-color: #555; /* Fond gris foncé pour les cartes non ouvertes */
            color: #f4f4f4; /* Texte blanc pour les cartes non ouvertes */
        }
        body.dark-mode .bg-light {
            background-color: #3d3d3d !important;
        }
    </style>
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
<script>
    function toggleCard(nameElement) {
        const card = nameElement.nextElementSibling;
        if (card.classList.contains('active')) {
            card.classList.remove('active');
        } else {
            card.classList.add('active');
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

    document.getElementById('theme-toggle').addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
        const buttons = document.querySelectorAll('button, .nav-btn');
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
