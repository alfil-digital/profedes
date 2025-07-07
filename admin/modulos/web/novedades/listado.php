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

			<!-- creo el div para la tabla  -->
			<table class="table table-striped table-hover table-bordered " id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Titulo</th>
						<th>SubTitulo</th>
						<th>Categoria</th>
						<th>Estado</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Titulo</th>
						<th>SubTitulo</th>
						<th>Categoria</th>
						<th>Estado</th>
						<th>Acciones</th>
					</tr>
				</tfoot>
				<tbody>
					<?php

					// preparo la consulta
					$sql = "SELECT id
									,titulo
									,subtitulo
									,(SELECT descripcion FROM categorias WHERE id = categoria_id) as categoria
									,publicado 
								FROM novedades";

					// ejecuto el sql 
					$resultado = mysqli_query($con, $sql);

					// obtengo los datos de la db y recorro

					while ($row = mysqli_fetch_array($resultado)) {


						$id = isset($row['id']) ? $row['id'] : '';
						$titulo = isset($row['titulo']) ? $row['titulo'] : '';
						$subtitulo = isset($row['subtitulo']) ? $row['subtitulo'] : '';
						$categoria = $row['categoria'];
						$publicado = $row['publicado'];
						
						// si estado es igual a borrador 
						if($publicado == 0){
							$publicado_db = 1;
							$estado = "Borrador";
							$class = "warning";
							$icon = "upload";
							$text = "publicar";
							$class_text = "success";
						} else{
							$publicado_db = 0;
							$estado = "Publicado";
							$class = "success";
							$icon = "download";
							$text = "despublicar";
							$class_text = "warning";
						}


					?>
						<tr>
							<td align="center"><?= $id; ?></td>
							<td><?= $titulo; ?></td>
							<td><?= $subtitulo; ?></td>
							<td><?= $categoria; ?></td>
							<td>
								<span class="badge bg-<?=$class;?>"><?= $estado; ?></span>
							</td>
							<td>
								<a onclick="editar(<?= $id; ?>)" class="btn btn-primary btn-icon-split" title="Editar">
									<span class="icon text-white-50">
										<i class="fas fa-edit"> editar</i>
									</span>
								</a>
								<a onclick="publicar(<?= $id; ?>,<?= $publicado_db; ?>)" class="btn btn-<?= $class_text; ?> btn-icon-split" title="Editar">
									<span class="icon text-white-50">
										<i class="fas fa-cloud-<?= $icon; ?>-alt"> <?= $text; ?></i>
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