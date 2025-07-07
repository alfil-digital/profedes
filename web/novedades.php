<?php include_once "partials/head.php"; ?>
<?php include_once "novedades/get_novedades.php"; ?>

<body class="d-flex flex-column">
    <main class="flex-shrink-0">

        <!-- Navigation-->
        <?php include_once "partials/menu.php"; ?>
        <!-- Header-->

        <!-- Page Content-->
        <section class="py-5">
            <div class="container px-5">
                <h1 class="fw-bolder fs-5 mb-4">ProFeDes Novedades</h1>
                <div class="card border-0 shadow rounded-3 overflow-hidden">
                    <!-- muestro la ultima destacada -->
                    <?php $novedad = get_novedad_portada(); ?>

                    <div class="card-body p-0">
                        <div class="row gx-0">
                            <div class="col-lg-6 col-xl-5 py-lg-5">
                                <div class="p-4 p-md-5">
                                    <div class="badge bg-primary bg-gradient rounded-pill mb-2">
                                        <div id="tag">Nueva</div>
                                    </div>
                                    <div class="h2 fw-bolder">
                                        <div id="title_portada"><?= $novedad['titulo']; ?></div>
                                    </div>
                                    <div id="subtitle_portada"><?= $novedad['subtitulo']; ?></div>
                                    <a class="stretched-link text-decoration-none" href="post.php?id=<?= $novedad['id']; ?>">
                                        ingresar
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-7">
                                <img class="" src="../fotos/<?= $novedad['id']; ?>/<?= $novedad['nombre_imagen']; ?>" alt="" width="100%" height="80%" id="img_portada">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Blog preview section-->
        <section class="py-5">
            <div class="container px-5">
                <h2 class="fw-bolder fs-5 mb-4">Featured Stories</h2>
                <div class="row gx-5">

                    <?php
                    $novedades = get_novedades();
                    foreach ($novedades as $novedad) { ?>

                        <div class="col-lg-4 mb-5">
                            <div class="card h-100 shadow border-0">
                                <img class="card-img-top" width="600" height="300" src="../fotos/<?= $novedad['id']; ?>/<?= $novedad['nombre_imagen']; ?>" alt="..." />
                                <div class="card-body p-4">
                                    <div class="badge bg-primary bg-gradient rounded-pill mb-2">Nuevo</div>
                                    <a class="text-decoration-none link-dark stretched-link" href="#!">
                                        <div class="h5 card-title mb-3 text-wrap" style="text-align: justify;"><?= $novedad['titulo']; ?></div>
                                    </a>
                                    <p class="card-text mb-0 " style="text-align: justify;"><?= $novedad['subtitulo']; ?></p>
                                    <a class="stretched-link text-decoration-none" href="post.php?id=<?= $novedad['id']; ?>">
                                        ingresar
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                                <!-- <div class="card-footer p-4 pt-0 bg-transparent border-top-0">
                                    <div class="d-flex align-items-end justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <img class="rounded-circle me-3" src="https://dummyimage.com/40x40/ced4da/6c757d" alt="..." />
                                            <div class="small">
                                                <div class="fw-bold">Kelly Rowan</div>
                                                <div class="text-muted">March 12, 2023 &middot; 6 min read</div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="text-end mb-5 mb-xl-0">
                    <a class="text-decoration-none" href="#!">
                        More stories
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>
    </main>
    <!-- Footer-->
    <?php include_once "partials/footer.php"; ?>

</body>

</html>