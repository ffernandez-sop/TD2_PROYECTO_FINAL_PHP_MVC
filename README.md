# TD2_PROYECTO_FINAL_PHP_MVC

Proyecto final — aplicación web en PHP (patrón MVC) para gestionar tareas y categorías.

## Resumen
 - Backend: API REST en PHP (controladores, modelos, repositorios).
 - Frontend: React + Tailwind (cliente SPA - Single Page Application).
 - Soporta MySQL.

## Requisitos
 - PHP 8+ con extensiones PDO/ MySQLi según configuración
 - MySQL 8+ 
 - Apache (XAMPP) o el servidor embebido de PHP para pruebas

## Configuración rápida
1. Clona o copia el proyecto dentro de tu carpeta `htdocs` (XAMPP) o en la ruta que uses para servir PHP.
2. Revisa la configuración de conexión a base de datos en `config/database.php`.
3. Configurar la conexión del cliente con la API PHP
   Antes de iniciar el servidor Apache, es necesario indicarle al cliente (frontend) dónde se encuentra el punto de acceso (endpoint) de la API PHP.
   Esto se hace editando el archivo de configuración:  /view/config.json
   Dentro de este archivo, modifica la propiedad apiBaseUrl para que apunte a la ubicación exacta del archivo index.php, que actúa como punto de entrada de la API.
   Por ejemplo, si tu proyecto está en: C:\xampp\htdocs\proyecto-final-php
   deberás configurarlo así:
   {
     "apiBaseUrl": "http://localhost/proyecto-final-php/index.php"
   }

## Inicializar la base de datos

1. Crea la base de datos en MySQL (ej. `task_management`).
2. Ajusta las credenciales en `config/database.php` (host, usuario, pass, nombre DB).
3. Ejecuta la migración SQL incluida (`data/task_management.sql`).

Esto generará la base de datos con tablas y datos de ejemplo.

## URL de acceso 

  ej. `http://localhost/<ruta local>/view/`.

## API — endpoints principales (ejemplos)
 - Listar tareas: GET /actions=tasks
 - Ver tarea: GET /actions=tasks&id=1
 - Crear tarea: POST /actions=tasks  (JSON)
 - Actualizar tarea: PUT /actions=tasks&id=1 (JSON)
 - Eliminar tarea: DELETE /actions=tasks&id=1

 - Listar categorías: GET /actions=categorias
 - Crear categoría: POST /actions=categorias (JSON {name,color})
 - Actualizar categoría: PUT /actions=categorias&id=1
 - Eliminar categoría: DELETE /actions=categorias&id=1

Ejemplos de respuesta JSON
--------------------------

Tareas

- Listar tareas
Respuesta (200):
```json
[{
   "id": 1,
   "titulo": "Terminar proyecto de programación",
   "descripcion": "Completar backend y pruebas",
   "estado": "pendiente",
   "fecha_vencimiento": "2025-11-03",
   "pioridad": "alta",
   "category_id": 1,
  
}]
```

- Ver tarea

Respuesta (200):
```json
{
   "id": 1,
   "titulo": "Terminar proyecto de programación",
   "descripcion": "Completar backend y pruebas",
   "estado": "pendiente",
   "fecha_vencimiento": "2025-11-03",
   "prioridad": "alta",
   "categoria_id": 1,

}
```

- Crear tarea (payload) ejemplo:
```json
{
   "titulo": "Nueva tarea",
   "descripcion": "Detalle...",
   "fecha_vencimiento": "2025-11-05",
   "prioridad": "media",
   "categoria_id": 2
}
```
Respuesta del servidor 
```json
{ "id": 42 }
```

- Actualizar tarea (PUT /tasks/:id) — payload / respuesta:
```json
// request body
{
   "titulo": "Título actualizado",
   "description": "...",
   "estado": "completada",
   "fecha_vencimiento": "2025-11-06",
   "prioridad": "alta",
   "categoria_id": 1
}

// response
{ "success": true }
```

- Eliminar tarea (DELETE ) — respuesta:
```json
{ "success": true }
```

- Marcar estado (PATCH )
```json
// request body
{ "estado": "completada" }

// response
{ "success": true }
```

Categorías

- Listar categorías (GET /categories?q=texto)

Respuesta (200):
```json
[{
   "id": 1,
   "nombre": "Trabajo",
   "color": "blue"
}]
```
- Ver categoría (GET )

Respuesta (200):
```json
{
   "id": 1,
   "name": "Trabajo",
   "code": "TRABAJO",
   "color": "color"
}
```
- Crear categoría (POST ) — payload ejemplo:
```json
{ "name": "Personal", "color": "green" }
```

Respuesta (201 / 200):
```json
{ "id": 3 }
```

- Actualizar categoría (PUT) — respuesta:
```json
{ "success": true }
```

- Eliminar categoría (DELETE ) — respuesta:
```json
{ "success": true }
```

## Autor
Fabio Fernandez



