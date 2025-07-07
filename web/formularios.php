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
        <section class="py-5 bg-light" id="formularios">
            <div class="container px-5 my-5">
                <div class="text-center mb-5">
                    <h2 class="fw-bolder">Formularios</h2>
                    <p class="lead fw-normal text-muted mb-0">Formularios necesarios para trámites y solicitudes.</p>
                </div>
                <div class="row gx-5 justify-content-center">
                    <div class="col-lg-10">
                        <div class="card shadow border-0 mb-4">
                            <div class="card-body p-4">
                                <h3 class="card-title fw-bolder">Formulario de Inscripción de Matrícula</h3>
                                <p class="card-text text-muted mb-3">Formulario para nuevos matriculados.</p>
                                <div class="ratio ratio-4x3 mb-3">
                                    <iframe src="assets/documentos" border:none;"
                                        title="Formulario de Inscripción"></iframe>
                                </div>
                                <p class="text-center">
                                    <a href="documentos/formulario_inscripcion.pdf"
                                        download="Formulario_Inscripcion_Matricula.pdf" class="btn btn-primary">
                                        <i class="bi bi-download me-2"></i>Descargar Formulario
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="card shadow border-0 mb-4">
                            <div class="card-body p-4">
                                <h3 class="card-title fw-bolder">Solicitud de Baja Temporal</h3>
                                <p class="card-text text-muted mb-3">Utilice este formulario para solicitar una baja
                                    temporal de su matrícula.</p>
                                <div class="ratio ratio-4x3 mb-3">
                                    <iframe src="documentos/solicitud_baja.pdf#toolbar=0" style="border:none;"
                                        title="Solicitud de Baja Temporal"></iframe>
                                </div>
                                <p class="text-center">
                                    <a href="documentos/solicitud_baja.pdf" download="Solicitud_Baja_Temporal.pdf"
                                        class="btn btn-primary">
                                        <i class="bi bi-download me-2"></i>Descargar Solicitud
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5" id="guias">
            <div class="container px-5 my-5">
                <div class="text-center mb-5">
                    <h2 class="fw-bolder">Guías y Recursos</h2>
                    <p class="lead fw-normal text-muted mb-0">Materiales de apoyo y guías prácticas.</p>
                </div>
                <div class="row gx-5 justify-content-center">
                    <div class="col-lg-10">
                        <div class="card shadow border-0 mb-4">
                            <div class="card-body p-4">
                                <h3 class="card-title fw-bolder">Guía de Buenas Prácticas Docentes</h3>
                                <p class="card-text text-muted mb-3">Una guía completa para mejorar la práctica
                                    educativa.</p>
                                <div class="ratio ratio-4x3 mb-3">
                                    <iframe src="documentos/guia_buenas_practicas.pdf#toolbar=0" style="border:none;"
                                        title="Guía de Buenas Prácticas Docentes"></iframe>
                                </div>
                                <p class="text-center">
                                    <a href="documentos/guia_buenas_practicas.pdf"
                                        download="Guia_Buenas_Practicas_Docentes.pdf" class="btn btn-primary">
                                        <i class="bi bi-download me-2"></i>Descargar Guía
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