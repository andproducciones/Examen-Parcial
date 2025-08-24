<?php
// router.php para el servidor embebido de PHP.
// Uso: php -S 0.0.0.0:8000 router.php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($path !== '/' && file_exists(__DIR__ . $path)) {
    return false; // servir archivo estático
}
require __DIR__ . '/index.php';
