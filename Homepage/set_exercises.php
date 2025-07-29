<?php
// Database connection settings
$host = 'localhost';
$db   = 'geneswim';
$user = 'root';
$pass = 'geneswim2025';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch all sets for dropdown
$sets = $conn->query('SELECT s_id, s_name FROM `set` ORDER BY s_name');

// Get s_id from GET or POST
$s_id = $_GET['s_id'] ?? ($_POST['s_id'] ?? '');
$exercises = [];
$set_name = '';
if ($s_id) {
    // Get set name
    $stmt = $conn->prepare('SELECT s_name FROM `set` WHERE s_id = ?');
    $stmt->bind_param('i', $s_id);
    $stmt->execute();
    $stmt->bind_result($set_name);
    $stmt->fetch();
    $stmt->close();

    // Get exercises for this set
    $query = 'SELECT e.e_id, e.e_name FROM lockset l JOIN exercise e ON l.e_id = e.e_id WHERE l.s_id = ? ORDER BY e.e_name';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $s_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $exercises[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercises in Set</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="register-container">
        <h2>Exercises in Set</h2>
        <form method="get" action="">
            <label for="s_id">Select Set:</label>
            <select id="s_id" name="s_id" required onchange="this.form.submit()">
                <option value="">Choose a set</option>
                <?php foreach ($sets as $set): ?>
                    <option value="<?php echo $set['s_id']; ?>" <?php if ($s_id == $set['s_id']) echo 'selected'; ?>><?php echo htmlspecialchars($set['s_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <?php if ($s_id && $set_name): ?>
            <h3>Set: <?php echo htmlspecialchars($set_name); ?></h3>
            <?php if (count($exercises) > 0): ?>
                <table border="1" cellpadding="8" style="margin-top:1rem;">
                    <tr><th>Exercise ID</th><th>Exercise Name</th></tr>
                    <?php foreach ($exercises as $ex): ?>
                        <tr>
                            <td><?php echo $ex['e_id']; ?></td>
                            <td><?php echo htmlspecialchars($ex['e_name']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No exercises linked to this set.</p>
            <?php endif; ?>
        <?php elseif ($s_id): ?>
            <p>Set not found.</p>
        <?php endif; ?>
        <p><a href="homepage.php">← Back to Home</a></p>
    </div>
</body>
</html> 