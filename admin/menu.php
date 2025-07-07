<!-- Flujo de Trabajo:

Generación Dinámica del Menú (PHP):
menu.php es incluido por index.php.
Consulta las tablas opciones y grupos_opciones para recuperar las opciones del menú principal que el $_SESSION['grupo_id'] del usuario tiene permiso para ver.
Luego, itera a través de cada opción principal y, para cada una, consulta las tablas items y grupos_items para recuperar los elementos del submenú (módulos) a los que el grupo del usuario tiene acceso.
Para cada elemento del submenú, genera dinámicamente una etiqueta <a>. El atributo href utiliza window.location para establecer los parámetros GET pagina y op en la URL. El parámetro pagina (la ruta del módulo) está base64_encode()'ado para un nivel básico de ofuscación.
Elementos Interactivos (Bootstrap/JavaScript):
Menú Colapsable: Los elementos del menú principal utilizan los atributos data-toggle="collapse" y data-target de Bootstrap. Esta funcionalidad es impulsada por el JavaScript de Bootstrap (que se basa en jQuery). Cuando se hace clic en un elemento del menú principal, el div correspondiente (con ID op_< ?php echo $row_o['id'] ?>) se expande o se contrae.
Alternador de la Barra Lateral: El botón #sidebarToggle utiliza JavaScript (probablemente de sb-admin-2.min.js) para alternar el estado colapsado de toda la barra lateral, que es una característica común en los paneles de administración.
Navegación de Subelementos: El atributo onClick="window.location='index.php?pagina=< ?php echo $cadena_codificada; ?>&op=< ?php echo $row_i['opcion_id']; ?>'" en los elementos del submenú dispara una recarga completa de la página a index.php con los nuevos parámetros GET pagina y op. Tras esta recarga, index.php detecta estos parámetros e incluye el contenido del módulo apropiado, como se explicó en el flujo de trabajo de index.php.
Resaltado del Menú: El archivo index.php contiene código JavaScript que se ejecuta después de que la página se carga. Este JavaScript utiliza jQuery para agregar/eliminar clases (collapse show, item_seleccionado) para indicar visualmente el elemento de menú activo basándose en los parámetros GET op y pagina, asegurando que la sección de menú correcta esté expandida y el elemento actual esté resaltado. -->

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
            <img src="./vendor/img/logo-DocEdEs.png" alt="Logo" class="img-fluid rounded-circle">
        </div>
        <div class="sidebar-brand-text mx-3">Colegio ProfEdEs</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item active">
        <a class="nav-link" href="index.php">
            <i class="fas fa-home"></i>
            <span>Inicio</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Interface
    </div>
    <?php
    /////CARGO LAS OPCIONES (Cargar las opciones principales del menú basadas en los permisos del grupo de usuarios)
    $sql_opciones = "SELECT o.*
                     FROM opciones o
                     JOIN grupos_opciones g ON g.opcion_id = o.id
                     WHERE g.grupo_id = " . (int) $_SESSION['grupo_id'] . "
                     AND o.estado = 1
                     ORDER BY o.orden";
    $resultado_opciones = mysqli_query($con, $sql_opciones);
    // Recorrer cada opción principal
    while ($row_o = mysqli_fetch_array($resultado_opciones)) {
        $descripcion = str_replace(' ', '_', $row_o['descripcion']); // Se usa para crear IDs únicos.
        ?>

        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#op_<?php echo $row_o['id'] ?>"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="<?php echo $row_o['icono']; ?>"></i>
                <span><?php echo $row_o['descripcion']; ?></span>
            </a>
            <div id="op_<?php echo $row_o['id'] ?>" class="collapse" aria-labelledby="headingTwo"
                data-parent="#accordionSidebar">
                <div class="bg-light py-2 collapse-inner rounded">
                    <h6 class="collapse-header"><?php echo $row_o['titulo']; ?></h6>

                    <?php
                    /////CARGO LOS ITEMS DE CADA OPCION (Cargar los elementos del submenú para la opción actual)
                    $sql_items = "SELECT i.*
                                FROM items i
                                JOIN grupos_items g ON g.item_id = i.id
                                WHERE g.grupo_id = " . (int) $_SESSION['grupo_id'] . "
                                AND i.opcion_id = " . (int) $row_o['id'] . "
                                AND i.estado = 1
                                ORDER BY i.orden";
                    $resultado_items = mysqli_query($con, $sql_items);

                    // Recorrer cada subelemento.
                    while ($row_i = mysqli_fetch_array($resultado_items)) {
                        // Codificar el enlace del elemento (enlace) en base64.
                        $cadena_codificada = base64_encode($row_i['enlace']);
                        ?>
                        <a class="collapse-item" id="<?php echo str_replace('/', '_', $row_i['enlace']); ?>" href="#"
                            onClick="window.location='index.php?pagina=<?php echo $cadena_codificada; ?>&op=<?php echo $row_i['opcion_id']; ?>'">
                            <?php echo $row_i['descripcion']; ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </li>
    <?php } ?>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <div class="sidebar-card d-none d-lg-flex">
        <img class="sidebar-card-illustration mb-2" src="inc/img/test.svg" alt="...">
        <p class="text-center mb-2"><strong>Texto para rellenar</strong></p>
        <a class="btn btn-success btn-sm" href="#">Información Extra</a>
    </div>

</ul>