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
$image_name = $parts[1];

// Only allow deletion of own images
if ($username === $_SESSION['user']['username'] && file_exists($image_path)) {
    delete_image($username, $image_name);
}

redirect('index.php');
?>