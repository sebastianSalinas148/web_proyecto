<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acerca de nosotros - MiCV</title>
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

    <div class="form-container" style="width:600px;">
        <h2>¿Quienes somos?</h2>
        <p>MiCV es un proyecto academico que busca ayudar a estudiantes a construir su curriculum vitae de forma ordenada, sin necesidad de usar programas complicados.</p>
        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=500" style="width:100%; border-radius:6px; margin:10px 0;" alt="Equipo trabajando">
        <p>Con MiCV puedes registrar tu informacion, guardar varios curriculums y editarlos cuando lo necesites, todo desde un solo lugar.</p>
    </div>

    <footer>MiCV &copy; <?php echo date("Y"); ?> - Desarrollado por: Estudiante del curso</footer>
</body>
</html>
