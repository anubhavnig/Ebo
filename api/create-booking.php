<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/razorpay.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    sendJSON(['success' => false, 'message' => 'Please login to book'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJSON(['success' => false, 'message' => 'Invalid request method'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);

$required_fields = ['package_id', 'travelers', 'start_date', 'email', 'phone'];
foreach ($required_fields as $field) {
    if (!isset($input[$field])) {
        sendJSON(['success' => false, 'message' => 'Missing required field: ' . $field], 400);
    }
}

$package_id = (int)$input['package_id'];
$travelers = (int)$input['travelers'];
$start_date = sanitize($input['start_date']);
$email = filter_var($input['email'], FILTER_VALIDATE_EMAIL);
$phone = sanitize($input['phone']);
$coupon_code = sanitize($input['coupon_code'] ?? '');

if (!$email) {
    sendJSON(['success' => false, 'message' => 'Invalid email'], 400);
}

// Get package details
$package = $conn->query("SELECT * FROM packages WHERE id = $package_id")->fetch_assoc();
if (!$package) {
    sendJSON(['success' => false, 'message' => 'Package not found'], 404);
}

$total_amount = $package['price'] * $travelers;

// Apply coupon if provided
if (!empty($coupon_code)) {
    $coupon_result = applyCoupon($coupon_code, $total_amount);
    if ($coupon_result['success']) {
        $total_amount = $coupon_result['final_amount'];
    }
}

// Create booking
$booking_ref = 'BK' . time() . rand(1000, 9999);
$insert_query = "INSERT INTO bookings (user_id, package_id, booking_ref, travelers, start_date, email, phone, total_amount, status, created_at) 
                 VALUES ('{$_SESSION['user_id']}', $package_id, '$booking_ref', $travelers, '$start_date', '$email', '$phone', $total_amount, 'pending', NOW())";

if ($conn->query($insert_query)) {
    $booking_id = $conn->insert_id;

    // Create Razorpay order
    $razorpay = new Razorpay();
    $order = $razorpay->createOrder($total_amount, $booking_id, "EBOstay Tour Booking - $booking_ref");

    if (isset($order['id'])) {
        sendJSON([
            'success' => true,
            'booking_id' => $booking_id,
            'booking_ref' => $booking_ref,
            'order_id' => $order['id'],
            'total_amount' => $total_amount,
            'razorpay_key' => RAZORPAY_KEY_ID
        ]);
    } else {
        sendJSON(['success' => false, 'message' => 'Failed to create payment order'], 500);
    }
} else {
    sendJSON(['success' => false, 'message' => 'Booking creation failed'], 500);
}
?>