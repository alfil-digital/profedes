<?php
session_start();
include("../../../inc/conexion.php");
conectar();
?>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Listado</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Nro.Factura</th>
						<th>Fecha Pago</th>
						<th>Persona</th>
						<th>Monto Total</th>
						<th>Total Detalles</th>
						<th>Concepto</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nro.Factura</th>
						<th>Fecha Pago</th>
						<th>Persona</th>
						<th>Monto Total</th>
						<th>Total Detalles</th>
						<th>Concepto</th>
						<th>Acciones</th>
					</tr>
				</tfoot>
				<tbody>
					<?php

					// preparo la consulta
					$sql = "SELECT e.*
									,(SELECT SUM(monto) FROM ingresos_detalle d WHERE d.ingreso_id = e.id) as monto_total_factura 
									,(select count(id) from ingresos_detalle d where d.ingreso_id  = e.id limit 1) as total_detalles
							FROM ingresos e";

					// ejecuto el sql 
					$resultado = mysqli_query($con, $sql);

					// obtengo los datos de la db y recorro
					
					while ($row = mysqli_fetch_array($resultado)) {
						
						$id = isset($row['id']) ? $row['id'] : '';
						$fecha_emision_factura = isset($row['fecha_emision_factura']) ? $row['fecha_emision_factura'] : '';
						$monto_total = isset($row['monto_total_factura']) ? '$' . $row['monto_total_factura'] : '<a href="javascript:void(0)" onclick="agregarDetalles(' . $id . ')">Agregar Detalles</a>';
						$total_detalles= $row['total_detalles'];
						
						// pregunto si viene una peronsa o un proveedor y traigo los dato de dicha tabla
						if(isset($row['persona_id']) && !empty($row['persona_id'])){
							
							$persona_id = $row['persona_id'];
							$sql_persona = "SELECT concat(nombres, ' ', apellido) as nombre FROM personas WHERE id = $persona_id";
							$resultado_persona = mysqli_query($con, $sql_persona);
							$row_persona = mysqli_fetch_array($resultado_persona);
							$entidad = $row_persona['nombre'];

						}else{
							
							$proveedor_id = $row['proveedor_id'];
							$sql_proveedor = "SELECT razon_social FROM proveedores WHERE id = $proveedor_id";
							$resultado_proveedor = mysqli_query($con, $sql_proveedor);
							$row_proveedor = mysqli_fetch_array($resultado_proveedor);
							$entidad = $row_proveedor['razon_social'];

						}

						// obtengo el concepto
						$concepto_id = $row['concepto_id'];
						$numero_factura = $row['numero_factura'];
						$sql_concepto = "SELECT nombre FROM conceptos WHERE id = $concepto_id";
						$resultado_concepto = mysqli_query($con, $sql_concepto);
						$row_concepto = mysqli_fetch_array($resultado_concepto);
						$concepto = $row_concepto['nombre'];
					?>
						<tr>
							<td align="center"><?= $numero_factura; ?></td>
							<td><?= $fecha_emision_factura; ?></td>
							<td><?= $entidad; ?></td>
							<td><?= $monto_total; ?></td>
							<td><?= $total_detalles; ?></td>
							<td><?= $concepto; ?></td>
							<td>
								<a onclick="editar(<?= $id; ?>)" class="btn btn-primary btn-icon-split" title="Editar">
									<span class="icon text-white-50">
										<i class="fas fa-edit"> editar</i>
									</span>
								</a>
								<a onclick="agregarDetalles(<?= $id; ?>)" class="btn btn-primary btn-icon-split" title="Editar">
									<span class="icon text-white-50">
										<i class="fas fa-edit"> Agregar detalle</i>
									</span>
								</a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>