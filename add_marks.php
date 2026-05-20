<?php
$pageTitle = 'Add Marks';
$headerTitle = 'Add Marks';
include 'header.php';

// Fetch students and subjects for selects
$students = $conn->query("SELECT id, student_name FROM students ORDER BY student_name");
$subjects = $conn->query("SELECT id, subject_name FROM subjects ORDER BY subject_name");
?>

    <h4>Add Marks</h4>
    <form action="add_marks.php" method="POST" class="mt-3">
        <div class="mb-3">
            <label for="student_id" class="form-label">Student</label>
            <select class="form-select" id="student_id" name="student_id" required>
                <option value="">-- Select student --</option>
                <?php if ($students) { while($s = $students->fetch_assoc()) {
                    $label = htmlspecialchars($s['student_name']) ?: 'ID '.$s['id'];
                    echo '<option value="'.htmlspecialchars($s['id']).'">'. $label . ' (' . htmlspecialchars($s['id']) . ')</option>';
                } } ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="subject_id" class="form-label">Subject</label>
            <select class="form-select" id="subject_id" name="subject_id" required>
                <option value="">-- Select subject --</option>
                <?php if ($subjects) { while($sub = $subjects->fetch_assoc()) {
                    echo '<option value="'.htmlspecialchars($sub['id']).'">'.htmlspecialchars($sub['subject_name']).' ('.htmlspecialchars($sub['id']).')</option>';
                } } ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="marks" class="form-label">Marks</label>
            <input type="number" class="form-control" id="marks" name="marks" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="">-- Select status --</option>
                <option value="Fail">Fail</option>
                <option value="Passed">Passed</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Marks</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $student_id = isset($_POST['student_id']) ? (int)$_POST['student_id'] : 0;
        $subject_id = isset($_POST['subject_id']) ? (int)$_POST['subject_id'] : 0;
        $marks = isset($_POST['marks']) ? (int)$_POST['marks'] : 0;
        $status = isset($_POST['status']) ? trim($_POST['status']) : '';

        // Validate status
        $allowed = ['Fail','Passed'];
        if (!in_array($status, $allowed, true)) {
            echo "<div class='alert alert-danger mt-3'>Error: Invalid status selected.</div>";
        } else {
            // Validate student exists
            $stmt = $conn->prepare("SELECT id FROM students WHERE id = ? LIMIT 1");
            $stmt->bind_param('i', $student_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows === 0) {
                echo "<div class='alert alert-danger mt-3'>Error: Selected student does not exist.</div>";
                $stmt->close();
                // stop processing
            } else {
                $stmt->close();
                // Validate subject exists
                $stmt2 = $conn->prepare("SELECT id FROM subjects WHERE id = ? LIMIT 1");
                $stmt2->bind_param('i', $subject_id);
                $stmt2->execute();
                $stmt2->store_result();
                if ($stmt2->num_rows === 0) {
                    echo "<div class='alert alert-danger mt-3'>Error: Selected subject does not exist.</div>";
                    $stmt2->close();
                } else {
                    $stmt2->close();
                    // Ensure marks table has status column
                    $colRes = $conn->query("SHOW COLUMNS FROM marks LIKE 'status'");
                    if (!($colRes && $colRes->num_rows > 0)) {
                        // try to add the column (may fail if user lacks ALTER privilege)
                        $alterSql = "ALTER TABLE marks ADD COLUMN `status` VARCHAR(10) NOT NULL DEFAULT '' AFTER `marks`";
                        if (!$conn->query($alterSql)) {
                            echo "<div class='alert alert-warning mt-3'>Notice: could not add 'status' column automatically. Please add a VARCHAR(10) `status` column to the `marks` table or contact your DBA. Error: " . htmlspecialchars($conn->error) . "</div>";
                        }
                    }

                    // Try to insert with status
                    $ins = $conn->prepare("INSERT INTO marks (student_id, subject_id, marks, status) VALUES (?, ?, ?, ?)");
                    if ($ins) {
                        $ins->bind_param('iiis', $student_id, $subject_id, $marks, $status);
                        if ($ins->execute()) {
                            echo "<div class='alert alert-success mt-3'>Marks added successfully.</div>";
                        } else {
                            echo "<div class='alert alert-danger mt-3'>Error: " . htmlspecialchars($conn->error) . "</div>";
                        }
                        $ins->close();
                    } else {
                        // Prepared failed, fallback to insert without status
                        $fallback = $conn->prepare("INSERT INTO marks (student_id, subject_id, marks) VALUES (?, ?, ?)");
                        if ($fallback) {
                            $fallback->bind_param('iii', $student_id, $subject_id, $marks);
                            if ($fallback->execute()) {
                                echo "<div class='alert alert-success mt-3'>Marks added (without status) – database doesn't have a status column.</div>";
                            } else {
                                echo "<div class='alert alert-danger mt-3'>Error: " . htmlspecialchars($conn->error) . "</div>";
                            }
                            $fallback->close();
                        } else {
                            echo "<div class='alert alert-danger mt-3'>Error preparing insert: " . htmlspecialchars($conn->error) . "</div>";
                        }
                    }
                }
            }
        }
    }
    ?>

<?php include 'footer.php'; ?>
