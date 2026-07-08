<?php
require 'conexion.php';
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $fila = $stmt->fetch();

    if ($fila && password_verify($password, $fila['password'])) {
        $_SESSION['id_usuario'] = $fila['id_usuario'];
        $_SESSION['usuario_nombre'] = $fila['nombre'];
        $_SESSION['usuario_login'] = $fila['usuario'];
        header("Location: panel.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="estilos.css">
    <title>Iniciar Sesion - MiCV</title>
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
        <h2>Iniciar Sesion</h2>
        <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Enviar</button>
            <button type="reset" style="background-color:#6c757d; margin-top:5px;">Resetear</button>
        </form>
        <p><a href="recuperar.php">¿Olvidaste tu contraseña?</a></p>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
    </div>

    <footer>MiCV &copy; <?php echo date("Y"); ?> - Desarrollado por: sebastian salinas</footer>
</body>
</html>
