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

// Create the circuit table if it doesn't exist
$conn->query("
    CREATE TABLE IF NOT EXISTS circuit (
        id INT AUTO_INCREMENT PRIMARY KEY,
        s_id INT NOT NULL,
        e_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (s_id) REFERENCES `set`(s_id) ON DELETE CASCADE,
        FOREIGN KEY (e_id) REFERENCES exercise(e_id) ON DELETE CASCADE
    )
");

// Fetch sets for the first step
$sets = $conn->query('SELECT s_id, s_name FROM `set` ORDER BY s_name');

// Fetch all exercises for each dropdown
$all_exercises = $conn->query("
    SELECT e_id, e_name 
    FROM exercise
    ORDER BY e_id
");

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['step']) && $_POST['step'] === '1') {
        // Step 1: Select a set
        $s_id = $_POST['s_id'] ?? '';
        if (!$s_id) {
            $error = 'Please select a set.';
        } else {
            // Proceed to step 2
            $step = 2;
        }
    } elseif (isset($_POST['step']) && $_POST['step'] === '2') {
        // Step 2: Select exercises
        $s_id = $_POST['s_id'] ?? '';
        $warm_up = $_POST['warm_up'] ?? '';
        $main = $_POST['main'] ?? '';
        $main_2 = $_POST['main_2'] ?? ''; // New second main set
        $warm_down = $_POST['warm_down'] ?? '';
        if (!$s_id || (!$warm_up && !$main && !$main_2 && !$warm_down)) {
            $error = 'Please select a set and at least one exercise from each category.';
        } else {
            // Save the circuit (example logic)
            $selected_exercises = array_filter([$warm_up, $main, $main_2, $warm_down]);
            foreach ($selected_exercises as $e_id) {
                $stmt = $conn->prepare('INSERT INTO circuit (s_id, e_id) VALUES (?, ?)');
                $stmt->bind_param('ii', $s_id, $e_id);
                $stmt->execute();
                $stmt->close();
            }
            $success = 'Circuit added successfully!';
            $step = 1; // Reset to step 1

            // Fetch exercise names for the selected options
            $selected_exercise_names = [];
            if ($warm_up) {
                $result = $conn->query("SELECT e_name FROM exercise WHERE e_id = $warm_up");
                $selected_exercise_names['warm_up'] = $result->fetch_assoc()['e_name'];
            }
            if ($main) {
                $result = $conn->query("SELECT e_name FROM exercise WHERE e_id = $main");
                $selected_exercise_names['main'] = $result->fetch_assoc()['e_name'];
            }
            if ($main_2) {
                $result = $conn->query("SELECT e_name FROM exercise WHERE e_id = $main_2");
                $selected_exercise_names['main_2'] = $result->fetch_assoc()['e_name'];
            }
            if ($warm_down) {
                $result = $conn->query("SELECT e_name FROM exercise WHERE e_id = $warm_down");
                $selected_exercise_names['warm_down'] = $result->fetch_assoc()['e_name'];
            }
        }
    }
} else {
    $step = 1; // Default to step 1
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Circuit Generator</title>
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

        <?php if (!empty($selected_exercise_names)): ?>
            <h2>Selected Exercises</h2>
            <table border="1" cellpadding="8" style="margin-top:1rem;">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Exercise Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($selected_exercise_names['warm_up'])): ?>
                        <tr>
                            <td>Warm-Up</td>
                            <td><?php echo htmlspecialchars($selected_exercise_names['warm_up']); ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (isset($selected_exercise_names['main'])): ?>
                        <tr>
                            <td>Main</td>
                            <td><?php echo htmlspecialchars($selected_exercise_names['main']); ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (isset($selected_exercise_names['main_2'])): ?>
                        <tr>
                            <td>Second Main</td>
                            <td><?php echo htmlspecialchars($selected_exercise_names['main_2']); ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (isset($selected_exercise_names['warm_down'])): ?>
                        <tr>
                            <td>Warm-Down</td>
                            <td><?php echo htmlspecialchars($selected_exercise_names['warm_down']); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if ($step === 1): ?>
            <!-- Step 1: Select a Set -->
            <form method="post" action="">
                <h2>Select a Set</h2>
                <input type="hidden" name="step" value="1">
                <label for="s_id">Set:</label>
                <select id="s_id" name="s_id" required>
                    <option value="">Select a set</option>
                    <?php while ($row = $sets->fetch_assoc()): ?>
                        <option value="<?php echo $row['s_id']; ?>"><?php echo htmlspecialchars($row['s_name']); ?></option>
                    <?php endwhile; ?>
                </select>
                <button type="submit">Next</button>
            </form>
        <?php elseif ($step === 2): ?>
            <!-- Step 2: Select Exercises -->
            <form method="post" action="">
                <h2>Select Exercises</h2>
                <input type="hidden" name="step" value="2">
                <input type="hidden" name="s_id" value="<?php echo htmlspecialchars($s_id); ?>">

                <!-- Warm-Up Dropdown -->
                <label for="warm_up">Warm-Up:</label>
                <select id="warm_up" name="warm_up">
                    <option value="">Select a warm-up exercise</option>
                    <?php while ($row = $all_exercises->fetch_assoc()): ?>
                        <option value="<?php echo $row['e_id']; ?>"><?php echo htmlspecialchars($row['e_name']); ?></option>
                    <?php endwhile; ?>
                </select>

                <!-- Main Dropdown -->
                <label for="main">Main:</label>
                <select id="main" name="main">
                    <option value="">Select a main exercise</option>
                    <?php
                    // Reset the result pointer for reuse
                    $all_exercises->data_seek(0);
                    while ($row = $all_exercises->fetch_assoc()): ?>
                        <option value="<?php echo $row['e_id']; ?>"><?php echo htmlspecialchars($row['e_name']); ?></option>
                    <?php endwhile; ?>
                </select>

                <!-- Second Main Dropdown -->
                <label for="main_2">Second Main:</label>
                <select id="main_2" name="main_2">
                    <option value="">Select a second main exercise</option>
                    <?php
                    // Reset the result pointer for reuse
                    $all_exercises->data_seek(0);
                    while ($row = $all_exercises->fetch_assoc()): ?>
                        <option value="<?php echo $row['e_id']; ?>"><?php echo htmlspecialchars($row['e_name']); ?></option>
                    <?php endwhile; ?>
                </select>

                <!-- Warm-Down Dropdown -->
                <label for="warm_down">Warm-Down:</label>
                <select id="warm_down" name="warm_down">
                    <option value="">Select a warm-down exercise</option>
                    <?php
                    // Reset the result pointer for reuse
                    $all_exercises->data_seek(0);
                    while ($row = $all_exercises->fetch_assoc()): ?>
                        <option value="<?php echo $row['e_id']; ?>"><?php echo htmlspecialchars($row['e_name']); ?></option>
                    <?php endwhile; ?>
                </select>

                <button type="submit">Add Circuit</button>
            </form>
        <?php endif; ?>

        <p><a href="homepage.php">← Go Back</a></p>
    </div>
</body>
</html>