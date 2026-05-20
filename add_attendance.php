<?php
$pageTitle = 'Add Attendance';
include 'header.php';
?>

    <h4>Add Attendance</h4>
    <form action="add_attendance.php" method="POST" class="mt-3">
        <div class="mb-3">
            <label for="student_id" class="form-label">Student ID</label>
            <input type="number" class="form-control" id="student_id" name="student_id" required>
        </div>
        <div class="mb-3">
            <label for="attendance_date" class="form-label">Attendance Date</label>
            <input type="date" class="form-control" id="attendance_date" name="attendance_date" required>
        </div>
        <div class="mb-3">
            <label for="attendance" class="form-label">Attendance (0 for Absent, 1 for Present)</label>
            <input type="number" class="form-control" id="attendance" name="attendance" min="0" max="1" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Attendance</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $student_id = (int)$_POST['student_id'];
        $attendance_date = $conn->real_escape_string($_POST['attendance_date']);
        $attendance = (int)$_POST['attendance'];

        $sql = "INSERT INTO attendance (student_id, attendance_date, attendance) VALUES ('$student_id', '$attendance_date', '$attendance')";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success mt-3'>Attendance added successfully.</div>";
        } else {
            echo "<div class='alert alert-danger mt-3'>Error: " . htmlspecialchars($conn->error) . "</div>";
        }
    }
    ?>

<?php include 'footer.php'; ?>
