<?php
require_once 'config.php';

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    try {
        if ($accion === 'crear') {
            $stmt = $pdo->prepare("INSERT INTO alumnos (nombre, identificacion, telefono, direccion) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                trim($_POST['nombre']),
                trim($_POST['identificacion']),
                trim($_POST['telefono']),
                trim($_POST['direccion'])
            ]);
            $mensaje = 'Alumno registrado correctamente.';
        } elseif ($accion === 'editar') {
            $stmt = $pdo->prepare("UPDATE alumnos SET nombre = ?, identificacion = ?, telefono = ?, direccion = ? WHERE id = ?");
            $stmt->execute([
                trim($_POST['nombre']),
                trim($_POST['identificacion']),
                trim($_POST['telefono']),
                trim($_POST['direccion']),
                (int) $_POST['id']
            ]);
            $mensaje = 'Alumno actualizado correctamente.';
        } elseif ($accion === 'eliminar') {
            $stmt = $pdo->prepare("DELETE FROM alumnos WHERE id = ?");
            $stmt->execute([(int) $_POST['id']]);
            $mensaje = 'Alumno eliminado correctamente.';
        }
    } catch (PDOException $e) {
        $error = 'No se pudo completar la operación. Verifique que la identificación no esté repetida.';
    }
}

$editar = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM alumnos WHERE id = ?");
    $stmt->execute([(int) $_GET['editar']]);
    $editar = $stmt->fetch();
}

$alumnos = $pdo->query("SELECT * FROM alumnos ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RepoAlumnos</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f3f6fb; color: #1f2937; }
        header { background: #1d4ed8; color: white; padding: 25px; text-align: center; }
        main { width: min(1100px, 94%); margin: 25px auto; }
        .card { background: white; padding: 22px; border-radius: 12px; box-shadow: 0 3px 12px #00000012; margin-bottom: 22px; }
        h1, h2 { margin-top: 0; }
        form { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
        label { font-weight: bold; display: flex; flex-direction: column; gap: 6px; }
        input { padding: 11px; border: 1px solid #cbd5e1; border-radius: 7px; font-size: 15px; }
        .full { grid-column: 1 / -1; }
        button, .btn { border: 0; border-radius: 7px; padding: 10px 14px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 14px; }
        button[type=submit] { background: #1d4ed8; color: white; }
        .cancel { background: #64748b; color: white; }
        .edit { background: #f59e0b; color: #111827; }
        .delete { background: #dc2626; color: white; }
        .mensaje { padding: 12px; background: #dcfce7; color: #166534; border-radius: 7px; margin-bottom: 15px; }
        .error { padding: 12px; background: #fee2e2; color: #991b1b; border-radius: 7px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 10px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        th { background: #eff6ff; }
        .acciones { display: flex; gap: 7px; flex-wrap: wrap; }
        @media (max-width: 700px) {
            form { grid-template-columns: 1fr; }
            .full { grid-column: auto; }
            .table-wrap { overflow-x: auto; }
            table { min-width: 760px; }
        }
    </style>
</head>
<body>
<header>
    <h1>RepoAlumnos</h1>
    <p>Gestión de alumnos</p>
</header>
<main>
    <?php if ($mensaje): ?><div class="mensaje"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <section class="card">
        <h2><?= $editar ? 'Editar alumno' : 'Registrar alumno' ?></h2>
        <form method="post">
            <input type="hidden" name="accion" value="<?= $editar ? 'editar' : 'crear' ?>">
            <?php if ($editar): ?><input type="hidden" name="id" value="<?= (int)$editar['id'] ?>"><?php endif; ?>

            <label>Nombre
                <input type="text" name="nombre" required maxlength="150" value="<?= htmlspecialchars($editar['nombre'] ?? '') ?>">
            </label>
            <label>Identificación
                <input type="text" name="identificacion" required maxlength="50" value="<?= htmlspecialchars($editar['identificacion'] ?? '') ?>">
            </label>
            <label>Teléfono
                <input type="text" name="telefono" required maxlength="30" value="<?= htmlspecialchars($editar['telefono'] ?? '') ?>">
            </label>
            <label>Dirección de residencia
                <input type="text" name="direccion" required maxlength="255" value="<?= htmlspecialchars($editar['direccion'] ?? '') ?>">
            </label>
            <div class="full">
                <button type="submit"><?= $editar ? 'Actualizar alumno' : 'Guardar alumno' ?></button>
                <?php if ($editar): ?><a class="btn cancel" href="index.php">Cancelar</a><?php endif; ?>
            </div>
        </form>
    </section>

    <section class="card">
        <h2>Listado de alumnos</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Identificación</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!$alumnos): ?>
                    <tr><td colspan="6">No hay alumnos registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($alumnos as $alumno): ?>
                    <tr>
                        <td><?= (int)$alumno['id'] ?></td>
                        <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                        <td><?= htmlspecialchars($alumno['identificacion']) ?></td>
                        <td><?= htmlspecialchars($alumno['telefono']) ?></td>
                        <td><?= htmlspecialchars($alumno['direccion']) ?></td>
                        <td class="acciones">
                            <a class="btn edit" href="?editar=<?= (int)$alumno['id'] ?>">Editar</a>
                            <form method="post" style="display:inline;" onsubmit="return confirm('¿Desea eliminar este alumno?');">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="id" value="<?= (int)$alumno['id'] ?>">
                                <button class="delete" type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
</body>
</html>
