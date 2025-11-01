<?php
class CategoryRepository
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db; // $db es mysqli
  }

  public function getAll($filters = [])
  {
    // Si no hay filtros, consulta directa
    if (empty($filters) || empty($filters['q'])) {
      $result = $this->db->query("SELECT * FROM categorias");
      return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Filtrar por nombre (LIKE)
    $q = '%' . $filters['q'] . '%';
    $stmt = $this->db->prepare("SELECT * FROM categorias WHERE nombre LIKE ?");
    $stmt->bind_param("s", $q);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function find($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }

  public function create($data)
  {
    $codigo = strtoupper(preg_replace('/[^A-Z0-9]/', '',  strtoupper(iconv('UTF-8', 'ASCII//TRANSLIT', $data['nombre']))));
    $stmt = $this->db->prepare("INSERT INTO categorias (nombre, codigo, color) VALUES (?, ?, ?)");
    $nombre = $data['nombre'];
    $color = $data['color'] ?? null;
    $stmt->bind_param(
      "sss",
      $nombre,
      $codigo,
      $color
    );

    if ($stmt->execute()) {
      $insertedId = $this->db->insert_id;
      return $this->find($insertedId); // devuelve la categoría recién creada
    } else {
      return false;
    }
  }

  public function update($id, $data)
  {
    $codigo = strtoupper(preg_replace('/[^A-Z0-9]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $data['codigo'])));
    $nombre = $data['nombre'];
    $color = $data['color'] ?? null;

    $stmt = $this->db->prepare("UPDATE categorias SET nombre=?, codigo=?, color=? WHERE id=?");
    $stmt->bind_param(
      "sssi",
      $nombre,
      $codigo,
      $color,
      $id
    );

    if ($stmt->execute()) {
      return $this->find($id); //devuelve la categoría actualizada
    } else {
      return false;
    }
  }

  public function delete($id)
  {

    $stmt = $this->db->prepare("SELECT COUNT(*) FROM tareas WHERE categoria_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->fetch_row()[0] > 0) {
      return false;
    }

    $stmt = $this->db->prepare("DELETE FROM categorias WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
  }
}
