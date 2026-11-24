<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema de Gestión de Biblioteca | Registro de Bienes Bibliográficos</title>
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-dark: #172554;
            --secondary: #334155;
            --secondary-light: #64748b;
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --border-color: #cbd5e1;
            --border-light: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #475569;
            --badge-bg: #e0e7ff;
            --badge-text: #1e3a8a;
            --danger: #b91c1c;
            --success: #15803d;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            line-height: 1.5;
            font-size: 14px;
        }

        /* Franja institucional superior */
        .top-stripe {
            background-color: var(--primary-dark);
            color: #e2e8f0;
            padding: 6px 24px;
            font-size: 11px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #3b82f6;
        }

        .header-main {
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 16px 24px;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .brand-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-subtitle {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 400;
            margin-top: 2px;
        }

        .container {
            max-width: 1200px;
            margin: 24px auto;
            padding: 0 20px;
        }

        /* Tarjetas de Indicadores */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .metric-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-left: 4px solid var(--primary);
            padding: 16px;
            border-radius: 2px;
        }

        .metric-label {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--text-muted);
            letter-spacing: 0.5px;
        }

        .metric-value {
            font-size: 26px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-top: 4px;
        }

        /* Sección de Gestión */
        .section-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 2px;
            margin-bottom: 24px;
        }

        .section-header {
            padding: 14px 20px;
            background-color: #f1f5f9;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .toolbar {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .form-control {
            padding: 7px 12px;
            border: 1px solid var(--border-color);
            border-radius: 2px;
            font-size: 13px;
            background-color: #ffffff;
            color: var(--text-main);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }

        .btn {
            padding: 7px 14px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 2px;
            cursor: pointer;
            border: 1px solid transparent;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
            border-color: var(--primary-dark);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-secondary {
            background-color: #ffffff;
            color: var(--secondary);
            border-color: var(--border-color);
        }

        .btn-secondary:hover {
            background-color: #e2e8f0;
        }

        .btn-danger {
            background-color: #ffffff;
            color: var(--danger);
            border-color: #fca5a5;
            padding: 4px 8px;
            font-size: 12px;
        }

        .btn-danger:hover {
            background-color: #fee2e2;
        }

        /* Tabla de inventario */
        .table-responsive {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .data-table th {
            background-color: #f8fafc;
            color: var(--secondary);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            border-bottom: 2px solid var(--border-color);
            border-top: 1px solid var(--border-light);
        }

        .data-table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-light);
            vertical-align: middle;
        }

        .data-table tr:hover {
            background-color: #f8fafc;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 2px;
            background-color: var(--badge-bg);
            color: var(--badge-text);
            border: 1px solid #bfdbfe;
        }

        .badge-gray {
            background-color: #f1f5f9;
            color: #475569;
            border-color: #cbd5e1;
        }

        .code-col {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-weight: 600;
            color: var(--secondary);
            font-size: 12px;
        }

        /* Formularios desplegables */
        .panel-collapse {
            display: none;
            padding: 20px;
            background-color: #f8fafc;
            border-bottom: 1px solid var(--border-color);
        }

        .grid-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--secondary);
        }

        /* Referencia técnica de endpoints */
        .api-docs-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .api-docs-table th, .api-docs-table td {
            padding: 10px 14px;
            border: 1px solid var(--border-color);
        }

        .api-docs-table th {
            background-color: #f1f5f9;
            color: var(--secondary);
            font-weight: 700;
        }

        .method-badge {
            font-family: monospace;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 2px;
            font-size: 11px;
        }

        .method-get { background: #dbeafe; color: #1e40af; }
        .method-post { background: #dcfce7; color: #15803d; }
        .method-put { background: #fef9c3; color: #854d0e; }
        .method-delete { background: #fee2e2; color: #991b1b; }

        .footer {
            margin-top: 40px;
            border-top: 1px solid var(--border-color);
            background: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

    <div class="top-stripe">
        <span>Dirección de Administración y Custodia de Bienes Públicos</span>
        <span>Módulo de Control Bibliotecario v1.0</span>
    </div>

    <header class="header-main">
        <div class="header-content">
            <div>
                <div class="brand-title">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                        <path d="M6 6h10"/>
                        <path d="M6 10h10"/>
                    </svg>
                    Sistema de Gestión de Biblioteca
                </div>
                <div class="brand-subtitle">Registro Oficial de Acervo, Control de Fondos y Clasificación Patrimonial</div>
            </div>
            <div>
                <span class="badge badge-gray">Entorno: Local / SQLite</span>
            </div>
        </div>
    </header>

    <main class="container">

        <!-- Métricas Institucionales -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-label">Libros Registrados</div>
                <div class="metric-value">{{ $books->count() }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Categorías Activas</div>
                <div class="metric-value">{{ $categories->count() }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Estado del Repositorio</div>
                <div class="metric-value" style="font-size: 18px; color: var(--success); margin-top: 8px;">Conforme / Operativo</div>
            </div>
        </div>

        <!-- Panel de Registro de Libros (Desplegable) -->
        <div id="panelNuevoLibro" class="panel-collapse">
            <h4 style="margin-bottom: 14px; color: var(--primary-dark); font-size: 14px; text-transform: uppercase;">Formulario de Ingreso de Ejemplar</h4>
            <form id="formStoreBook">
                <div class="grid-form">
                    <div class="form-group">
                        <label class="form-label">Título de la Obra *</label>
                        <input type="text" name="title" class="form-control" required placeholder="Ej. Manual de Procedimientos">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Autor Responsable *</label>
                        <input type="text" name="author" class="form-control" required placeholder="Ej. Dirección General">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Año de Edición *</label>
                        <input type="number" name="published_year" class="form-control" min="1000" max="{{ date('Y') }}" value="{{ date('Y') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Categoría Asignada *</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Seleccione una categoría</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label">Descripción / Resumen (Opcional)</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Observaciones o notas de registro"></textarea>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn btn-primary">Registrar en Inventario</button>
                    <button type="button" class="btn btn-secondary" onclick="togglePanel('panelNuevoLibro')">Cancelar</button>
                </div>
            </form>
        </div>

        <!-- Panel de Registro de Categoría (Desplegable) -->
        <div id="panelNuevaCategoria" class="panel-collapse">
            <h4 style="margin-bottom: 14px; color: var(--primary-dark); font-size: 14px; text-transform: uppercase;">Registro de Nueva Categoría</h4>
            <form id="formStoreCategory" style="max-width: 480px;">
                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label">Nombre de Categoría *</label>
                    <input type="text" name="name" class="form-control" required placeholder="Ej. Archivo Histórico">
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn btn-primary">Guardar Categoría</button>
                    <button type="button" class="btn btn-secondary" onclick="togglePanel('panelNuevaCategoria')">Cancelar</button>
                </div>
            </form>
        </div>

        <!-- Inventario Principal -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-title">Inventario General de Obras y Ejemplares</div>
                <div class="toolbar">
                    <form method="GET" action="/" style="display: flex; gap: 8px; align-items: center;">
                        <select name="category" class="form-control" onchange="this.form.submit()">
                            <option value="">Todas las categorías</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $categoryFilter == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ $cat->books_count }})
                                </option>
                            @endforeach
                        </select>
                        @if($categoryFilter)
                            <a href="/" class="btn btn-secondary">Limpiar</a>
                        @endif
                    </form>

                    <button type="button" class="btn btn-primary" onclick="togglePanel('panelNuevoLibro')">
                        + Registrar Libro
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="togglePanel('panelNuevaCategoria')">
                        + Categoría
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Código</th>
                            <th>Título de la Obra</th>
                            <th>Autor</th>
                            <th style="width: 130px;">Categoría</th>
                            <th style="width: 90px; text-align: center;">Año</th>
                            <th>Descripción</th>
                            <th style="width: 90px; text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $book)
                            <tr id="book-row-{{ $book->id }}">
                                <td class="code-col">LIB-{{ str_pad($book->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td style="font-weight: 600;">{{ $book->title }}</td>
                                <td>{{ $book->author }}</td>
                                <td>
                                    <span class="badge">{{ $book->category ? $book->category->name : 'N/A' }}</span>
                                </td>
                                <td style="text-align: center; font-weight: 600;">{{ $book->published_year }}</td>
                                <td style="color: var(--text-muted); font-size: 13px;">{{ $book->description ?: '—' }}</td>
                                <td style="text-align: center;">
                                    <button type="button" class="btn btn-danger" onclick="eliminarLibro({{ $book->id }})">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 28px; color: var(--text-muted);">
                                    No se encontraron registros en el inventario bajo los criterios seleccionados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Documentación de Servicios API -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-title">Especificación Técnica de Endpoints REST</div>
                <span class="badge badge-gray">Formato: JSON</span>
            </div>
            <div class="table-responsive" style="padding: 16px;">
                <table class="api-docs-table">
                    <thead>
                        <tr>
                            <th>Método</th>
                            <th>Endpoint</th>
                            <th>Descripción</th>
                            <th>Respuesta Esperada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="method-badge method-get">GET</span></td>
                            <td><code>/api/books</code></td>
                            <td>Listar todos los libros con relación de categoría precargada (eager loading).</td>
                            <td>HTTP 200</td>
                        </tr>
                        <tr>
                            <td><span class="method-badge method-get">GET</span></td>
                            <td><code>/api/books?category={id}</code></td>
                            <td>Filtrar lista de libros según la categoría especificada.</td>
                            <td>HTTP 200</td>
                        </tr>
                        <tr>
                            <td><span class="method-badge method-post">POST</span></td>
                            <td><code>/api/books</code></td>
                            <td>Crear nuevo libro validando campos obligatorios y año de publicación.</td>
                            <td>HTTP 201</td>
                        </tr>
                        <tr>
                            <td><span class="method-badge method-put">PUT</span></td>
                            <td><code>/api/books/{id}</code></td>
                            <td>Actualizar atributos de un libro existente (404 si no existe).</td>
                            <td>HTTP 200 / 404</td>
                        </tr>
                        <tr>
                            <td><span class="method-badge method-delete">DELETE</span></td>
                            <td><code>/api/books/{id}</code></td>
                            <td>Eliminar libro verificando su existencia previa.</td>
                            <td>HTTP 200 / 404</td>
                        </tr>
                        <tr>
                            <td><span class="method-badge method-get">GET</span></td>
                            <td><code>/api/categories</code></td>
                            <td>Listar catálogo general de categorías.</td>
                            <td>HTTP 200</td>
                        </tr>
                        <tr>
                            <td><span class="method-badge method-post">POST</span></td>
                            <td><code>/api/categories</code></td>
                            <td>Registrar categoría garantizando unicidad de nombre.</td>
                            <td>HTTP 201 / 422</td>
                        </tr>
                        <tr>
                            <td><span class="method-badge method-delete">DELETE</span></td>
                            <td><code>/api/categories/{id}</code></td>
                            <td>Eliminar categoría si no posee libros asociados (409 si tiene dependencias).</td>
                            <td>HTTP 200 / 404 / 409</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <footer class="footer">
        <div>Sistema Integrado de Control de Bienes y Material Bibliográfico &copy; {{ date('Y') }}</div>
        <div style="margin-top: 4px; font-size: 11px;">Desarrollado en arquitectura Laravel bajo estándares PSR-12</div>
    </footer>

    <script>
        function togglePanel(id) {
            const panel = document.getElementById(id);
            panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
        }

        document.getElementById('formStoreBook')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const payload = {
                title: formData.get('title'),
                author: formData.get('author'),
                published_year: parseInt(formData.get('published_year')),
                category_id: parseInt(formData.get('category_id')),
                description: formData.get('description') || null
            };

            try {
                const res = await fetch('/api/books', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                });

                if (res.status === 201) {
                    window.location.reload();
                } else {
                    const data = await res.json();
                    alert('Error en validación: ' + (data.message || JSON.stringify(data.errors)));
                }
            } catch (err) {
                alert('Ocurrió un error al procesar la solicitud.');
            }
        });

        document.getElementById('formStoreCategory')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const payload = { name: formData.get('name') };

            try {
                const res = await fetch('/api/categories', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                });

                if (res.status === 201) {
                    window.location.reload();
                } else {
                    const data = await res.json();
                    alert('Error: ' + (data.message || 'La categoría ya existe o es inválida'));
                }
            } catch (err) {
                alert('Ocurrió un error al registrar la categoría.');
            }
        });

        async function eliminarLibro(id) {
            if (!confirm('¿Confirma la desincorporación física y lógica de este ejemplar?')) return;

            try {
                const res = await fetch(`/api/books/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (res.status === 200) {
                    const row = document.getElementById(`book-row-${id}`);
                    if (row) row.remove();
                    window.location.reload();
                } else {
                    alert('No fue posible eliminar el registro.');
                }
            } catch (err) {
                alert('Error al comunicarse con el servidor.');
            }
        }
    </script>
</body>
</html>
