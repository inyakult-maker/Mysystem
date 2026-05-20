<?php
// Login page: merge original login logic with the new UI
session_start();
require_once 'db.php';

$error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($username === '' || $password === '') {
        $error = 'Please enter username and password.';
    } else {
        $sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $username;
                header('Location: index.php');
                exit();
            } else {
                $error = 'Invalid username or password.';
            }
        } else {
            $error = 'Invalid username or password.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
    
    
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Record Management System - Login</title>
    <style>
        /* CSS for the Login Page */
        :root {
            --primary-color: #007bff; /* A blue color for buttons/accents */
            --secondary-color: #6c757d;
            --background-color: #f8f9fa; /* Light grey background */
            --card-background: #ffffff; /* White card background */
            --text-color: #212529;
            --border-radius: 8px;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--background-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: var(--text-color);
        }

        .login-container {
            display: flex;
            max-width: 900px;
            width: 90%;
            background-color: var(--card-background);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        /* Left side - Image and System Title */
        .login-illustration {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, var(--primary-color), #0056b3);
            color: white;
            text-align: center;
        }

        .login-illustration img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
            /* Placeholder for your specific image adjustments */
            border-radius: var(--border-radius);
        }

        .login-illustration h1 {
            font-size: 1.8em;
            margin-bottom: 10px;
        }

        .login-illustration p {
            font-size: 1em;
            opacity: 0.9;
        }

        /* Right side - Login Form */
        .login-form-area {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form-area h2 {
            font-size: 1.5em;
            margin-bottom: 30px;
            color: var(--primary-color);
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.9em;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Password toggle button inside input */
        .password-wrapper { position: relative; }
        /* ensure the password input has extra right padding so text doesn't go under the button */
        .password-wrapper input { padding-right: 84px; }
        .password-wrapper .toggle-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            /* nudge slightly further downward: shift the translateY by +12px */
            transform: translateY(calc(-50% + 12px));
            background: transparent;
            border: none;
            color: var(--secondary-color);
            padding: 6px 10px;
            cursor: pointer;
            font-size: 0.9em;
            z-index: 2; /* make sure it's above the input */
            pointer-events: auto;
        }
        .password-wrapper .toggle-btn:focus { outline: none; }

        .login-button {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
            font-weight: 600;
        }

        .login-button:hover {
            background-color: #0056b3;
        }

        .login-button:active {
            transform: scale(0.99);
        }

        .forgot-password {
            text-align: right;
            margin-top: 15px;
        }

        .forgot-password a {
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 0.9em;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                width: 95%;
            }

            .login-illustration {
                padding: 30px 20px;
                border-bottom-left-radius: 0;
                border-top-right-radius: var(--border-radius);
            }

            .login-form-area {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        
        <div class="login-illustration">
            <img src="img.png" alt="Students studying with graduation caps on books">
            <h1>Student Record Management System</h1>
            <p>Your centralized platform for managing student data efficiently and securely.</p>
        </div>
        
        <div class="login-form-area">
            <h2>System Login</h2>
            <form action="#" method="POST">
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required placeholder="Enter your username">
                </div>
                
                <div class="form-group password-wrapper">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                    <button type="button" class="toggle-btn" id="togglePassword" aria-pressed="false" aria-label="Show password">Show</button>
                </div>
                
                <button type="submit" class="login-button">Log In</button>
                
                <div class="forgot-password">
                    <a href="#">Forgot Password?</a>
                </div>
            </form>
        </div>

    </div>

</body>
</html>
<script>
    // Attach toggle after DOM is ready so elements exist
    document.addEventListener('DOMContentLoaded', function () {
        var pwd = document.getElementById('password');
        var btn = document.getElementById('togglePassword');
        if (!pwd || !btn) return;
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var isPassword = pwd.getAttribute('type') === 'password';
            pwd.setAttribute('type', isPassword ? 'text' : 'password');
            btn.textContent = isPassword ? 'Hide' : 'Show';
            btn.setAttribute('aria-pressed', isPassword ? 'true' : 'false');
            btn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
        });
    });
</script>