<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Function to validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to log subscription (in production, this would save to database)
function logSubscription($email, $subscribeStatus) {
    $logFile = 'subscriptions.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] Email: $email, Subscribed: " . ($subscribeStatus ? 'Yes' : 'No') . "\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

// Response array
$response = array(
    'success' => false,
    'message' => '',
    'data' => array()
);

try {
    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method. Only POST requests are allowed.');
    }

    // Get POST data
    $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
    $subscribe = isset($_POST['subscribe']) ? true : false;

    // Validate required fields
    if (empty($email)) {
        throw new Exception('Email address is required.');
    }

    // Validate email format
    if (!validateEmail($email)) {
        throw new Exception('Please provide a valid email address.');
    }

    // Check if user agreed to subscribe
    if (!$subscribe) {
        throw new Exception('Please check the subscription checkbox to continue.');
    }

    // In a real application, you would:
    // 1. Check if email already exists in database
    // 2. Add email to newsletter database table
    // 3. Send confirmation email
    // 4. Integrate with email service (MailChimp, SendGrid, etc.)

    // For this demo, we'll just log the subscription
    logSubscription($email, $subscribe);

    // Simulate some processing time
    usleep(500000); // 0.5 seconds

    // Success response
    $response['success'] = true;
    $response['message'] = "Thank you! We've successfully added your email to our newsletter.";
    $response['data'] = array(
        'email' => $email,
        'timestamp' => date('Y-m-d H:i:s')
    );

    // In production, you might want to:
    // - Send a welcome email
    // - Add to your email marketing platform
    // - Store additional user preferences

} catch (Exception $e) {
    // Error response
    $response['success'] = false;
    $response['message'] = $e->getMessage();

    // Log error (in production, use proper logging)
    error_log("Newsletter subscription error: " . $e->getMessage());
}

// Return JSON response
echo json_encode($response);

// For AJAX requests, we can also handle them differently
if (isset($_POST['ajax']) && $_POST['ajax'] === 'true') {
    exit(); // Just return the JSON response
}

// For regular form submissions, redirect back with message
if ($response['success']) {
    // Redirect with success message
    header("Location: index.html?message=" . urlencode("Successfully subscribed to newsletter!"));
} else {
    // Redirect with error message
    header("Location: index.html?error=" . urlencode($response['message']));
}
exit();
?>
