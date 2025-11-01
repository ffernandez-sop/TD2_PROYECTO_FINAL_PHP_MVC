<?php
// ===== HABILITAR CORS =====
header("Access-Control-Allow-Origin: *"); // permite cualquier origen (para desarrollo)
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/routes/api.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

$router = new Router();
$router->handle($method, $action);
