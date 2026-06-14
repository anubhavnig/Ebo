<?php
// Application Constants
define('SITE_URL', 'http://ebostay.local');
define('SITE_NAME', 'EBOstay - Flash Tours & Travel');
define('ADMIN_EMAIL', 'admin@ebostay.com');

// Session timeout in minutes
define('SESSION_TIMEOUT', 30);

// Pagination
define('ITEMS_PER_PAGE', 12);

// File upload limits
define('MAX_UPLOAD_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf']);

// Coupon status
define('COUPON_ACTIVE', 1);
define('COUPON_INACTIVE', 0);

// Booking status
define('BOOKING_PENDING', 'pending');
define('BOOKING_CONFIRMED', 'confirmed');
define('BOOKING_COMPLETED', 'completed');
define('BOOKING_CANCELLED', 'cancelled');

?>