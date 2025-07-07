<?php
session_start();
include("../../../inc/conexion.php");
conectar();

/* 
 * CAMBIOS REALIZADOS:
 * 1. Agregamos verificaciones con isset() para todos los campos
 * 2. Creamos variables intermedias para cada campo
 * 3. Asignamos valores vacíos como fallback si no existen datos
 * 4. Utilizamos variables verificadas en lugar de acceso directo al array
 *
 * BENEFICIOS:
 * - Evita advertencias de PHP por índices null o no existentes
 * - Código más seguro contra datos inconsistentes
 * - Mantiene la funcionalidad original de manera robusta
 * - Facilita la detección de problemas
 */

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
				while ($row1 = mysqli_fetch_array($resultado_opciones)) {
					// Previene errores de índices null para datos de las opciones
					$opcion_id = isset($row1['id']) ? $row1['id'] : '';                    // Asegura ID válido de la opción
					$opcion_descripcion = isset($row1['descripcion']) ? $row1['descripcion'] : ''; // Asegura descripción válida de la opción

					// Consulta con casting de tipos para prevenir inyección SQL
					$sql_grupos_opciones = "SELECT * FROM grupos_opciones WHERE grupo_id=" . (int)$grupo_id . " AND opcion_id=" . (int)$opcion_id;
					$resultado_grupos_opciones = mysqli_query($con, $sql_grupos_opciones);
					$row2 = mysqli_fetch_array($resultado_grupos_opciones);

					// Verifica existencia y coincidencia del grupo_id antes de marcar como checked
					$checked = ($row2 && isset($row2['grupo_id']) && $row2['grupo_id'] == $grupo_id) ? ' checked="checked" ' : '';
				?>
					<input style="margin-left:20px;" type="checkbox"
						name="<?php echo $grupo_id; ?>_<?php echo $opcion_id; ?>"
						id="<?php echo $grupo_id; ?>_<?php echo $opcion_id; ?>"
						<?php echo $checked; ?>
						onclick="actualizar(<?php echo $grupo_id; ?>, <?php echo $opcion_id; ?>,'<?php echo $grupo_id; ?>_<?php echo $opcion_id; ?>')">
					<?php echo $opcion_descripcion; ?>
				<?php } ?>
			</div>
		</div>
	</div>
<?php } ?>