<?php
require 'conexion.php';
session_start();
if (!isset($_SESSION['id_usuario'])) { header("Location: login.php"); exit; }

$id_usuario = $_SESSION['id_usuario'];

if (!isset($_GET['id'])) { header("Location: panel.php"); exit; }
$id_cv = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $perfil_profesional = $_POST['perfil_profesional'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $stmt = $pdo->prepare("UPDATE cvs SET titulo = ?, perfil_profesional = ?, telefono = ?, direccion = ? WHERE id_cv = ? AND id_usuario = ?");
    $stmt->execute([$titulo, $perfil_profesional, $telefono, $direccion, $id_cv, $id_usuario]);
    header("Location: panel.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM cvs WHERE id_cv = ? AND id_usuario = ?");
$stmt->execute([$id_cv, $id_usuario]);
$cv = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cv) { header("Location: panel.php"); exit; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="estilos.css">
    <title>Editar CV - MiCV</title>
</head>
<body>
    <div class="menu-simple">
        <a href="index.php">Inicio</a>
        <a href="acerca.php">Acerca de nosotros</a>
        <a href="catalogo.php">Plantillas</a>
        <a href="contacto.php">Contactanos</a>
        <a href="logout.php">Salir</a>
    </div>

    <div class="form-container" style="width: 380px;">
        <h2>Editar CV</h2>
        <form method="POST">
            <input type="text" name="titulo" value="<?php echo htmlspecialchars($cv['titulo']); ?>" required>
            <textarea name="perfil_profesional" rows="3"><?php echo htmlspecialchars($cv['perfil_profesional']); ?></textarea>
            <input type="text" name="telefono" value="<?php echo htmlspecialchars($cv['telefono']); ?>">
            <input type="text" name="direccion" value="<?php echo htmlspecialchars($cv['direccion']); ?>">
            <button type="submit">Actualizar</button>
        </form>
        <p><a href="panel.php">Volver a mis CVs</a></p>
    </div>

    <footer>MiCV &copy; <?php echo date("Y"); ?> - Desarrollado por: Estudiante del curso</footer>
</body>
</html>
