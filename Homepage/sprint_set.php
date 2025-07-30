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

$s_id = 1; // Always show set with s_id = 1
$exercises = [];
$set_name = '';
// Get set name
$stmt = $conn->prepare('SELECT s_name FROM `set` WHERE s_id = ?');
$stmt->bind_param('i', $s_id);
$stmt->execute();
$stmt->bind_result($set_name);
$stmt->fetch();
$stmt->close();

// Get exercises for this set, join exercisetype, order by custom type order then name
$query = 'SELECT e.e_id, e.e_name, et.et_name
    FROM lockset l
    JOIN exercise e ON l.e_id = e.e_id
    JOIN exercisetype et ON e.et_id = et.et_id
    WHERE l.s_id = ?
    ORDER BY
        FIELD(et.et_name, "warm-up", "main", "warm-down"),
        et.et_name,
        e.e_name';
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $s_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $exercises[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sprint Set</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="register-container">
        <h2>Sprint Set</h2>
        <?php if (count($exercises) > 0): ?>
            <table border="1" cellpadding="8" style="margin-top:1rem;">
                <tr><th>Exercise ID</th><th>Exercise Name</th><th>Type</th></tr>
                <?php foreach ($exercises as $ex): ?>
                    <tr>
                        <td><?php echo $ex['e_id']; ?></td>
                        <td><?php echo htmlspecialchars($ex['e_name']); ?></td>
                        <td><?php echo htmlspecialchars($ex['et_name']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div class="set-qr">
                <img src="frame.png" alt="Sprint Set QR code" loading="lazy">
            </div>
        <?php else: ?>
            <p>No exercises linked to this set.</p>
        <?php endif; ?>
        <p><a href="structured-sets.php">← Go Back </a></p>
    </div>
</body>
</html>