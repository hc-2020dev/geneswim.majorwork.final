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

// Fetch durations and exercise types for dropdowns
$durations = $conn->query('SELECT d_id, d_time FROM duration ORDER BY d_time');
$types = $conn->query('SELECT et_id, et_name FROM exercisetype ORDER BY et_name');

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $e_name = trim($_POST['e_name'] ?? '');
    $d_id = $_POST['d_id'] ?? '';
    $et_id = $_POST['et_id'] ?? '';
    if (!$e_name || !$d_id || !$et_id) {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = $conn->prepare('INSERT INTO exercise (e_name, d_id, et_id) VALUES (?, ?, ?)');
        $stmt->bind_param('sii', $e_name, $d_id, $et_id);
        if ($stmt->execute()) {
            $success = 'Exercise added successfully!';
        } else {
            $error = 'Error adding exercise.';
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
    <title>Add Exercise</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="register-container">
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="post" action="">
        <h2>Add Exercise</h2>
            <label for="e_name">Exercise Name:</label>
            <input type="text" id="e_name" name="e_name" maxlength="45" required>

            <label for="d_id">Duration:</label>
            <select id="d_id" name="d_id" required>
                <option value="">Select duration</option>
                <?php while ($row = $durations->fetch_assoc()): ?>
                    <option value="<?php echo $row['d_id']; ?>"><?php echo htmlspecialchars($row['d_time']); ?></option>
                <?php endwhile; ?>
            </select>

            <label for="et_id">Exercise Type:</label>
            <select id="et_id" name="et_id" required>
                <option value="">Select type</option>
                <?php while ($row = $types->fetch_assoc()): ?>
                    <option value="<?php echo $row['et_id']; ?>"><?php echo htmlspecialchars($row['et_name']); ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Add Exercise</button>
        </form>
        <p><a href="homepage.php">← Back to Home</a></p>
    </div>
</body>
</html> 