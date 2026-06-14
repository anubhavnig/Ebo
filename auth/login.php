<?php
require_once __DIR__ . '/../includes/header.php';

if (isLoggedIn()) {
    redirect(SITE_URL . '/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email || empty($password)) {
        $error = 'Please enter email and password';
    } else {
        $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (verifyPassword($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                
                if ($user['role'] === 'admin') {
                    redirect(SITE_URL . '/admin/dashboard.php');
                } else {
                    redirect(SITE_URL . '/pages/my-bookings.php');
                }
            } else {
                $error = 'Invalid credentials';
            }
        } else {
            $error = 'Email not registered';
        }
    }
}
?>

<div class="auth-container">
    <div class="auth-form">
        <h2>Login to EBOstay</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Login</button>
        </form>
        
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

<style>
.auth-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: calc(100vh - 200px);
    background: var(--light);
    padding: 2rem;
}

.auth-form {
    background: var(--white);
    padding: 3rem;
    border-radius: 10px;
    box-shadow: var(--shadow);
    width: 100%;
    max-width: 400px;
}

.auth-form h2 {
    text-align: center;
    margin-bottom: 2rem;
    color: var(--dark);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--text);
}

.form-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    font-family: inherit;
}

.form-group input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 5px rgba(255, 107, 107, 0.3);
}

.alert {
    padding: 1rem;
    border-radius: 5px;
    margin-bottom: 1.5rem;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
}

.alert-success {
    background: #d4edda;
    color: #155724;
}

.auth-form p {
    text-align: center;
    margin-top: 1.5rem;
}

.auth-form a {
    color: var(--primary);
    text-decoration: none;
}

.auth-form a:hover {
    text-decoration: underline;
}
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
