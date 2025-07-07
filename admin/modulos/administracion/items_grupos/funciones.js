
function listado() {
  $.get("modulos/administracion/items_grupos/listado.php", function (dato) {
    $("#listado").html(dato);
    $('#listado').fadeIn('slow');
    return false;
  });
}


function actualizar(grupo_id, item_id, tipo) {
  if (document.getElementById(tipo).checked == true) {

    $.get("modulos/administracion/items_grupos/controlador.php?grupo_id=" + grupo_id + "&item_id=" + item_id + "&tipo=add", function (dato) {
      $("#mensaje").html(dato);
      $('#mensaje').fadeIn('slow');
    });
  }
  else {
    $.get("modulos/administracion/items_grupos/controlador.php?grupo_id=" + grupo_id + "&item_id=" + item_id + "&tipo=rm", function (dato) {
      $("#mensaje").html(dato);
      $('#mensaje').fadeIn('slow');
    });

  }
}
