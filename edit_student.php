<?php
require_once "DB_operations.php";
session_start();
$conn = db_connect();

// Check if admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Get student data
$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student = getStudentById($student_id);

if (!$student) {
    $_SESSION['error'] = "Student not found";
    header("Location: tables.php");
    exit;
}

// Get all departments for dropdown
$departments = getDepartments($conn); // You'll need to create this function

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = updateStudent(
        $student_id,
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['date_of_birth'],
        $_POST['gender'],
        $_POST['email'],
        $_POST['phone_number'],
        $_POST['address'],
        $_POST['department_id']
    );

    if ($result['success']) {
        $_SESSION['success'] = $result['message'];
        header("Location: tables.php");
        exit;
    } else {
        $error = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875em;
        }
    </style>
</head>

<body>
    <!-- <?php include 'admin_header.php'; ?> -->

    <div class="container">
        <div class="form-container">
            <h2 class="mb-4">Edit Student</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name"
                            value="<?= htmlspecialchars($student['first_name']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name"
                            value="<?= htmlspecialchars($student['last_name']) ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="<?= htmlspecialchars($student['email']) ?>" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                            value="<?= htmlspecialchars($student['date_of_birth']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="Male" <?= $student['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $student['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= $student['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone_number" name="phone_number"
                        value="<?= htmlspecialchars($student['phone_number']) ?>">
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3"><?= htmlspecialchars($student['address']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="department_id" class="form-label">Department</label>
                    <select class="form-select" id="department_id" name="department_id" required>
                        <option value="">Select Department</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= $dept['department_id'] ?>"
                                <?= $dept['department_id'] == $student['department_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dept['department_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="tables.php" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Student</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>