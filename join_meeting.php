<?php
require_once "DB_operations.php";
session_start();

if (!isset($_SESSION['logged_in'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['classroom_id'])) {
    die("Invalid classroom");
}

$classroom_id = intval($_GET['classroom_id']);
$meeting_link = urldecode($_GET['link']);

// Record attendance for student
if ($_SESSION['user_role'] === 'student') {
    $result = recordVirtualAttendance($_SESSION['user_id'], $classroom_id);

    // You can uncomment this if you want to show a message
    // $_SESSION['attendance_message'] = $result['message'];
}

// Redirect to the meeting link
header("Location: " . $meeting_link);
exit;
