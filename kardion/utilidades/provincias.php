<?php
require_once('../clases/paises_controller.php');


$ctr = new paises;
$PaisId = $_POST['cbopais'];
$datos = $ctr->getProvincia($PaisId);

?>


    <script>
    
    $(document).ready(function(){

 

            $('#cboprovincia').on('change', function() {
                     var url = "../utilidades/ciudades.php";
                     $.ajax({
                         type:"POST",
                         url: url,
                         data: $("#frm_filtrar").serialize(),
                         success: function(data){
                                 $("#ciudad").html(data);
                         }
                     });
                     return false;
          });


     });
    
</script>

<label class='control-label'>Provincia</label>
 <div class='controls'>
    <select id="cboprovincia" name="cboprovincia" >
          <option value='0' selected disabled>-- Seleccione una --</option>
          <?php
                foreach ($datos as $key => $value) {
                    $id = $value['ProvinciaId'];
                    $nombre = $value['Nombre'];
                  echo "<option value='$id'>$nombre</option>";
                }
          ?>
    </select>
</div>