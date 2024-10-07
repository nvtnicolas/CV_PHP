<?php
// Include the database connection
include 'db.php';

try {
    // Fetch all CVs from the 'cvs' table
    $stmt = $pdo->prepare('SELECT cvs.*, users.username FROM cvs 
                           JOIN users ON cvs.user_id = users.id');
    $stmt->execute();
    $cvs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Error fetching CVs: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projects - View CVs</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h2>View CVs of Users</h2>

<?php if (!empty($cvs)): ?>
    <div class="cv-list">
        <?php foreach ($cvs as $cv): ?>
            <div class="cv-item">
                <h3>CV of <?php echo htmlspecialchars($cv['fullname']); ?> (<?php echo htmlspecialchars($cv['username']); ?>)</h3>
                <p><strong>Education:</strong> <?php echo nl2br(htmlspecialchars($cv['education'])); ?></p>
                <p><strong>Skills:</strong> <?php echo nl2br(htmlspecialchars($cv['skills'])); ?></p>
                <p><strong>Experience:</strong> <?php echo nl2br(htmlspecialchars($cv['experience'])); ?></p>
                <p><strong>Contact:</strong> <?php echo nl2br(htmlspecialchars($cv['contact'])); ?></p>
                <hr>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No CVs found.</p>
<?php endif; ?>

</body>
</html>
