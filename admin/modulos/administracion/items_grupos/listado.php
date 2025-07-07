<!-- listado Items Grupos -->
<?php
session_start();
include("../../../inc/conexion.php");
conectar();

$sql = "SELECT * FROM grupos ORDER BY descripcion";
$resultado_grupos = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($resultado_grupos)) {
	// Previene errores de índices null para datos del grupo
	$grupo_id = isset($row['id']) ? $row['id'] : '';                          // Asegura ID válido del grupo
	$grupo_descripcion = isset($row['descripcion']) ? $row['descripcion'] : ''; // Asegura descripción válida del grupo
?>
	<div class="card shadow mb-4">
		<!-- Uso de ID de grupo verificado en los atributos del acordeón -->
		<a href="#opcion_<?php echo $grupo_id; ?>" class="d-block card-header py-3" data-toggle="collapse"
			role="button" aria-expanded="true" aria-controls="opcion_<?php echo $grupo_id; ?>">
			<h6 class="m-0 font-weight-bold text-primary"><?php echo $grupo_descripcion; ?></h6>
		</a>
		<!-- Card Content - Collapse -->
		<div class="collapse" id="opcion_<?php echo $grupo_id; ?>">
			<div class="card-body">

				<?php
				$sql_opciones = "SELECT * FROM opciones ORDER BY orden";
				$resultado_opciones = mysqli_query($con, $sql_opciones);
				while ($row3 = mysqli_fetch_array($resultado_opciones)) {
					// Previene errores de índices null para datos de las opciones
					$opcion_id = isset($row3['id']) ? $row3['id'] : '';                    // Asegura ID válido de la opción
					$opcion_descripcion = isset($row3['descripcion']) ? $row3['descripcion'] : ''; // Asegura descripción válida de la opción
				?>
					<h6 class="m-0 font-weight-bold text-primary"><?php echo $opcion_descripcion; ?></h6>

					<?php
					// Consulta segura usando casting de tipos para items
					$sql_items = "SELECT * FROM items WHERE opcion_id=" . (int)$opcion_id . " ORDER BY orden";
					$resultado_items = mysqli_query($con, $sql_items);
					$contador = 0;
					while ($row1 = mysqli_fetch_array($resultado_items)) {
						// Previene errores de índices null para datos de los items
						$item_id = isset($row1['id']) ? $row1['id'] : '';                    // Asegura ID válido del item
						$item_descripcion = isset($row1['descripcion']) ? $row1['descripcion'] : ''; // Asegura descripción válida del item

						// Consulta segura usando casting de tipos para grupos_items
						$sql_grupos_items = "SELECT * FROM grupos_items WHERE grupo_id=" . (int)$grupo_id . " AND item_id=" . (int)$item_id;
						$resultado_grupos_items = mysqli_query($con, $sql_grupos_items);
						$row2 = mysqli_fetch_array($resultado_grupos_items);

						// Verifica existencia y coincidencia del grupo_id antes de marcar como checked
						$checked = ($row2 && isset($row2['grupo_id']) && $row2['grupo_id'] == $grupo_id) ? ' checked="checked" ' : '';
					?>
						<input style="margin-left:20px;" type="checkbox"
							name="<?php echo $grupo_id; ?>_<?php echo $item_id; ?>"
							id="<?php echo $grupo_id; ?>_<?php echo $item_id; ?>"
							<?php echo $checked; ?>
							onclick="actualizar(<?php echo $grupo_id; ?>, <?php echo $item_id; ?>,'<?php echo $grupo_id; ?>_<?php echo $item_id; ?>')">

					<?php
						echo $item_descripcion;
					} ?>
					<hr>
				<?php } ?>
			</div>
		</div>
	</div>
	</div>
<?php } ?>