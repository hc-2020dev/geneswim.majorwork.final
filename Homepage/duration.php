<?php
// Database connection settings
$host = 'localhost';
$db   = 'geneswim'; // Change if needed
$user = 'root';
$pass = 'geneswim2025';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d_time = $_POST['d_time'] ?? '';
    if ($d_time === '' || !is_numeric($d_time) || $d_time <= 0) {
        $error = 'Please enter a valid duration in minutes.';
    } else {
        $d_time = (int)$d_time;
        $stmt = $conn->prepare('INSERT INTO duration (d_time) VALUES (?)');
        $stmt->bind_param('i', $d_time);
        if ($stmt->execute()) {
            $success = 'Duration added successfully!';
        } else {
            $error = 'Error adding duration.';
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
    <title>Add Duration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="register-container">
        <h2>Add Duration</h2>
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="d_time">Duration (minutes):</label>
            <input type="number" id="d_time" name="d_time" min="1" step="1" required>
            <button type="submit">Add Duration</button>
        </form>
        <p><a href="homepage.php">← Back to Home</a></p>
    </div>
</body>
</html> 