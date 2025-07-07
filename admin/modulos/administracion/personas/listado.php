<?php
session_start();
include("../../../inc/conexion.php");
$con = conectar();


$sql = "SELECT * 
		FROM personas p 
		ORDER BY p.apellido, p.nombre";
$resultado = mysqli_query($con, $sql);

					
?>

					<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Listado de Personas</h6>
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
						<th>Acciones</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
						
					while ($row = mysqli_fetch_array($resultado)) {
						// Protección contra valores null o no existentes en la base de datos
						$id = isset($row['id']) ? $row['id'] : '';                                    // Asegura ID válido
						$nombre_completo = isset($row['nombre']) ? $row['apellido'] . ' ' . $row['nombre'] : ''; // Asegura nombre válido
						$dni = isset($row['dni']) ? $row['dni'] : '';                                // Asegura DNI válido
						$cuil = isset($row['cuil']) ? $row['cuil'] : '';                            // Asegura CUIL válido
						$telefono = isset($row['telefono']) ? $row['telefono'] : '';                // Asegura teléfono válido
						$mail = isset($row['mail']) ? $row['mail'] : '';                            // Asegura email válido
						$localidad = isset($row['localidad']) ? $row['localidad'] : '';             // Asegura localidad válida
						$entidad = isset($row['entidad']) ? $row['entidad'] : '';                   // Asegura entidad válida
					?>
						<tr>
							<!-- Uso de variables verificadas para prevenir errores de índices null -->
							<td align="center"><?php echo $id; ?></td>
							<td><?php echo $nombre_completo; ?></td>
							<td><?php echo $dni; ?></td>
							<td><?php echo $cuil; ?></td>
							<td><?php echo $telefono; ?></td>
							<td><?php echo $mail; ?></td>
							<td><?php echo $localidad; ?></td>
							<td>
								<!-- Botones de acción usando ID verificado para prevenir errores -->
								<a onclick="editar(<?php echo $id; ?>)" class="btn btn-primary btn-icon-split" title="Editar Persona">
									<span class="icon text-white-50">
										<i class="fas fa-edit"></i>
									</span>
								</a>

								<a onclick="eliminar(<?php echo $id; ?>)" class="btn btn-danger btn-icon-split" title="Eliminar Persona">
									<span class="icon text-white-50">
										<i class="fas fa-trash"></i>
									</span>
								</a>

								<!-- <a onclick="ver_datos(<?php echo $id; ?>)" class="btn btn-info btn-icon-split" title="Ver datos completos">
									<span class="icon text-white-50">
										<i class="fas fa-info-circle"></i>
									</span>
								</a> -->
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Modal para ver datos completos -->
<div class="modal fade" id="modalDatos" tabindex="-1" role="dialog" aria-labelledby="modalDatosLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalDatosLabel">Datos Completos</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="modalDatosContent">
				<!-- El contenido se cargará dinámicamente -->
			</div>
		</div>
	</div>
</div>

<script>
	function ver_datos(id) {
		$.get("modulos/administracion/personas/ver_datos.php", {
			id: id
		}, function(data) {
			$("#modalDatosContent").html(data);
			$("#modalDatos").modal('show');
		});
	}
</script>