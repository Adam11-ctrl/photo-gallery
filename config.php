<?php
// Update these for your environment
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'photo_gallery');
define('DB_USER', 'root');
define('DB_PASS', '');
// Base URL (no trailing slash). Adjust if you use a subfolder, e.g. '/gallery'
define('BASE_URL', 'http://localhost/photo-gallery');

// File uploads
define('UPLOAD_DIR', __DIR__ . '/uploads'); // absolute path
define('UPLOAD_URI', BASE_URL . '/uploads'); // public path

if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}

?>
