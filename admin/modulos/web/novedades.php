<link rel="stylesheet" href="../admin/vendor/ckeditor/ckeditor5.css">
<script type="text/javascript" src="modulos/web/novedades/funciones.js"></script>
<script src="../admin/vendor/ckeditor/ckeditor5.umd.js"></script>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Novedades Existentes</h1>
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
    <div id="mensaje"></div>
    <hr>
</div>
<script>
    listado();
</script>
<div id="formulario" style="display: none;"></div>
<div id="listado" style="display: none;"></div>
<br>