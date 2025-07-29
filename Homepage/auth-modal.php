<?php
session_start();

// Database connection settings
$host = 'localhost';
$db   = 'geneswim';
$user = 'root';
$pass = 'geneswim2025';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$success = $error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'login') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $error = 'Email and password are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email address.';
        } else {
            $stmt = $conn->prepare('SELECT u_id, u_firstname, u_lastname, u_email, u_password FROM users WHERE u_email = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                
                if (password_verify($password, $user['u_password'])) {
                    $_SESSION['user_id'] = $user['u_id'];
                    $_SESSION['user_email'] = $user['u_email'];
                    $_SESSION['user_name'] = $user['u_firstname'] . ' ' . $user['u_lastname'];
                    $success = 'Login successful!';
                } else {
                    $error = 'Invalid email or password.';
                }
            } else {
                $error = 'Invalid email or password.';
            }
            $stmt->close();
        }
    } elseif ($action === 'register') {
        $firstname = trim($_POST['firstname'] ?? '');
        $lastname = trim($_POST['lastname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (!$firstname || !$lastname || !$email || !$password || !$confirm_password) {
            $error = 'All fields are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email address.';
        } elseif ($password !== $confirm_password) {
            $error = 'Passwords do not match.';
        } else {
            $stmt = $conn->prepare('SELECT u_id FROM users WHERE u_email = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $error = 'Email is already registered.';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare('INSERT INTO users (u_firstname, u_lastname, u_email, u_password) VALUES (?, ?, ?, ?)');
                $stmt->bind_param('ssss', $firstname, $lastname, $email, $hashed_password);
                if ($stmt->execute()) {
                    $success = 'Registration successful! You can now log in.';
                } else {
                    $error = 'Registration failed. Please try again.';
                }
            }
            $stmt->close();
        }
    } elseif ($action === 'logout') {
        session_destroy();
        $success = 'Logged out successfully!';
    }
}
?>

<!-- Auth Modal -->
<div id="authModal" class="auth-modal">
    <div class="auth-modal-content">
        <span class="auth-close">&times;</span>
        
        <!-- Login Form -->
        <div id="loginForm" class="auth-form active" style="display: block;">
            <h2>Login</h2>
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <input type="hidden" name="action" value="login">
                <div class="form-group">
                    <label for="login-email">Email:</label>
                    <input type="email" id="login-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="login-password">Password:</label>
                    <input type="password" id="login-password" name="password" required>
                </div>
                <button type="submit" class="submit-btn">Login</button>
            </form>
            <p class="form-footer">
                Don't have an account? <a href="#" class="switch-form" data-form="register">Register</a>
            </p>
        </div>

        <!-- Register Form -->
        <div id="registerForm" class="auth-form" style="display: none;">
            <h2>Register</h2>
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <input type="hidden" name="action" value="register">
                <div class="form-group">
                    <label for="register-firstname">First Name:</label>
                    <input type="text" id="register-firstname" name="firstname" required>
                </div>
                <div class="form-group">
                    <label for="register-lastname">Last Name:</label>
                    <input type="text" id="register-lastname" name="lastname" required>
                </div>
                <div class="form-group">
                    <label for="register-email">Email:</label>
                    <input type="email" id="register-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="register-password">Password:</label>
                    <input type="password" id="register-password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="register-confirm-password">Confirm Password:</label>
                    <input type="password" id="register-confirm-password" name="confirm_password" required>
                </div>
                <button type="submit" class="submit-btn">Register</button>
            </form>
            <p class="form-footer">
                Already have an account? <a href="#" class="switch-form" data-form="login">Login</a>
            </p>
        </div>

        <!-- Logout Confirmation -->
        <div id="logoutForm" class="auth-form" style="display: none;">
            <h2>Logout</h2>
            <p>Are you sure you want to logout?</p>
            <form method="post" action="">
                <input type="hidden" name="action" value="logout">
                <button type="submit" class="submit-btn">Yes, Logout</button>
            </form>
            <p class="form-footer">
                <a href="#" class="switch-form" data-form="login">Cancel</a>
            </p>
        </div>
    </div>
</div> 