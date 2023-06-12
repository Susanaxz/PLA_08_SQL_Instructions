<?php
session_start();
// session_unset();
// session_destroy();


require_once 'funciones/conexion.php';
require_once 'funciones/alta.php';

$conexionBanco = conectar();

// inicializar variables
$nif = $_POST['nif'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$apellidos = $_POST['apellidos'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$email = $_POST['email'] ?? '';


// inicializar mensaje y errores
$mensaje = '';
$errores = '';


if (isset($_POST['alta'])) {
	try {

		// recupera los datos del formulario
		$nif = filter_input(INPUT_POST, 'nif', FILTER_SANITIZE_ADD_SLASHES);
		$nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_ADD_SLASHES));
		$apellidos = trim(filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_ADD_SLASHES));
		$direccion = trim(filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_ADD_SLASHES));
		$telefono = trim(filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_ADD_SLASHES));
		$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));

		// convertir primera letra del nombre y apellidos en mayúsculas
		$nombre = ucwords(strtolower($nombre));
		$apellidos = ucwords(strtolower($apellidos));


		// guardamos los datos de la sesión
		$_SESSION['nif'] = $nif;
		$_SESSION['nombre'] = $nombre;
		$_SESSION['apellidos'] = $apellidos;
		$_SESSION['direccion'] = $direccion;
		$_SESSION['telefono'] = $telefono;
		$_SESSION['email'] = $email;

		// dar de alta en la base de datos
		altaPersona($nif, $nombre, $apellidos, $direccion, $telefono, $email);

		$mensajeExito = "Alta efectuada correctamente";
		$redirigir = true;

		// limpiar datos de la sesión
		session_unset();
		session_destroy();

	} catch (Exception $e) {
		$errores .= $e->getMessage() . "<br>";
	}
}



//MODIFICACION

//BAJA

//CONSULTA DE UNA PERSONA DE LA TABLA

//CONSULTA DE TODAS LAS PERSONAS

?>

<html>

<head>
	<title>Banco</title>
	<meta charset='UTF-8'>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/estilos.css">
</head>

<body>
	<div class='container'>
		<form id='formulario' method='post' action='#'>
			<input type='hidden' id='idpersona' name='idpersona'>
			<div class="row mb-3">
				<label for="nif" class="col-sm-2 col-form-label">NIF</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="nif" name='nif' value="<?php echo $_SESSION['nif'] ?? null ?>">
				</div>
			</div>
			<div class="row mb-3">
				<label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $_SESSION['nombre'] ?? null ?>">
				</div>
			</div>
			<div class="row mb-3">
				<label for="apellidos" class="col-sm-2 col-form-label">Apellidos</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo $_SESSION['apellidos'] ?? null ?>">
				</div>
			</div>
			<div class="row mb-3">
				<label for="direccion" class="col-sm-2 col-form-label">Dirección</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $_SESSION['direccion'] ?? null ?>">
				</div>
			</div>
			<div class="row mb-3">
				<label for="telefono" class="col-sm-2 col-form-label">Teléfono</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $_SESSION['telefono'] ?? null ?>">
				</div>
			</div>
			<div class=" row mb-3">
					<label for="email" class="col-sm-2 col-form-label">Email</label>
					<div class="col-sm-10">
						<input type="email" class="form-control" id="email" name="email" value="<?php echo $_SESSION['email'] ?? null ?>">
					</div>
				</div>
				<label class="col-sm-2 col-form-label"></label>
				<button type="submit" class="btn btn-success" id='alta' name='alta'>Alta</button>
				<button type="submit" class="btn btn-warning" id='modificacion' name='modificacion'>Modificación</button>
				<button type="submit" class="btn btn-danger" id='baja' name='baja'>Baja</button>
				<button type="reset" class="btn btn-success">Limpiar</button>
				<label class="col-sm-2 col-form-label"></label>
				<p class='mensajes'>
					<?php
					echo $mensaje;
					echo $errores;

					if (isset($_POST['alta'])) {
						echo $mensajeExito ?? null;
					}

					

					?>
				</p>
		</form><br><br>
		<table id='listapersonas' class="table table-striped">

		</table>
	</div>
	<form id='formconsulta' method='post' action='#'>
		<input type='hidden' id='consulta' name='consulta'>
	</form>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<script type="text/javascript" src='scripts/script.js'></script>
</body>

</html>