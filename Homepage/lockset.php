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

// Fetch sets and exercises for dropdowns
$sets = $conn->query('SELECT s_id, s_name FROM `set` ORDER BY s_name');
$exercises = $conn->query('SELECT e_id, e_name FROM exercise ORDER BY e_name');

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $s_id = $_POST['s_id'] ?? '';
    $e_id = $_POST['e_id'] ?? '';
    if (!$s_id || !$e_id) {
        $error = 'Please select both a set and an exercise.';
    } else {
        $stmt = $conn->prepare('INSERT INTO lockset (s_id, e_id) VALUES (?, ?)');
        $stmt->bind_param('ii', $s_id, $e_id);
        if ($stmt->execute()) {
            $success = 'Set linked to exercise successfully!';
        } else {
            $error = 'Error linking set to exercise.';
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
    <title>Link Set to Exercise</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="register-container">
        <h2>Link Set to Exercise</h2>
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="s_id">Set:</label>
            <select id="s_id" name="s_id" required>
                <option value="">Select set</option>
                <?php while ($row = $sets->fetch_assoc()): ?>
                    <option value="<?php echo $row['s_id']; ?>"><?php echo htmlspecialchars($row['s_name']); ?></option>
                <?php endwhile; ?>
            </select>

            <label for="e_id">Exercise:</label>
            <select id="e_id" name="e_id" required>
                <option value="">Select exercise</option>
                <?php while ($row = $exercises->fetch_assoc()): ?>
                    <option value="<?php echo $row['e_id']; ?>"><?php echo htmlspecialchars($row['e_name']); ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Link Set to Exercise</button>
        </form>
        <p><a href="homepage.php">← Back to Home</a></p>
    </div>
</body>
</html> 