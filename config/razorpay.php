<?php
// Razorpay Configuration
define('RAZORPAY_KEY_ID', 'YOUR_RAZORPAY_KEY_ID');
define('RAZORPAY_KEY_SECRET', 'YOUR_RAZORPAY_KEY_SECRET');

class Razorpay {
    private $key_id;
    private $key_secret;

    public function __construct() {
        $this->key_id = RAZORPAY_KEY_ID;
        $this->key_secret = RAZORPAY_KEY_SECRET;
    }

    public function createOrder($amount, $booking_id, $description) {
        $api_url = 'https://api.razorpay.com/v1/orders';

        $data = [
            'amount' => $amount * 100, // Convert to paise
            'currency' => 'INR',
            'receipt' => 'receipt#' . $booking_id,
            'description' => $description,
            'notes' => [
                'booking_id' => $booking_id
            ]
        ];

        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->key_id . ':' . $this->key_secret);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function verifyPayment($razorpay_payment_id, $razorpay_order_id, $razorpay_signature) {
        $expected_signature = hash_hmac('sha256', $razorpay_order_id . '|' . $razorpay_payment_id, $this->key_secret);
        return $expected_signature === $razorpay_signature;
    }
}
?>