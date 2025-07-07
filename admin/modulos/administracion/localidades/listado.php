<?php
session_start();
include("../../../inc/conexion.php");
$con = conectar(); // Asegúrate de que 'conectar()' devuelva la conexión
?>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Listado de Localidades</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>Provincia</th>
						<th>Código Postal</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>Provincia</th>
						<th>Código Postal</th>
						<th>Acciones</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
					$sql = "SELECT l.*, 
					               p.nombre AS nombre_provincia
					        FROM localidades l 
					        LEFT JOIN provincias p ON p.id = l.provincia_id 
					        ORDER BY l.nombre";
					$resultado = mysqli_query($con, $sql);

					if (!$resultado) { // Añadido: Verificar si la consulta fue exitosa
						echo '<tr><td colspan="5" class="text-center text-danger">Error al cargar listado: ' . mysqli_error($con) . '</td></tr>';
					} elseif (mysqli_num_rows($resultado) == 0) { // Añadido: Mensaje si no hay registros
						echo '<tr><td colspan="5" class="text-center">No se encontraron localidades.</td></tr>';
					} else {
						while ($row = mysqli_fetch_array($resultado)) {
							// Protección contra valores null o no existentes en la base de datos
							$id = isset($row['id']) ? $row['id'] : '';
							$nombre = isset($row['nombre']) ? $row['nombre'] : '';
							$provincia = isset($row['nombre_provincia']) ? $row['nombre_provincia'] : '';
							$codigo_postal = isset($row['codigo_postal']) ? $row['codigo_postal'] : '';
							?>
							<tr>
								<td align="center"><?php echo $id; ?></td>
								<td><?php echo htmlspecialchars($nombre); ?></td>
								<td><?php echo htmlspecialchars($provincia); ?></td>
								<td><?php echo htmlspecialchars($codigo_postal); ?></td>
								<td>
									<a onclick="editar(<?php echo $id; ?>)" class="btn btn-primary btn-icon-split"
										title="Editar Localidad">
										<span class="icon text-white-50">
											<i class="fas fa-edit"></i>
										</span>
									</a>

									<a onclick="eliminar(<?php echo $id; ?>)" class="btn btn-danger btn-icon-split"
										title="Eliminar Localidad">
										<span class="icon text-white-50">
											<i class="fas fa-trash"></i>
										</span>
									</a>
								</td>
							</tr>
							<?php
						}
					} // Fin del if/else
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>