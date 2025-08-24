<?php

// index.php - Servicio web simple en PHP que devuelve JSON.
// Rutas:
//   GET  /                -> info del servicio
//   GET  /api/status      -> estado estático
//   GET  /api/time        -> hora del servidor
//   POST /api/echo        -> devuelve el JSON enviado

declare(strict_types=1);

// Encabezados comunes (CORS + JSON)
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// --- Normalización de ruta (soporta subcarpetas en WAMP) ---
$rawPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/'); // p.ej. /parcial_1/pregunta_1
if ($scriptDir && $scriptDir !== '/' && str_starts_with($rawPath, $scriptDir)) {
    $rawPath = substr($rawPath, strlen($scriptDir)) ?: '/';
}
// quitar barra final salvo raíz
$uri = rtrim($rawPath, '/');
if ($uri === '') {
    $uri = '/';
}

function json_ok(array $data): void
{
    http_response_code(200);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function json_error(string $message, int $code = 400): void
{
    http_response_code($code);
    echo json_encode(['ok' => false, 'error' => $message, 'code' => $code], JSON_UNESCAPED_UNICODE);
    exit;
}

switch ($uri) {
    case '/':
    case '/index.php':
        json_ok([
            'ok' => true,
            'service' => 'php-json-service',
            'message' => 'API is running',
            'endpoints' => ['/api/status', '/api/time', '/api/echo']
        ]);
        break;

    case '/api/status':
        if ($method !== 'GET') {
            json_error('Método no permitido', 405);
        }
        json_ok([
            'project' => 'HERRAMIENTAS DE DESARROLLO DE SOFTWARE - Servicio JSON (PHP)',
            'version' => '1.0.0',
            'quality_model' => ['funcional', 'no_funcional'],
            'status' => 'operational'
        ]);
        break;

    case '/api/time':
        if ($method !== 'GET') {
            json_error('Método no permitido', 405);
        }
        $now = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        json_ok([
            'iso' => $now->format('c'),
            'epoch_ms' => (int) (microtime(true) * 1000)
        ]);
        break;

    case '/api/echo':
        if ($method !== 'POST') {
            json_error('Método no permitido', 405);
        }
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            json_error('JSON inválido: ' . json_last_error_msg(), 422);
        }
        json_ok([
            'you_sent' => $data,
            'note' => 'Visualizacion de funcionamiento de servicio, HERRAMIENTAS DE DESARROLLO DE SOFTWARE '
        ]);
        break;

    default:
        json_error('Ruta no encontrada: ' . $uri, 404);
}
