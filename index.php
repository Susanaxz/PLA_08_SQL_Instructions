<?php



session_start();
// session_unset();
// session_destroy();


require_once 'funciones/conexion.php';
require_once 'funciones/alta.php';
require_once 'funciones/modificacion.php';

$conexionBanco = conectar();

// inicializar variables
$nif = $_POST['nif'] ?? null;
$nombre = $_POST['nombre'] ?? null;
$apellidos = $_POST['apellidos'] ?? null;
$direccion = $_POST['direccion'] ?? null;
$telefono = $_POST['telefono'] ?? null;
$email = $_POST['email'] ?? null;
$idpersonaBaja = $_POST['idpersonaBaja'] ?? null;


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

if (isset($_POST['limpiar'])) {
	$_SESSION['nif'] = null;
	$_SESSION['nombre'] = null;
	$_SESSION['apellidos'] = null;
	$_SESSION['direccion'] = null;
	$_SESSION['telefono'] = null;
	$_SESSION['email'] = null;
}



//MODIFICACION

require_once 'funciones/modificacion.php';


if (isset($_POST['modificacion'])) {
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

		// dar de alta en la base de datos
		$mensajeExito = modificacionPersona($conexionBanco, $nif, $nombre, $apellidos, $direccion, $telefono, $email);



		// limpiar datos de la sesión
		session_unset();
		session_destroy();
	} catch (Exception $e) {
		$errores .= $e->getMessage() . "<br>";
	}
}

//BAJA

if (isset($_POST['baja'])) {
	$idpersona = $_POST['idpersonaBaja'] ?? '';

	if (empty($idpersona)) {
		echo "No se proporcionó ningún ID de persona para la baja.";
	} else {
		$sql = "SELECT * FROM cuentas WHERE idpersona = ?";
		$stmt = $conexionBanco->prepare($sql);
		$stmt->bind_param("i", $idpersona);
		$stmt->execute();
		$cuenta = $stmt->get_result()->fetch_assoc();
		$stmt->close();

		echo "ID de persona a eliminar: " . $idpersona . "<br>";

		if ($cuenta) {
			echo "La persona tiene cuentas asociadas. No se puede eliminar.";
		} else {
			$sql = "DELETE FROM personas WHERE idpersona = ?";
			$stmt = $conexionBanco->prepare($sql);
			$stmt->bind_param("i", $idpersona);
			$stmt->execute();
			$stmt->close();

			if ($conexionBanco->affected_rows > 0) {
				echo "La persona ha sido eliminada exitosamente.";
			} else {
				echo "No se encontró una persona con el ID proporcionado.";
			}
		}
	}
}



//CONSULTA DE UNA PERSONA DE LA TABLA

if (isset($_POST['consulta'])) {
	$idpersona = $_POST['consulta'];

	$sql = "SELECT * FROM personas WHERE idpersona = " . $idpersona;
	$objetoDatos = mysqli_query($conexionBanco, $sql);
	$persona = mysqli_fetch_assoc($objetoDatos);

	if ($persona) {
		$_SESSION['nif'] = $persona['nif'];
		$_SESSION['nombre'] = $persona['nombre'];
		$_SESSION['apellidos'] = $persona['apellidos'];
		$_SESSION['direccion'] = $persona['direccion'];
		$_SESSION['telefono'] = $persona['telefono'];
		$_SESSION['email'] = $persona['email'];
		$_SESSION['idpersona'] = $idpersona;
	} else {
		$errores .= "No se pudo encontrar una persona con el ID proporcionado.<br>";
	}
}

//CONSULTA DE TODAS LAS PERSONAS

// Confeccionar la sentencia SELECT
$sql = "SELECT * FROM personas ORDER BY nombre, apellidos";
$objetoDatos = mysqli_query($conexionBanco, $sql);

// Comprobar si la consulta devuelve datos
if ($objetoDatos->num_rows == 0) {
	echo "No se encontraron personas.";
} else {
	// Extraer los datos en un array asociativo
	$personas = mysqli_fetch_all($objetoDatos, MYSQLI_ASSOC);
}




?>

<html>

<head>
	<title>Banco</title>
	<meta charset='UTF-8'>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<script type="text/javascript" src='scripts/script.js'></script>
	<link rel="stylesheet" type="text/css" href="css/estilos.css">
</head>

<body>
	<div class='container'>
		<form id='formulario' method='post' action='#'>
			<input type="hidden" name="idpersonaBaja" value="<?php echo $idpersona; ?>">

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
			<button type="submit" class="btn btn-danger" id="baja" name="baja">Baja</button>
			<button type="submit" class="btn btn-success" id='limpiar' name='limpiar'>Limpiar</button>
			<label class="col-sm-2 col-form-label"></label>
			<p class='mensajes'>
				<?php
				echo $mensaje;
				echo $errores;
				echo $mensajeExito ?? null;

				echo "ID de persona a eliminar: " . $idpersona . "<br>";
				echo "ID de personaBaja: " . $idpersonaBaja . "<br>";


				if (isset($_POST['alta'])) {
					echo $mensajeExito ?? null;
				}



				?>
			</p>
		</form><br><br>
		<table id='listapersonas' class="table table-striped">

			<?php
			if (isset($personas)) {
				echo "<tr><th>NIF</th><th>Nombre</th><th>Apellidos</th><th>Dirección</th><th>Teléfono</th><th>Email</th></tr>";
				foreach ($personas as $persona) {
					echo "<tr data-id='" . $persona['idpersona'] . "' onclick='consultaPersona(" . $persona['idpersona'] . ")'>
					<td>" . $persona['nif'] . "</td>
					<td>" . $persona['nombre'] . "</td>
					<td>" . $persona['apellidos'] . "</td>
					<td>" . $persona['direccion'] . "</td>
					<td>" . $persona['telefono'] . "</td>
					<td>" . $persona['email'] . "</td>
					</tr>";
				}
			}
			// //imprimir el array de forma ordenada
			// echo "<pre>";
			// print_r($personas);

			?>

		</table>
	</div>

	<form id='formconsulta' method='post' action='#'>
		<input type='hidden' id='consulta' name='consulta'>
		<input type='hidden' id='idpersonaFormulario' name='idpersonaFormulario' value="<?php echo $_SESSION['idpersona'] ?? ''; ?>">
		<input type='hidden' id='idpersonaConsulta' name='idpersonaConsulta' value="<?php echo $idpersona; ?>">
		<input type="hidden" name="idpersonaBaja" value="<?php echo $_SESSION['idpersona'] ?? ''; ?>">
	</form>


	</form>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<script type="text/javascript" src='scripts/script.js'></script>
</body>

</html>