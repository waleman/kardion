<?php
require_once('../clases/paises_controller.php');

$ctr = new paises;
$ProvinciaId = $_POST['cboprovincia'];
$datos = $ctr->getCiudad($ProvinciaId);

?>


<label class='control-label'>Municipio</label>
 <div class='controls'>
    <select id="cbociudad" name="cbociudad" >
          <option value='0' selected disabled>-- Seleccione una --</option>
          <?php
                foreach ($datos as $key => $value) {
                    $id = $value['MunicipioId'];
                    $nombre = $value['Nombre'];
                  echo "<option value='$id'>$nombre</option>";
                }
          ?>
    </select>
</div>