<?php
require_once('../clases/cargar_dr.php');
require_once('../clases/roles_controller.php');
require_once('../clases/pruebas_controller.php');

$html = new cargardr;
$roles = new roles;
$pruebas = new pruebas;
$html->sessionDataSistem(); //iniciamos la sesion en el navegador
//Buscamos los permisos para entrar en esta pantalla
        $permisos = array(3);
        $rol = $_SESSION['k6']['RolId'];
        $permiso = $roles->buscarpermisos($rol,$permisos);
        if(!$permiso){
        header("Location: accesoprohibido.php");
        }
echo $html->PrintHead(); //Cargamos en header
echo $html->LoadCssSystem("sistema"); // cargamos todas las librerias css del sistema interno
echo $html->LoadJquery("sistema"); //cargamos todas las librerias Jquery del sistema interno  
echo $html->PrintBodyOpen(); //cargamos la primera parte del body
echo $html->PrintHeader(); //cargamos el header


$usuarioid = $_SESSION['k6']['UsuarioId'];

?>
<div id='wrapper'>
<div id='main-nav-bg'></div>

<?php
//cargamos el menu lateral
       echo $html->PrintSideMenu();
      

       if(isset($_POST['btnasignar'])){
         $verificar = $pruebas->VerificarSoloUnaPrueba($usuarioid);
         if($verificar == 0 ){
              $resp =$pruebas->asignar($usuarioid);
              echo"<script language='javascript'>window.location='prueba_dr.php'</script>;";
         }else{
          //ya tienes una prueba pendiente
          echo "<script>
                  $(window).load(function(){
                      $('#modalanuncio').modal('show');
                  });
              </script>";
         }
          
       }

$cantidad = $pruebas->contarpruebas();
$ListaPruebas = $pruebas->VerPruebasDR();

if(empty($ListaPruebas)){
    $ListaPruebas =[];
}

?>



    <section id='content'>
                  <div class='container-fluid'>
                    <div class='row-fluid' id='content-wrapper'>
                      <div class='span12'>
                        <div class='row-fluid'>
                          <div class='span12'>
                            <div class='page-header'>
                              <h1 class='pull-left'>
                                <span>Pruebas pendientes</span>
                              </h1>
                                 
                            </div>
                          </div>
                        </div>

                       
                        <br>
                        <form method="POST">
                         <input class="btn btn-primary" style="margin-bottom:5px" value="Asigname una prueba" type="submit" id="btnasignar" name="btnasignar">
                        </form>
                        <br><br>
                   
                        <div class='row-fluid'>
                                    <div class='span12 box bordered-box orange-border' style='margin-bottom:0;'>
                                    <div class='box-content box-no-padding'>
                                        <div class='responsive-table'>
                                        <div class='scrollable-area'>
                                            <table class='table table-bordered table-hover table-striped'>
                                            <thead>
                                                <tr>
                                                  <th>
                                                      Paciente
                                                  </th>
                                                  <th>
                                                      Fecha de envio
                                                  </th>
                                                  <th>
                                                      Prioridad
                                                  </th>
                                                  <th>
                                                    Tipo de prueba
                                                  </th>
                                                  <th>
                                                      Centro
                                                  </th>
                                                  <th>
                                                      Estado
                                                  </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                         
                                                    foreach ($ListaPruebas as $key => $value) {
                                                        $Persona = $value['Persona'];
                                                        $Fecha = $value['FC'];
                                                        $Prioridad = $value['Prioridad'];
                                                        $Centro = $value['Centro'];
                                                        $estado = $value['Estado'];
                                                        $estadoId = $value['PruebaEstadoId']; 
                                                        $clasificacion = $value['Titulo'];
                                                            echo "  
                                                            <tr>
                                                              <td>$Persona</td>
                                                              <td>$Fecha</td>
                                                              <td>$Prioridad</td>
                                                              <td>$clasificacion</td>
                                                              <td>$Centro</td>
                                                              <td><span class='label label-success'>Pendiente</span</td>
                                                            </td> 
                                                            </tr>
                                                            ";
                                                        
                                                    }
                                            ?>
                                            
                                            </tbody>
                                            </table>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                            </div>



                      </div>
                    </div>
                  </div>
     </section>


     <div class='modal hide fade' id='modalanuncio' role='dialog' tabindex='-1'>
                      <div class='modal-header'>
                        <button class='close' data-dismiss='modal' type='button'>&times;</button>
                        <h3>No se puede asignar otra prueba</h3>
                      </div>
                      <div class='modal-body'>
                             <label id="contenidomodal">Usted tiene una prueba en proceso actualmente. Por favor complétela y vuelva a intentarlo. </label>                       
                      </div>
                      <div class='modal-footer'>
                        <button class='btn btn-danger' data-dismiss='modal'>Ok</button>
                      </div>
      </div>





</div>

<?php 

echo $html->loadJS("sistema");

echo $html->PrintBodyClose();

?>