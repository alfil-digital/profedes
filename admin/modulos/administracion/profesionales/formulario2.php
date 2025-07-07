<div class="col-md-4 position-relative">
    <label for="numero_matricula_principal" class="form-label">Matrícula Principal</label>
    <input type="text" class="form-control" id="numero_matricula_principal" name="numero_matricula_principal"
      placeholder="Número de Matrícula" value="<?php echo $numero_matricula_principal; ?>">
  </div>

  <div class="col-md-4 position-relative">
    <label for="detalle" class="form-label">Detalle Profesional</label>
    <input type="text" class="form-control" id="detalle" name="detalle" placeholder="Detalle Profesional"
      value="<?php echo isset($row['detalle']) ? htmlspecialchars($row['detalle']) : ''; ?>">
  </div>

  <div class="col-md-4 position-relative">
    <label for="estado_id" class="form-label">Profesional</label>
    <select class="form-control" id="estado_id" name="estado_id" required>
      <option selected disabled value="">Seleccionar</option>
      <?php
      $sql_estados = "SELECT id, nombre FROM estados WHERE tipo = 'profesional' ORDER BY nombre"; //
      $resultado_estados = mysqli_query($con, $sql_estados); //
      if ($resultado_estados) { //
        while ($row_estado = mysqli_fetch_array($resultado_estados)) { //
          $selected = ""; //
          if (isset($row['estado_id']) && $row['estado_id'] == $row_estado['id']) { //
            $selected = "selected"; //
          }
          ?>
          <option <?php echo $selected; ?> value="<?php echo $row_estado['id']; ?>">
            <?php echo htmlspecialchars($row_estado['nombre']); ?>
          </option>
          <?php
        }
      }
      ?>
    </select>
  </div>

<?php if ($id > 0): // Solo mostrar si estamos editando un profesional existente (profesional_id > 0) ?>
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Gestión de Matrículas Asociadas</h6>
      </div>
      <div class="card-body">
        <div id="matriculas-list-container">
        </div>
      </div>
    </div>

    <script>
      $(document).ready(function () { //
        var profesional_id = <?php echo $id; ?>; //
        if (profesional_id > 0) { //
          cargarMatriculasProfesional(profesional_id); //
        }
      });
    </script>
  <?php endif; ?>