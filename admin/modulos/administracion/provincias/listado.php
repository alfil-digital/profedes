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
		<h6 class="m-0 font-weight-bold text-primary">Listado de Provincias</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>País</th>
						<th>Fecha de Creación</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>País</th>
						<th>Fecha de Creación</th>
						<th>Acciones</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
					// Consulta SQL para obtener las provincias junto con el país y la fecha de creación
					$sql = "SELECT
								p.id,
								p.nombre,
								DATE_FORMAT(p.created_at, '%d/%m/%Y %H:%i') AS fecha,
								pais.nombre AS nombre_pais
							FROM
								provincias AS p
							LEFT JOIN
								paises AS pais ON pais.id = p.pais_id"; // Asegúrate de que el pais_id esté correctamente relacionado
					$resultado = mysqli_query($con, $sql); // Ejecuta la consulta
					while ($row = mysqli_fetch_array($resultado)) { // Itera sobre los resultados
						$id = isset($row['id']) ? $row['id'] : ''; // Obtiene el ID de la provincia
						$nombre = isset($row['nombre']) ? $row['nombre'] : ''; // Obtiene el nombre de la provincia
						$pais = isset($row['nombre_pais']) ? $row['nombre_pais'] : ''; // Obtiene la descripción del país
						$fecha = isset($row['fecha']) ? $row['fecha'] : ''; // Obtiene la fecha de creación
					?>
						<tr>
							<td align="center"><?php echo $id; ?></td> <!-- Muestra el ID -->
							<td><?php echo $nombre; ?></td> <!-- Muestra el nombre -->
							<td><?php echo $pais; ?></td> <!-- Muestra el país -->
							<td align="center"><?php echo $fecha; ?></td> <!-- Muestra la fecha de creación -->
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