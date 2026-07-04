<?php
require 'conexion.php';
session_start();
if (!isset($_SESSION['id_usuario'])) { header("Location: login.php"); exit; }

$id_usuario = $_SESSION['id_usuario'];
if (isset($_GET['id'])) {
    $id_educacion = $_GET['id'];

    $stmt = $pdo->prepare("SELECT e.id_cv FROM educacion e JOIN cvs c ON e.id_cv = c.id_cv WHERE e.id_educacion = ? AND c.id_usuario = ?");
    $stmt->execute([$id_educacion, $id_usuario]);
    $fila = $stmt->fetch();

    if ($fila) {
        $stmt = $pdo->prepare("DELETE FROM educacion WHERE id_educacion = ?");
        $stmt->execute([$id_educacion]);
        header("Location: cv_detalle.php?id=" . $fila['id_cv']);
        exit;
    }
}
header("Location: panel.php");
exit;
?>
