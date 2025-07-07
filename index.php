<?php
  
// pregunto si la constante 'WEB' es true, si no es así, redirijo a la página al admin, sino a la web
if (!defined('WEB')) {
    header('Location: admin');
    exit;
}else{
    // Si la constante 'WEB' es true, redirijo a la página a la web
    header('Location: web');
    exit;
}