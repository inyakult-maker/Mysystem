<?php
$pageTitle = 'Subject Records';
$headerTitle = 'View Subjects';
include 'header.php';

$sql = "SELECT * FROM subjects";
$result = $conn->query($sql);
?>

    <h4>Subject Records</h4>
    <?php
    // Detect if subject_code column exists so we can display it
    $show_code = false;
    $colRes = $conn->query("SHOW COLUMNS FROM subjects LIKE 'subject_code'");
    if ($colRes && $colRes->num_rows > 0) { $show_code = true; }
    ?>

    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subject Name</th>
                    <?php if ($show_code) echo '<th>Subject Code</th>'; ?>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                    <?php if ($show_code) { echo '<td>'.htmlspecialchars($row['subject_code']).'</td>'; } ?>
                    <td>
                        <a href="edit_subject.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_subject.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

<?php include 'footer.php'; ?>
