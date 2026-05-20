<?php
$pageTitle = 'Marks Records';
$headerTitle = 'View Marks';
include 'header.php';

// Detect if status column exists
$show_status = false;
$colRes = $conn->query("SHOW COLUMNS FROM marks LIKE 'status'");
if ($colRes && $colRes->num_rows > 0) { $show_status = true; }

$sql = "SELECT * FROM marks";
$result = $conn->query($sql);
?>

    <h4>Marks Records</h4>
    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student ID</th>
                    <th>Subject ID</th>
                    <th>Marks</th>
                    <?php if ($show_status) echo '<th>Status</th>'; ?>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['subject_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['marks']); ?></td>
                    <?php if ($show_status) { echo '<td>'.htmlspecialchars($row['status']).'</td>'; } ?>
                    <td>
                        <a href="edit_marks.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_marks.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

<?php include 'footer.php'; ?>
