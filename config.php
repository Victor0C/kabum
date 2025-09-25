<?php
require_once __DIR__ . '/app/Utils/ManageEnv.php';

ManageEnv::loadEnv(__DIR__ . '/.env');

define('BASE_URL', $_ENV['BASE_URL'] ?? 'http://127.0.0.1/');
define('DB_HOST', $_ENV['DB_HOST'] ?? '127.0.0.1');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'kabum');
