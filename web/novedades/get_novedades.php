<?php

// include the database connection file
include_once '../admin/inc/conexion.php';

/**
 * NOVEDAD PORTADA
 */

function get_novedad_portada() {
    
    $con = conectar();
    $sql = "SELECT id,titulo,subtitulo,nombre_imagen 
            FROM novedades 
            WHERE publicado = 1  
            ORDER BY fecha 
            DESC limit 1";
    $resultado = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($resultado);

    // retorno el json de novedad
    return $row;

}


/**
 * NOVEDADES AL PIE
 */
function get_novedades() {
    
    $con = conectar();
    $sql = "SELECT id, titulo, subtitulo, nombre_imagen 
            FROM novedades 
            WHERE publicado = 1 
            ORDER BY fecha DESC limit 3 offset 1";
    $resultado = mysqli_query($con, $sql);
    $novedades = array();

    while ($row = mysqli_fetch_assoc($resultado)) {
        $novedades[] = $row;
    }

    // retorno el json de novedades
    return $novedades;

}

/**
 * POST NOVEDAD
 */
function get_post($id) {
    
    // Verifico que el id sea un numero
    if (!is_numeric($id)) {
        return null;
    }

    $con = conectar();
    $sql = "SELECT id,titulo,subtitulo,nombre_imagen, cuerpo ,fecha
            FROM novedades 
            WHERE publicado = 1 and id = $id
            ORDER BY fecha 
            DESC limit 1";
    $resultado = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($resultado);

    // retorno el json de novedad
    return $row;

}


