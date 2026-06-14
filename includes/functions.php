<?php

// Sanitize input
function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(strip_tags(trim($data)));
}

// Hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Generate random token
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Format currency
function formatCurrency($amount) {
    return '₹' . number_format($amount, 2, '.', ',');
}

// Format date
function formatDate($date, $format = 'j M Y') {
    return date($format, strtotime($date));
}

// Get user IP
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
}

// Send JSON response
function sendJSON($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Redirect
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin';
}

// Get page number for pagination
function getPageNumber() {
    return isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
}

// Pagination query
function getPaginationLimit($page = 1, $per_page = 12) {
    $offset = ($page - 1) * $per_page;
    return "LIMIT $offset, $per_page";
}

// Calculate tour rating from reviews
function calculateRating($tour_id) {
    global $conn;
    $result = $conn->query("SELECT AVG(rating) as avg_rating FROM reviews WHERE tour_id = $tour_id");
    $row = $result->fetch_assoc();
    return round($row['avg_rating'], 1) ?? 0;
}

// Apply coupon
function applyCoupon($coupon_code, $amount) {
    global $conn;
    $coupon_code = sanitize($coupon_code);
    
    $result = $conn->query("SELECT * FROM coupons WHERE code = '$coupon_code' AND status = 1 AND expires_at > NOW()");
    
    if ($result->num_rows > 0) {
        $coupon = $result->fetch_assoc();
        $discount = ($amount * $coupon['discount_percent']) / 100;
        return [
            'success' => true,
            'discount' => $discount,
            'final_amount' => $amount - $discount
        ];
    }
    
    return [
        'success' => false,
        'message' => 'Invalid or expired coupon code'
    ];
}

// Get top packages
function getTopPackages($limit = 6) {
    global $conn;
    return $conn->query("SELECT * FROM packages ORDER BY popularity DESC LIMIT $limit");
}

// Get trending tours
function getTrendingTours($limit = 6) {
    global $conn;
    return $conn->query("SELECT * FROM tours ORDER BY created_at DESC LIMIT $limit");
}

?>