<?php
require 'conexion.php';
session_start();
if (!isset($_SESSION['id_usuario'])) { header("Location: login.php"); exit; }

$id_usuario = $_SESSION['id_usuario'];
if (isset($_GET['id'])) {
    $id_experiencia = $_GET['id'];

    $stmt = $pdo->prepare("SELECT e.id_cv FROM experiencia e JOIN cvs c ON e.id_cv = c.id_cv WHERE e.id_experiencia = ? AND c.id_usuario = ?");
    $stmt->execute([$id_experiencia, $id_usuario]);
    $fila = $stmt->fetch();

    if ($fila) {
        $stmt = $pdo->prepare("DELETE FROM experiencia WHERE id_experiencia = ?");
        $stmt->execute([$id_experiencia]);
        header("Location: cv_detalle.php?id=" . $fila['id_cv']);
        exit;
    }
}
header("Location: panel.php");
exit;
?>
