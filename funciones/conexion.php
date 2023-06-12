<?php


function conectar()
{
    // Intenta conectar a la base de datos MySQL
    $conexionBanco = mysqli_connect('localhost', 'root', '', 'banco');

    // Si no se puede conectar, detén el script y muestra un mensaje de error
    if (!$conexionBanco) {
        die('No se pudo conectar a la base de datos');
    }

    // Establece el conjunto de caracteres de la conexión a 'utf8'
    mysqli_set_charset($conexionBanco, 'utf8');

    // Devuelve la conexión
    return $conexionBanco;
}
?>