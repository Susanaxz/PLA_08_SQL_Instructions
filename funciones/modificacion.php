<?php

function modificacionPersona($conexion, $nif, $nombre, $apellidos, $direccion, $telefono, $email)
{
    $consultaActual = "SELECT * FROM personas WHERE nif = '$nif'";
    $resultadoActual = mysqli_query($conexion, $consultaActual);

    if ($resultadoActual) {
        $personaActual = mysqli_fetch_assoc($resultadoActual); // mysqli_fetch_assoc() devuelve un array asociativo que corresponde a la fila obtenida o NULL si es incorrecto.

        if ($personaActual === null) {
            return "Persona no existe o No se han modificado datos";
        }

        if ($personaActual['nombre'] == $nombre && $personaActual['apellidos'] == $apellidos && $personaActual['direccion'] == $direccion && $personaActual['telefono'] == $telefono && $personaActual['email'] == $email) {
            return "No se han modificado datos";
        }

        $consulta = "UPDATE personas SET nombre = '$nombre', apellidos = '$apellidos', direccion = '$direccion', telefono = '$telefono', email = '$email' WHERE nif = '$nif'";
        $resultado = mysqli_query($conexion, $consulta);

        if ($resultado && $conexion->affected_rows > 0) {
            return "Persona modificada con éxito";
        } else {
            return "Error al modificar la persona";
        }
    } else {
        return "Error al obtener los datos actuales de la persona";
    }
}

?>