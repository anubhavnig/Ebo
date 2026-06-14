<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<nav class="navbar">
    <div class="container">
        <div class="navbar-brand">
            <h1>🌍 EBOstay Tours</h1>
        </div>
        <ul class="navbar-menu">
            <li><a href="<?php echo SITE_URL; ?>/index.php">Home</a></li>
            <li><a href="<?php echo SITE_URL; ?>/pages/packages.php">Packages</a></li>
            <li><a href="<?php echo SITE_URL; ?>/pages/customize-tour.php">Customize Tour</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="<?php echo SITE_URL; ?>/pages/my-bookings.php">My Bookings</a></li>
                <li><a href="<?php echo SITE_URL; ?>/auth/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="<?php echo SITE_URL; ?>/auth/login.php">Login</a></li>
                <li><a href="<?php echo SITE_URL; ?>/auth/register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>