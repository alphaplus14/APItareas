# 📋 API de Tareas — PHP + PDO

API REST desarrollada en PHP puro con PDO para la gestión de tareas, empleados, áreas y asignaciones. Permite realizar operaciones CRUD completas sobre cada recurso, con validaciones de integridad referencial y reglas de negocio.

---

## 🗂️ Estructura del proyecto

```
apitareas/
├── index.php               # Enrutador principal
├── db.php                  # Configuración de la conexión PDO
├── .htaccess               # Reescritura de URLs con mod_rewrite
├── controllers/
│   ├── areaController.php
│   ├── empleadoController.php
│   ├── tareasController.php
│   ├── estadosController.php
│   └── asignacionesController.php
└── models/
    ├── area.php
    ├── empleado.php
    ├── tareas.php
    ├── estados.php
    └── asignaciones.php
```

---

## ⚙️ Requisitos

- PHP 7.4 o superior
- Apache con `mod_rewrite` habilitado
- MySQL 5.7 o superior
- Extensión PDO y PDO_MySQL habilitadas

---

## 🚀 Instalación

1. Clona o copia la carpeta `apitareas/` en el directorio raíz de tu servidor (por ejemplo, `htdocs/` o `www/`).

2. Crea la base de datos en MySQL:

```sql
CREATE DATABASE apitareas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

3. Crea las tablas necesarias:

```sql
USE apitareas;

CREATE TABLE areas (
    id_area INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT
);

CREATE TABLE estados (
    id_estados INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE tareas (
    id_tareas INT PRIMARY KEY,
    descripcion TEXT NOT NULL,
    prioridad VARCHAR(50)
);

CREATE TABLE empleados (
    id_empleados INT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    telefono VARCHAR(20)
);

CREATE TABLE asignaciones (
    id_asignacion INT AUTO_INCREMENT PRIMARY KEY,
    empleados_id_empleados INT NOT NULL,
    tareas_id_tareas INT NOT NULL,
    estados_id_estados INT NOT NULL,
    fecha_asignacion DATE NOT NULL,
    fecha_entrega DATE,
    FOREIGN KEY (empleados_id_empleados) REFERENCES empleados(id_empleados),
    FOREIGN KEY (tareas_id_tareas) REFERENCES tareas(id_tareas),
    FOREIGN KEY (estados_id_estados) REFERENCES estados(id_estados)
);
```

4. Configura la conexión en `db.php`:

```php
$host = 'localhost';
$db   = 'apitareas';
$user = 'root';
$pass = '';
```

---

## 📡 Endpoints

La URL base de la API es:

```
http://localhost/apitareas/
```

Todos los recursos aceptan y retornan JSON. El `Content-Type` debe ser `application/json`.

---

### 👥 Empleados — `/empleados`

| Método | URL | Descripción |
|--------|-----|-------------|
| GET | `/empleados` | Obtiene todos los empleados |
| GET | `/empleados/{id}` | Obtiene un empleado por ID |
| POST | `/empleados` | Crea un nuevo empleado |
| PUT | `/empleados/{id}` | Actualiza un empleado existente |
| DELETE | `/empleados/{id}` | Elimina un empleado |

**Body para POST / PUT:**
```json
{
    "id_empleados": 1,
    "nombre": "Carlos",
    "apellidos": "Pérez",
    "telefono": "3001234567"
}
```

> ⚠️ No se puede eliminar un empleado que tenga asignaciones activas (error `409`).

---

### ✅ Tareas — `/tareas`

| Método | URL | Descripción |
|--------|-----|-------------|
| GET | `/tareas` | Obtiene todas las tareas |
| GET | `/tareas/{id}` | Obtiene una tarea por ID |
| POST | `/tareas` | Crea una nueva tarea |
| PUT | `/tareas/{id}` | Actualiza una tarea existente |
| DELETE | `/tareas/{id}` | Elimina una tarea |

**Body para POST / PUT:**
```json
{
    "id_tareas": 10,
    "descripcion": "Revisar reportes mensuales",
    "prioridad": "Alta"
}
```

> ⚠️ No se puede eliminar una tarea que tenga asignaciones activas (error `409`).

---

### 🏢 Áreas — `/areas`

| Método | URL | Descripción |
|--------|-----|-------------|
| GET | `/areas` | Obtiene todas las áreas |
| GET | `/areas/{id}` | Obtiene un área por ID |
| POST | `/areas` | Crea una nueva área |
| PUT | `/areas/{id}` | Actualiza un área existente |
| DELETE | `/areas/{id}` | Elimina un área |

**Body para POST / PUT:**
```json
{
    "nombre": "Recursos Humanos",
    "descripcion": "Área encargada del personal"
}
```

---

### 🔖 Estados — `/estados`

| Método | URL | Descripción |
|--------|-----|-------------|
| GET | `/estados` | Obtiene todos los estados |
| GET | `/estados/{id}` | Obtiene un estado por ID |
| POST | `/estados` | Crea un nuevo estado |
| PUT | `/estados/{id}` | Actualiza un estado existente |
| DELETE | `/estados/{id}` | Elimina un estado |

**Body para POST / PUT:**
```json
{
    "nombre": "En progreso"
}
```

> ⚠️ No se puede eliminar un estado que tenga asignaciones activas (error `409`).

---

### 📌 Asignaciones — `/asignaciones`

| Método | URL | Descripción |
|--------|-----|-------------|
| GET | `/asignaciones` | Obtiene todas las asignaciones |
| GET | `/asignaciones/{id}` | Obtiene una asignación por ID |
| POST | `/asignaciones` | Crea una nueva asignación |
| PUT | `/asignaciones/{id}` | Actualiza una asignación existente |
| DELETE | `/asignaciones/{id}` | Elimina una asignación |

**Body para POST / PUT:**
```json
{
    "empleados_id_empleados": 1,
    "tareas_id_tareas": 10,
    "estados_id_estados": 2,
    "fecha_asignacion": "2026-02-25",
    "fecha_entrega": "2026-03-10"
}
```

**Campos requeridos en POST:** `empleados_id_empleados`, `tareas_id_tareas`, `estados_id_estados`, `fecha_asignacion`.

**Validaciones:**
- El empleado, la tarea y el estado deben existir previamente.
- No se puede asignar la misma tarea a un empleado más de una vez (error `409`).
- La `fecha_asignacion` no puede ser posterior a la `fecha_entrega` (error `400`).

---

## 📬 Códigos de respuesta HTTP

| Código | Significado |
|--------|-------------|
| `200` | Operación exitosa |
| `201` | Recurso creado correctamente |
| `400` | Solicitud incorrecta (faltan campos o datos inválidos) |
| `404` | Recurso no encontrado |
| `405` | Método HTTP no permitido |
| `409` | Conflicto de integridad (relaciones activas o duplicados) |
| `500` | Error interno del servidor |

---

## 🔒 CORS

La API tiene CORS habilitado para todas las origenes (`*`), permitiendo los métodos `GET`, `POST`, `PUT`, `DELETE` y `OPTIONS`. Esto está configurado tanto en `.htaccess` como en `index.php`.

---

## 🛠️ Tecnologías utilizadas

- **PHP** — Lenguaje del servidor
- **PDO** — Acceso a base de datos con sentencias preparadas
- **MySQL** — Motor de base de datos
- **Apache + mod_rewrite** — Enrutamiento limpio de URLs