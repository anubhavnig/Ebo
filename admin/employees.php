<?php
require_once __DIR__ . '/../includes/header.php';

if (!isAdmin()) {
    redirect(SITE_URL . '/auth/login.php');
}

$page = getPageNumber();
$employees = $conn->query("SELECT u.*, e.* FROM users u 
                           JOIN employees e ON u.id = e.user_id 
                           ORDER BY u.name ASC " . getPaginationLimit($page, 20));
?>

<div class="admin-container">
    <div class="admin-sidebar">
        <nav class="admin-nav">
            <a href="dashboard.php" class="nav-item"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a href="manage-tours.php" class="nav-item"><i class="fas fa-map"></i> Manage Tours</a>
            <a href="manage-bookings.php" class="nav-item"><i class="fas fa-calendar-check"></i> Bookings</a>
            <a href="expenses.php" class="nav-item"><i class="fas fa-money-bill"></i> Expenses</a>
            <a href="employees.php" class="nav-item active"><i class="fas fa-users"></i> Employees</a>
            <a href="coupons.php" class="nav-item"><i class="fas fa-ticket-alt"></i> Coupons</a>
        </nav>
    </div>

    <div class="admin-content">
        <h1>Employee Management</h1>

        <div class="admin-section">
            <button class="btn-primary" onclick="openEmployeeModal()">+ Add Employee</button>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Department</th>
                        <th>Salary</th>
                        <th>Status</th>
                        <th>Joining Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($emp = $employees->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($emp['name']); ?></td>
                        <td><?php echo htmlspecialchars($emp['email']); ?></td>
                        <td><?php echo htmlspecialchars($emp['phone']); ?></td>
                        <td><?php echo htmlspecialchars($emp['department']); ?></td>
                        <td><?php echo formatCurrency($emp['salary']); ?></td>
                        <td><span class="status-badge status-<?php echo $emp['status']; ?>"><?php echo ucfirst($emp['status']); ?></span></td>
                        <td><?php echo formatDate($emp['joining_date']); ?></td>
                        <td>
                            <a href="#" class="btn-small">Edit</a>
                            <a href="#" class="btn-small" onclick="deleteEmployee(<?php echo $emp['id']; ?>)">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>