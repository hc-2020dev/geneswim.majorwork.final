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
    $et_name = trim($_POST['et_name'] ?? '');
    if (!$et_name) {
        $error = 'Please enter an exercise type name.';
    } else {
        $stmt = $conn->prepare('INSERT INTO exercisetype (et_name) VALUES (?)');
        $stmt->bind_param('s', $et_name);
        if ($stmt->execute()) {
            $success = 'Exercise type added successfully!';
        } else {
            $error = 'Error adding exercise type.';
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
    <title>Add Exercise Type</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="register-container">
        <h2>Add Exercise Type</h2>
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="et_name">Exercise Type Name:</label>
            <input type="text" id="et_name" name="et_name" maxlength="20" required>
            <button type="submit">Add Exercise Type</button>
        </form>
        <p><a href="homepage.php">← Back to Home</a></p>
    </div>
</body>
</html> 