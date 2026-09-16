<?php
require_once "config.php";

$mensaje = "";
$tipo = "success";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accion = $_POST["accion"] ?? "";

    if ($accion === "crear") {
        $nombre = trim($_POST["nombre"] ?? "");
        $identificacion = trim($_POST["identificacion"] ?? "");
        $telefono = trim($_POST["telefono"] ?? "");
        $direccion = trim($_POST["direccion"] ?? "");

        if ($nombre === "" || $identificacion === "") {
            $mensaje = "Nombre e identificación son obligatorios.";
            $tipo = "error";
        } else {
            $stmt = $conn->prepare("INSERT INTO estudiantes (nombre, identificacion, telefono, direccion) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombre, $identificacion, $telefono, $direccion);
            if ($stmt->execute()) {
                $mensaje = "Estudiante registrado correctamente.";
            } else {
                $mensaje = $stmt->errno == 1062 ? "La identificación ya está registrada." : "No se pudo registrar el estudiante.";
                $tipo = "error";
            }
            $stmt->close();
        }
    }

    if ($accion === "eliminar") {
        $id = intval($_POST["id"] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM estudiantes WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $mensaje = $stmt->affected_rows ? "Estudiante eliminado." : "No se encontró el estudiante.";
            $tipo = $stmt->affected_rows ? "success" : "error";
            $stmt->close();
        }
    }
}

$editar = null;
if (isset($_GET["editar"])) {
    $id = intval($_GET["editar"]);
    $stmt = $conn->prepare("SELECT * FROM estudiantes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $editar = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["accion"] ?? "") === "actualizar") {
    $id = intval($_POST["id"] ?? 0);
    $nombre = trim($_POST["nombre"] ?? "");
    $identificacion = trim($_POST["identificacion"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $direccion = trim($_POST["direccion"] ?? "");

    if ($id <= 0 || $nombre === "" || $identificacion === "") {
        $mensaje = "Nombre e identificación son obligatorios.";
        $tipo = "error";
    } else {
        $stmt = $conn->prepare("UPDATE estudiantes SET nombre=?, identificacion=?, telefono=?, direccion=? WHERE id=?");
        $stmt->bind_param("ssssi", $nombre, $identificacion, $telefono, $direccion, $id);
        if ($stmt->execute()) {
            $mensaje = "Estudiante actualizado correctamente.";
        } else {
            $mensaje = $stmt->errno == 1062 ? "La identificación ya está registrada." : "No se pudo actualizar el estudiante.";
            $tipo = "error";
        }
        $stmt->close();
        $editar = null;
    }
}

$buscar = trim($_GET["buscar"] ?? "");
if ($buscar !== "") {
    $like = "%" . $buscar . "%";
    $stmt = $conn->prepare("SELECT * FROM estudiantes WHERE nombre LIKE ? OR identificacion LIKE ? OR telefono LIKE ? ORDER BY id DESC");
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $estudiantes = $stmt->get_result();
} else {
    $estudiantes = $conn->query("SELECT * FROM estudiantes ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agenda de Estudiantes</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header class="hero">
    <div class="hero-inner">
        <div>
            <span class="eyebrow">GESTIÓN ACADÉMICA</span>
            <h1>Agenda de Estudiantes</h1>
            <p>Administra de forma sencilla los datos de tus estudiantes.</p>
        </div>
        <div class="hero-icon">🎓</div>
    </div>
</header>

<main class="container">
<?php if ($mensaje): ?>
<div class="alert <?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<section class="panel form-panel">
    <div class="section-title">
        <div>
            <span class="mini-label"><?= $editar ? "EDITAR REGISTRO" : "NUEVO REGISTRO" ?></span>
            <h2><?= $editar ? "Actualizar estudiante" : "Registrar estudiante" ?></h2>
        </div>
    </div>

    <form method="POST" class="form-grid">
        <input type="hidden" name="accion" value="<?= $editar ? "actualizar" : "crear" ?>">
        <?php if ($editar): ?><input type="hidden" name="id" value="<?= (int)$editar["id"] ?>"><?php endif; ?>

        <label>Nombre completo
            <input type="text" name="nombre" required maxlength="120" value="<?= htmlspecialchars($editar["nombre"] ?? "") ?>" placeholder="Ej. María González">
        </label>

        <label>Identificación
            <input type="text" name="identificacion" required maxlength="30" value="<?= htmlspecialchars($editar["identificacion"] ?? "") ?>" placeholder="Ej. 1234567890">
        </label>

        <label>Teléfono
            <input type="text" name="telefono" maxlength="30" value="<?= htmlspecialchars($editar["telefono"] ?? "") ?>" placeholder="Ej. 300 123 4567">
        </label>

        <label>Dirección
            <input type="text" name="direccion" maxlength="180" value="<?= htmlspecialchars($editar["direccion"] ?? "") ?>" placeholder="Ej. Calle 10 # 20-30">
        </label>

        <div class="form-actions">
            <button class="btn primary" type="submit"><?= $editar ? "Guardar cambios" : "Registrar estudiante" ?></button>
            <?php if ($editar): ?><a class="btn secondary" href="index.php">Cancelar</a><?php endif; ?>
        </div>
    </form>
</section>

<section class="panel">
    <div class="list-header">
        <div>
            <span class="mini-label">DIRECTORIO</span>
            <h2>Estudiantes registrados</h2>
        </div>
        <form class="search" method="GET">
            <input type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>" placeholder="Buscar estudiante...">
            <button class="btn secondary" type="submit">Buscar</button>
            <?php if ($buscar !== ""): ?><a class="clear" href="index.php">Limpiar</a><?php endif; ?>
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Nombre</th><th>Identificación</th><th>Teléfono</th><th>Dirección</th><th>Acciones</th></tr>
            </thead>
            <tbody>
            <?php if ($estudiantes && $estudiantes->num_rows > 0): ?>
                <?php while ($row = $estudiantes->fetch_assoc()): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row["nombre"]) ?></strong></td>
                    <td><?= htmlspecialchars($row["identificacion"]) ?></td>
                    <td><?= htmlspecialchars($row["telefono"]) ?></td>
                    <td><?= htmlspecialchars($row["direccion"]) ?></td>
                    <td class="actions">
                        <a class="action edit" href="?editar=<?= (int)$row["id"] ?>">Editar</a>
                        <form method="POST" onsubmit="return confirm('¿Deseas eliminar este estudiante?');">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?= (int)$row["id"] ?>">
                            <button class="action delete" type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" class="empty">No hay estudiantes para mostrar.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
</main>

<footer>Agenda de Estudiantes · PHP + MySQL · Preparada para AlwaysData</footer>
</body>
</html>