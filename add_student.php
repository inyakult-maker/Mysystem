<?php
$pageTitle = 'Add Student';
include 'header.php';
?>

    <h4>Add Student</h4>
    <form action="add_student.php" method="POST" class="mt-3">
        <div class="mb-3">
            <label for="enrollment_no" class="form-label">Enrollment No</label>
            <input type="text" class="form-control" id="enrollment_no" name="enrollment_no" required>
        </div>
        <div class="mb-3">
            <label for="student_name" class="form-label">Student Name</label>
            <input type="text" class="form-control" id="student_name" name="student_name" required>
        </div>
        <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <input type="text" class="form-control" id="department" name="department" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Student</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $enrollment_no = $conn->real_escape_string($_POST['enrollment_no']);
        $student_name = $conn->real_escape_string($_POST['student_name']);
        $department = $conn->real_escape_string($_POST['department']);
        $phone = $conn->real_escape_string($_POST['phone']);

        $sql = "INSERT INTO students (enrollment_no, student_name, department, phone) VALUES ('$enrollment_no', '$student_name', '$department', '$phone')";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success mt-3'>Student added successfully.</div>";
        } else {
            echo "<div class='alert alert-danger mt-3'>Error: " . htmlspecialchars($conn->error) . "</div>";
        }
    }
    ?>

<?php include 'footer.php'; ?>
