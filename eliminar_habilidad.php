<?php
require 'conexion.php';
session_start();
if (!isset($_SESSION['id_usuario'])) { header("Location: login.php"); exit; }

$id_usuario = $_SESSION['id_usuario'];
if (isset($_GET['id'])) {
    $id_habilidad = $_GET['id'];

    $stmt = $pdo->prepare("SELECT h.id_cv FROM habilidades h JOIN cvs c ON h.id_cv = c.id_cv WHERE h.id_habilidad = ? AND c.id_usuario = ?");
    $stmt->execute([$id_habilidad, $id_usuario]);
    $fila = $stmt->fetch();

    if ($fila) {
        $stmt = $pdo->prepare("DELETE FROM habilidades WHERE id_habilidad = ?");
        $stmt->execute([$id_habilidad]);
        header("Location: cv_detalle.php?id=" . $fila['id_cv']);
        exit;
    }
}
header("Location: panel.php");
exit;
?>
