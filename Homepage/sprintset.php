<?php
// DB connection (adjust credentials as needed)
$host = 'localhost';
$db = 'geneswim';
$user = 'root';
$pass = 'geneswim2025';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get set ID from GET or POST
$s_id = isset($_GET['s_id']) ? intval($_GET['s_id']) : 0;

if ($s_id > 0) {
    // Adjust table and column names if necessary
    $query = "SELECT e_id, e_name FROM exercises WHERE s_id = 0";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $s_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h2>Exercises in Set ID: $s_id</h2>";
    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>Exercise ID: " . $row['e_id'] . " - " . $row['e_name'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No exercises found for this set.";
    }

    $stmt->close();
} else {
    echo "Invalid set ID.";
}

$conn->close();
?>
