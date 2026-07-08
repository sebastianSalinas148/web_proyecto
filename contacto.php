<?php
require 'conexion.php';
session_start();
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $consulta = $_POST['consulta'];
    $fecha = $_POST['fecha'];

    $stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS contactos (id INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(80), email VARCHAR(100), consulta TEXT, fecha DATE)");
    $stmt->execute();

    $stmt = $pdo->prepare("INSERT INTO contactos (nombre, email, consulta, fecha) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $email, $consulta, $fecha]);

    $mensaje = "Gracias, su mensaje fue enviado correctamente.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contactanos - MiCV</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <div class="menu-simple">
        <a href="index.php">Inicio</a>
        <a href="acerca.php">Acerca de nosotros</a>
        <a href="catalogo.php">Plantillas</a>
        <a href="contacto.php">Contactanos</a>
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <a href="panel.php">Mis CVs</a>
            <a href="logout.php">Salir</a>
        <?php else: ?>
            <a href="login.php">Salir</a>
        <?php endif; ?>
    </div>

    <div class="form-container">
        <h2>Contactanos</h2>
        <?php if($mensaje) echo "<p>$mensaje</p>"; ?>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="date" name="fecha">
            <textarea name="consulta" placeholder="Consulta" rows="3" required></textarea>
            <button type="submit">Enviar</button>
            <button type="reset" style="background-color:#6c757d; margin-top:5px;">Resetear</button>
        </form>
    </div>

    <footer>MiCV &copy; <?php echo date("Y"); ?> - Desarrollado por: sebastian salinas</footer>
</body>
</html>
