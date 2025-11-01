<?php
class TaskRepository
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db; // $db es mysqli
  }

  public function getAll($filters)
  {
    $whereClauses = [];

    // === FILTRO POR FECHA DE VENCIMIENTO ===
    if (!empty($filters['fecha_vencimiento_desde']) && !empty($filters['fecha_vencimiento_hasta'])) {
      $desde = $this->db->real_escape_string($filters['fecha_vencimiento_desde']);
      $hasta = $this->db->real_escape_string($filters['fecha_vencimiento_hasta']);
      $whereClauses[] = "tareas.fecha_vencimiento BETWEEN '$desde' AND '$hasta'";
    } elseif (!empty($filters['fecha_vencimiento_desde'])) {
      $desde = $this->db->real_escape_string($filters['fecha_vencimiento_desde']);
      $whereClauses[] = "tareas.fecha_vencimiento >= '$desde'";
    } elseif (!empty($filters['fecha_vencimiento_hasta'])) {
      $hasta = $this->db->real_escape_string($filters['fecha_vencimiento_hasta']);
      $whereClauses[] = "tareas.fecha_vencimiento <= '$hasta'";
    }

    // === FILTRO POR CATEGORÍA ===
    if (!empty($filters['categoria_id']) && $filters['categoria_id'] !== 'all') {
      $categoriaId = intval($filters['categoria_id']);
      $whereClauses[] = "tareas.categoria_id = $categoriaId";
    }

    // === FILTRO POR ESTADO ===
    if (!empty($filters['estado']) && $filters['estado'] !== 'all') {
      $estado = $this->db->real_escape_string($filters['estado']);
      $whereClauses[] = "tareas.estado = '$estado'";
    }

    // === FILTRO POR PRIORIDAD ===
    if (!empty($filters['prioridad']) && $filters['prioridad'] !== 'all') {
      $prioridad = $this->db->real_escape_string($filters['prioridad']);
      $whereClauses[] = "tareas.prioridad = '$prioridad'";
    }

    // === FILTRO POR TÍTULO ===
    if (!empty($filters['titulo'])) {
      $titulo = $this->db->real_escape_string($filters['titulo']);
      $whereClauses[] = "tareas.titulo LIKE '%$titulo%'";
    }

    // === FILTRO POR DESCRIPCIÓN ===
    if (!empty($filters['descripcion'])) {
      $descripcion = $this->db->real_escape_string($filters['descripcion']);
      $whereClauses[] = "tareas.descripcion LIKE '%$descripcion%'";
    }

    // === CONSTRUCCIÓN DEL WHERE FINAL ===
    $whereSql = !empty($whereClauses) ? ' WHERE ' . implode(' AND ', $whereClauses) : '';

    // === CONSULTA PRINCIPAL ===
    $sql = "
        SELECT 
            tareas.id, 
            tareas.titulo, 
            tareas.descripcion, 
            tareas.estado, 
            tareas.fecha_vencimiento, 
            tareas.prioridad, 
            tareas.categoria_id, 
            categorias.nombre AS categoria, 
            categorias.color
        FROM tareas
        JOIN categorias ON categorias.id = tareas.categoria_id
        $whereSql
        ORDER BY tareas.fecha_vencimiento ASC
    ";

    $result = $this->db->query($sql);
    if (!$result) {
      throw new Exception("Error en la consulta SQL: " . $this->db->error);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function create($data)
  {
    $stmt = $this->db->prepare(
      "INSERT INTO tareas (titulo, descripcion, estado, fecha_vencimiento, prioridad, categoria_id) VALUES (?, ?, ?, ?, ?, ?)"
    );

    $titulo = $data['titulo'];
    $descripcion = $data['descripcion'] ?? '';
    $fecha_vencimiento = $data['fecha_vencimiento'] ?? null;
    $prioridad = $data['prioridad'] ?? null;
    $estado = 'pendiente';
    $categoria_id = $data['categoria_id'];

    $stmt->bind_param(
      "sssssi",
      $titulo,
      $descripcion,
      $estado,
      $fecha_vencimiento,
      $prioridad,
      $categoria_id
    );

    if ($stmt->execute()) {
      $insertedId = $this->db->insert_id;
      return $this->find($insertedId);
    } else {
      return false;
    }
  }

  public function update($id, $data)
  {
    $stmt = $this->db->prepare(
      "UPDATE tareas 
       SET titulo = ?, descripcion = ?, estado = ?, fecha_vencimiento = ?, prioridad = ?, categoria_id = ? 
       WHERE id = ?"
    );

    $titulo = $data['titulo'];
    $descripcion = $data['descripcion'] ?? '';
    $estado = $data['estado'];
    $fecha_vencimiento = $data['fecha_vencimiento'];
    $prioridad = $data['prioridad'];
    $categoria_id = $data['categoria_id'];

    $stmt->bind_param(
      "ssssssi",
      $titulo,
      $descripcion,
      $estado,
      $fecha_vencimiento,
      $prioridad,
      $categoria_id,
      $id
    );

    if ($stmt->execute()) {
      return $this->find($id);
    } else {
      return false;
    }
  }

  public function delete($id)
  {
    $stmt = $this->db->prepare("DELETE FROM tareas WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
  }

  public function updateEstado($id, $estado)
  {
    $stmt = $this->db->prepare("UPDATE tareas SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $estado, $id);

    if ($stmt->execute()) {
      return $this->find($id);
    } else {
      return false;
    }
  }

  public function find($id)
  {
    $stmt = $this->db->prepare(
      "SELECT 
          tareas.id, 
          tareas.titulo, 
          tareas.descripcion, 
          tareas.estado, 
          tareas.fecha_vencimiento, 
          tareas.prioridad, 
          tareas.categoria_id, 
          categorias.nombre AS categoria, 
          categorias.color
       FROM tareas
       JOIN categorias ON categorias.id = tareas.categoria_id
       WHERE tareas.id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc();
  }
}
