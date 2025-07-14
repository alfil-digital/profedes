<?php
session_start();
include("../../../inc/conexion.php");
$con = conectar();


$sql = "SELECT p.id,
				per.apellido,
				per.nombre,
				p.cuit,
				(SELECT descripcion FROM matriculas as mat WHERE mat.id = p.matricula_id) AS matricula,
				(SELECT descripcion FROM titulos as tit WHERE tit.id = p.titulo_id) AS titulo,
				(SELECT descripcion FROM estados as est WHERE est.id = p.estado_id) AS estado,
				(SELECT label FROM estados as est WHERE est.id = p.estado_id) AS label
		FROM profesionales p 
		INNER JOIN 	personas per ON p.persona_id = per.id
		ORDER BY per.apellido, per.nombre";
$resultado = $con->query($sql);

					
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
						<th>CUIT</th>
						<th>Matricula</th>
						<th>Titulo</th>
						<th>Estado</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Apellido y Nombre</th>
						<th>DNI</th>
						<th>CUIT</th>
						<th>Matricula</th>
						<th>Titulo</th>
						<th>Estado</th>
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
						$cuit = isset($row['cuit']) ? $row['cuit'] : '';                            // Asegura CUIT válido
						$matricula = isset($row['matricula']) ? $row['matricula'] : '';                // Asegura Matricula válido
						$titulo = isset($row['titulo']) ? $row['titulo'] : '';                            // Asegura Titulo válido
						$estado = isset($row['estado']) ? $row['estado'] : '';                            // estado del profesional
						$label = isset($row['label']) ? $row['label'] : '';                            // estado del profesional
					?>
						<tr>
							<!-- Uso de variables verificadas para prevenir errores de índices null -->
							<td align="center"><?php echo $id; ?></td>
							<td><?php echo $nombre_completo; ?></td>
							<td><?php echo $dni; ?></td>
							<td><?php echo $cuit; ?></td>
							<td><?php echo $matricula; ?></td>
							<td><?php echo $titulo; ?></td>
							<td><span class="badge bg-<?php echo $label; ?>"><?php echo $estado; ?></span></td>
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