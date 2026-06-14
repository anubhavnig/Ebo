<?php
require_once __DIR__ . '/../includes/header.php';

if (!isAdmin()) {
    redirect(SITE_URL . '/auth/login.php');
}

// Get statistics
$total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT SUM(total_amount) as sum FROM bookings WHERE status = 'confirmed'")->fetch_assoc()['sum'] ?? 0;
$total_customers = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'customer'")->fetch_assoc()['count'];
$pending_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'")->fetch_assoc()['count'];

// Get recent bookings
$recent_bookings = $conn->query("SELECT b.*, p.name as package_name, u.name as customer_name FROM bookings b 
                                 JOIN packages p ON b.package_id = p.id 
                                 JOIN users u ON b.user_id = u.id 
                                 ORDER BY b.created_at DESC LIMIT 10");

// Get recent expenses
$recent_expenses = $conn->query("SELECT e.*, u.name as employee_name, b.booking_ref FROM expenses e 
                                 LEFT JOIN users u ON e.employee_id = u.id 
                                 LEFT JOIN bookings b ON e.booking_id = b.id 
                                 ORDER BY e.created_at DESC LIMIT 10");
?>

<div class="admin-container">
    <div class="admin-sidebar">
        <nav class="admin-nav">
            <a href="dashboard.php" class="nav-item active"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a href="manage-tours.php" class="nav-item"><i class="fas fa-map"></i> Manage Tours</a>
            <a href="manage-bookings.php" class="nav-item"><i class="fas fa-calendar-check"></i> Bookings</a>
            <a href="expenses.php" class="nav-item"><i class="fas fa-money-bill"></i> Expenses</a>
            <a href="employees.php" class="nav-item"><i class="fas fa-users"></i> Employees</a>
            <a href="coupons.php" class="nav-item"><i class="fas fa-ticket-alt"></i> Coupons</a>
            <a href="analytics.php" class="nav-item"><i class="fas fa-chart-pie"></i> Analytics</a>
        </nav>
    </div>

    <div class="admin-content">
        <h1>Admin Dashboard</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #FF6B6B;"><i class="fas fa-calendar-check"></i></div>
                <h3>Total Bookings</h3>
                <p class="stat-value"><?php echo $total_bookings; ?></p>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #4ECDC4;"><i class="fas fa-money-bill-wave"></i></div>
                <h3>Total Revenue</h3>
                <p class="stat-value"><?php echo formatCurrency($total_revenue); ?></p>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #FFE66D;"><i class="fas fa-users"></i></div>
                <h3>Total Customers</h3>
                <p class="stat-value"><?php echo $total_customers; ?></p>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #FF8B94;"><i class="fas fa-hourglass-half"></i></div>
                <h3>Pending Bookings</h3>
                <p class="stat-value"><?php echo $pending_bookings; ?></p>
            </div>
        </div>

        <div class="admin-section">
            <h2>Recent Bookings</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Booking Ref</th>
                        <th>Customer</th>
                        <th>Package</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($booking = $recent_bookings->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['booking_ref']); ?></td>
                        <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['package_name']); ?></td>
                        <td><?php echo formatCurrency($booking['total_amount']); ?></td>
                        <td><span class="status-badge status-<?php echo $booking['status']; ?>"><?php echo ucfirst($booking['status']); ?></span></td>
                        <td><?php echo formatDate($booking['created_at']); ?></td>
                        <td><a href="booking-detail.php?id=<?php echo $booking['id']; ?>" class="btn-small">View</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="admin-section">
            <h2>Recent Expenses</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Booking Ref</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Employee</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($expense = $recent_expenses->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($expense['booking_ref']); ?></td>
                        <td><?php echo htmlspecialchars($expense['category']); ?></td>
                        <td><?php echo formatCurrency($expense['amount']); ?></td>
                        <td><?php echo htmlspecialchars($expense['employee_name'] ?? 'N/A'); ?></td>
                        <td><?php echo formatDate($expense['created_at']); ?></td>
                        <td><a href="expense-detail.php?id=<?php echo $expense['id']; ?>" class="btn-small">View</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.admin-container {
    display: flex;
    gap: 2rem;
    padding: 2rem;
    background: var(--light);
    min-height: calc(100vh - 200px);
}

.admin-sidebar {
    width: 250px;
    background: var(--white);
    border-radius: 10px;
    padding: 1.5rem;
    height: fit-content;
    box-shadow: var(--shadow);
}

.admin-nav {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    color: var(--text);
    text-decoration: none;
    border-radius: 5px;
    transition: all 0.3s;
}

.nav-item:hover,
.nav-item.active {
    background: var(--primary);
    color: var(--white);
}

.admin-content {
    flex: 1;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: var(--white);
    padding: 2rem;
    border-radius: 10px;
    box-shadow: var(--shadow);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary);
}

.admin-section {
    background: var(--white);
    padding: 2rem;
    border-radius: 10px;
    box-shadow: var(--shadow);
    margin-bottom: 2rem;
}

.admin-section h2 {
    margin-bottom: 1.5rem;
    color: var(--dark);
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table thead {
    background: var(--light);
}

.admin-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: var(--dark);
}

.admin-table td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #ecf0f1;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-pending {
    background: #FFF3CD;
    color: #856404;
}

.status-confirmed {
    background: #D4EDDA;
    color: #155724;
}

.status-completed {
    background: #D1ECF1;
    color: #0C5460;
}

.status-cancelled {
    background: #F8D7DA;
    color: #721C24;
}

.btn-small {
    padding: 0.5rem 1rem;
    background: var(--primary);
    color: var(--white);
    text-decoration: none;
    border-radius: 5px;
    font-size: 0.85rem;
    transition: all 0.3s;
}

.btn-small:hover {
    background: #FF5252;
}
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>