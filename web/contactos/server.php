<?php

if (isset($_POST['envioFormulario'])) {
    $nombre = $_POST['name'];
    $subjecto = $_POST['subject'];
    $email = $_POST['email'];
    $telefono = $_POST['phone'];
    $mensaje = $_POST['message'];

    echo "ok";

    // Aquí puedes agregar la lógica para enviar el formulario, como enviar un correo electrónico o guardar en una base de datos.
    /* echo "<pre>";
    print_r($_POST);
    echo "Formulario enviado con éxito. Nombre: $nombre, Email: $email, Teléfono: $telefono, Mensaje: $mensaje";
    echo "</pre>"; */
}
?>