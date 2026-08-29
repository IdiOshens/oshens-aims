<?php
require_once "DB_operations.php";
session_start();

// Check if admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Get classroom data
$classroom_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$classroom = getClassroomById($classroom_id);

if (!$classroom) {
    $_SESSION['error'] = "Classroom not found";
    header("Location: tables.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = updateClassroom(
        $classroom_id,
        $_POST['building'],
        $_POST['room_number'],
        $_POST['capacity'],
        $_POST['virtual_meeting_link']
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
    <title>Edit Classroom</title>
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
            <h2 class="mb-4">Edit Classroom</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="building" class="form-label">Building</label>
                        <input type="text" class="form-control" id="building" name="building"
                            value="<?= htmlspecialchars($classroom['building']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="room_number" class="form-label">Room Number</label>
                        <input type="text" class="form-control" id="room_number" name="room_number"
                            value="<?= htmlspecialchars($classroom['room_number']) ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="capacity" class="form-label">Capacity</label>
                        <input type="number" class="form-control" id="capacity" name="capacity"
                            value="<?= htmlspecialchars($classroom['capacity']) ?>" min="1" required>
                    </div>
                    <div class="col-md-6">
                        <label for="virtual_meeting_link" class="form-label">Virtual Meeting Link</label>
                        <input type="url" class="form-control" id="virtual_meeting_link" name="virtual_meeting_link"
                            value="<?= htmlspecialchars($classroom['virtual_meeting_link']) ?>"
                            placeholder="https://zoom.us/j/123456789">
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="tables.php" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Classroom</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>