<?php
require_once "DB_operations.php";
session_start();

// Check if admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: admin_home.php");
    exit;
}

// Get schedule data
$schedule_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$schedule = getScheduleById($schedule_id);

if (!$schedule) {
    $_SESSION['error'] = "Schedule not found";
    header("Location: admin_home.php");
    exit;
}

// Get all courses and classrooms for dropdowns
$courses = getCourse();
$classrooms = getClassroom();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = updateCourseSchedule(
        $schedule_id,
        $_POST['course_id'],
        $_POST['classroom_id'],
        $_POST['day_of_week'],
        $_POST['start_time'],
        $_POST['end_time']
    );

    if ($result['success']) {
        $_SESSION['success'] = $result['message'];
        header("Location: admin_home.php");
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
    <title>Edit Course Schedule</title>
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
            <h2 class="mb-4">Edit Course Schedule</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="course_id" class="form-label">Course</label>
                        <select class="form-select" id="course_id" name="course_id" required>
                            <option value="">Select Course</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= $course['course_id'] ?>"
                                    <?= $course['course_id'] == $schedule['course_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($course['course_code'] . ' - ' . $course['course_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="classroom_id" class="form-label">Classroom</label>
                        <select class="form-select" id="classroom_id" name="classroom_id" required>
                            <option value="">Select Classroom</option>
                            <?php foreach ($classrooms as $classroom): ?>
                                <option value="<?= $classroom['classroom_id'] ?>"
                                    <?= $classroom['classroom_id'] == $schedule['classroom_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($classroom['building'] . ' - ' . $classroom['room_number']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="day_of_week" class="form-label">Day of Week</label>
                        <select class="form-select" id="day_of_week" name="day_of_week" required>
                            <option value="Monday" <?= $schedule['day_of_week'] == 'Monday' ? 'selected' : '' ?>>Monday</option>
                            <option value="Tuesday" <?= $schedule['day_of_week'] == 'Tuesday' ? 'selected' : '' ?>>Tuesday</option>
                            <option value="Wednesday" <?= $schedule['day_of_week'] == 'Wednesday' ? 'selected' : '' ?>>Wednesday</option>
                            <option value="Thursday" <?= $schedule['day_of_week'] == 'Thursday' ? 'selected' : '' ?>>Thursday</option>
                            <option value="Friday" <?= $schedule['day_of_week'] == 'Friday' ? 'selected' : '' ?>>Friday</option>
                            <option value="Saturday" <?= $schedule['day_of_week'] == 'Saturday' ? 'selected' : '' ?>>Saturday</option>
                            <option value="Sunday" <?= $schedule['day_of_week'] == 'Sunday' ? 'selected' : '' ?>>Sunday</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="time" class="form-control" id="start_time" name="start_time"
                            value="<?= substr($schedule['start_time'], 0, 5) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="time" class="form-control" id="end_time" name="end_time"
                            value="<?= substr($schedule['end_time'], 0, 5) ?>" required>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="admin_home.php" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Schedule</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>