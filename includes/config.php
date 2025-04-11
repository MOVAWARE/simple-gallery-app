<?php
// Secure session settings
/* ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Enable if using HTTPS
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_lifetime', 0); // Until browser closes
ini_set('session.gc_maxlifetime', 1800); // 30 minutes
*/
// Basic configuration
session_start();
// Add to config.php after session_start()
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
function generate_csrf_token() {
    return $_SESSION['csrf_token'];
}
function validate_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Security headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; script-src 'self'");
// For HTTPS sites
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    header("Strict-Transport-Security: max-age=63072000; includeSubDomains; preload");
}

///////////////////////////////////////////////////////
////////////////////   Edit This   ////////////////////
///////////////////////////////////////////////////////

define('BASE_URL', 'http://127.0.0.1/_3'); //change to web url
define('UPLOAD_DIR', 'uploads'); //image database folder, default: uploads
define('USERS_DIR', 'users'); //users database folder, default: users
define('WEB_TITLE', 'MovaWare Gallery'); //website title, default: MovaWare Gallery
define('WEB_LOGO_NAME', 'MovaLery'); //website logo name, default: MovaLery

//example: define('UPLOAD_LIMIT', 20);// 20 uploads per hour
define('UPLOAD_LIMIT', 40);  //40 uploads per hour, default: 40

//example: define('LOGIN_ATTEMPT', 10);// 10 attempts per 2 minutes
define('LOGIN_ATTEMPT', 40);  //40 attempts per 2 minutes, default: 20

///////////////////////////////////////////////////////
////////////////////   Edit This   ////////////////////
///////////////////////////////////////////////////////

// Create directories if they don't exist
if (!file_exists(UPLOAD_DIR)) mkdir(UPLOAD_DIR);
if (!file_exists(USERS_DIR)) mkdir(USERS_DIR);

// Simple user authentication check
function is_logged_in() {
    return isset($_SESSION['user']);
}

function redirect($url) {
    header("Location: $url");
    exit;
}
?>