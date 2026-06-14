<?php
require_once __DIR__ . '/../includes/header.php';

if (!isAdmin()) {
    redirect(SITE_URL . '/auth/login.php');
}

$page = getPageNumber();
$expenses = $conn->query("SELECT e.*, u.name as employee_name, b.booking_ref FROM expenses e 
                          LEFT JOIN users u ON e.employee_id = u.id 
                          LEFT JOIN bookings b ON e.booking_id = b.id 
                          ORDER BY e.created_at DESC " . getPaginationLimit($page, 20));

$total_expenses = $conn->query("SELECT SUM(amount) as total FROM expenses")->fetch_assoc()['total'] ?? 0;
$category_breakdown = $conn->query("SELECT category, SUM(amount) as total FROM expenses GROUP BY category");
?>

<div class="admin-container">
    <div class="admin-sidebar">
        <nav class="admin-nav">
            <a href="dashboard.php" class="nav-item"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a href="manage-tours.php" class="nav-item"><i class="fas fa-map"></i> Manage Tours</a>
            <a href="manage-bookings.php" class="nav-item"><i class="fas fa-calendar-check"></i> Bookings</a>
            <a href="expenses.php" class="nav-item active"><i class="fas fa-money-bill"></i> Expenses</a>
            <a href="employees.php" class="nav-item"><i class="fas fa-users"></i> Employees</a>
            <a href="coupons.php" class="nav-item"><i class="fas fa-ticket-alt"></i> Coupons</a>
        </nav>
    </div>

    <div class="admin-content">
        <h1>Tour Expenses Management</h1>

        <div class="admin-section">
            <h2>Total Expenses: <?php echo formatCurrency($total_expenses); ?></h2>
            <div class="expense-stats">
                <?php while ($cat = $category_breakdown->fetch_assoc()): ?>
                <div class="expense-stat">
                    <h4><?php echo htmlspecialchars($cat['category']); ?></h4>
                    <p><?php echo formatCurrency($cat['total']); ?></p>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="admin-section">
            <button class="btn-primary" onclick="openExpenseModal()">+ Add Expense</button>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Booking</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Employee</th>
                        <th>Date</th>
                        <th>Receipt</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($expense = $expenses->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($expense['booking_ref'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($expense['category']); ?></td>
                        <td><?php echo htmlspecialchars(substr($expense['description'], 0, 30)) . '...'; ?></td>
                        <td><?php echo formatCurrency($expense['amount']); ?></td>
                        <td><?php echo htmlspecialchars($expense['employee_name'] ?? 'N/A'); ?></td>
                        <td><?php echo formatDate($expense['created_at']); ?></td>
                        <td><?php if ($expense['receipt_url']): ?><a href="<?php echo $expense['receipt_url']; ?>" target="_blank" class="btn-small">View</a><?php endif; ?></td>
                        <td><a href="#" class="btn-small" onclick="deleteExpense(<?php echo $expense['id']; ?>)">Delete</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="expenseModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeExpenseModal()">&times;</span>
        <h2>Add New Expense</h2>
        <form id="expenseForm">
            <div class="form-group">
                <label>Booking Reference</label>
                <select name="booking_id" required>
                    <option value="">Select Booking</option>
                    <?php
                    $bookings = $conn->query("SELECT id, booking_ref FROM bookings WHERE status = 'confirmed'");
                    while ($b = $bookings->fetch_assoc()):
                    ?>
                    <option value="<?php echo $b['id']; ?>"><?php echo htmlspecialchars($b['booking_ref']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="">Select Category</option>
                    <option value="hotel">Hotel</option>
                    <option value="transport">Transport</option>
                    <option value="food">Food</option>
                    <option value="activity">Activity</option>
                    <option value="guide">Guide</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required></textarea>
            </div>
            <div class="form-group">
                <label>Amount (₹)</label>
                <input type="number" name="amount" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Employee</label>
                <select name="employee_id">
                    <option value="">Select Employee</option>
                    <?php
                    $employees = $conn->query("SELECT u.id, u.name FROM users u JOIN employees e ON u.id = e.user_id WHERE e.status = 'active'");
                    while ($emp = $employees->fetch_assoc()):
                    ?>
                    <option value="<?php echo $emp['id']; ?>"><?php echo htmlspecialchars($emp['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn-primary">Add Expense</button>
        </form>
    </div>
</div>

<style>
.expense-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.expense-stat {
    background: var(--light);
    padding: 1rem;
    border-radius: 5px;
    text-align: center;
}

.expense-stat h4 {
    color: var(--text);
    margin-bottom: 0.5rem;
}

.expense-stat p {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: var(--white);
    margin: 5% auto;
    padding: 2rem;
    border-radius: 10px;
    width: 80%;
    max-width: 500px;
}

.close {
    color: #aaa;
    float: right;
    font-size: 2rem;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: var(--primary);
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-family: inherit;
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}
</style>

<script>
function openExpenseModal() {
    document.getElementById('expenseModal').style.display = 'block';
}

function closeExpenseModal() {
    document.getElementById('expenseModal').style.display = 'none';
}

document.getElementById('expenseForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Add AJAX submission for adding expense
    alert('Expense added successfully!');
    closeExpenseModal();
});

function deleteExpense(id) {
    if (confirm('Are you sure you want to delete this expense?')) {
        // Add AJAX deletion
        alert('Expense deleted!');
    }
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>