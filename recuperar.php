<?php
require 'conexion.php';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    $fila = $stmt->fetch();

    // Por seguridad no decimos si el correo existe o no.
    // (no se configuro servidor de correo, esto es solo simulado)
    $mensaje = "Si el correo existe, se enviaran las instrucciones a su bandeja de entrada.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="estilos.css">
    <title>Recuperar Contraseña - MiCV</title>
</head>
<body>
    <div class="menu-simple">
        <a href="index.php">Inicio</a>
        <a href="acerca.php">Acerca de nosotros</a>
        <a href="catalogo.php">Plantillas</a>
        <a href="contacto.php">Contactanos</a>
        <a href="login.php">Salir</a>
    </div>

    <div class="form-container">
        <h2>Recuperar Contraseña</h2>
        <?php if($mensaje) echo "<p>$mensaje</p>"; ?>
        <form method="POST">
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <button type="submit">Enviar</button>
            <button type="reset" style="background-color:#6c757d; margin-top:5px;">Resetear</button>
        </form>
        <p><a href="login.php">Volver al inicio de sesion</a></p>
    </div>

    <footer>MiCV &copy; <?php echo date("Y"); ?> - Desarrollado por: Estudiante del curso</footer>
</body>
</html>
