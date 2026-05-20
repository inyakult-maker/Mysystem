<?php
include 'db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch counts for dashboard cards
$students_count = 0;
$subjects_count = 0;
$marks_count = 0;
$attendance_count = 0;
$users_count = 0;

$res = $conn->query('SELECT COUNT(*) AS c FROM students');
if ($res) { $row = $res->fetch_assoc(); $students_count = $row['c']; }
$res = $conn->query('SELECT COUNT(*) AS c FROM subjects');
if ($res) { $row = $res->fetch_assoc(); $subjects_count = $row['c']; }
$res = $conn->query('SELECT COUNT(*) AS c FROM marks');
if ($res) { $row = $res->fetch_assoc(); $marks_count = $row['c']; }
$res = $conn->query('SELECT COUNT(*) AS c FROM attendance');
if ($res) { $row = $res->fetch_assoc(); $attendance_count = $row['c']; }
$res = $conn->query('SELECT COUNT(*) AS c FROM users');
if ($res) { $row = $res->fetch_assoc(); $users_count = $row['c']; }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <style>
            /* Index-only: extend the greeting card to the right */
            .greeting { width: calc(100% + 1px) !important; margin-right: -28px !important; }
        </style>
    <style>
        :root{
            --card-radius:12px;
        }
        body { min-height:100vh; background:#f4f6fb; }
        .sidebar { width:250px; background:#fff; height:100vh; padding:20px; box-shadow: 2px 0 6px rgba(0,0,0,0.03); position:fixed; }
        .sidebar .nav-link{ color:#333; padding:10px 8px; border-radius:8px; }
        .sidebar .nav-link:hover{ background:#f1f5ff; color:#0b5fff; }
        .main { margin-left:270px; padding:28px; }
        .topbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; }
        .greeting { background:linear-gradient(90deg,#6f42c1,#7b61ff); color:#fff; padding:18px; border-radius:12px; box-shadow:0 6px 18px rgba(59,53,120,0.08); }
        .stats .card { border-radius:var(--card-radius); color:#fff; }
        .card-purple{ background: linear-gradient(90deg,#6f42c1,#8a6bff); }
        .card-yellow{ background: linear-gradient(90deg,#ffb84d,#ff8a4d); }
        .card-red{ background: linear-gradient(90deg,#ff5f6d,#ffc371); }
        .card-green{ background: linear-gradient(90deg,#28c76f,#9be15d); }
        .announcements, .panel { background:#fff; padding:18px; border-radius:12px; box-shadow:0 6px 20px rgba(37,47,63,0.04); }
        .clock { font-size:14px; color:#fff; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h5 class="mb-3">Student Management</h5>
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
        <div class="topbar">
            <div class="greeting">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Hello, <?php echo htmlspecialchars($username); ?></h4>
                        <div style="opacity:.9">Welcome! You are logged in to the Faculty Administrator.</div>
                    </div>
                    <div class="text-end">
                        <div class="clock" id="clock"></div>
                    </div>
                </div>
            </div>
            <!-- quick actions removed -->
        </div>

        <div class="row stats g-3 mb-4">
            <div class="col-md-3">
                <div class="card card-purple p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small>STUDENTS</small>
                            <h3 class="mt-2"><?php echo $students_count; ?></h3>
                        </div>
                        <i class="fas fa-user-graduate fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-yellow p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small>SUBJECTS</small>
                            <h3 class="mt-2"><?php echo $subjects_count; ?></h3>
                        </div>
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-red p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small>MARKS</small>
                            <h3 class="mt-2"><?php echo $marks_count; ?></h3>
                        </div>
                        <i class="fas fa-clipboard-check fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-green p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small>ATTENDANCE</small>
                            <h3 class="mt-2"><?php echo $attendance_count; ?></h3>
                        </div>
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-8">
                <div class="announcements">
                    <h5>Announcements</h5>
                    <p class="mb-0">No announcements yet. Use this space to show notices to faculty and students.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="announcements text-center">
                    <h6>System Info</h6>
                    <p class="mb-1">Users: <?php echo $users_count; ?></p>
                    <p class="mb-1">Last backup: Not configured</p>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateClock(){
            const el = document.getElementById('clock');
            if(!el) return;
            const now = new Date();
            el.textContent = now.toLocaleString();
        }
        updateClock(); setInterval(updateClock,1000);
    </script>
</body>
</html>
