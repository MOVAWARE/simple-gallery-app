<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$images = get_user_images($_SESSION['user']['username']);
?>

<?php include 'includes/header.php'; ?>

<h2>Your Gallery</h2>

<?php if (isset($_GET['upload_success'])): ?>
    <div class="alert alert-success">Image uploaded successfully!</div>
<?php endif; ?>

<?php if (empty($images)): ?>
    <div class="empty-state">
        <h3>Your gallery is empty</h3>
        <p>Get started by uploading your first image</p>
        <a href="upload.php" class="primary">Upload Image</a>
    </div>
<?php else: ?>
    <div class="gallery">
        <?php foreach ($images as $image): ?>
            <div class="gallery-item">
                <a href="view.php?img=<?= urlencode($image['path']) ?>">
                    <img src="<?= htmlspecialchars($image['url']) ?>" alt="<?= htmlspecialchars($image['name']) ?>">
                </a>
                <div class="gallery-item-info">
                    <p><?= htmlspecialchars($image['name']) ?></p>
                    <div class="gallery-item-actions">
                        <a href="view.php?img=<?= urlencode($image['path']) ?>">View</a>
                        <a href="delete.php?img=<?= urlencode($image['path']) ?>" class="delete" 
                           onclick="return confirm('Are you sure you want to delete this image?')">Delete</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>