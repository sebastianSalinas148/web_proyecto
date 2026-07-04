<?php
require 'conexion.php';
session_start();
if (!isset($_SESSION['id_usuario'])) { header("Location: login.php"); exit; }

$id_usuario = $_SESSION['id_usuario'];
if (!isset($_GET['id'])) { header("Location: panel.php"); exit; }
$id_experiencia = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE experiencia e JOIN cvs c ON e.id_cv = c.id_cv
                            SET e.empresa = ?, e.cargo = ?, e.descripcion = ?, e.fecha_inicio = ?, e.fecha_fin = ?
                            WHERE e.id_experiencia = ? AND c.id_usuario = ?");
    $stmt->execute([$_POST['empresa'], $_POST['cargo'], $_POST['descripcion'], $_POST['fecha_inicio'], $_POST['fecha_fin'], $id_experiencia, $id_usuario]);

    $stmt2 = $pdo->prepare("SELECT id_cv FROM experiencia WHERE id_experiencia = ?");
    $stmt2->execute([$id_experiencia]);
    $fila = $stmt2->fetch();
    header("Location: cv_detalle.php?id=" . $fila['id_cv']);
    exit;
}

$stmt = $pdo->prepare("SELECT e.* FROM experiencia e JOIN cvs c ON e.id_cv = c.id_cv WHERE e.id_experiencia = ? AND c.id_usuario = ?");
$stmt->execute([$id_experiencia, $id_usuario]);
$exp = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$exp) { header("Location: panel.php"); exit; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="estilos.css">
    <title>Editar Experiencia - MiCV</title>
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
        <h2>Editar Experiencia</h2>
        <form method="POST">
            <input type="text" name="empresa" value="<?php echo htmlspecialchars($exp['empresa']); ?>" required>
            <input type="text" name="cargo" value="<?php echo htmlspecialchars($exp['cargo']); ?>">
            <textarea name="descripcion" rows="3"><?php echo htmlspecialchars($exp['descripcion']); ?></textarea>
            <input type="date" name="fecha_inicio" value="<?php echo $exp['fecha_inicio']; ?>">
            <input type="date" name="fecha_fin" value="<?php echo $exp['fecha_fin']; ?>">
            <button type="submit">Actualizar</button>
        </form>
        <p><a href="cv_detalle.php?id=<?php echo $exp['id_cv']; ?>">Volver al CV</a></p>
    </div>

    <footer>MiCV &copy; <?php echo date("Y"); ?> - Desarrollado por: Estudiante del curso</footer>
</body>
</html>
