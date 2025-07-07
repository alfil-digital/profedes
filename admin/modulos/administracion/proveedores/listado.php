<?php
session_start();
include("../../../inc/conexion.php");
conectar();
?>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Listado de Proveedores</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>id</th>
						<th>CUIT</th>
						<th>Nombre y Apellido</th>
						<th>Nombre Fantasia</th>
						<th>Domiclio</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>id</th>
						<th>CUIT</th>
						<th>Nombre y Apellido</th>
						<th>Nombre Fantasia</th>
						<th>Domiclio</th>
						<th>Acciones</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
					$sql = "SELECT p.*
					        FROM proveedores p 
					        ORDER BY p.cuit";
					$resultado = mysqli_query($con, $sql);
					while ($row = mysqli_fetch_array($resultado)) {
						$id = isset($row['id']) ? $row['id'] : '';
						$cuit = isset($row['cuit']) ? $row['cuit'] : '';
						$nombre = isset($row['razon_social']) ? $row['razon_social'] : '';
						$nombre_fantasia = isset($row['nombre_fantasia']) ? $row['nombre_fantasia'] : '';
						$domicilio = isset($row['domicilio']) ? $row['domicilio'] : '';
					?>
						<tr>
							<td align="center"><?php echo $id; ?></td>
							<td><?php echo $cuit; ?></td>
							<td><?php echo $nombre; ?></td>
							<td><?php echo $nombre_fantasia; ?></td>
							<td><?php echo $domicilio; ?></td>
							<td>
								<a onclick="editar(<?php echo $id; ?>)" class="btn btn-primary btn-icon-split" title="Editar Proveedor">
									<span class="icon text-white-50">
										<i class="fas fa-edit"></i>
									</span>
								</a>
								<a onclick="eliminar(<?php echo $id; ?>)" class="btn btn-danger btn-icon-split" title="Eliminar Proveedor">
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