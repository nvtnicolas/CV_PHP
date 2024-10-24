<?php

include 'db.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    echo 'Access denied. Admins only.';
    exit();
}


if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    try {
        $pdo->prepare('DELETE FROM cvs WHERE user_id = :user_id')->execute(['user_id' => $user_id]);
        $pdo->prepare('DELETE FROM users WHERE id = :user_id')->execute(['user_id' => $user_id]);
        echo '<p style="color:green;">User and CV deleted successfully.</p>';
    } catch (PDOException $e) {
        echo '<p style="color:red;">Error deleting user: ' . $e->getMessage() . '</p>';
    }
}

if (isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['new_role'];

    try {
        $pdo->prepare('UPDATE users SET role = :role WHERE id = :user_id')->execute(['role' => $new_role, 'user_id' => $user_id]);
        echo '<p style="color:green;">User role updated successfully.</p>';
    } catch (PDOException $e) {
        echo '<p style="color:red;">Error updating role: ' . $e->getMessage() . '</p>';
    }
}


try {
    $stmt = $pdo->query('
        SELECT u.id, u.username, u.email, u.role, c.id AS cv_id, c.fullname, c.education, c.skills, c.experience, c.contact
        FROM users u
        LEFT JOIN cvs c ON u.id = c.user_id
        ORDER BY u.id
    ');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $messages_stmt = $pdo->query('SELECT * FROM contacts ORDER BY created_at DESC');
    $messages = $messages_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<p style="color:red;">Error loading data: ' . $e->getMessage() . '</p>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="test.css">
</head>
<body>
<header class="bg-dark text-white p-3 mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <form method="GET" action="router.php" class="d-flex">
            <button class="btn btn-secondary mr-2" name="page" value="index">Home</button>
            <button class="btn btn-secondary mr-2" name="page" value="admin">Admin</button>
            <button class="btn btn-secondary mr-2" name="page" value="profile">Profile</button>
            <button class="btn btn-secondary mr-2" name="page" value="project">Projects</button>
            <button class="btn btn-secondary" name="page" value="contact">Contact</button>
        </form>
        <div>
            <button id="theme-toggle" class="btn btn-light">Dark Mode</button>
            <a href="logout.php" class="btn btn-danger">Log out</a>
        </div>
    </div>
</header>


<div class="container">
    <h2>Admin Panel</h2>

    <h3>Users and CVs</h3>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Full Name (CV)</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td><?php echo $user['fullname'] ? htmlspecialchars($user['fullname']) : 'No CV for the moment'; ?></td>
                <td>
                    <form method="POST" action="" class="d-inline">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Delete User</button>
                    </form>
                    <form method="POST" action="" class="d-inline">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <select name="new_role" class="form-control form-control-sm d-inline w-auto">
                            <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                            <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                        <button type="submit" name="update_role" class="btn btn-primary btn-sm">Update Role</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Messages from Users</h3>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Sent At</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($messages as $message): ?>
            <tr>
                <td><?php echo htmlspecialchars($message['id']); ?></td>
                <td><?php echo htmlspecialchars($message['name']); ?></td>
                <td><?php echo htmlspecialchars($message['email']); ?></td>
                <td><?php echo htmlspecialchars($message['message']); ?></td>
                <td><?php echo htmlspecialchars($message['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
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

