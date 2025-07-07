<script type="text/javascript" src="modulos/administracion/usuarios/funciones.js"></script>


<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Usuarios</h1>                        
</div>

<div id="menu" class="form-group" align="left">
  <a class="btn btn-success btn-icon-split" onclick="window.history.go(-1)">
    <span class="icon text-white-50">
      <i class="fas fa-reply"></i>
    </span>
    <span class="text">Volver</span>
  </a>&nbsp;

  <a href="#" class="btn btn-primary btn-icon-split" onclick="editar(0);">
    <span class="icon text-white-50">
      <i class="fas fa-plus-circle"></i>
    </span>
    <span class="text">Nuevo</span>
  </a>

  <br />
  <hr>
</div>
<script>listado();</script>
<div id="formulario" style="display: none;"></div>
<div id="mensaje" style="display: none;"></div>
<div id="listado" style="display: none;"></div>
<br>