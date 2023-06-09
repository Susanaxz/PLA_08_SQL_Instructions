function consultaPersona(idpersona) {
	
	//trasladar el id al formulario oculto
	document.querySelector('#consulta').value = idpersona

	//submit del formulario oculto
	document.querySelector('#formconsulta').submit()
}