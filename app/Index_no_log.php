<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="test.css">
</head>
<body>


<main class="container text-center my-5">
    <h1 class="mb-4">Welcome to the Homepage!</h1>


    <a href="login.php" class="btn btn-primary btn-lg mb-3">Login</a>


    <a href="contact.php" class="btn btn-secondary btn-lg mb-3">Contact Us</a>


    <a href="project.php" class="btn btn-success btn-lg mb-3">View Projects</a>
</main>


<footer class="container-fluid text-center py-3 bg-dark text-white">
    <p>© 2024 All rights reserved.</p>
    <p>Contact us at <a href="contact.php" class="btn btn-light">nicolas.nguyenvanthnah@ynov.com</a></p>
</footer>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.createElement('button');
        themeToggle.id = 'theme-toggle';
        themeToggle.className = 'btn btn-light';
        themeToggle.textContent = 'Dark Mode';
        document.body.insertBefore(themeToggle, document.body.firstChild);

        themeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            if (document.body.classList.contains('dark-mode')) {
                themeToggle.textContent = 'Light Mode';
                themeToggle.classList.remove('btn-light');
                themeToggle.classList.add('btn-dark');
            } else {
                themeToggle.textContent = 'Dark Mode';
                themeToggle.classList.remove('btn-dark');
                themeToggle.classList.add('btn-light');
            }
        });
    });
</script>
</body>
</html>
