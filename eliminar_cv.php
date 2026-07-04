<?php
require 'conexion.php';
session_start();
if (!isset($_SESSION['id_usuario'])) { header("Location: login.php"); exit; }

if (isset($_GET['id'])) {
    $id_cv = $_GET['id'];
    $id_usuario = $_SESSION['id_usuario'];

    $stmt = $pdo->prepare("DELETE FROM cvs WHERE id_cv = ? AND id_usuario = ?");
    $stmt->execute([$id_cv, $id_usuario]);
}

header("Location: panel.php");
exit;
?>
