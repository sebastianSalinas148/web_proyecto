<?php
require 'conexion.php';
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plantillas - MiCV</title>
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

    <h2 style="text-align:center;">Nuestras Plantillas</h2>

    <div class="tarjetas">
        <div class="tarjeta">
            <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=300" alt="Plantilla clasica">
            <div>Plantilla Clasica<br>Ideal para puestos administrativos</div>
        </div>
        <div class="tarjeta">
            <img src="https://images.unsplash.com/photo-1586282391129-76a6df230234?w=300" alt="Plantilla moderna">
            <div>Plantilla Moderna<br>Ideal para areas creativas</div>
        </div>
        <div class="tarjeta">
            <img src="https://images.unsplash.com/photo-1519337265831-281ec6cc8514?w=300" alt="Plantilla minimalista">
            <div>Plantilla Minimalista<br>Facil de leer por reclutadores</div>
        </div>
        <div class="tarjeta">
            <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=300" alt="Plantilla tecnica">
            <div>Plantilla Tecnica<br>Enfocada en habilidades tecnicas</div>
        </div>
    </div>

    <footer>MiCV &copy; <?php echo date("Y"); ?> - Desarrollado por: Estudiante del curso</footer>
</body>
</html>
