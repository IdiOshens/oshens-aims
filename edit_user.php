<?php
require_once "DB_operations.php";

// Initialize variables
$error = '';
$success = '';
$user = [];

// Get existing user data
$user_id = $_GET['id'] ?? 0;
$user = getUserById($user_id);

if (!$user) {
    die("User not found");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = updateUser(
        $_POST['user_id'],
        $_POST['name'],
        $_POST['email'],
        $_POST['role']
    );

    if ($result['success']) {
        $success = $result['message'];
        // Refresh user data after successful update
        $user = getUserById($user_id);
    } else {
        $error = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit User</title>
    <style>
        .edit-form {
            width: 50%;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .error {
            color: #dc3545;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .success {
            color: #28a745;
            background-color: #d4edda;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0069d9;
        }

        a.cancel {
            color: #6c757d;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <div class="edit-form">
        <h2>Edit User</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">

            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>Role:</label>
                <select name="role" required>
                    <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                    <option value="lecturer" <?= $user['role'] === 'lecturer' ? 'selected' : '' ?>>Lecturer</option>
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
                <small style="color: #6c757d;">User must exist in the selected role's institutional records</small>
            </div>

            <button type="submit">Save Changes</button>
            <a href="tables.php" class="cancel">Cancel</a>
        </form>
    </div>
</body>

</html>