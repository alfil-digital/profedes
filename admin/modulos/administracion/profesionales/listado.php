<?php
// Inicia o reanuda una sesión PHP. Las sesiones se utilizan para almacenar
// información del usuario a través de múltiples páginas.
session_start();

// Incluye el archivo 'conexion.php' desde el directorio "../../../inc/".
// Este archivo probablemente contiene el código para establecer la conexión a la base de datos.
include("../../../inc/conexion.php");

// Llama a la función 'conectar()' (definida en 'conexion.php') para establecer
// la conexión a la base de datos y asigna el objeto de conexión a la variable $con.
$con = conectar();
?>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Listado de Profesionales</h6>
        </div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Apellido y Nombre</th>
						<th>DNI</th>
						<th>CUIL</th>
						<th>Teléfono</th>
						<th>Email</th>
						<th>Localidad</th>
						<th>Detalle Profesional</th>
                        <th>Número Matrícula</th>
                        <th>Tipo Matrícula</th>
                        <th>Acciones</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Apellido y Nombre</th>
						<th>DNI</th>
						<th>CUIL</th>
						<th>Teléfono</th>
						<th>Email</th>
						<th>Localidad</th>
						<th>Detalle Profesional</th>
                        <th>Número Matrícula</th>
                        <th>Tipo Matrícula</th>
                        <th>Acciones</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
					// Consulta SQL para seleccionar los datos de las personas que son profesionales.
					// MODIFICACIÓN: Se unieron las tablas 'profesionales' y 'matriculas' en lugar de 'clientes'.
                    // El filtro en 'entidades' ahora busca 'Profesional'.
					$sql = "SELECT
								p.dni,                        -- DNI de la persona
								p.cuil,                       -- CUIL de la persona
								p.telefono,                   -- Teléfono de la persona
								p.email,                      -- Email de la persona
								p.id,                         -- ID de la persona
								CONCAT(p.apellido, ', ', p.nombre) AS nombre_completo, -- Apellido y nombre concatenados
								l.nombre AS localidad,        -- Nombre de la localidad
								prof.detalle AS detalle_profesional,  -- Detalle específico del profesional (de la tabla 'profesionales')
                                m.numero_matricula,           -- Número de matrícula
                                m.tipo AS tipo_matricula      -- Tipo de matrícula
							FROM
								personas p                    -- Tabla de personas
							INNER JOIN
								localidades l ON l.id = p.localidad_id -- Une con localidades
							INNER JOIN
								personas_entidades pe ON p.id = pe.persona_id -- Une con la tabla intermedia personas_entidades
							INNER JOIN
								entidades ent ON pe.entidad_id = ent.id -- Une con la tabla entidades
							LEFT JOIN                             -- MODIFICACIÓN: LEFT JOIN para 'profesionales' (antes 'clientes')
								profesionales prof ON p.id = prof.persona_id -- Une con profesionales
                            LEFT JOIN                             -- NUEVO: LEFT JOIN para 'matriculas'
                                matriculas m ON prof.id = m.profesional_id -- Une con matriculas
							WHERE
								ent.tipo_entidad = 'Profesional'  -- MODIFICACIÓN: Filtra para obtener solo las entidades de tipo 'Profesional'
							ORDER BY
								p.apellido, p.nombre;";       // Ordena los resultados por apellido y luego por nombre
					// Ejecuta la consulta SQL.
					$resultado = mysqli_query($con, $sql);
					// Comprueba si la consulta falló.
					if (!$resultado) {
						// Si hay un error en la consulta, lo muestra.
						echo "Error en la consulta: " . mysqli_error($con);
					} else {
						// Itera sobre cada fila de resultados obtenida de la base de datos.
						while ($row = mysqli_fetch_array($resultado)) {
							// Asigna los valores de cada columna a variables, usando el operador de fusión de null (??)
							// para evitar errores si alguna clave no existe.
							$id = isset($row['id']) ? $row['id'] : '';
							$nombre_completo = isset($row['nombre_completo']) ? $row['nombre_completo'] : '';
							$dni = isset($row['dni']) ? $row['dni'] : '';
							$cuil = isset($row['cuil']) ? $row['cuil'] : '';
							$telefono = isset($row['telefono']) ? $row['telefono'] : '';
							$email = isset($row['email']) ? $row['email'] : '';
							$localidad = isset($row['localidad']) ? $row['localidad'] : '';
							$detalle_profesional = isset($row['detalle_profesional']) ? $row['detalle_profesional'] : ''; // MODIFICACIÓN: Nueva variable
                            $numero_matricula = isset($row['numero_matricula']) ? $row['numero_matricula'] : ''; // NUEVA VARIABLE
                            $tipo_matricula = isset($row['tipo_matricula']) ? $row['tipo_matricula'] : ''; // NUEVA VARIABLE
							?>
							<tr>
								<td align="center"><?php echo $id; ?></td>
								<td><?php echo $nombre_completo; ?></td>
								<td><?php echo $dni; ?></td>
								<td><?php echo $cuil; ?></td>
								<td><?php echo $telefono; ?></td>
								<td><?php echo $email; ?></td>
								<td><?php echo $localidad; ?></td>
								<td><?php echo $detalle_profesional; ?></td>
                                <td><?php echo $numero_matricula; ?></td>
                                <td><?php echo $tipo_matricula; ?></td>
                                <td>
									<a onclick="editar(<?php echo $id; ?>)" class="btn btn-primary btn-icon-split"
										title="Editar Profesional">
										<span class="icon text-white-50">
											<i class="fas fa-edit"></i>
										</span>
									</a>

									<a onclick="eliminar(<?php echo $id; ?>)" class="btn btn-danger btn-icon-split"
										title="Eliminar Profesional">
										<span class="icon text-white-50">
											<i class="fas fa-trash"></i>
										</span>
									</a>

                                    <a onclick="ver_datos(<?php echo $id; ?>)" class="btn btn-info btn-icon-split"
										title="Ver Datos del Profesional">
                                        <span class="icon text-white-50">
											<i class="fas fa-eye"></i>
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