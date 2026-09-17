<?php
/**
 * RepoAlumnos - Gestión de Alumnos
 * Listado principal y formulario de alta/edición
 */

require_once 'config.php';

$pdo = getConnection();
$mensaje = '';
$tipoMensaje = '';
$alumnoEditar = null;

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'crear' || $accion === 'actualizar') {
        $nombre = trim($_POST['nombre'] ?? '');
        $identificacion = trim($_POST['identificacion'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');

        if (empty($nombre) || empty($identificacion)) {
            $mensaje = 'El nombre y la identificación son obligatorios.';
            $tipoMensaje = 'error';
        } else {
            try {
                if ($accion === 'crear') {
                    $stmt = $pdo->prepare("
                        INSERT INTO alumnos (nombre, identificacion, telefono, direccion)
                        VALUES (?, ?, ?, ?)
                    ");
                    $stmt->execute([$nombre, $identificacion, $telefono ?: null, $direccion ?: null]);
                    $mensaje = 'Alumno registrado correctamente.';
                    $tipoMensaje = 'success';
                } else {
                    $id = (int)($_POST['id'] ?? 0);
                    $stmt = $pdo->prepare("
                        UPDATE alumnos
                        SET nombre = ?, identificacion = ?, telefono = ?, direccion = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([$nombre, $identificacion, $telefono ?: null, $direccion ?: null, $id]);
                    $mensaje = 'Alumno actualizado correctamente.';
                    $tipoMensaje = 'success';
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $mensaje = 'Ya existe un alumno con esa identificación.';
                } else {
                    $mensaje = 'Error al guardar: ' . htmlspecialchars($e->getMessage());
                }
                $tipoMensaje = 'error';
            }
        }
    }
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    try {
        $stmt = $pdo->prepare("DELETE FROM alumnos WHERE id = ?");
        $stmt->execute([$id]);
        $mensaje = 'Alumno eliminado correctamente.';
        $tipoMensaje = 'success';
    } catch (PDOException $e) {
        $mensaje = 'Error al eliminar: ' . htmlspecialchars($e->getMessage());
        $tipoMensaje = 'error';
    }
}

// Cargar alumno para editar
if (isset($_GET['editar'])) {
    $id = (int)$_GET['editar'];
    $stmt = $pdo->prepare("SELECT * FROM alumnos WHERE id = ?");
    $stmt->execute([$id]);
    $alumnoEditar = $stmt->fetch();
}

// Listar alumnos
$busqueda = trim($_GET['q'] ?? '');
if ($busqueda !== '') {
    $stmt = $pdo->prepare("
        SELECT * FROM alumnos
        WHERE nombre LIKE ? OR identificacion LIKE ? OR telefono LIKE ? OR direccion LIKE ?
        ORDER BY nombre ASC
    ");
    $like = '%' . $busqueda . '%';
    $stmt->execute([$like, $like, $like, $like]);
} else {
    $stmt = $pdo->query("SELECT * FROM alumnos ORDER BY nombre ASC");
}
$alumnos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RepoAlumnos - Gestión de Alumnos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📚 RepoAlumnos</h1>
            <p class="subtitle">Sistema de gestión de alumnos</p>
        </header>

        <?php if ($mensaje): ?>
            <div class="alert alert-<?= $tipoMensaje ?>">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <div class="grid">
            <!-- Formulario -->
            <section class="card form-card">
                <h2><?= $alumnoEditar ? '✏️ Editar Alumno' : '➕ Nuevo Alumno' ?></h2>
                <form method="POST" action="index.php">
                    <input type="hidden" name="accion" value="<?= $alumnoEditar ? 'actualizar' : 'crear' ?>">
                    <?php if ($alumnoEditar): ?>
                        <input type="hidden" name="id" value="<?= (int)$alumnoEditar['id'] ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="nombre">Nombre completo *</label>
                        <input type="text" id="nombre" name="nombre" required
                               value="<?= htmlspecialchars($alumnoEditar['nombre'] ?? '') ?>"
                               placeholder="Ej: Juan Pérez">
                    </div>

                    <div class="form-group">
                        <label for="identificacion">Identificación *</label>
                        <input type="text" id="identificacion" name="identificacion" required
                               value="<?= htmlspecialchars($alumnoEditar['identificacion'] ?? '') ?>"
                               placeholder="Cédula / DNI / Pasaporte">
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" id="telefono" name="telefono"
                               value="<?= htmlspecialchars($alumnoEditar['telefono'] ?? '') ?>"
                               placeholder="Ej: 3001234567">
                    </div>

                    <div class="form-group">
                        <label for="direccion">Dirección de residencia</label>
                        <textarea id="direccion" name="direccion" rows="3"
                                  placeholder="Calle, número, barrio, ciudad..."><?= htmlspecialchars($alumnoEditar['direccion'] ?? '') ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?= $alumnoEditar ? 'Guardar cambios' : 'Registrar alumno' ?>
                        </button>
                        <?php if ($alumnoEditar): ?>
                            <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </section>

            <!-- Listado -->
            <section class="card list-card">
                <div class="list-header">
                    <h2>📋 Listado de Alumnos</h2>
                    <form method="GET" class="search-form">
                        <input type="text" name="q" placeholder="Buscar..."
                               value="<?= htmlspecialchars($busqueda) ?>">
                        <button type="submit" class="btn btn-sm">Buscar</button>
                        <?php if ($busqueda !== ''): ?>
                            <a href="index.php" class="btn btn-sm btn-secondary">Limpiar</a>
                        <?php endif; ?>
                    </form>
                </div>

                <?php if (count($alumnos) === 0): ?>
                    <p class="empty">No hay alumnos registrados<?= $busqueda ? ' con ese criterio' : '' ?>.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Identificación</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alumnos as $a): ?>
                                    <tr>
                                        <td data-label="Nombre"><?= htmlspecialchars($a['nombre']) ?></td>
                                        <td data-label="Identificación"><?= htmlspecialchars($a['identificacion']) ?></td>
                                        <td data-label="Teléfono"><?= htmlspecialchars($a['telefono'] ?? '—') ?></td>
                                        <td data-label="Dirección"><?= htmlspecialchars($a['direccion'] ?? '—') ?></td>
                                        <td data-label="Acciones" class="actions">
                                            <a href="?editar=<?= (int)$a['id'] ?>" class="btn btn-sm btn-edit" title="Editar">✏️</a>
                                            <a href="?eliminar=<?= (int)$a['id'] ?>"
                                               class="btn btn-sm btn-delete"
                                               title="Eliminar"
                                               onclick="return confirm('¿Eliminar a <?= htmlspecialchars(addslashes($a['nombre'])) ?>?')">🗑️</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <p class="total"><?= count($alumnos) ?> alumno(s) encontrado(s)</p>
                <?php endif; ?>
            </section>
        </div>

        <footer>
            <p>RepoAlumnos &copy; <?= date('Y') ?> — Gestión sencilla de alumnos</p>
        </footer>
    </div>
</body>
</html>
