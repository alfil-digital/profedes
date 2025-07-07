<?php
if (!defined('BASEPATH')) {
    define('BASEPATH', __DIR__);
}
define('DB_HOST', 'localhost');
define('DB_USER', 'pablo');
define('DB_PASS', 'Google_77');
define('DB_NAME', 'colegio_profesional');
define('DB_PORT', '3306');
define('ENTORNO', 'LOCAL'); // Cambiar a 'PRODUCCION' en servidor real
define('WEB', false); // habilita el sitio web


// Asignar a variables de sesión
$_SESSION['DB_HOST'] = DB_HOST;
$_SESSION['DB_USER'] = DB_USER;
$_SESSION['DB_PASS'] = DB_PASS;
$_SESSION['DB_NAME'] = DB_NAME;
$_SESSION['DB_PORT'] = DB_PORT;
$_SESSION['ENTORNO'] = ENTORNO;

// Configuraciones adicionales de seguridad
ini_set('display_errors', ENTORNO === 'LOCAL' ? 1 : 0);
error_reporting(ENTORNO === 'LOCAL' ? E_ALL : 0);
?>