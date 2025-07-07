<?php
// listado matriculas
session_start();
include("../../../inc/conexion.php"); // Asegúrate de la ruta a tu conexión
$con = conectar();

$profesional_id = (int)($_GET['profesional_id'] ?? 0);

if ($profesional_id <= 0) {
    echo '<div class="alert alert-warning" role="alert">Debe seleccionar un profesional para ver sus matrículas.</div>';
    exit;
}
?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Matrículas del Profesional (ID: <?php echo $profesional_id; ?>)</h6>
        <button class="btn btn-success btn-sm" onclick="agregarMatricula(<?php echo $profesional_id; ?>)">
            <i class="fas fa-plus"></i> Nueva Matrícula
        </button>
    </div>
    <div class="card-body">
        <div id="formulario-matricula-container"></div> <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered" id="dataTableMatriculas" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Número Matrícula</th>
                        <th>Fecha Alta</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Número Matrícula</th>
                        <th>Fecha Alta</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    $sql = "SELECT id, numero_matricula, fecha_alta, tipo FROM matriculas WHERE profesional_id = $profesional_id ORDER BY fecha_alta DESC";
                    $resultado = mysqli_query($con, $sql);

                    if (!$resultado) {
                        echo '<tr><td colspan="5" class="text-center text-danger">Error al cargar matrículas: ' . mysqli_error($con) . '</td></tr>';
                    } elseif (mysqli_num_rows($resultado) == 0) {
                        echo '<tr><td colspan="5" class="text-center">No se encontraron matrículas para este profesional.</td></tr>';
                    } else {
                        while ($row = mysqli_fetch_array($resultado)) {
                            $id_matricula = isset($row['id']) ? $row['id'] : '';
                            $numero_matricula = isset($row['numero_matricula']) ? $row['numero_matricula'] : '';
                            $fecha_alta = isset($row['fecha_alta']) ? $row['fecha_alta'] : '';
                            $tipo = isset($row['tipo']) ? $row['tipo'] : '';
                    ?>
                        <tr>
                            <td align="center"><?php echo $id_matricula; ?></td>
                            <td><?php echo htmlspecialchars($numero_matricula); ?></td>
                            <td><?php echo htmlspecialchars($fecha_alta); ?></td>
                            <td><?php echo htmlspecialchars($tipo); ?></td>
                            <td>
                                <a onclick="editarMatricula(<?php echo $id_matricula; ?>, <?php echo $profesional_id; ?>)" class="btn btn-primary btn-icon-split btn-sm" title="Editar Matrícula">
                                    <span class="icon text-white-50"><i class="fas fa-edit"></i></span>
                                </a>
                                <a onclick="eliminarMatricula(<?php echo $id_matricula; ?>, <?php echo $profesional_id; ?>)" class="btn btn-danger btn-icon-split btn-sm" title="Eliminar Matrícula">
                                    <span class="icon text-white-50"><i class="fas fa-trash"></i></span>
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

<script>
// Inicializar DataTables solo para la tabla de matrículas si existe
// Esto se ejecuta cuando el contenido de listado.php se inserta en el DOM
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#dataTableMatriculas')) {
        $('#dataTableMatriculas').DataTable().destroy();
        console.log("DataTable de Matrículas existente destruida.");
    }
    $('#dataTableMatriculas').DataTable({
        language: {
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sProcessing": "Procesando...",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        },
        "order": [[ 0, "desc" ]] // Ordenar por ID descendente por defecto
    });
    console.log("DataTable de Matrículas inicializada.");
});
</script>