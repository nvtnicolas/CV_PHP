<?php

include __DIR__ . '/../db/db.php'; // Ensure the correct path to the db.php file

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

if (isset($_POST['delete_message'])) {
    $message_id = $_POST['message_id'];

    try {
        $pdo->prepare('DELETE FROM contacts WHERE id = :message_id')->execute(['message_id' => $message_id]);
        echo '<p style="color:green;">Message deleted successfully.</p>';
    } catch (PDOException $e) {
        echo '<p style="color:red;">Error deleting message: ' . $e->getMessage() . '</p>';
    }
}

if (isset($_POST['delete_cvs'])) {
    $user_id = $_POST['user_id'];

    try {
        $pdo->prepare('DELETE FROM cvs WHERE user_id = :user_id')->execute(['user_id' => $user_id]);
        echo '<p style="color:green;">CV deleted successfully.</p>';
    } catch (PDOException $e) {
        echo '<p style="color:red;">Error deleting CV: ' . $e->getMessage() . '</p>';
    }
}

try {
    $stmt = $pdo->query('
        SELECT u.id, u.username, u.email, u.role, c.id AS cv_id, c.fullname, c.education, c.skills, c.experience, c.contact, c.description, c.profile_image
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
    <style>
        /* General table style to prevent headers from stacking */
        table {
            width: 100%;
            table-layout: fixed; /* Ensures columns don't shrink */
        }

        th, td {
            white-space: nowrap; /* Prevents wrapping */
            text-align: left;
            padding: 8px;
            vertical-align: middle; /* Centers text vertically */
            overflow: hidden; /* Hide overflow */
            text-overflow: ellipsis; /* Add ellipsis for overflow text */
        }

        /* Fixed width for specific columns */
        .fixed-width {
            max-width: 200px; /* Adjust as needed */
            word-wrap: break-word;
        }

        /* Scrollable cell content */
        .scrollable-cell {
            max-height: 100px; /* Adjust as needed */
            overflow-y: auto; /* Enable vertical scrolling */
        }

        /* Dark mode styles */
        body.dark-mode {
            background-color: #121212;
            color: #f4f4f4;
        }

        body.dark-mode th, body.dark-mode td {
            color: #f4f4f4;
        }

        body.dark-mode .bg-dark {
            background-color: #1c1c1c !important;
        }

        body.dark-mode .btn-secondary {
            background-color: #333 !important;
            border-color: #444 !important;
        }

        body.dark-mode .btn-light {
            background-color: #444 !important;
            color: #f4f4f4 !important;
        }

        body.dark-mode .btn-danger {
            background-color: #b33a3a !important;
            border-color: #b33a3a !important;
        }

        body.dark-mode .table-striped tbody tr:nth-of-type(odd) {
            background-color: #2c2c2c;
        }

        body.dark-mode .table-striped tbody tr:nth-of-type(even) {
            background-color: #1c1c1c;
        }

        body.dark-mode .table-striped tbody tr:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
<header class="bg-dark text-white p-3 mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <form method="GET" action="../router.php" class="d-flex">
            <button class="btn btn-secondary mr-2" name="page" value="index">Home</button>
            <button class="btn btn-secondary mr-2" name="page" value="admin">Admin</button>
            <button class="btn btn-secondary mr-2" name="page" value="profile">Profile</button>
            <button class="btn btn-secondary mr-2" name="page" value="project">Projects</button>
            <button class="btn btn-secondary" name="page" value="contact">Contact</button>
        </form>
        <div>
            <button id="theme-toggle" class="btn btn-light">Dark Mode</button>
            <a href="../auth/logout.php" class="btn btn-danger">Log out</a>
        </div>
    </div>
</header>

<div class="container">
    <h2>Admin Panel</h2>

    <h3>Users</h3>
    <table class="table table-striped">
        <thead>
        <tr>
            <th class="fixed-width">ID</th>
            <th class="fixed-width">Username</th>
            <th class="fixed-width">Email</th>
            <th class="fixed-width">Role</th>
            <th class="fixed-width">Full Name (CV)</th>
            <th class="fixed-width">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td class="fixed-width"><?php echo htmlspecialchars($user['id']); ?></td>
                <td class="fixed-width"><?php echo htmlspecialchars($user['username']); ?></td>
                <td class="fixed-width"><?php echo htmlspecialchars($user['email']); ?></td>
                <td class="fixed-width"><?php echo htmlspecialchars($user['role']); ?></td>
                <td class="fixed-width"><?php echo $user['fullname'] ? htmlspecialchars($user['fullname']) : 'No CV for the moment'; ?></td>
                <td class="fixed-width">
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

    <h3>CV by User</h3>
    <table class="table table-striped">
        <thead>
        <tr>
            <th class="fixed-width">Full Name</th>
            <th class="fixed-width">Education</th>
            <th class="fixed-width">Skills</th>
            <th class="fixed-width">Experience</th>
            <th class="fixed-width">Contact</th>
            <th class="fixed-width">Description</th>
            <th class="fixed-width">Profile Image</th>
            <th class="fixed-width">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td class="fixed-width scrollable-cell"><?php echo $user['fullname'] !== null ? htmlspecialchars($user['fullname']) : 'No CV for the moment'; ?></td>
                <td class="fixed-width scrollable-cell"><?php echo $user['education'] !== null ? htmlspecialchars($user['education']) : 'No CV for the moment'; ?></td>
                <td class="fixed-width scrollable-cell"><?php echo $user['skills'] !== null ? htmlspecialchars($user['skills']) : 'No CV for the moment'; ?></td>
                <td class="fixed-width scrollable-cell"><?php echo $user['experience'] !== null ? htmlspecialchars($user['experience']) : 'No CV for the moment'; ?></td>
                <td class="fixed-width scrollable-cell"><?php echo $user['contact'] !== null ? htmlspecialchars($user['contact']) : 'No CV for the moment'; ?></td>
                <td class="fixed-width scrollable-cell"><?php echo $user['description'] !== null ? htmlspecialchars($user['description']) : 'No CV for the moment'; ?></td>
                <td class="fixed-width">
                    <?php if (!empty($user['profile_image']) && file_exists(__DIR__ . '/../' . $user['profile_image'])): ?>
                        <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Image" style="width: 100px; height: auto;">
                    <?php else: ?>
                        <img src="../uploads/profil.png" alt="Default Profile Image" style="width: 100px; height: auto;">
                    <?php endif; ?>
                </td>
                <td class="fixed-width">
                    <form method="POST" action="" class="d-inline">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" name="delete_cvs" class="btn btn-warning btn-sm">Delete CV</button>
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
            <th class="fixed-width">ID</th>
            <th class="fixed-width">Name</th>
            <th class="fixed-width">Email</th>
            <th class="fixed-width">Message</th>
            <th class="fixed-width">Sent At</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($messages as $message): ?>
            <tr>
                <td class="fixed-width"><?php echo htmlspecialchars($message['id']); ?></td>
                <td class="fixed-width"><?php echo htmlspecialchars($message['name']); ?></td>
                <td class="fixed-width"><?php echo htmlspecialchars($message['email']); ?></td>
                <td class="fixed-width scrollable-cell"><?php echo htmlspecialchars($message['message']); ?></td>
                <td class="fixed-width"><?php echo htmlspecialchars($message['created_at']); ?></td>
                <td>
                    <form method="POST" action="" class="d-inline">
                        <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                        <button type="submit" name="delete_message" class="btn btn-danger btn-sm">Delete Message</button>
                    </form>
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

        // Update text and button color dynamically
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