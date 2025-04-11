<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
// At the top of login.php
require_once 'includes/rate_limiter.php';
enforce_rate_limit('login_attempt', LOGIN_ATTEMPT, 120); // 20 attempts per 2 minutes

if (is_logged_in()) {
    redirect('index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields';
    } elseif (login_user($username, $password)) {
        redirect('index.php');
    } else {
        $error = 'Invalid username or password';
    }
}
?>

<?php include 'includes/header.php'; ?>

<h2>Login</h2>

<?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">
    <div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit">Login</button>
</form>

<p class="regis-login">Don't have an account? <a href="register.php">Register here</a>.</p>

<?php include 'includes/footer.php'; ?>