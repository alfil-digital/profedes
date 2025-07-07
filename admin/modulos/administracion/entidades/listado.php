<?php
session_start();
include("../../../inc/conexion.php");
$con = conectar();
?>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Listado de Entidades</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Tipo de Entidad</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Tipo de Entidad</th>
						<th>Acciones</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
					$sql = "SELECT id, tipo_entidad FROM entidades ORDER BY tipo_entidad";
					$resultado = mysqli_query($con, $sql);

					if (!$resultado) {
						echo '<tr><td colspan="3" class="text-center text-danger">Error al cargar listado: ' . mysqli_error($con) . '</td></tr>';
					} elseif (mysqli_num_rows($resultado) == 0) {
						echo '<tr><td colspan="3" class="text-center">No se encontraron entidades.</td></tr>';
					} else {
						while ($row = mysqli_fetch_array($resultado)) {
							$id = isset($row['id']) ? $row['id'] : '';
							$tipo_entidad = isset($row['tipo_entidad']) ? $row['tipo_entidad'] : '';
							?>
							<tr>
								<td align="center"><?php echo $id; ?></td>
								<td><?php echo htmlspecialchars($tipo_entidad); ?></td>
								<td>
									<a onclick="editar(<?php echo $id; ?>)" class="btn btn-primary btn-icon-split"
										title="Editar Entidad">
										<span class="icon text-white-50">
											<i class="fas fa-edit"></i>
										</span>
									</a>

									<a onclick="eliminar(<?php echo $id; ?>)" class="btn btn-danger btn-icon-split"
										title="Eliminar Entidad">
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