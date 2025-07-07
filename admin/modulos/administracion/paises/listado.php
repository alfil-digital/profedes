<!-- listado de paises -->
<?php
session_start();
include("../../../inc/conexion.php");
conectar();
?>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Listado de Países</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Descripción</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Descripción</th>
						<th>Acciones</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
					// Consulta para obtener todos los países
					$sql = "SELECT * FROM paises ORDER BY descripcion";
					$resultado = mysqli_query($con, $sql);
					while ($row = mysqli_fetch_array($resultado)) {
						// Protección contra valores null o no existentes en la base de datos
						$id = isset($row['id']) ? $row['id'] : '';                          // Asegura ID válido
						$descripcion = isset($row['descripcion']) ? $row['descripcion'] : ''; // Asegura descripción válida
					?>
						<tr>
							<!-- Uso de variables verificadas para prevenir errores de índices null -->
							<td align="center"><?php echo $id; ?></td>
							<td><?php echo $descripcion; ?></td>
							<td>
								<!-- Botones de acción usando ID verificado para prevenir errores -->
								<a onclick="editar(<?php echo $id; ?>)" class="btn btn-primary btn-icon-split" title="Editar">
									<span class="icon text-white-50">
										<i class="fas fa-edit"></i>
									</span>
								</a>

								<a onclick="eliminar(<?php echo $id; ?>)" class="btn btn-danger btn-icon-split" title="Eliminar">
									<span class="icon text-white-50">
										<i class="fas fa-trash"></i>
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
		$.get("modulos/administracion/paises/ver_datos.php", {
			id: id
		}, function(data) {
			$("#modalDatosContent").html(data);
			$("#modalDatos").modal('show');
		});
	}
</script>