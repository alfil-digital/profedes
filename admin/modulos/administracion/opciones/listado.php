<!-- listado Opciones -->
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
						<th>Titulo</th>
						<th>Descripción</th>
						<th>Icono</th>
						<th>Orden</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>id</th>
						<th>Titulo</th>
						<th>Descripción</th>
						<th>Icono</th>
						<th>Orden</th>
						<th>Acciones</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
					$sql = "SELECT * FROM opciones ORDER BY orden";
					$resultado = mysqli_query($con, $sql);

					while ($row = mysqli_fetch_array($resultado)) {
						// Previene errores de índices null para cada campo de la tabla opciones
						$id = isset($row['id']) ? $row['id'] : '';                          // Asegura ID válido
						$titulo = isset($row['titulo']) ? $row['titulo'] : '';              // Asegura título válido
						$descripcion = isset($row['descripcion']) ? $row['descripcion'] : ''; // Asegura descripción válida
						$icono = isset($row['icono']) ? $row['icono'] : '';                // Asegura icono válido
						$orden = isset($row['orden']) ? $row['orden'] : '';                // Asegura orden válido
					?>
						<tr>
							<!-- Uso de variables verificadas para prevenir errores de índices null -->
							<td align="center"><?php echo $id; ?></td>
							<td><?php echo $titulo; ?></td>
							<td><?php echo $descripcion; ?></td>
							<td align="center"><span class="<?php echo $icono; ?>"></span></td>
							<td align="center"><?php echo $orden; ?></td>
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