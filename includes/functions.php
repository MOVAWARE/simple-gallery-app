<?php
require_once 'config.php';

// User management functions
function register_user($username, $password) {
    $user_file = USERS_DIR . "/$username.txt";
    
    if (file_exists($user_file)) {
        return false; // User already exists
    }
    
    $user_data = [
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    file_put_contents($user_file, json_encode($user_data));
    return true;
}

function login_user($username, $password) {
    $user_file = USERS_DIR . "/$username.txt";
    
    if (!file_exists($user_file)) {
        return false;
    }
    
    $user_data = json_decode(file_get_contents($user_file), true);
    
    if (password_verify($password, $user_data['password'])) {
        $_SESSION['user'] = $user_data;
        return true;
    }
    
    return false;
}

// Image management functions
function get_user_images($username) {
    $user_dir = UPLOAD_DIR . "/$username";
    
    if (!file_exists($user_dir)) {
        mkdir($user_dir);
        return [];
    }
    
    $images = array_diff(scandir($user_dir), ['.', '..']);
    return array_map(function($img) use ($username) {
        return [
            'path' => "$username/$img",
            'name' => $img,
            'url' => BASE_URL . "/" . UPLOAD_DIR . "/$username/$img"
        ];
    }, $images);
}

function delete_image($username, $image_name) {
    $image_path = UPLOAD_DIR . "/$username/$image_name";
    if (file_exists($image_path)) {
        unlink($image_path);
        return true;
    }
    return false;
}

// Add these functions to functions.php
function is_valid_image($file_path) {
    // Check if the file is actually an image
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'video/mp4'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file_path);
    finfo_close($finfo);
    
    return in_array($mime, $allowed_types);
}

function sanitize_filename($filename) {
    // Remove any path information
    $filename = basename($filename);
    // Replace spaces and trim
    $filename = preg_replace('/\s+/', '_', trim($filename));
    // Remove special characters
    $filename = preg_replace('/[^a-zA-Z0-9\-\._]/', '', $filename);
    // Limit length
    $filename = substr($filename, 0, 100);
    return $filename;
}

function check_file_content($file_path) {
    // Simple malware check by looking for PHP tags in first 100 bytes
    $contents = file_get_contents($file_path, false, null, 0, 100);
    return strpos($contents, '<?php') === false;
}
?>