<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/rate_limiter.php';

if (!is_logged_in()) {
    redirect('login.php');
}



$error = '';
$username = $_SESSION['user']['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        die('CSRF token validation failed');
    }
    
    if (isset($_FILES['image'])) {
        $file = $_FILES['image'];
        
        // Basic checks
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $error = 'Upload error occurred';
        } elseif (!is_uploaded_file($file['tmp_name'])) {
            $error = 'Invalid file upload';
        } elseif ($file['size'] > 5 * 1024 * 1024) {
            $error = 'File too large (max 5MB)';
        } elseif (!is_valid_image($file['tmp_name'])) {
            $error = 'Invalid image/video type';
        } elseif (!check_file_content($file['tmp_name'])) {
            $error = 'Invalid file content';
        } else {
            // Process upload
            $filename = sanitize_filename($file['name']);
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $unique_name = uniqid() . '.' . $extension;
            $destination = UPLOAD_DIR . "/$username/$unique_name";
            
            if (!file_exists(UPLOAD_DIR . "/$username")) {
                mkdir(UPLOAD_DIR . "/$username", 0755, true);
            }
            
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                enforce_rate_limit('file_upload', UPLOAD_LIMIT, 3600); // 40 uploads per hour
                redirect('index.php?upload_success=1');
            } else {
                $error = 'Error saving file';
            }
        }
    } else {
        $error = 'No file selected';
    }
}
?>

<?php include 'includes/header.php'; ?>

<h2>Upload Image</h2>

<?php if ($error): ?>
<div class="alert alert-error">
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <div>
        <!-- Add this inside your forms -->
        <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
        <label for="image">Select Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required>
    </div>
    <button type="submit">Upload</button>
</form>

<?php include 'includes/footer.php'; ?>