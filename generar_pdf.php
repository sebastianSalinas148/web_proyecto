<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
ob_start();

require 'conexion.php';
require_once __DIR__ . '/fpdf.php';
session_start();
if (!isset($_SESSION['id_usuario'])) { header("Location: login.php"); exit; }

$id_usuario = $_SESSION['id_usuario'];
if (!isset($_GET['id'])) { header("Location: panel.php"); exit; }
$id_cv = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM cvs WHERE id_cv = ? AND id_usuario = ?");
$stmt->execute([$id_cv, $id_usuario]);
$cv = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$cv) { header("Location: panel.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM educacion WHERE id_cv = ?");
$stmt->execute([$id_cv]);
$educacion = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM experiencia WHERE id_cv = ?");
$stmt->execute([$id_cv]);
$experiencia = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM habilidades WHERE id_cv = ?");
$stmt->execute([$id_cv]);
$habilidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

function fecha_corta($f) {
    if (empty($f) || $f == '0000-00-00') return 'Actualidad';
    $partes = explode('-', $f);
    return $partes[1] . '/' . $partes[0];
}

function texto_pdf($texto) {
    if ($texto === null) {
        return '';
    }

    if (function_exists('mb_convert_encoding')) {
        return mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8');
    }

    if (function_exists('iconv')) {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $texto);
    }

    return utf8_encode($texto);
}

// ===================== Clase PDF personalizada =====================
class CVPdf extends FPDF {
    // ancho de la barra lateral izquierda
    public $anchoBarra = 65;

    function Header() {
        // franja superior de color (azul oscuro)
        $this->SetFillColor(44, 62, 80);
        $this->Rect(0, 0, 210, 38, 'F');
    }
}

$pdf = new CVPdf();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 15);

// ---------- Encabezado con nombre y puesto ----------
$pdf->SetTextColor(255, 255, 255);
$pdf->SetXY(10, 10);
$pdf->SetFont('Arial', 'B', 22);
$pdf->Cell(0, 10, texto_pdf(strtoupper($usuario['nombre'] . ' ' . $usuario['apellidos'])), 0, 1);

$pdf->SetX(10);
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(210, 220, 230);
$pdf->Cell(0, 8, texto_pdf($cv['titulo']), 0, 1);

// ---------- Barra lateral (fondo gris claro) ----------
$pdf->SetFillColor(240, 242, 245);
$pdf->Rect(0, 38, $pdf->anchoBarra, 260, 'F');

$margenBarra = 8;
$anchoTextoBarra = $pdf->anchoBarra - ($margenBarra * 2);

$pdf->SetXY($margenBarra, 46);
$pdf->SetTextColor(44, 62, 80);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell($anchoTextoBarra, 7, 'CONTACTO', 0, 1);
$pdf->SetDrawColor(52, 152, 219);
$pdf->Line($margenBarra, $pdf->GetY(), $margenBarra + $anchoTextoBarra, $pdf->GetY());
$pdf->Ln(2);

$pdf->SetFont('Arial', '', 9.5);
$pdf->SetTextColor(60, 60, 60);
$pdf->SetX($margenBarra);
$pdf->MultiCell($anchoTextoBarra, 5.5, texto_pdf("Tel: " . $cv['telefono']));
$pdf->SetX($margenBarra);
$pdf->MultiCell($anchoTextoBarra, 5.5, texto_pdf("Correo: " . $usuario['correo']));
$pdf->SetX($margenBarra);
$pdf->MultiCell($anchoTextoBarra, 5.5, texto_pdf("Direccion: " . $cv['direccion']));
$pdf->Ln(4);

// habilidades en la barra lateral
$pdf->SetX($margenBarra);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(44, 62, 80);
$pdf->Cell($anchoTextoBarra, 7, 'HABILIDADES', 0, 1);
$pdf->SetX($margenBarra);
$pdf->Line($margenBarra, $pdf->GetY(), $margenBarra + $anchoTextoBarra, $pdf->GetY());
$pdf->Ln(2);

$nivelesBarra = ['Basico' => 0.4, 'Intermedio' => 0.7, 'Avanzado' => 1.0];
foreach ($habilidades as $h) {
    $pdf->SetX($margenBarra);
    $pdf->SetFont('Arial', '', 9.5);
    $pdf->SetTextColor(60, 60, 60);
    $pdf->Cell($anchoTextoBarra, 5, texto_pdf($h['nombre']), 0, 1);

    // barrita de nivel
    $pdf->SetX($margenBarra);
    $pct = isset($nivelesBarra[$h['nivel']]) ? $nivelesBarra[$h['nivel']] : 0.5;
    $pdf->SetFillColor(220, 220, 220);
    $pdf->Rect($margenBarra, $pdf->GetY(), $anchoTextoBarra, 2.5, 'F');
    $pdf->SetFillColor(52, 152, 219);
    $pdf->Rect($margenBarra, $pdf->GetY(), $anchoTextoBarra * $pct, 2.5, 'F');
    $pdf->Ln(6);
}

// ---------- Columna principal (derecha) ----------
$margenPrincipal = $pdf->anchoBarra + 8;
$anchoPrincipal = 210 - $margenPrincipal - 10;

function tituloSeccion($pdf, $texto, $margen, $ancho) {
    $pdf->SetX($margen);
    $pdf->SetFont('Arial', 'B', 13);
    $pdf->SetTextColor(44, 62, 80);
    $pdf->Cell($ancho, 8, texto_pdf($texto), 0, 1);
    $pdf->SetDrawColor(52, 152, 219);
    $pdf->SetLineWidth(0.6);
    $pdf->Line($margen, $pdf->GetY(), $margen + $ancho, $pdf->GetY());
    $pdf->SetLineWidth(0.2);
    $pdf->Ln(3);
}

$pdf->SetY(46);

// Perfil profesional
tituloSeccion($pdf, 'PERFIL PROFESIONAL', $margenPrincipal, $anchoPrincipal);
$pdf->SetX($margenPrincipal);
$pdf->SetFont('Arial', '', 10.5);
$pdf->SetTextColor(70, 70, 70);
$pdf->MultiCell($anchoPrincipal, 5.5, texto_pdf($cv['perfil_profesional']));
$pdf->Ln(6);

// Experiencia laboral
tituloSeccion($pdf, 'EXPERIENCIA LABORAL', $margenPrincipal, $anchoPrincipal);
foreach ($experiencia as $ex) {
    $pdf->SetX($margenPrincipal);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetTextColor(40, 40, 40);
    $pdf->Cell($anchoPrincipal * 0.7, 6, texto_pdf($ex['cargo']), 0, 0);

    $pdf->SetFont('Arial', 'I', 9.5);
    $pdf->SetTextColor(120, 120, 120);
    $pdf->Cell($anchoPrincipal * 0.3, 6, texto_pdf(fecha_corta($ex['fecha_inicio']) . ' - ' . fecha_corta($ex['fecha_fin'])), 0, 1, 'R');

    $pdf->SetX($margenPrincipal);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(52, 152, 219);
    $pdf->Cell($anchoPrincipal, 5, texto_pdf($ex['empresa']), 0, 1);

    $pdf->SetX($margenPrincipal);
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetTextColor(70, 70, 70);
    $pdf->MultiCell($anchoPrincipal, 5, texto_pdf($ex['descripcion']));
    $pdf->Ln(4);
}
$pdf->Ln(2);

// Educacion
tituloSeccion($pdf, 'EDUCACION', $margenPrincipal, $anchoPrincipal);
foreach ($educacion as $e) {
    $pdf->SetX($margenPrincipal);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetTextColor(40, 40, 40);
    $pdf->Cell($anchoPrincipal * 0.7, 6, texto_pdf($e['carrera']), 0, 0);

    $pdf->SetFont('Arial', 'I', 9.5);
    $pdf->SetTextColor(120, 120, 120);
    $pdf->Cell($anchoPrincipal * 0.3, 6, texto_pdf(fecha_corta($e['fecha_inicio']) . ' - ' . fecha_corta($e['fecha_fin'])), 0, 1, 'R');

    $pdf->SetX($margenPrincipal);
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetTextColor(70, 70, 70);
    $pdf->Cell($anchoPrincipal, 5, texto_pdf($e['institucion'] . ' - ' . $e['grado']), 0, 1);
    $pdf->Ln(4);
}

ob_end_clean();
$pdf->Output('D', 'CV_' . $usuario['nombre'] . '_' . $usuario['apellidos'] . '.pdf');
?>
