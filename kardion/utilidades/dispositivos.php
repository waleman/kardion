<?php
require_once('../clases/centros_controller.php');


$centros = new centros;
$CentroId = $_POST['cbocentro'];
$datos = $centros->DispositivosPorCentro($CentroId);

?>



<label class='control-label'>Dispositivo</label>
 <div class='controls'>
    <select id="cbodispositivo" name="cbodispositivo" >
          <option value='0' selected disabled>-- Seleccione uno --</option>
          <?php
                foreach ($datos as $key => $value) {
                    $id = $value['AparatoId'];
                    $nombre = $value['Nombre'];
                    $serie= $value["Serie"];
                  echo "<option value='$id'>$nombre - Serie $serie</option>";
                }
          ?>
    </select>
</div>