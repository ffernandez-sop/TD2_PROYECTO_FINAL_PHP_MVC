<?php
require_once __DIR__ . '/../app/repositories/TaskRepository.php';
require_once __DIR__ . '/../app/repositories/CategoryRepository.php';
require_once __DIR__ . '/../app/model/Task.php';
require_once __DIR__ . '/../app/model/Category.php';
require_once __DIR__ . '/../app/controller/TaskController.php';
require_once __DIR__ . '/../app/controller/CategoryController.php';
require_once __DIR__ . '/../app/controller/ControllerFactory.php';

class Router
{
  private $db;

  public function __construct()
  {
    $this->db = (new Database())->connect();
  }

  public function handle($method, $action)
  {
    if ($method === 'OPTIONS') {
      http_response_code(200);
      echo json_encode(['ok' => true]);
      return;
    }

    try {

      $controller = ControllerFactory::create($action, $this->db);
    } catch (InvalidArgumentException $e) {

      http_response_code(404);
      echo json_encode(['error' => $e->getMessage()]);

      return;
    }
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->processRequest($method, $data);
  }
}
