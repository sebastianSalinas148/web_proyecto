<?php
require 'conexion.php';
session_start();
if (!isset($_SESSION['id_usuario'])) { header("Location: login.php"); exit; }

$id_usuario = $_SESSION['id_usuario'];
if (!isset($_GET['id'])) { header("Location: panel.php"); exit; }
$id_habilidad = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE habilidades h JOIN cvs c ON h.id_cv = c.id_cv
                            SET h.nombre = ?, h.nivel = ?
                      WHERE h.id_habilidad = ? AND c.id_usuario = ?");
    $stmt->execute([$_POST['nombre'], $_POST['nivel'], $id_habilidad, $id_usuario]);

    $stmt2 = $pdo->prepare("SELECT id_cv FROM habilidades WHERE id_habilidad = ?");
    $stmt2->execute([$id_habilidad]);
    $fila = $stmt2->fetch();
    header("Location: cv_detalle.php?id=" . $fila['id_cv']);
    exit;
}

$stmt = $pdo->prepare("SELECT h.* FROM habilidades h JOIN cvs c ON h.id_cv = c.id_cv WHERE h.id_habilidad = ? AND c.id_usuario = ?");
$stmt->execute([$id_habilidad, $id_usuario]);
$hab = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$hab) { header("Location: panel.php"); exit; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="estilos.css">
    <title>Editar Habilidad - MiCV</title>
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
        <h2>Editar Habilidad</h2>
        <form method="POST">
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($hab['nombre']); ?>" required>
            <select name="nivel" style="width:100%; padding:10px; margin:8px 0; border:1px solid #ccc; border-radius:4px;">
                <option value="Basico" <?php if ($hab['nivel']=='Basico') echo 'selected'; ?>>Basico</option>
                <option value="Intermedio" <?php if ($hab['nivel']=='Intermedio') echo 'selected'; ?>>Intermedio</option>
                <option value="Avanzado" <?php if ($hab['nivel']=='Avanzado') echo 'selected'; ?>>Avanzado</option>
            </select>
            <button type="submit">Actualizar</button>
        </form>
        <p><a href="cv_detalle.php?id=<?php echo $hab['id_cv']; ?>">Volver al CV</a></p>
    </div>

    <footer>MiCV &copy; <?php echo date("Y"); ?> - Desarrollado por: Estudiante del curso</footer>
</body>
</html>
