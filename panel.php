<?php
require 'conexion.php';
session_start();
if (!isset($_SESSION['id_usuario'])) { header("Location: login.php"); exit; }

$id_usuario = $_SESSION['id_usuario'];

// 1. CREATE: Procesar insercion de nuevo CV
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_cv'])) {
    $titulo = $_POST['titulo'];
    $perfil_profesional = $_POST['perfil_profesional'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $stmt = $pdo->prepare("INSERT INTO cvs (id_usuario, titulo, perfil_profesional, telefono, direccion) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id_usuario, $titulo, $perfil_profesional, $telefono, $direccion]);
    header("Location: panel.php");
    exit;
}

// 2. READ: Filtrar CVs que le pertenecen SOLO a este usuario
$stmt = $pdo->prepare("SELECT * FROM cvs WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$cvs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="estilos.css">
    <title>Mis CVs - MiCV</title>
</head>
<body>
    <div class="menu-simple">
        <a href="index.php">Inicio</a>
        <a href="acerca.php">Acerca de nosotros</a>
        <a href="catalogo.php">Plantillas</a>
        <a href="contacto.php">Contactanos</a>
        <a href="logout.php">Salir</a>
    </div>

    <div class="form-container" style="width: 460px;">
        <h2>Panel de <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></h2>
        <p><a href="logout.php">Cerrar Sesión</a></p>
        <hr>

        <h3>Agregar CV</h3>
        <form method="POST">
            <input type="text" name="titulo" placeholder="Titulo del CV" required>
            <textarea name="perfil_profesional" placeholder="Perfil profesional" rows="3"></textarea>
            <input type="text" name="telefono" placeholder="Telefono">
            <input type="text" name="direccion" placeholder="Direccion">
            <button type="submit" name="crear_cv">Guardar CV</button>
        </form>

        <hr>
        <h3>Mis CVs</h3>
        <?php if (empty($cvs)): ?>
            <p>No tienes CVs registrados.</p>
        <?php else: ?>
            <?php foreach ($cvs as $cv): ?>
                <div class="cv-item">
                    <strong><?php echo htmlspecialchars($cv['titulo']); ?></strong>
                    <p style="margin: 5px 0; color: #555;"><?php echo htmlspecialchars($cv['perfil_profesional']); ?></p>
                    <div class="acciones">
                        <a class="btn-warning" href="cv_detalle.php?id=<?php echo $cv['id_cv']; ?>">Ver / Editar secciones</a>
                        <a class="btn-warning" href="editar_cv.php?id=<?php echo $cv['id_cv']; ?>">Editar datos</a>
                        <a class="btn-danger" href="eliminar_cv.php?id=<?php echo $cv['id_cv']; ?>" onclick="return confirm('¿Eliminar este CV y todas sus secciones?')">Borrar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <footer>MiCV &copy; <?php echo date("Y"); ?> - Desarrollado por: Estudiante del curso</footer>
</body>
</html>
