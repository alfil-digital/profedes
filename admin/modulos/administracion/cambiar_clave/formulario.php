<?php
session_start();
?>

<div class="content-popup" style="width: 40%;">
  <div class="close"><a class="popup-cerrar" onclick="cerrar_pass();">X</a>
  </div>

  <h2>&nbsp;&nbsp;&nbsp;Cambiar Clave</h2>

  <form method="post" id="formulario" class="row needs-validation" >
    <div class="col-md-12">

      <div class="input-group mb-3">
        <div class="input-group-append ">
          <span class="input-group-text">Clave Actual</i></span>
        </div>
        <input type="password" class="form-control" id="clave_actual" name="clave_actual"  required minlength="3" value="">
        <div class="invalid-feedback">
          controlar el campo
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="input-group mb-3">
        <div class="input-group-append">
          <span class="input-group-text">Clave Nueva</i></span>
        </div>
        <input type="password" class="form-control" id="clave_nueva" name="clave_nueva"  required minlength="3" value="">
        <div class="invalid-feedback">
          controlar el campo
        </div>
      </div>
    </div>
    <div class="col-md-12">

      <div class="input-group mb-3">
        <div class="input-group-append">
          <span class="input-group-text">Repetir Clave Nueva</i></span>
        </div>
        <input type="password" class="form-control" id="clave_nueva_1" name="clave_nueva_1"  required minlength="3" value="">
        <div class="invalid-feedback">
          controlar el campo
        </div>
      </div>
    </div>
  </form>


  <hr>
  <div align="center">
  <button class="btn btn-primary" onClick="controlar_pass();" style="margin-left: 10px; border:none;" ><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
  <br>
</div>
</div>
