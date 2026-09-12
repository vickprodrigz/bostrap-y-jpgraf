<?php
require_once 'conexion.php';

// Cargar la librería JpGraph (Asegúrate de que la ruta coincida con donde guardaste la carpeta)
require_once 'jpgraph/src/jpgraph.php';
require_once 'jpgraph/src/jpgraph_pie.php';

try {
    // Consultar cuántos vehículos hay por cada estado
    $stmt = $conexion->query("SELECT estado, COUNT(*) as total FROM flota GROUP BY estado");
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}

$datos = [];
$leyendas = [];

// Preparar los datos para JpGraph
foreach ($resultados as $fila) {
    $datos[] = $fila['total'];
    $leyendas[] = $fila['estado'] . " (" . $fila['total'] . ")";
}

// Si no hay datos, mostramos un gráfico vacío por defecto
if (empty($datos)) {
    $datos = [1];
    $leyendas = ["Sin datos"];
}

// Crear el gráfico (Ancho x Alto)
$graph = new PieGraph(600, 400);
$graph->title->Set("Proporción de Vehículos: Activos vs Inactivos");
$graph->title->SetFont(FF_ARIAL, FS_BOLD, 14);

// Crear el gráfico de pastel
$p1 = new PiePlot($datos);
$p1->SetLegends($leyendas);
$p1->SetSize(0.35); // Tamaño del pastel
$p1->SetCenter(0.4, 0.5); // Posición del centro

// Colores personalizados: Verde para Activo, Rojo para Inactivo, Naranja para otros
$p1->SetSliceColors(['#16a34a', '#dc2626', '#f59e0b']); 

$graph->Add($p1);

// Mostrar la imagen generada
$graph->Stroke();
?>