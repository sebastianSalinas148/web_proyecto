<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiCV - Crea tu curriculum vitae</title>
    <meta name="description" content="MiCV es una plataforma para crear y administrar tu curriculum vitae.">
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <div class="encabezado">
        <img src="https://cdn-icons-png.flaticon.com/512/1077/1077114.png" alt="Logo MiCV">
        <span>MiCV</span>
    </div>

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

    <div class="carrusel" id="carrusel">
        <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=1000" class="activa" alt="Entrevista de trabajo">
        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1000" alt="Personas trabajando">
        <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=1000" alt="Persona firmando documentos">
    </div>

    <div class="form-container" style="width:500px; text-align:center;">
        <h2>Bienvenido a MiCV</h2>
        <p>Registra tu informacion, crea tu curriculum vitae y adminístralo cuando quieras. Rápido, simple y gratuito.</p>
        <a href="registro.php"><button type="button">Registrate Gratis</button></a>
    </div>

    <footer>MiCV &copy; <?php echo date("Y"); ?> - Desarrollado por: Estudiante del curso</footer>

    <script>
        // carrusel simple: cambia de imagen cada 3 segundos
        let imgs = document.querySelectorAll("#carrusel img");
        let actual = 0;
        setInterval(function () {
            imgs[actual].classList.remove("activa");
            actual = (actual + 1) % imgs.length;
            imgs[actual].classList.add("activa");
        }, 3000);
    </script>
</body>
</html>
