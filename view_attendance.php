<?php
$pageTitle = 'Attendance Records';
$headerTitle = 'View Attendance';
include 'header.php';

$sql = "SELECT * FROM attendance";
$result = $conn->query($sql);
?>

    <h4>Attendance Records</h4>
    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student ID</th>
                    <th>Date</th>
                    <th>Attendance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['attendance_date']); ?></td>
                    <td><?php echo $row['attendance'] == 1 ? 'Present' : 'Absent'; ?></td>
                    <td>
                        <a href="edit_attendance.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_attendance.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

<?php include 'footer.php'; ?>
