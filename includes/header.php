<!DOCTYPE html>
<?php
require_once 'config.php';
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=WEB_TITLE?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1><a><?=WEB_LOGO_NAME?></a></h1>
        <nav>
            <?php if (is_logged_in()): ?>
                <a href="index.php">Home</a>
                <a href="upload.php">Upload</a>
                <span>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="index.php">Home</a>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>