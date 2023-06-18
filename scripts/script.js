function consultaPersona(idpersona) {
	console.log("consultaPersona se ha llamado con idpersona:", idpersona);
	
	

  //trasladar el id al formulario oculto
  document.querySelector("#idpersonaFormulario").value = idpersona;

  // Confirmar que el valor se ha establecido correctamente
  console.log(
    "El valor de idpersonaFormulario ahora es:",
    document.querySelector("#idpersonaFormulario").value
  );

  //trasladar el id al otro formulario oculto
  document.querySelector("#consulta").value = idpersona;

  //submit del formulario oculto
	document.querySelector("#formconsulta").submit();
	
}
