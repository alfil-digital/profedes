<?php include_once "partials/head.php"; ?>
<?php include_once "novedades/get_novedades.php"; ?>

<?php

$id = $_GET['id'] ?? 0;
$post = get_post($id);

if (!$post) {
    header("Location: index.php");
    exit;
}

?>

<body class="d-flex flex-column">
    <main class="flex-shrink-0">

        <!-- Navigation-->
        <?php include_once "partials/menu.php"; ?>

        <!-- Page Content-->
        <section class="py-5">
            <div class="container px-5 my-5">
                <div class="row gx-5">
                    <!-- <div class="col-lg-3">
                            <div class="d-flex align-items-center mt-lg-5 mb-4">
                                <img class="img-fluid rounded-circle" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." />
                                <div class="ms-3">
                                    <div class="fw-bold">Valerie Luna</div>
                                    <div class="text-muted">News, Business</div>
                                </div>
                            </div>
                        </div> -->
                    <div class="col-lg-9">
                        <!-- Post content-->
                        <article>
                            <!-- Post header-->
                            <header class="mb-4">
                                <!-- Post title-->
                                <h1 class="fw-bolder mb-1"><?= $post['titulo']; ?></h1>
                                <!-- Post meta content-->
                                <div class="text-muted fst-italic mb-2"><?= $post['fecha']; ?></div>
                                <!-- Post categories-->
                                <!-- <a class="badge bg-secondary text-decoration-none link-light" href="#!">Web Design</a>
                                    <a class="badge bg-secondary text-decoration-none link-light" href="#!">Freebies</a> -->
                            </header>
                            <!-- Preview image figure-->
                            <figure class="mb-4"><img class="img-fluid rounded" src="../fotos/<?= $post['id']; ?>/<?= $post['nombre_imagen']; ?>" alt="..." /></figure>
                            <!-- Post content-->
                            <section class="mb-5">
                                <?= $post['cuerpo']; ?>
                            </section>
                        </article>

                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- Footer-->
    <?php include_once "partials/footer.php"; ?>
</body>

</html>