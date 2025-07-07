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
                            <h1 class="fw-bolder mb-3">Nuestros Planes</h1>
                            <p class="lead fw-normal text-muted mb-4">
                                Elige el plan que mejor se adapte a tus necesidades. Todos nuestros planes incluyen acceso completo a nuestros recursos y beneficios.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <section class="bg-light py-5">
            <div class="container px-5">
                <div class="row gx-5 justify-content-center">
                    <div class="col-lg-6 col-xl-4">
                        <div class="card mb-5 mb-xl-0">
                            <div class="card-body p-5">
                                <div class="small text-uppercase fw-bold text-muted">Básico</div>
                                <div class="mb-3">
                                    <span class="display-4 fw-bold">$1200</span>
                                    <span class="text-muted">/ mes</span>
                                </div>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        <strong>1 usuario</strong>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        5GB de almacenamiento
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        Soporte ilimitado
                                    </li>
                                    <li class="mb-2 text-muted">
                                        <i class="bi bi-x"></i>
                                        Informes mensuales
                                    </li>
                                    <li class="mb-2 text-muted">
                                        <i class="bi bi-x"></i>
                                        Acceso a la comunidad
                                    </li>
                                    <li class="text-muted">
                                        <i class="bi bi-x"></i>
                                        Beneficios premium
                                    </li>
                                </ul>
                                <div class="d-grid">
                                    <form action="https://www.mercadopago.com.ar/integrations/v1/web-payment-checkout.html?preference-id=YOUR_PREFERENCE_ID_BASIC" method="GET">
                                        <button type="submit" class="btn btn-primary-profedes-blue btn-lg px-4 me-sm-3 d-block w-100">Pagar con Mercado Pago</button>
                                    </form>
                                    <p class="text-muted text-center mt-2 small">
                                        <!-- *Este es un ejemplo, reemplaza con tu botón de Mercado Pago real -->.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-4">
                        <div class="card">
                            <div class="card-body p-5">
                                <div class="small text-uppercase fw-bold text-muted">Premium</div>
                                <div class="mb-3">
                                    <span class="display-4 fw-bold">$3000</span>
                                    <span class="text-muted">/ mes</span>
                                </div>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        <strong>Usuarios ilimitados</strong>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        Almacenamiento ilimitado
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        Soporte prioritario
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        Informes mensuales
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        Acceso a la comunidad
                                    </li>
                                    <li class="text-muted">
                                        <i class="bi bi-check text-primary"></i>
                                        Beneficios premium
                                    </li>
                                </ul>
                                <div class="d-grid">
                                    <form action="https://www.mercadopago.com.ar/integrations/v1/web-payment-checkout.html?preference-id=YOUR_PREFERENCE_ID_PREMIUM" method="GET">
                                        <button type="submit" class="btn btn-primary-profedes-blue btn-lg px-4 me-sm-3 d-block w-100">Pagar con Mercado Pago</button>
                                    </form>
                                    <p class="text-muted text-center mt-2 small">
                                       <!--  *Este es un ejemplo, reemplaza con tu botón de Mercado Pago real. -->
                                    </p>
                                </div>
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