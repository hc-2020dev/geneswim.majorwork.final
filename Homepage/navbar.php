<?php
session_start();
?>
<link rel="stylesheet" href="style.css">
<header class="header">
        <nav class="nav">
            <div class="nav-container">
                <div class="nav-logo">
                    <a href="homepage.php">
                        <img src="geneswimupdate.png" alt="GeneSwim Logo" class="logo-img">
                        <span class="logo-text">GeneSwim</span>
                    </a>
                </div>
                <div class="nav-links">
                    <a href="circuit_generator.php" class="nav-link">Circuit Generator</a>
                    <a href="structured-sets.php" class="nav-link">Structured Sets</a>
                </div>
                <div class="nav-auth">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span class="user-name">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        <a href="logout.php" class="btn-logout">Log Out</a>
                    <?php else: ?>
                        <a href="login.php" class="btn-login">Log In</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>