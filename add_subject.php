<?php
$pageTitle = 'Add Subject';
include 'header.php';
?>

    <h4>Add Subject</h4>
    <form action="add_subject.php" method="POST" class="mt-3">
        <div class="row">
            <div class="col-md-8 mb-3">
                <label for="subject_name" class="form-label">Subject Name</label>
                <input type="text" class="form-control" id="subject_name" name="subject_name" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="subject_code" class="form-label">Subject Code (optional)</label>
                <input type="text" class="form-control" id="subject_code" name="subject_code" placeholder="e.g. MATH101">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add Subject</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $subject_name = $conn->real_escape_string($_POST['subject_name']);
        $subject_code = isset($_POST['subject_code']) ? trim($_POST['subject_code']) : '';
        $subject_code_esc = $conn->real_escape_string($subject_code);

        // Check if the subjects table has a subject_code column
        $hasCode = false;
        $colRes = $conn->query("SHOW COLUMNS FROM subjects LIKE 'subject_code'");
        if ($colRes && $colRes->num_rows > 0) {
            $hasCode = true;
        } else {
            // Try to add the column safely (MySQL versions may vary)
            $alterSql = "ALTER TABLE subjects ADD COLUMN subject_code VARCHAR(50) DEFAULT NULL";
            @$conn->query($alterSql);
            $colRes2 = $conn->query("SHOW COLUMNS FROM subjects LIKE 'subject_code'");
            if ($colRes2 && $colRes2->num_rows > 0) {
                $hasCode = true;
            }
        }

        if ($hasCode && $subject_code !== '') {
            $sql = "INSERT INTO subjects (subject_name, subject_code) VALUES ('$subject_name', '$subject_code_esc')";
        } else {
            $sql = "INSERT INTO subjects (subject_name) VALUES ('$subject_name')";
        }

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success mt-3'>Subject added successfully.</div>";
        } else {
            echo "<div class='alert alert-danger mt-3'>Error: " . htmlspecialchars($conn->error) . "</div>";
        }
    }
    ?>

<?php include 'footer.php'; ?>
