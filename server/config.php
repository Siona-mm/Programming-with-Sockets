<?php
define('SERVER_IP', '127.0.0.1');
define('UDP_PORT', 9090);
define('HTTP_PORT', 8081);
define('STORAGE_DIR', __DIR__ . '/../shared/files/');
define('LOG_FILE', __DIR__ . '/../logs.txt');
define('MAX_CLIENTS', 4);
define('CLIENT_TIMEOUT', 60);

if (!is_dir(STORAGE_DIR)) {
    mkdir(STORAGE_DIR, 0777, true);
}