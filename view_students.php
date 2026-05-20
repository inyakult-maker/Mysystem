<?php
// header.php already starts the session; remove stray session_start()
$pageTitle = 'Students List';
$headerTitle = 'View Students';
include 'header.php';

$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>

    <h4>Students List</h4>
    <div class="table-responsive mt-3">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Enrollment No</th>
                    <th>Student Name</th>
                    <th>Department</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>'
                            . '<td>' . htmlspecialchars($row['id']) . '</td>'
                            . '<td>' . htmlspecialchars($row['enrollment_no']) . '</td>'
                            . '<td>' . htmlspecialchars($row['student_name']) . '</td>'
                            . '<td>' . htmlspecialchars($row['department']) . '</td>'
                            . '<td>' . htmlspecialchars($row['phone']) . '</td>'
                            . '<td>'
                                . '<a href="edit_student.php?id=' . urlencode($row['id']) . '" class="btn btn-warning btn-sm">Edit</a> '
                                . '<a href="delete_student.php?id=' . urlencode($row['id']) . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</a>'
                            . '</td>'
                          . '</tr>';
                    }
                } else {
                    echo "<tr><td colspan='6'>No students found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

<?php include 'footer.php'; ?>
