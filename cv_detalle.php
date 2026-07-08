<?php
require 'conexion.php';
session_start();
if (!isset($_SESSION['id_usuario'])) { header("Location: login.php"); exit; }

$id_usuario = $_SESSION['id_usuario'];

if (!isset($_GET['id'])) { header("Location: panel.php"); exit; }
$id_cv = $_GET['id'];

// verificamos que el cv sea del usuario logueado
$stmt = $pdo->prepare("SELECT * FROM cvs WHERE id_cv = ? AND id_usuario = ?");
$stmt->execute([$id_cv, $id_usuario]);
$cv = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$cv) { header("Location: panel.php"); exit; }

// CREATE educacion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_educacion'])) {
    $stmt = $pdo->prepare("INSERT INTO educacion (id_cv, institucion, carrera, grado, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id_cv, $_POST['institucion'], $_POST['carrera'], $_POST['grado'], $_POST['fecha_inicio'], $_POST['fecha_fin']]);
    header("Location: cv_detalle.php?id=$id_cv");
    exit;
}

// CREATE experiencia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_experiencia'])) {
    $stmt = $pdo->prepare("INSERT INTO experiencia (id_cv, empresa, cargo, descripcion, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id_cv, $_POST['empresa'], $_POST['cargo'], $_POST['descripcion'], $_POST['fecha_inicio'], $_POST['fecha_fin']]);
    header("Location: cv_detalle.php?id=$id_cv");
    exit;
}

// CREATE habilidad
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_habilidad'])) {
    $stmt = $pdo->prepare("INSERT INTO habilidades (id_cv, nombre, nivel) VALUES (?, ?, ?)");
    $stmt->execute([$id_cv, $_POST['nombre'], $_POST['nivel']]);
    header("Location: cv_detalle.php?id=$id_cv");
    exit;
}

// READ de las 3 secciones
$stmt = $pdo->prepare("SELECT * FROM educacion WHERE id_cv = ?");
$stmt->execute([$id_cv]);
$educacion = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM experiencia WHERE id_cv = ?");
$stmt->execute([$id_cv]);
$experiencia = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM habilidades WHERE id_cv = ?");
$stmt->execute([$id_cv]);
$habilidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="estilos.css">
    <title>Detalle CV - MiCV</title>
</head>
<body>
    <div class="menu-simple">
        <a href="index.php">Inicio</a>
        <a href="acerca.php">Acerca de nosotros</a>
        <a href="catalogo.php">Plantillas</a>
        <a href="contacto.php">Contactanos</a>
        <a href="logout.php">Salir</a>
    </div>

    <div class="form-container" style="width: 500px;">
        <h2><?php echo htmlspecialchars($cv['titulo']); ?></h2>
        <p>
            <a href="panel.php">Volver a mis CVs</a> |
            <a href="generar_pdf.php?id=<?php echo $id_cv; ?>" target="_blank">Descargar CV en PDF</a>
        </p>
        <hr>

        <h3>Educacion</h3>
        <form method="POST">
            <input type="text" name="institucion" placeholder="Institucion" required>
            <input type="text" name="carrera" placeholder="Carrera">
            <input type="text" name="grado" placeholder="Grado (Bachiller, Titulado, etc)">
            <input type="date" name="fecha_inicio">
            <input type="date" name="fecha_fin">
            <button type="submit" name="agregar_educacion">Agregar Educacion</button>
        </form>
        <?php foreach ($educacion as $e): ?>
            <div class="cv-item">
                <strong><?php echo htmlspecialchars($e['institucion']); ?></strong>
                <p style="margin:5px 0; color:#555;"><?php echo htmlspecialchars($e['carrera']); ?> - <?php echo htmlspecialchars($e['grado']); ?></p>
                <p style="margin:5px 0; color:#555;"><?php echo $e['fecha_inicio']; ?> a <?php echo $e['fecha_fin']; ?></p>
                <div class="acciones">
                    <a class="btn-warning" href="editar_educacion.php?id=<?php echo $e['id_educacion']; ?>">Editar</a>
                    <a class="btn-danger" href="eliminar_educacion.php?id=<?php echo $e['id_educacion']; ?>" onclick="return confirm('¿Eliminar este registro?')">Borrar</a>
                </div>
            </div>
        <?php endforeach; ?>

        <hr>
        <h3>Experiencia Laboral</h3>
        <form method="POST">
            <input type="text" name="empresa" placeholder="Empresa" required>
            <input type="text" name="cargo" placeholder="Cargo">
            <textarea name="descripcion" placeholder="Descripcion" rows="2"></textarea>
            <input type="date" name="fecha_inicio">
            <input type="date" name="fecha_fin">
            <button type="submit" name="agregar_experiencia">Agregar Experiencia</button>
        </form>
        <?php foreach ($experiencia as $ex): ?>
            <div class="cv-item">
                <strong><?php echo htmlspecialchars($ex['empresa']); ?></strong>
                <p style="margin:5px 0; color:#555;"><?php echo htmlspecialchars($ex['cargo']); ?></p>
                <p style="margin:5px 0; color:#555;"><?php echo htmlspecialchars($ex['descripcion']); ?></p>
                <p style="margin:5px 0; color:#555;"><?php echo $ex['fecha_inicio']; ?> a <?php echo $ex['fecha_fin']; ?></p>
                <div class="acciones">
                    <a class="btn-warning" href="editar_experiencia.php?id=<?php echo $ex['id_experiencia']; ?>">Editar</a>
                    <a class="btn-danger" href="eliminar_experiencia.php?id=<?php echo $ex['id_experiencia']; ?>" onclick="return confirm('¿Eliminar este registro?')">Borrar</a>
                </div>
            </div>
        <?php endforeach; ?>

        <hr>
        <h3>Habilidades</h3>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Habilidad (Ej: Excel)" required>
            <select name="nivel" style="width:100%; padding:10px; margin:8px 0; border:1px solid #ccc; border-radius:4px;">
                <option value="Basico">Basico</option>
                <option value="Intermedio">Intermedio</option>
                <option value="Avanzado">Avanzado</option>
            </select>
            <button type="submit" name="agregar_habilidad">Agregar Habilidad</button>
        </form>
        <?php foreach ($habilidades as $h): ?>
            <div class="cv-item">
                <strong><?php echo htmlspecialchars($h['nombre']); ?></strong> - <?php echo htmlspecialchars($h['nivel']); ?>
                <div class="acciones">
                    <a class="btn-warning" href="editar_habilidad.php?id=<?php echo $h['id_habilidad']; ?>">Editar</a>
                    <a class="btn-danger" href="eliminar_habilidad.php?id=<?php echo $h['id_habilidad']; ?>" onclick="return confirm('¿Eliminar esta habilidad?')">Borrar</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <footer>MiCV &copy; <?php echo date("Y"); ?> - Desarrollado por: sebastian salinas</footer>
</body>
</html>
