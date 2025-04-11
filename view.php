<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!is_logged_in()) {
    redirect('login.php');
}

if (!isset($_GET['img'])) {
    redirect('index.php');
}

$image_path = UPLOAD_DIR . '/' . $_GET['img'];
$parts = explode('/', $_GET['img']);
$username = $parts[0];

// Only allow viewing of own images
if ($username !== $_SESSION['user']['username'] || !file_exists($image_path)) {
    redirect('index.php');
}

$image_name = basename($image_path);
$image_url = BASE_URL . '/' . $image_path;
?>

<?php include 'includes/header.php'; ?>

<div class="image-view">
    <h2><?= htmlspecialchars($image_name) ?></h2>
    <img src="<?= htmlspecialchars($image_url) ?>" alt="<?= htmlspecialchars($image_name) ?>">
    
    <div class="image-view-actions">
        <a href="index.php" class="primary">Back to Gallery</a>
        <a href="<?= htmlspecialchars($image_url) ?>" download>Download</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>