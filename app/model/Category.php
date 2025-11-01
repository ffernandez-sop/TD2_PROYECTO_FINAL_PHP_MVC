<?php
class Category
{
  private $repo;

  public function __construct($db)
  {
    $this->repo = new CategoryRepository($db);
  }

  public function all()
  {
    $args = func_get_args();
    $filters = $args[0] ?? [];
    return $this->repo->getAll($filters);
  }

  public function find($id)
  {
    return $this->repo->find($id);
  }

  public function create($data)
  {
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
}
