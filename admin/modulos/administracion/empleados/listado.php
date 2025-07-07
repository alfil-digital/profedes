<?php
session_start();
include("../../../inc/conexion.php");
$con = conectar();
?>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Listado de Empleados</h6>
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
						<th>Legajo</th>
						<th>Teléfono</th>
						<th>Email</th>
						<th>Localidad</th>
						<th>Detalle</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Apellido y Nombre</th>
						<th>DNI</th>
						<th>CUIL</th>
						<th>Legajo</th>
						<th>Teléfono</th>
						<th>Email</th>
						<th>Localidad</th>
						<th>Detalle</th>
						<th>Acciones</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
					$sql = "SELECT
								p.dni,
								p.cuil,
								p.telefono,
								p.email, -- Usar 'email' que es el nombre de la columna
								p.id,
								CONCAT(p.apellido, ', ', p.nombre) AS nombre_completo,
								l.nombre AS localidad,
								e.detalle AS detalle_empleado, -- Se selecciona el detalle de empleados
								e.legajo AS legajo_empleado    -- Se selecciona el legajo de empleados
							FROM
								personas p
							INNER JOIN
								localidades l ON l.id = p.localidad_id
							INNER JOIN -- Conecta personas con la tabla de relación personas_entidades
								personas_entidades pe ON p.id = pe.persona_id
							INNER JOIN -- Conecta personas_entidades con la tabla entidades
								entidades ent ON pe.entidad_id = ent.id -- Alias 'ent' para entidades para evitar conflicto
							LEFT JOIN -- LEFT JOIN para empleados, ya que no todas las personas pueden ser empleados
								empleados e ON p.id = e.persona_id
							WHERE
								ent.tipo_entidad = 'Empleado' -- Filtra por tipo de entidad 'Empleado'
							ORDER BY
								p.apellido, p.nombre;";
					$resultado = mysqli_query($con, $sql);
					if (!$resultado) {
						echo "Error en la consulta: " . mysqli_error($con); // Verifica errores en la consulta
					} else {
						while ($row = mysqli_fetch_array($resultado)) {
							// Protección contra valores null o no existentes en la base de datos
							$id = isset($row['id']) ? $row['id'] : '';
							$nombre_completo = isset($row['nombre_completo']) ? $row['nombre_completo'] : '';
							$dni = isset($row['dni']) ? $row['dni'] : '';
							$cuil = isset($row['cuil']) ? $row['cuil'] : '';
							$telefono = isset($row['telefono']) ? $row['telefono'] : '';
							$email = isset($row['email']) ? $row['email'] : ''; // Usar 'email'
							$localidad = isset($row['localidad']) ? $row['localidad'] : '';
							$detalle_empleado = isset($row['detalle_empleado']) ? $row['detalle_empleado'] : '';
							$legajo_empleado = isset($row['legajo_empleado']) ? $row['legajo_empleado'] : '';
							?>
							<tr>
								<td align="center"><?php echo $id; ?></td>
								<td><?php echo $nombre_completo; ?></td>
								<td><?php echo $dni; ?></td>
								<td><?php echo $cuil; ?></td>
								<td><?php echo $legajo_empleado; ?></td>
								<td><?php echo $telefono; ?></td>
								<td><?php echo $email; ?></td>
								<td><?php echo $localidad; ?></td>
								<td><?php echo $detalle_empleado; ?></td>
								<td>
									<a onclick="editar(<?php echo $id; ?>)" class="btn btn-primary btn-icon-split"
										title="Editar Empleado">
										<span class="icon text-white-50">
											<i class="fas fa-edit"></i>
										</span>
									</a>

									<a onclick="eliminar(<?php echo $id; ?>)" class="btn btn-danger btn-icon-split"
										title="Eliminar Empleado">
										<span class="icon text-white-50">
											<i class="fas fa-trash"></i>
										</span>
									</a>
								</td>
							</tr>
							<?php
						}
					} // Fin del if ($resultado)
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
		$.get("modulos/administracion/empleados/ver_datos.php", {
			id: id
		}, function (data) {
			$("#modalDatosContent").html(data);
			$("#modalDatos").modal('show');
		});
	}
</script>