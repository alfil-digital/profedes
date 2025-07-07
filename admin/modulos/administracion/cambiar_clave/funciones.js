function confirmar ( mensaje ) {
 return confirm( mensaje );
}

function cerrar(){

 $('#popup').fadeOut('slow');
 $('.popup-overlay').fadeOut('slow');
}

function cambiar_clave_pass()
{
  $.get("modulos/administracion/cambiar_clave/formulario.php",function(dato){

    $("#popup").html(dato);
    $('#popup').fadeIn('slow');            
    return false;
  });
}
function cerrar_pass(){

  $('#popup').fadeOut('slow');
  $('.popup-overlay').fadeOut('slow');
}

function validar_formulario(){

  if ($("#clave_actual").val().length < 3) {
    $("#clave_actual").focus();          
    return 0;
  }
  if ($("#clave_nueva").val().length < 3) {
    $("#clave_nueva").focus();          
    return 0;
  }
  if ($("#clave_nueva_1").val().length < 3) {
    $("#clave_nueva_1").focus();                 
    return 0;
  }

  if($("#clave_nueva_1").val() != $("#clave_nueva").val()){
    alertas("Las claves nuevas no coinciden");
    $("#clave_nueva").focus();
    clave_nueva.value = "";
    clave_nueva_1.value="";        
    return 0;
  }
}
function controlar_pass(){
  if(validar_formulario()==0){
    $("#formulario").addClass('was-validated');
    return;
  }
  $.post("modulos/administracion/cambiar_clave/controlador.php",$("#formulario").serialize(),function(dato){
    $("#mensaje").html(dato);
    $('#mensaje').fadeIn('slow');
  });
}
