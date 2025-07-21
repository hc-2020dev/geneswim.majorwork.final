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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - GeneSwim</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridlex/2.7.1/gridlex.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .contact-page {
            padding-top: 100px;
            min-height: 100vh;
            background: linear-gradient(135deg, #d8e9f1 0%, #63badf 100%);
        }

        .contact-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }

        .contact-card {
            background: white;
            border-radius: 12px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .contact-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #040d1c;
            margin-bottom: 1rem;
            text-align: center;
        }

        .contact-subtitle {
            font-size: 1.1rem;
            color: #676d72;
            text-align: center;
            margin-bottom: 3rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #2d3e4d;
            margin-bottom: 0.5rem;
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #d8e9f1;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #216bb6;
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn-submit {
            background: #216bb6;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-submit:hover {
            background: #2d3e4d;
        }

        .error-messages {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .back-link {
            display: inline-block;
            color: #216bb6;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 2rem;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #2d3e4d;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav">
            <div class="nav-container">
                <div class="nav-logo">
                    <img src="https://ext.same-assets.com/4193966861/740545503.svg" alt="GeneSwim Logo" class="logo-img">
                    <span class="logo-text">GeneSwim</span>
                </div>
                <div class="nav-links">
                    <a href="index.html" class="nav-link">Home</a>
                    <a href="#circuit-generator" class="nav-link">Circuit Generator</a>
                    <a href="#structured-sets" class="nav-link">Structured Sets</a>
                </div>
                <div class="nav-auth">
                    <button class="btn-login">Log In</button>
                </div>
            </div>
        </nav>
    </header>

    <!-- Contact Page -->
    <main class="contact-page">
        <div class="contact-container">
            <a href="index.html" class="back-link">← Back to Home</a>

            <div class="contact-card">
                <h1 class="contact-title">Contact Us</h1>
                <p class="contact-subtitle">Get in touch with the GeneSwim team. We'd love to hear from you!</p>

                <?php if (!empty($errors)): ?>
                    <div class="error-messages">
                        <ul style="margin: 0; padding-left: 1rem;">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="success-message">
                        <strong>Thank you!</strong> Your message has been sent successfully. We'll get back to you soon.
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name" class="form-label">Name *</label>
                        <input type="text" id="name" name="name" class="form-input" value="<?php echo htmlspecialchars($name); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" id="email" name="email" class="form-input" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="subject" class="form-label">Subject *</label>
                        <input type="text" id="subject" name="subject" class="form-input" value="<?php echo htmlspecialchars($subject); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label">Message *</label>
                        <textarea id="message" name="message" class="form-textarea" required><?php echo htmlspecialchars($message); ?></textarea>
                    </div>

                    <button type="submit" class="btn-submit">Send Message</button>
                </form>
            </div>
        </div>
    </main>

    <script src="js/script.js"></script>
</body>
</html>
