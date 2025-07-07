<?php
require_once '../../../../config/conexion.php';

$accion = $_GET['f'] ?? '';

if ($accion === 'editar') {
    $id = $_POST['id'] ?? null;
    $operacion_id = $_POST['operacion_id'] ?? null;
    $numero_factura = $_POST['numero_factura'] ?? '';
    $fecha_emision_factura = $_POST['fecha_emision_factura'] ?? '';
    $fecha_facturacion = $_POST['fecha_facturacion'] ?? '';
    $fecha_pago = $_POST['fecha_pago'] ?? '';
    $entidad_id = $_POST['entidad_id'] ?? null;
    $estado = $_POST['estado'] ?? '';
    $observaciones = $_POST['observaciones'] ?? '';
    $archivo_adjunto = $_POST['archivo_adjunto'] ?? '';
    
    if ($id) {
        $sql = "UPDATE transacciones_cabecera SET operacion_id=?, numero_factura=?, fecha_emision_factura=?, fecha_facturacion=?, fecha_pago=?, entidad_id=?, estado=?, observaciones=?, archivo_adjunto=?, fecha_actualizacion=NOW() WHERE id=?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$operacion_id, $numero_factura, $fecha_emision_factura, $fecha_facturacion, $fecha_pago, $entidad_id, $estado, $observaciones, $archivo_adjunto, $id]);
    } else {
        $sql = "INSERT INTO transacciones_cabecera (operacion_id, numero_factura, fecha_emision_factura, fecha_facturacion, fecha_pago, entidad_id, estado, observaciones, archivo_adjunto, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$operacion_id, $numero_factura, $fecha_emision_factura, $fecha_facturacion, $fecha_pago, $entidad_id, $estado, $observaciones, $archivo_adjunto]);
    }
    echo "Registro guardado correctamente.";
} elseif ($accion === 'eliminar') {
    $id = $_POST['id'] ?? null;
    if ($id) {
        $sql = "DELETE FROM transacciones_cabecera WHERE id=?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$id]);
        echo "Registro eliminado correctamente.";
    }
} elseif ($accion === 'listado') {
    $sql = "SELECT SELECT tc.id, tc.numero_factura, o.tipo AS tipo_operacion, tc.fecha_emision_factura, tc.fecha_pago, 
                   COALESCE(SUM(td.monto_total_linea), 0) AS monto, tc.estado 
            FROM transacciones_cabecera tc 
            JOIN operacion o ON tc.operacion_id = o.id 
            LEFT JOIN transacciones_detalle td ON tc.id = td.transaccion_cabecera_id 
            GROUP BY tc.id, tc.numero_factura, o.tipo, tc.fecha_emision_factura, tc.fecha_pago, tc.estado";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($resultados);
}
?>
