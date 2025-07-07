<?php
session_start();
include("../../../inc/conexion.php");
$con = conectar(); // Asegúrate de llamar a la función para obtener la conexión
?>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Listado de Movimientos</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Tipo</th>       <th>Nro.Factura</th>
						<th>Fecha Emisión</th>
						<th>Persona/Proveedor</th> <th>Monto Total</th>
						<th>Descuento</th>    <th>Total Detalles</th>
						<th>Concepto</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Tipo</th>
						<th>Nro.Factura</th>
						<th>Fecha Emisión</th>
						<th>Persona/Proveedor</th>
						<th>Monto Total</th>
						<th>Descuento</th>
						<th>Total Detalles</th>
						<th>Concepto</th>
						<th>Acciones</th>
					</tr>
				</tfoot>
				<tbody>
					<?php

					// Prepara la consulta para obtener todos los movimientos.
					// Se selecciona el tipo de movimiento ('ingreso'/'egreso') y el descuento.
					$sql = "SELECT m.*
									,(SELECT SUM(monto) FROM movimientos_detalle md WHERE md.movimiento_id = m.id) AS monto_total_calculado_detalles
									,(SELECT COUNT(id) FROM movimientos_detalle md WHERE md.movimiento_id = m.id) AS total_detalles
							FROM movimientos m ORDER BY m.id DESC"; // Ordenar para ver los últimos movimientos primero

					// Ejecuta la consulta SQL
					$resultado = mysqli_query($con, $sql);

					// Itera sobre los resultados obtenidos de la base de datos
					while ($row = mysqli_fetch_array($resultado)) {
						
						$id = htmlspecialchars($row['id']);
						$tipo = htmlspecialchars($row['tipo']); // Obtiene el tipo de movimiento
						$numero_factura = htmlspecialchars($row['numero_factura']);
						$fecha_emision_factura = htmlspecialchars($row['fecha_emision_factura']);
						$monto_total_db = htmlspecialchars($row['monto_total']); // Monto total guardado en la DB
						$descuento = htmlspecialchars($row['descuento']); // Obtiene el descuento
						$total_detalles = htmlspecialchars($row['total_detalles']);
						
						// Determina el monto a mostrar: si hay detalles, se usa el calculado; si no, se muestra un mensaje.
						// El `monto_total_db` es el valor final después de aplicar el descuento.
						$monto_total_mostrar = ($monto_total_db > 0) ? '$' . number_format($monto_total_db, 2, ',', '.') : '<a href="javascript:void(0)" onclick="agregarDetalles(' . $id . ')">Agregar Detalles</a>';
						
						// Lógica para determinar si es una persona o un proveedor y obtener su nombre.
						$entidad_nombre = '';
						if (isset($row['persona_id']) && !empty($row['persona_id'])) {
							$persona_id = $row['persona_id'];
							$sql_persona = "SELECT CONCAT(nombres, ' ', apellido) AS nombre FROM personas WHERE id = $persona_id";
							$resultado_persona = mysqli_query($con, $sql_persona);
							$row_persona = mysqli_fetch_array($resultado_persona);
							$entidad_nombre = htmlspecialchars($row_persona['nombre']);

						} elseif (isset($row['proveedor_id']) && !empty($row['proveedor_id'])) {
							$proveedor_id = $row['proveedor_id'];
							$sql_proveedor = "SELECT razon_social FROM proveedores WHERE id = $proveedor_id";
							$resultado_proveedor = mysqli_query($con, $sql_proveedor);
							$row_proveedor = mysqli_fetch_array($resultado_proveedor);
							$entidad_nombre = htmlspecialchars($row_proveedor['razon_social']);
						} else {
							$entidad_nombre = 'N/A'; // Si no está asociado a nadie
						}

						// Obtiene el nombre del concepto.
						$concepto_id = $row['concepto_id'];
						$sql_concepto = "SELECT nombre FROM conceptos WHERE id = $concepto_id";
						$resultado_concepto = mysqli_query($con, $sql_concepto);
						$row_concepto = mysqli_fetch_array($resultado_concepto);
						$concepto_nombre = htmlspecialchars($row_concepto['nombre']);

						// Asigna un color de etiqueta según el tipo de movimiento.
						$tipo_label = ($tipo == 'egreso') ? 'danger' : 'success'; // 'danger' para egreso, 'success' para ingreso.
					?>
						<tr>
							<td align="center"><span class="badge badge-<?= $tipo_label; ?>"><?= htmlspecialchars(ucfirst($tipo)); ?></span></td> <td align="center"><?= $numero_factura; ?></td>
							<td><?= $fecha_emision_factura; ?></td>
							<td><?= $entidad_nombre; ?></td>
							<td><?= $monto_total_mostrar; ?></td>
							<td>$<?= number_format($descuento, 2, ',', '.'); ?></td> <td align="center"><?= $total_detalles; ?></td>
							<td><?= $concepto_nombre; ?></td>
							<td>
								<a onclick="editar(<?= $id; ?>)" class="btn btn-primary btn-icon-split btn-sm mb-1" title="Editar Movimiento">
									<span class="icon text-white-50">
										<i class="fas fa-edit"></i>
									</span>
									<span class="text">Editar</span>
								</a>
								<a onclick="agregarDetalles(<?= $id; ?>)" class="btn btn-info btn-icon-split btn-sm mb-1" title="Administrar Detalles">
									<span class="icon text-white-50">
										<i class="fas fa-list"></i>
									</span>
									<span class="text">Detalles</span>
								</a>
								<a onclick="eliminar(<?= $id; ?>)" class="btn btn-danger btn-icon-split btn-sm mb-1" title="Eliminar Movimiento">
									<span class="icon text-white-50">
										<i class="fas fa-trash"></i>
									</span>
									<span class="text">Eliminar</span>
								</a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>