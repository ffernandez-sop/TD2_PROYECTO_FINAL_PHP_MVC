<?php
class Task
{
  private $repo;

  public function __construct($db)
  {
    // espera una instancia PDO
    $this->repo = new TaskRepository($db);
  }

  public function all($filters)
  {
    return $this->repo->getAll($filters);
  }

  public function find($id)
  {
    return $this->repo->find($id);
  }

  public function create($data)
  {
    // could add validation here
    return $this->repo->create($data);
  }

  public function update($id, $data)
  {
    return $this->repo->update($id, $data);
  }

  public function delete($id)
  {
    return $this->repo->delete($id);
  }

  public function toggleStatus($id, $newStatus)
  {
    $task = $this->find($id);
    if (!$task) return false;
    return $this->repo->updateestado($id, $newStatus);
  }
}
