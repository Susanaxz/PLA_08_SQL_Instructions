<?php
require_once 'conexion.php';

function altaPersona($nif, $nombre, $apellidos, $direccion, $telefono, $email)
{
    global $conexionBanco;

    // VALIDAR DATOS OBLIGATORIOS
    $errores = '';

    if (empty($nif) || strlen($nif) != 9) {
        $errores .= "NIF con formato incorrecto<br>";
    } else {
        $letra = substr($nif, -1);
        $numeros = substr($nif, 0, -1);
        if (!ctype_alpha($letra) || !ctype_digit($numeros)) {
            $errores .= "NIF con formato incorrecto<br>";
        }
    }

    if (empty($nombre)) {
        $errores .= "Nombre obligatorio<br>";
    }

    if (empty($apellidos)) {
        $errores .= "Apellidos obligatorios<br>";
    }

    if (empty($direccion)) {
        $errores .= "Dirección obligatoria<br>";
    }

    if (empty($telefono)) {
        $errores .= "Teléfono obligatorio<br>";
    }

    if (!empty($errores)) {
        throw new Exception($errores); // Lanzamos la excepción si $errores no está vacía
    }

    // Validar el email si está presente
    if (!empty($email)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores .= "Email con formato incorrecto<br>";
            throw new Exception($errores);
        }
    }

    // Preparar el valor de $email para el SQL
    $email = empty($email) ? 'NULL' : "'" . mysqli_real_escape_string($conexionBanco, $email) . "'";

    // Confeccionar y ejecutar la sentencia SQL
    $sql = "INSERT INTO personas VALUES (NULL, '" . mysqli_real_escape_string($conexionBanco, $nif) . "', '" . mysqli_real_escape_string($conexionBanco, $nombre) . "', '" . mysqli_real_escape_string($conexionBanco, $apellidos) . "', '" . mysqli_real_escape_string($conexionBanco, $direccion) . "', '" . mysqli_real_escape_string($conexionBanco, $telefono) . "', $email, DEFAULT)";

    if (!mysqli_query($conexionBanco, $sql)) {
        if ($conexionBanco->errno == 1062) {
            throw new Exception("El nif o email ya existen en la base de datos");
        }
        //texto del error, código de error
        throw new Exception($conexionBanco->error, $conexionBanco->errno);
    
    }
}

