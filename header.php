<?php
// Shared header: starts session, checks login, includes DB, outputs sidebar and opening main wrapper
include 'db.php';
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Student Management'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root{ --card-radius:12px; }
        body { min-height:100vh; background:#f4f6fb; font-family:Arial,Helvetica,sans-serif; }
        .sidebar { width:250px; background:#fff; height:100vh; padding:20px; box-shadow: 2px 0 6px rgba(0,0,0,0.03); position:fixed; }
        .sidebar h5{ margin-bottom:18px; }
        .sidebar .nav-link{ color:#333; padding:10px 8px; border-radius:8px; }
        .sidebar .nav-link:hover{ background:#f1f5ff; color:#0b5fff; }
        .main { margin-left:270px; padding:28px; }
        .greeting { background:linear-gradient(90deg,#6f42c1,#7b61ff); color:#fff; padding:18px; border-radius:12px; box-shadow:0 6px 18px rgba(59,53,120,0.06); width:100%; box-sizing:border-box; display:flex; justify-content:space-between; align-items:center; }
        .page-card { background:#fff; padding:18px; border-radius:12px; box-shadow:0 6px 20px rgba(37,47,63,0.04); }
            /* Dashboard card colors (used on index.php and other pages) */
            .stats .card { border-radius:var(--card-radius); color:#fff; border: none !important; }
            .card-purple { background: linear-gradient(90deg,#6f42c1,#8a6bff) !important; color: #fff !important; }
            .card-yellow { background: linear-gradient(90deg,#ffb84d,#ff8a4d) !important; color: #fff !important; }
            .card-red { background: linear-gradient(90deg,#ff5f6d,#ffc371) !important; color: #fff !important; }
            .card-green { background: linear-gradient(90deg,#28c76f,#9be15d) !important; color: #fff !important; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h5>Student Management</h5>
        <nav class="nav flex-column">
            <a class="nav-link" href="index.php"><i class="fas fa-home me-2"></i> Dashboard</a>
            <a class="nav-link" href="add_student.php"><i class="fas fa-user-plus me-2"></i> Add Student</a>
            <a class="nav-link" href="view_students.php"><i class="fas fa-users me-2"></i> View Students</a>
            <a class="nav-link" href="add_subject.php"><i class="fas fa-book me-2"></i> Add Subject</a>
            <a class="nav-link" href="view_subjects.php"><i class="fas fa-book-open me-2"></i> View Subjects</a>
            <a class="nav-link" href="add_marks.php"><i class="fas fa-pen me-2"></i> Add Marks</a>
            <a class="nav-link" href="view_marks.php"><i class="fas fa-chart-bar me-2"></i> View Marks</a>
            <a class="nav-link" href="add_attendance.php"><i class="fas fa-calendar-check me-2"></i> Add Attendance</a>
            <a class="nav-link" href="view_attendance.php"><i class="fas fa-calendar-alt me-2"></i> View Attendance</a>
            <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </nav>
    </div>

    <div class="main">
        <div class="mb-3">
            <div class="greeting">
                <div>
                    <h4 class="mb-1"><?php echo isset($headerTitle) ? htmlspecialchars($headerTitle) : 'Hello, '.htmlspecialchars($username); ?></h4>
                    <div style="opacity:.9"><?php echo isset($headerSubtitle) ? htmlspecialchars($headerSubtitle) : 'Welcome! You are logged in to the Faculty Administrator.'; ?></div>
                </div>
                <div id="header-clock" style="font-size:.95rem; color:#fff;"></div>
            </div>
        </div>

        <!-- page content starts here -->
        <div class="page-card mb-4">
