<?php
require_once "DB_operations.php";
session_start();
$conn = db_connect();

// Check if admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Get course data
$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$course = getCourseById($course_id);

if (!$course) {
    $_SESSION['error'] = "Course not found";
    header("Location: tables.php");
    exit;
}

// Get all departments and lecturers for dropdowns
$departments = getDepartment();
$lecturers = getLecturers($conn);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = updateCourse(
        $course_id,
        $_POST['course_code'],
        $_POST['course_name'],
        $_POST['credits'],
        $_POST['department_id'],
        $_POST['lecturer_id']
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
    <title>Edit Course</title>
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
    </style>
</head>

<body>
    <!-- <?php include 'admin_header.php'; ?> -->

    <div class="container">
        <div class="form-container">
            <h2 class="mb-4">Edit Course</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="course_code" class="form-label">Course Code</label>
                        <input type="text" class="form-control" id="course_code" name="course_code"
                            value="<?= htmlspecialchars($course['course_code']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="credits" class="form-label">Credits</label>
                        <input type="number" class="form-control" id="credits" name="credits"
                            value="<?= htmlspecialchars($course['credits']) ?>" min="1" max="10" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="course_name" class="form-label">Course Name</label>
                    <input type="text" class="form-control" id="course_name" name="course_name"
                        value="<?= htmlspecialchars($course['course_name']) ?>" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="department_id" class="form-label">Department</label>
                        <select class="form-select" id="department_id" name="department_id" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['department_id'] ?>"
                                    <?= $dept['department_id'] == $course['department_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dept['department_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="lecturer_id" class="form-label">Lecturer</label>
                        <select class="form-select" id="lecturer_id" name="lecturer_id" required>
                            <option value="">Select Lecturer</option>
                            <?php foreach ($lecturers as $lecturer): ?>
                                <option value="<?= $lecturer['lecturer_id'] ?>"
                                    <?= $lecturer['lecturer_id'] == $course['lecturer_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($lecturer['first_name'] . ' ' . $lecturer['last_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="tables.php" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Course</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>