<?php
session_start();

// Database connection settings
$host = 'localhost';
$db   = 'geneswim'; // <-- Change this to your DB name
$user = 'root';      // <-- Change this to your DB user
$pass = 'geneswim2025';  // <-- Change this to your DB password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (!$email || !$password) {
        $error = 'Email and password are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } else {
        // Check user credentials
        $stmt = $conn->prepare('SELECT u_id, u_firstname, u_lastname, u_email, u_password FROM users WHERE u_email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['u_password'])) {
                // Password is correct, create session
                $_SESSION['user_id'] = $user['u_id'];
                $_SESSION['user_email'] = $user['u_email'];
                $_SESSION['user_name'] = $user['u_firstname'] . ' ' . $user['u_lastname'];
                
                // Redirect to homepage
                header('Location: homepage.php');
                exit();
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Invalid email or password.';
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
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <script src="auth.js" defer></script>
</head>
<body>
    <div class="login-container">
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" action="">
        <h2>Login</h2>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
            <p>Don't have an account? <a href="register.php">Register</a></p>
            <p><a href="homepage.php">← Back to Home</a></p>
        </form>
    </div>
</body>
</html> 