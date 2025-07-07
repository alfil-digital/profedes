<?php
// Incluye el archivo 'head.php' que contiene la sección <head> del HTML.
// Esto centraliza la información del encabezado (meta tags, títulos, enlaces a CSS, etc.)
// y asegura que todas las páginas tengan el mismo estilo y metadatos.
include_once "partials/head.php";
?>
  <body class="d-flex flex-column">
    <main class="flex-shrink-0">
      <?php
      // Incluye el archivo 'menu.php' que contiene la barra de navegación del sitio.
      // Esto permite tener una navegación consistente en todas las páginas
      // y facilita las actualizaciones futuras del menú.
      include_once "partials/menu.php";
      ?>

      <header class="py-5">
        <div class="container px-5">
          <div class="row justify-content-center">
            <div class="col-lg-8 col-xxl-6">
              <div class="text-center my-5">
                <h1 class="fw-bolder mb-3">
                  Nuestro objetivo es ayudar a los docentes a mejorar su
                  práctica educativa
                </h1>
                <p class="lead fw-normal text-muted mb-4">
                  Hemos creado una plataforma para ayudar a los docentes a
                  mejorar su práctica educativa. En ella podrán encontrar
                  recursos, herramientas y consejos para mejorar su enseñanza.
                  Además, podrán compartir sus experiencias y aprender de otros
                  docentes. Nuestro objetivo es ayudar a los docentes a mejorar
                  la educación de los alumnos. Creemos que la educación es la
                  clave para un futuro mejor y queremos contribuir a ello.
                  Estamos convencidos de que la educación es la base de una
                  sociedad justa y equitativa.
                </p>
                <a class="btn btn-primary btn-lg" href="#scroll-target"
                  >Vea nuestra Historia</a
                >
              </div>
            </div>
          </div>
        </div>
      </header>

      <section class="py-5 bg-light" id="scroll-target">
        <div class="container px-5 my-5">
          <div class="row gx-5 align-items-center">
            <div class="col-lg-6">
              <img
                class="img-fluid rounded mb-5 mb-lg-0"
                src="./assets/img/historia1.png"
                alt="imagen de la historia"
              />
            </div>
            <div class="col-lg-6">
              <h2 class="fw-bolder">
                HACIA LA CREACIÓN DEL COLEGIO PROFESIONAL
              </h2>
              <p class="lead fw-normal text-muted mb-0">
                En 2013 iniciamos el camino: integrantes del colectivo que viene
                empujando la creación del Colegio de Profesores de Educación
                Especial de Misiones: Fabiana Silva, Tea Tramanoni y Gianina
                Almozni; se reunieron con representantes de la provincia, el
                Diputado Martín Cesino y la Diputada Anazul Centeno.
              </p>
            </div>
          </div>
        </div>
      </section>

      <section class="py-5">
        <div class="container px-5 my-5">
          <div class="row gx-5 align-items-center">
            <div class="col-lg-6 order-first order-lg-last">
              <img
                class="img-fluid rounded mb-5 mb-lg-0"
                src="./assets/img/historia3.png"
                alt="imagen de la historia"
              />
            </div>
            <div class="col-lg-6">
              <h2 class="fw-bolder">Viviendo & Aprendiendo</h2>
              <p class="lead fw-normal text-muted mb-0">
                19 de Octubre es un día importante para la Educación Especial. -
                Participamos del dictamen para la Creación del Colegio de
                Profesores de Educación Especial y su regulación profesional. -
                Gracias al Diputado Martín Cesino @jorgemartincesino y a la
                Diputada Anazul Centeno @anazulcenteno 🙌 🙌 Estamos a un pasito
                de lograr este gran sueño, iniciado hace más de diez años. 💚💙!
              </p>
            </div>
          </div>
        </div>
      </section>

      <section class="py-5">
        <div class="container px-5 my-5">
          <div class="text-center mb-5">
            <h2 class="fw-bolder">Conoce más sobre nosotros</h2>
            <p class="lead fw-normal text-muted mb-0">
              Mira nuestro video informativo
            </p>
          </div>
          <div class="row gx-5 justify-content-center">
            <div class="col-lg-8">
              <div class="ratio ratio-16x9">
                <iframe
                  id="youtubeVideo"
                  src="https://www.youtube.com/embed/DphT-IKXQZo?si=WGGxo1r6W4v3jI0w"
                  title="YouTube video player"
                  frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen
                ></iframe>
              </div>
              <div class="text-center mt-4">
                <!-- <button class="btn btn-primary btn-lg" id="playVideoButton">
                  Reproducir Video
                </button> -->
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="py-5 bg-light">
        <div class="container px-5 my-5">
          <div class="text-center">
            <h2 class="fw-bolder">Nuestra Comision</h2>
            <p class="lead fw-normal text-muted mb-5">
              Dedicados a la educación inclusiva y a la formación docente.
            </p>
          </div>
          <div
            class="row gx-5 row-cols-1 row-cols-sm-2 row-cols-xl-4 justify-content-center"
          >
            <div class="col mb-5 mb-5 mb-xl-0">
              <div class="text-center">
                <img
                  class="img-fluid rounded-circle mb-4 px-4"
                  src="./assets/img/Presidente.png"
                  alt="..."
                />
                <h5 class="fw-bolder">Fabiana Silva</h5>
                <div class="fst-italic text-muted">
                  Presidenta & Profesora
                </div>
              </div>
            </div>
            <div class="col mb-5 mb-5 mb-xl-0">
              <div class="text-center">
                <img
                  class="img-fluid rounded-circle mb-4 px-4"
                  src="./assets/img/VicePresidente.png"
                  alt="..."
                />
                <h5 class="fw-bolder">Lis Margarita Zamudio</h5>
                <div class="fst-italic text-muted">Vice Presidente</div>
              </div>
            </div>
            <div class="col mb-5 mb-5 mb-sm-0">
              <div class="text-center">
                <img
                  class="img-fluid rounded-circle mb-4 px-4"
                  src="./assets/img/Secretaria.png"
                  alt="..."
                />
                <h5 class="fw-bolder">Amanda Alsina</h5>
                <div class="fst-italic text-muted">Secretaria</div>
              </div>
            </div>
            <div class="col mb-5">
              <div class="text-center">
                <img
                  class="img-fluid rounded-circle mb-4 px-4"
                  src="./assets/img/Tesorera.png"
                  alt="..."
                />
                <h5 class="fw-bolder">Eskinazi Florencia Natalia</h5>
                <div class="fst-italic text-muted">Tesorera</div>
              </div>
            </div>
          </div>
        </div>
      </section>
      
    </main>

    <?php
    // Incluye el archivo 'footer.php' que contiene el pie de página del sitio.
    // Al igual que con el encabezado y el menú, esto asegura consistencia
    // y facilita la gestión del contenido del pie de página.
    include_once "partials/footer.php";
    ?>
   
  </body>
</html>