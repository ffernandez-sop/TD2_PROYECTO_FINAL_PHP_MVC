<?php
require_once __DIR__ . '/ControllerInterface.php';
class TaskController implements ControllerInterface
{
  private $model;

  public function __construct($db)
  {
    $this->model = new Task($db);
  }

  public function processRequest($method, $params)
  {
    switch ($method) {
      case 'GET':
        if (isset($_GET['id'])) {
          $this->show($_GET['id']);
        } else {
          $filters = [];
          if (isset($_GET['estado'])) {
            $filters['estado'] = $_GET['estado'];
          }
          if (isset($_GET['categoria_id'])) {
            $filters['categoria_id'] = $_GET['categoria_id'];
          }
          if (isset($_GET['titulo'])) {
            $filters['titulo'] = $_GET['titulo'];
          }
          if (isset($_GET['descripcion'])) {
            $filters['descripcion'] = $_GET['descripcion'];
          }

          if (isset($_GET['fecha_vencimiento_desde'])) {
            $filters['fecha_vencimiento_desde'] = $_GET['fecha_vencimiento_desde'];
          }
          if (isset($_GET['fecha_vencimiento_hasta'])) {
            $filters['fecha_vencimiento_hasta'] = $_GET['fecha_vencimiento_hasta'];
          }
          $this->index($filters);
        }
        break;
      case 'POST':
        $this->create($params);
        break;
      case 'PATCH':
        if (isset($params['id'])) {
          $this->updateStatus($params['id'], $params['estado'] ?? null);
        }
        break;
      case 'PUT':
        $this->update($params['id'], $params);
        break;
      case 'DELETE':
        if (isset($params['id'])) {
          $this->delete($params['id']);
        }
        break;
      default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
    }
  }

  private function index($filters)
  {
    $tasks = $this->model->all($filters);
    echo json_encode($tasks);
  }

  private function show($id)
  {
    $task = $this->model->find($id);
    echo json_encode($task);
  }

  private function create($data)
  {
    $id = $this->model->create($data);
    echo json_encode(['task' => $id]);
  }

  private function update($id, $data)
  {

    echo json_encode($this->model->update($id, $data));
  }

  private function delete($id)
  {
    $this->model->delete($id);
    echo json_encode(['success' => true]);
  }

  private function updateStatus($id, $status)
  {
    $this->model->toggleStatus($id, $status);
    echo json_encode(['success' => true]);
  }
}
