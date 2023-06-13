<?php

function bajaPersona($conexion, $idpersona)
{
    $idpersona = filter_var($idpersona, FILTER_VALIDATE_INT);

    if (!$idpersona) {
        throw new Exception("El ID de la persona no es válido", 10);
    }

    // Baja de la persona en la base de datos
    $sql = "DELETE FROM personas WHERE idpersona = $idpersona";
    $resultado = mysqli_query($conexion, $sql);

    if (!$resultado) {
        throw new Exception("Error al realizar la baja de la persona: " . mysqli_error($conexion), 30);
    }

    return "Baja efectuada correctamente";
}

?>