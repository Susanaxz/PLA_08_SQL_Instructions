<?php

function modificacionPersona ($conexion, $nif, $nombre, $apellidos, $direccion, $telefono, $email) {
    
    $consulta = "UPDATE personas SET nombre = '$nombre', apellidos = '$apellidos', direccion = '$direccion', telefono = '$telefono', email = '$email' WHERE nif = '$nif'";
    $resultado = mysqli_query($conexion, $consulta);
    if ($resultado) {
        $mensajeExito = "Persona modificada con éxito";
    } else {
        $mensajeExito = "Error al modificar la persona";
    }
    return $mensajeExito;
}

?>