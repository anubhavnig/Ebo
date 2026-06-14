<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/razorpay.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJSON(['success' => false, 'message' => 'Invalid request method'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);

$required_fields = ['razorpay_payment_id', 'razorpay_order_id', 'razorpay_signature', 'booking_id'];
foreach ($required_fields as $field) {
    if (!isset($input[$field])) {
        sendJSON(['success' => false, 'message' => 'Missing required field: ' . $field], 400);
    }
}

$razorpay = new Razorpay();
$is_valid = $razorpay->verifyPayment(
    $input['razorpay_payment_id'],
    $input['razorpay_order_id'],
    $input['razorpay_signature']
);

if ($is_valid) {
    // Update booking status
    $booking_id = (int)$input['booking_id'];
    $update_query = "UPDATE bookings SET status = 'confirmed', payment_id = '{$input['razorpay_payment_id']}', payment_date = NOW() WHERE id = $booking_id";

    if ($conn->query($update_query)) {
        sendJSON([
            'success' => true,
            'message' => 'Payment verified successfully',
            'booking_id' => $booking_id
        ]);
    } else {
        sendJSON(['success' => false, 'message' => 'Failed to update booking'], 500);
    }
} else {
    sendJSON(['success' => false, 'message' => 'Payment verification failed'], 400);
}
?>