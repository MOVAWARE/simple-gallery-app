<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (is_logged_in()) {
    redirect('index.php');
}

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill in all fields';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif (register_user($username, $password)) {
        $success = true;
    } else {
        $error = 'Username already taken';
    }
}
?>

<?php include 'includes/header.php'; ?>

<h2>Register</h2>

<?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php elseif ($success): ?>
    <div class="alert alert-success">
        Registration successful! <a href="login.php">Login here</a>.
    </div>
<?php endif; ?>

<?php if (!$success): ?>
    <form method="post">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required minlength="6">
        </div>
        <div>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
        </div>
        <button type="submit">Register</button>
    </form>
    
    <p class="regis-login">Already have an account? <a href="login.php">Login here</a>.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>