<?php
session_start();

// Destroy all session data
session_destroy();

// Redirect to homepage
header('Location: homepage.php');
exit();
?> 