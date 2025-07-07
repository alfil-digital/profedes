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
						<th>Usuario</th>
						<th>Nombre</th>
						<th>Apellido</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>id</th>
						<th>Usuario</th>
						<th>Nombre</th>
						<th>Apellido</th>
						<th>Acciones</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
					// Consulta para obtener datos de transacciones
					$sql = "SELECT usuarios.estado,usuarios.id, usuarios.usuario, personas.nombre, personas.apellido
							FROM usuarios
							INNER JOIN personas ON personas.id = usuarios.persona_id";
					$resultado = mysqli_query($con, $sql);
					while ($row = mysqli_fetch_array($resultado)) {
						$id = isset($row['id']) ? $row['id'] : '';
						$usuario = isset($row['usuario']) ? $row['usuario'] : '';
						$nombre = isset($row['nombre']) ? $row['nombre'] : '';
						$apellido = isset($row['apellido']) ? $row['apellido'] : '';
						$estado = $row['estado'];

					?>
						<tr <?php if ($estado == 0) { ?> class="bg-gray-500" <?php } ?>>
							<td align="center"><?php echo $id; ?></td>
							<td><?php echo $usuario; ?></td>
							<td><?php echo $nombre; ?></td>
							<td><?php echo $apellido; ?></td>

							<td>
								<?php if ($estado == 0) { ?>
									<a onclick="activar_usuario(<?php echo $id; ?>)" class="btn btn-success btn-icon-split" title="Activar Usuario">
										<span class="icon text-white-50">
											<i class="fas fa-user-alt"></i>
										</span>
									</a>
								<?php } else { ?>
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

									<a onclick="resetear_clave(<?php echo $id; ?>)" class="btn btn-success btn-icon-split" title="Resetear la Clave">
										<span class="icon text-white-50">
											<i class="fas fa-undo-alt"></i>
										</span>
									</a>

									<a onclick="bloquear_usuario(<?php echo $id; ?>)" class="btn btn-danger btn-icon-split" title="Bloquear Usuario">
										<span class="icon text-white-50">
											<i class="fas fa-user-alt-slash"></i>
										</span>
									</a>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>