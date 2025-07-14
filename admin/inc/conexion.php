<?php
require_once __DIR__ . '/../core/env.php'; // Ajusta la ruta según donde esté realmente ubicado tu archivo env.php

function conectar()
{
    global $con;
    // Conexión a la base de datos usando MySQLi
    $con = new mysqli($_SESSION['DB_HOST'], $_SESSION['DB_USER'], $_SESSION['DB_PASS'], $_SESSION['DB_NAME'], $_SESSION['DB_PORT']);
    // Verificar si la conexión fue exitosa
    mysqli_set_charset($con, 'utf8mb4'); // Establecer el conjunto de caracteres a utf8mb4
    
    return $con;
}

function desconectar()
{
    global $con;
    mysqli_close($con);
}