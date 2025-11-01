<?php
require_once __DIR__ . '/ControllerInterface.php';
class CategoryController  implements ControllerInterface
{
  private $model;

  public function __construct($db)
  {
    $this->model = new Category($db);
  }

  public function processRequest($method, $params)
  {
    switch ($method) {
      case 'GET':
        if (isset($_GET['id'])) {
          $this->show($_GET['id']);
        } else {
          // permitir filtro por q (nombre)
          $filters = [];
          if (isset($_GET['q']) && $_GET['q'] !== '') $filters['q'] = $_GET['q'];
          $this->index($filters);
        }
        break;
      case 'POST':
        $this->create($params);
        break;
      case 'PUT':
        if (isset($params['id'])) {
          $this->update($params['id'], $params);
        } else {
          http_response_code(400);
          echo json_encode(['error' => 'ID is required for update']);
        }
        break;
      case 'DELETE':
        if (isset($params['id'])) {
          $this->delete($params['id']);
        } else {
          http_response_code(400);
          echo json_encode(['error' => 'ID is required for delete']);
        }
        break;
      default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
  }

  private function index($filters = [])
  {
    echo json_encode($this->model->all());
  }

  private function show($id)
  {
    echo json_encode($this->model->find($id));
  }

  private function create($data)
  {;
    echo json_encode($this->model->create($data));
  }

  private function update($id, $data)
  {
    echo json_encode($this->model->update($id, $data));
  }

  private function delete($id)
  {
    echo json_encode(['success' => $this->model->delete($id)]);
  }
}
