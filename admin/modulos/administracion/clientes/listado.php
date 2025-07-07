<?php
session_start();
include("../../../inc/conexion.php");
$con = conectar();
?>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Listado de Clientes</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Apellido y Nombre</th>
						<th>DNI</th>
						<th>CUIL</th>
						<th>Teléfono</th>
						<th>Email</th>
						<th>Localidad</th>
						<th>Detalle Cliente</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Apellido y Nombre</th>
						<th>DNI</th>
						<th>CUIL</th>
						<th>Teléfono</th>
						<th>Email</th>
						<th>Localidad</th>
						<th>Detalle Cliente</th>
						<th>Acciones</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
					$sql = "SELECT
								p.dni,
								p.cuil,
								p.telefono,
								p.email,
								p.id,
								CONCAT(p.apellido, ', ', p.nombre) AS nombre_completo,
								l.nombre AS localidad,
								c.detalle AS detalle_cliente -- Se selecciona el detalle de clientes
							FROM
								personas p
							INNER JOIN
								localidades l ON l.id = p.localidad_id
							INNER JOIN
								personas_entidades pe ON p.id = pe.persona_id
							INNER JOIN
								entidades ent ON pe.entidad_id = ent.id
							LEFT JOIN -- LEFT JOIN para clientes, ya que no todas las personas pueden ser clientes
								clientes c ON p.id = c.persona_id
							WHERE
								ent.tipo_entidad = 'Cliente' -- Filtra por tipo de entidad 'Cliente'
							ORDER BY
								p.apellido, p.nombre;";
					$resultado = mysqli_query($con, $sql);
					if (!$resultado) {
						echo "Error en la consulta: " . mysqli_error($con);
					} else {
						while ($row = mysqli_fetch_array($resultado)) {
							$id = isset($row['id']) ? $row['id'] : '';
							$nombre_completo = isset($row['nombre_completo']) ? $row['nombre_completo'] : '';
							$dni = isset($row['dni']) ? $row['dni'] : '';
							$cuil = isset($row['cuil']) ? $row['cuil'] : '';
							$telefono = isset($row['telefono']) ? $row['telefono'] : '';
							$email = isset($row['email']) ? $row['email'] : '';
							$localidad = isset($row['localidad']) ? $row['localidad'] : '';
							$detalle_cliente = isset($row['detalle_cliente']) ? $row['detalle_cliente'] : '';
							?>
							<tr>
								<td align="center"><?php echo $id; ?></td>
								<td><?php echo $nombre_completo; ?></td>
								<td><?php echo $dni; ?></td>
								<td><?php echo $cuil; ?></td>
								<td><?php echo $telefono; ?></td>
								<td><?php echo $email; ?></td>
								<td><?php echo $localidad; ?></td>
								<td><?php echo $detalle_cliente; ?></td>
								<td>
									<a onclick="editar(<?php echo $id; ?>)" class="btn btn-primary btn-icon-split"
										title="Editar Cliente">
										<span class="icon text-white-50">
											<i class="fas fa-edit"></i>
										</span>
									</a>

									<a onclick="eliminar(<?php echo $id; ?>)" class="btn btn-danger btn-icon-split"
										title="Eliminar Cliente">
										<span class="icon text-white-50">
											<i class="fas fa-trash"></i>
										</span>
									</a>
								</td>
							</tr>
							<?php
						}
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="modal fade" id="modalDatos" tabindex="-1" role="dialog" aria-labelledby="modalDatosLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalDatosLabel">Datos Completos</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="modalDatosContent">
			</div>
		</div>
	</div>
</div>

<script>
	function ver_datos(id) {
		$.get("modulos/administracion/clientes/ver_datos.php", {
			id: id
		}, function (data) {
			$("#modalDatosContent").html(data);
			$("#modalDatos").modal('show');
		});
	}
</script>