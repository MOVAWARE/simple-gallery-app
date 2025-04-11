<?php
function check_rate_limit($key, $limit = 5, $interval = 60) {
    $cache_dir = 'cache/rate_limits/';
    if (!file_exists($cache_dir)) {
        mkdir($cache_dir, 0755, true);
    }
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $file = $cache_dir . md5($ip . $key);
    
    $now = time();
    $data = [];
    
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
        // Clean old attempts
        $data = array_filter($data, function($time) use ($now, $interval) {
            return ($now - $time) < $interval;
        });
    }
    
    $data[] = $now;
    file_put_contents($file, json_encode($data));
    
    return count($data) <= $limit;
}

function enforce_rate_limit($key, $limit = 5, $interval = 60) {
    if (!check_rate_limit($key, $limit, $interval)) {
        http_response_code(429);
        die('Too many requests. Please try again later.');
    }
}
?>