<?php
class ControllerFactory
{
  private static $map = [
    'tasks' => TaskController::class,
    'categorias' => CategoryController::class
  ];

  public static function create($action, $db): ControllerInterface
  {
    if (!isset(self::$map[$action])) {
      throw new InvalidArgumentException("Controller '$action' not found");
    }
    $class = self::$map[$action];
    return new $class($db);
  }
}
