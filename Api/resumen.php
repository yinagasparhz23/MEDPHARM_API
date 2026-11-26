<?php
header("Content-Type: application/json");
require_once 'conexion.php';

$tablas = ["cliente", "detalle_venta", "factura", "inventario", "lote", "producto", "proveedor", "usuario", "venta"];
$resumen = [];

foreach ($tablas as $tabla) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) AS total FROM $tabla");
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        $resumen[$tabla] = $fila['total'];
    } catch (PDOException $e) {
        $resumen[$tabla] = "Error: " . $e->getMessage();
    }
}

echo json_encode($resumen);
?>