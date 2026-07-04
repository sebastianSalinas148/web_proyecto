<?php
require 'conexion.php';
session_start();
if (!isset($_SESSION['id_usuario'])) { header("Location: login.php"); exit; }

$id_usuario = $_SESSION['id_usuario'];
if (!isset($_GET['id'])) { header("Location: panel.php"); exit; }
$id_educacion = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE educacion e JOIN cvs c ON e.id_cv = c.id_cv
                            SET e.institucion = ?, e.carrera = ?, e.grado = ?, e.fecha_inicio = ?, e.fecha_fin = ?
                            WHERE e.id_educacion = ? AND c.id_usuario = ?");
    $stmt->execute([$_POST['institucion'], $_POST['carrera'], $_POST['grado'], $_POST['fecha_inicio'], $_POST['fecha_fin'], $id_educacion, $id_usuario]);

    $stmt2 = $pdo->prepare("SELECT id_cv FROM educacion WHERE id_educacion = ?");
    $stmt2->execute([$id_educacion]);
    $fila = $stmt2->fetch();
    header("Location: cv_detalle.php?id=" . $fila['id_cv']);
    exit;
}

$stmt = $pdo->prepare("SELECT e.* FROM educacion e JOIN cvs c ON e.id_cv = c.id_cv WHERE e.id_educacion = ? AND c.id_usuario = ?");
$stmt->execute([$id_educacion, $id_usuario]);
$edu = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$edu) { header("Location: panel.php"); exit; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="estilos.css">
    <title>Editar Educacion - MiCV</title>
</head>
<body>
    <div class="menu-simple">
        <a href="index.php">Inicio</a>
        <a href="acerca.php">Acerca de nosotros</a>
        <a href="catalogo.php">Plantillas</a>
        <a href="contacto.php">Contactanos</a>
        <a href="logout.php">Salir</a>
    </div>

    <div class="form-container">
        <h2>Editar Educacion</h2>
        <form method="POST">
            <input type="text" name="institucion" value="<?php echo htmlspecialchars($edu['institucion']); ?>" required>
            <input type="text" name="carrera" value="<?php echo htmlspecialchars($edu['carrera']); ?>">
            <input type="text" name="grado" value="<?php echo htmlspecialchars($edu['grado']); ?>">
            <input type="date" name="fecha_inicio" value="<?php echo $edu['fecha_inicio']; ?>">
            <input type="date" name="fecha_fin" value="<?php echo $edu['fecha_fin']; ?>">
            <button type="submit">Actualizar</button>
        </form>
        <p><a href="cv_detalle.php?id=<?php echo $edu['id_cv']; ?>">Volver al CV</a></p>
    </div>

    <footer>MiCV &copy; <?php echo date("Y"); ?> - Desarrollado por: Estudiante del curso</footer>
</body>
</html>
