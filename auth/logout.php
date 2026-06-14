<?php
require_once __DIR__ . '/../includes/header.php';

session_destroy();
redirect(SITE_URL . '/index.php');
?>
