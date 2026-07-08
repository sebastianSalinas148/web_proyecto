<?php
require 'conexion.php';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $correo = $_POST['correo'];
    $usuario = $_POST['usuario'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellidos, fecha_nacimiento, correo, usuario, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $apellidos, $fecha_nacimiento, $correo, $usuario, $password]);
        $mensaje = "Usuario registrado con éxito. <a href='login.php'>Iniciar sesión</a>";
    } catch (PDOException $e) {
        $mensaje = "Error: el usuario o correo ya existe.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="estilos.css">
    <title>Registro - MiCV</title>
</head>
<body>
    <div class="menu-simple">
        <a href="index.php">Inicio</a>
        <a href="acerca.php">Acerca de nosotros</a>
        <a href="catalogo.php">Plantillas</a>
        <a href="contacto.php">Contactanos</a>
        <a href="login.php">Salir</a>
    </div>

    <div class="form-container" style="width:380px;">
        <h2>Registro</h2>
        <?php if($mensaje) echo "<p>$mensaje</p>"; ?>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellidos" placeholder="Apellidos" required>
            <input type="date" name="fecha_nacimiento" required>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required minlength="6">
            <button type="submit">Enviar</button>
            <button type="reset" style="background-color:#6c757d; margin-top:5px;">Resetear</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Loguéate aquí</a></p>
    </div>

    <footer>MiCV &copy; <?php echo date("Y"); ?> - Desarrollado por: sebastian salinas</footer>
</body>
</html>
