<?php

function limpiarFormulario()
{
    $_SESSION['nif'] = null;
    $_SESSION['nombre'] = null;
    $_SESSION['apellidos'] = null;
    $_SESSION['direccion'] = null;
    $_SESSION['telefono'] = null;
    $_SESSION['email'] = null;
    $_SESSION['idpersona'] = null;
}

?>