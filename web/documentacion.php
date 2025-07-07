<?php
// Incluye el archivo 'head.php' que contiene la sección <head> del HTML.
// Esto centraliza metadatos, enlaces a hojas de estilo (CSS) y otros elementos importantes
// para la configuración de la página, asegurando consistencia en todo el sitio.
include_once "partials/head.php";
?>

<body class="d-flex flex-column h-100">
    <main class="flex-shrink-0">
        <?php
        // Incluye el archivo 'menu.php' que contiene la barra de navegación principal.
        // Esto permite tener un menú uniforme en todas las páginas del sitio,
        // facilitando su mantenimiento y actualización.
        include_once "partials/menu.php";
        ?>

        <header class="py-5 bg-light">
            <div class="container px-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-xxl-6">
                        <div class="text-center my-5">
                            <h1 class="fw-bolder mb-3">Documentación Importante</h1>
                            <p class="lead fw-normal text-muted mb-4">
                                Aquí podrás encontrar toda la documentación relevante para profesionales de la Educación
                                Especial.
                                Puedes visualizar los documentos directamente o descargarlos para tu consulta.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section class="py-5" id="reglamentos">
            <div class="container px-5 my-5">
                <div class="text-center mb-5">
                    <h2 class="fw-bolder">Reglamentos</h2>
                    <p class="lead fw-normal text-muted mb-0">Reglamentos y normativas vigentes.</p>
                </div>
                <div class="row gx-5 justify-content-center">
                    <div class="col-lg-10">
                        <div class="card shadow border-0 mb-4">
                            <div class="card-body p-4">
                                <h3 class="card-title fw-bolder">Reglamento General del Colegio</h3>
                                <p class="card-text text-muted mb-3">Este documento contiene el reglamento general que
                                    rige el funcionamiento del colegio.</p>
                                <div class="ratio ratio-4x3 mb-3">
                                    <iframe src="./assets/documentos/estatuto_completo.pdf#toolbar=0"
                                        style="border:none;" title="Reglamento General"></iframe>
                                </div>
                                <p class="text-center">
                                    <a href="./assets/documentos/estatuto_completo.pdf"
                                        download="Estatuto_Completo_Colegio_Profesionales_Educacion_Especial.pdf"
                                        class="btn btn-primary">
                                        <i class="bi bi-download me-2"></i>Descargar Documento
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="card shadow border-0 mb-4">
                            <div class="card-body p-4">
                                <h3 class="card-title fw-bolder">Código de Ética Profesional</h3>
                                <p class="card-text text-muted mb-3">Conoce el código de ética que deben seguir nuestros
                                    profesionales.</p>
                                <div class="ratio ratio-4x3 mb-3">
                                    <iframe src="./assets/documentos/codigo_etica.pdf" style="border:none;"
                                        title="Código de Ética Profesional"></iframe>
                                </div>
                                <p class="text-center">
                                    <a href="./assets/documentos/codigo_etica.pdf" download="Codigo_Etica_ProfEdEs.pdf"
                                        class="btn btn-primary">
                                        <i class="bi bi-download me-2"></i>Descargar Documento
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <?php
    // Incluye el archivo 'footer.php' que contiene el pie de página.
    // Esto asegura que el pie de página sea consistente en todas las páginas.
    include_once "partials/footer.php";
    ?>
</body>

</html>