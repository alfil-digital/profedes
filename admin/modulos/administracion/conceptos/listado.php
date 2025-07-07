<!-- listado de provincias -->
<?php
session_start(); // Inicia la sesión
include("../../../inc/conexion.php"); // Incluye el archivo de conexión a la base de datos
conectar(); // Establece la conexión a la base de datos
?>
<!-- <script>
	listado();
</script>
 -->
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Listado Conceptos</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>Acciones</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
					$sql = "SELECT
								p.id,
								p.nombre
							FROM
								conceptos AS p
							";
					$resultado = mysqli_query($con, $sql); // Ejecuta la consulta
					while ($row = mysqli_fetch_array($resultado)) { // Itera sobre los resultados
						$id = isset($row['id']) ? $row['id'] : ''; // Obtiene el ID de la provincia
						$nombre = isset($row['nombre']) ? $row['nombre'] : ''; // Obtiene el nombre de la provincia
						
					?>
						<tr>
							<td align="center"><?php echo $id; ?></td> <!-- Muestra el ID -->
							<td><?php echo $nombre; ?></td> <!-- Muestra el nombre -->
							<td>
								<!-- Botón para editar la provincia -->
								<a onclick="editar(<?php echo $id; ?>)" class="btn btn-primary btn-icon-split" title="Editar">
									<span class="icon text-white-50">
										<i class="fas fa-edit"></i>
									</span>
								</a>
								<!-- Botón para eliminar la provincia -->
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