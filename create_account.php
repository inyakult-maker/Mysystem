<?php
// create_account.php
// Simple helper to create a user account for the student management app.
// Usage (browser):
//  - Open this file in a browser: http://localhost/studentmanagement/create_account.php
//  - Fill the form and submit, or call with query params:
//    http://localhost/studentmanagement/create_account.php?username=admin&password=Secret123

include 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['username'])) {
    $username = isset($_POST['username']) ? trim($_POST['username']) : trim($_GET['username']);
    $password_plain = isset($_POST['password']) ? $_POST['password'] : (isset($_GET['password']) ? $_GET['password'] : '');

    if ($username === '' || $password_plain === '') {
        $message = 'Username and password are required.';
    } else {
        // Check if username exists
        $check = $conn->prepare('SELECT id FROM users WHERE username = ?');
        $check->bind_param('s', $username);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $message = 'User already exists.';
        } else {
            $hash = password_hash($password_plain, PASSWORD_BCRYPT);
            $stmt = $conn->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
            $stmt->bind_param('ss', $username, $hash);
            if ($stmt->execute()) {
                $message = 'User created successfully. Username: ' . htmlspecialchars($username);
            } else {
                $message = 'Error creating user: ' . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Create Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{padding:30px;} form{max-width:480px;}</style>
</head>
<body>
<div class="container">
    <h3>Create account for Student Management</h3>
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control" required>
        </div>
        <button class="btn btn-primary">Create Account</button>
    </form>

    <hr>
    <p>Or call via GET: <code>?username=admin&password=Secret123</code></p>
    <p>Important: This file is a helper. Remove it after creating accounts.</p>
</div>
</body>
</html>
