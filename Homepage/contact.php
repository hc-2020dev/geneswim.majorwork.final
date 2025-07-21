<?php
header('Content-Type: text/html; charset=UTF-8');

// Function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Initialize variables
$name = $email = $subject = $message = '';
$errors = array();
$success = false;

// Process form if submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize form data
    $name = sanitizeInput($_POST['name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $subject = sanitizeInput($_POST['subject'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');

    // Validate form data
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }

    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!validateEmail($email)) {
        $errors[] = 'Please provide a valid email address.';
    }

    if (empty($subject)) {
        $errors[] = 'Subject is required.';
    }

    if (empty($message)) {
        $errors[] = 'Message is required.';
    }

    // If no errors, process the form
    if (empty($errors)) {
        // In production, you would:
        // 1. Send email to admin
        // 2. Store message in database
        // 3. Send auto-reply to user

        // For demo, just log the message
        $logFile = 'contact_messages.log';
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] From: $name ($email)\nSubject: $subject\nMessage: $message\n\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);

        $success = true;

        // Clear form data after successful submission
        $name = $email = $subject = $message = '';
    }
}
?>
