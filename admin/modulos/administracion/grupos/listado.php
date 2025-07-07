<!-- listado Grupos -->
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
						<th>id</th>
						<th>Descripción</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>id</th>
						<th>Descripción</th>
						<th>Acciones</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
					// Consulta para obtener grupos activos ordenados por descripción
					$sql = "select * from grupos where estado=1 order by descripcion";
					$resultado = mysqli_query($con, $sql);
					while ($row = mysqli_fetch_array($resultado)) {
						// Previene errores de índices null para cada campo de la tabla grupos
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
									<!--<span class="text">Editar</span>-->
								</a>

								<a onclick="eliminar(<?php echo $id; ?>)" class="btn btn-danger btn-icon-split" title="Eliminar">
									<span class="icon text-white-50">
										<i class="fas fa-trash"></i>
									</span>
									<!--<span class="text">Eliminar</span>-->
								</a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>