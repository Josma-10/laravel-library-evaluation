# Sistema de Gestión de Biblioteca

Sistema desarrollado en Laravel y PHP para el registro, consulta y administración del acervo bibliográfico e inventario documental, estructurado bajo estándares de arquitectura limpia y principios de diseño PSR-12.

---

## 1. Requisitos del Sistema

- **PHP:** Versión 8.2 o superior.
  - Extensiones requeridas: `pdo_sqlite` (o `pdo_mysql`), `sqlite3`, `curl`, `mbstring`, `openssl`, `fileinfo`, `zip`.
- **Composer:** Versión 2.0 o superior.
- **Git:** Para clonación y gestión de versiones.

---

## 2. Instalación y Puesta en Marcha

Siga los pasos descritos a continuación para levantar el proyecto en un entorno local:

### Paso 1: Clonar el repositorio
```bash
git clone https://github.com/Josma-10/laravel-library-evaluation.git
cd laravel-library-evaluation
```

### Paso 2: Instalar dependencias con Composer
```bash
composer install
```

### Paso 3: Configurar variables de entorno
Copiar el archivo de plantilla `.env.example` a `.env`:
```bash
cp .env.example .env
```
Generar la clave de aplicación:
```bash
php artisan key:generate
```

---

## 3. Configuración de Base de Datos

El sistema viene preconfigurado para utilizar **SQLite** por defecto, lo que permite su ejecución y prueba inmediata sin requerir servicios externos de bases de datos.

### Opción A: SQLite (Recomendada para pruebas locales)
En el archivo `.env`, verifique la siguiente línea:
```env
DB_CONNECTION=sqlite
```
Asegúrese de que el archivo `database/database.sqlite` exista (se crea automáticamente o mediante el comando):
```bash
# En Windows (PowerShell):
if (!(Test-Path "database\database.sqlite")) { New-Item "database\database.sqlite" -ItemType File }

# En Linux / macOS:
touch database/database.sqlite
```

### Opción B: MySQL (Entornos de producción / staging)
Si prefiere utilizar un servidor MySQL, configure las credenciales correspondientes en `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_db
DB_USERNAME=root
DB_PASSWORD=secret
```

---

## 4. Migraciones y Sembrado de Datos

Ejecute las migraciones y los seeders para crear el esquema relacional (`categories` y `books`) y poblar las categorías y libros iniciales para pruebas:

```bash
php artisan migrate:fresh --seed
```

El seeder inicial crea automáticamente:
- **3 Categorías iniciales:** *Ficción*, *Tecnología*, *Historia*.
- **3 Libros reales vinculados:**
  - *Cien años de soledad* (Gabriel García Márquez, 1967)
  - *Clean Code: A Handbook of Agile Software Craftsmanship* (Robert C. Martin, 2008)
  - *Sapiens: De animales a dioses* (Yuval Noah Harari, 2011)

---

## 5. Ejecución de Pruebas Automatizadas

El proyecto incluye un conjunto completo de pruebas automatizadas con PHPUnit / Pest que evalúan cada requisito de la especificación técnica:

```bash
php artisan test
```

Casos verificados en la suite de pruebas:
- Listado general de libros con carga ansiosa (*eager loading* de la relación `category`).
- Filtrado de libros por identificador de categoría (`?category={id}`).
- Creación de libros y validación de campos obligatorios y año de edición (`StoreBookRequest`).
- Actualización parcial/total de libros y control de existencia (200 / 404).
- Eliminación de libros verificando existencia previa (200 / 404).
- Registro de categorías garantizando unicidad de nombre (`StoreCategoryRequest`).
- Bloqueo y protección de integridad referencial al intentar eliminar categorías con libros asociados (HTTP 409).

---

## 6. Inicio del Servidor Local

Inicie el servidor de desarrollo de Laravel:

```bash
php artisan serve
```

El sistema estará disponible en `http://localhost:8000`.

- **Interfaz Web Administrativa:** `http://localhost:8000/`
- **Endpoints de la API:** `http://localhost:8000/api/...` (también accesibles directamente en la raíz por compatibilidad).

---

## 7. Catálogo de Endpoints de la API

Todos los endpoints retornan respuestas estructuradas en formato JSON.

### Resumen de Rutas

| Método | Endpoint | Descripción | Códigos HTTP |
| :--- | :--- | :--- | :--- |
| **GET** | `/api/books` | Lista todos los libros con la categoría precargada | `200 OK` |
| **GET** | `/api/books?category={id}` | Filtra los libros por `category_id` | `200 OK` |
| **POST** | `/api/books` | Registra un nuevo libro | `201 Created`, `422 Unprocessable` |
| **GET** | `/api/books/{id}` | Consulta el detalle de un libro | `200 OK`, `404 Not Found` |
| **PUT** | `/api/books/{id}` | Actualiza un libro existente | `200 OK`, `404 Not Found`, `422 Unprocessable` |
| **DELETE** | `/api/books/{id}` | Elimina un libro del catálogo | `200 OK`, `404 Not Found` |
| **GET** | `/api/categories` | Lista todas las categorías | `200 OK` |
| **POST** | `/api/categories` | Registra una nueva categoría | `201 Created`, `422 Unprocessable` |
| **DELETE** | `/api/categories/{id}` | Elimina una categoría sin libros asociados | `200 OK`, `404 Not Found`, `409 Conflict` |

---

### Ejemplos de Petición y Respuestas

#### 1. Listar Libros
- **Petición:** `GET /api/books` o `GET /api/books?category=2`
- **Respuesta (`200 OK`):**
```json
[
  {
    "id": 2,
    "title": "Clean Code: A Handbook of Agile Software Craftsmanship",
    "author": "Robert C. Martin",
    "description": "Guía fundamental sobre buenas prácticas y desarrollo de software.",
    "published_year": 2008,
    "category_id": 2,
    "created_at": "2026-11-24T12:00:00.000000Z",
    "updated_at": "2026-11-24T12:00:00.000000Z",
    "category": {
      "id": 2,
      "name": "Tecnología",
      "created_at": "2026-11-24T12:00:00.000000Z",
      "updated_at": "2026-11-24T12:00:00.000000Z"
    }
  }
]
```

#### 2. Crear un Libro
- **Petición:** `POST /api/books`
- **Headers:** `Content-Type: application/json`, `Accept: application/json`
- **Cuerpo (Payload):**
```json
{
  "title": "Estructuras de Datos y Algoritmos",
  "author": "Alfred Aho",
  "description": "Texto clásico sobre algoritmia y complejidad computacional.",
  "published_year": 1983,
  "category_id": 2
}
```
- **Respuesta (`201 Created`):**
```json
{
  "id": 4,
  "title": "Estructuras de Datos y Algoritmos",
  "author": "Alfred Aho",
  "description": "Texto clásico sobre algoritmia y complejidad computacional.",
  "published_year": 1983,
  "category_id": 2,
  "created_at": "2026-11-24T12:05:00.000000Z",
  "updated_at": "2026-11-24T12:05:00.000000Z",
  "category": {
    "id": 2,
    "name": "Tecnología"
  }
}
```

#### 3. Actualizar un Libro
- **Petición:** `PUT /api/books/4`
- **Cuerpo (Payload):**
```json
{
  "published_year": 1988,
  "description": "Segunda edición corregida y aumentada."
}
```
- **Respuesta (`200 OK`):** Retorna el objeto del libro actualizado con su categoría.

#### 4. Eliminar un Libro
- **Petición:** `DELETE /api/books/4`
- **Respuesta (`200 OK`):**
```json
{
  "message": "Libro eliminado exitosamente"
}
```
- **Respuesta (`404 Not Found`):**
```json
{
  "message": "Libro no encontrado"
}
```

#### 5. Registrar Categoría
- **Petición:** `POST /api/categories`
- **Cuerpo (Payload):**
```json
{
  "name": "Economía y Finanzas"
}
```
- **Respuesta (`201 Created`):** Retorna la categoría creada.
- **Respuesta si el nombre está duplicado (`422 Unprocessable`):** Retorna el error de validación de unicidad.

#### 6. Eliminar Categoría con Control de Dependencias
- **Petición:** `DELETE /api/categories/1`
- **Respuesta si la categoría contiene libros vinculados (`409 Conflict`):**
```json
{
  "message": "No es posible eliminar la categoría porque tiene libros asociados"
}
```
- **Respuesta si no tiene libros asociados (`200 OK`):**
```json
{
  "message": "Categoría eliminada exitosamente"
}
```

---

## 8. Arquitectura y Estructura del Código

- **Modelos:**
  - `App\Models\Category`: Define relación `books(): HasMany`.
  - `App\Models\Book`: Define atributos `$fillable`, casteo de datos y relación `category(): BelongsTo`.
- **Validaciones:**
  - `App\Http\Requests\StoreBookRequest`: Valida campos requeridos, longitud y año válido.
  - `App\Http\Requests\UpdateBookRequest`: Validación con regla `sometimes` para actualizaciones parciales.
  - `App\Http\Requests\StoreCategoryRequest`: Regla `unique:categories,name`.
- **Controladores:**
  - `App\Http\Controllers\BookController`: Lógica de consulta, filtrado, persistencia y eliminación.
  - `App\Http\Controllers\CategoryController`: Control de catálogo e integridad ante eliminación.
- **Rutas:**
  - `routes/api.php`: Rutas oficiales de la API REST.
  - `routes/web.php`: Interfaz gráfica administrativa y rutas de compatibilidad directa.
