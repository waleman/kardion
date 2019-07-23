<?php 
require_once("../clases/cargar_master.php");
require_once("../clases/pruebas_controller.php");
require_once("../clases/roles_controller.php");
require_once("../clases/alertas_controller.php");
$html = new cargar;
$_pruebas = new pruebas;
$roles = new roles;
$_alertas = new alertas;
$html->sessionDataSistem();
echo $html->PrintHead();
echo $html->LoadCssSystem("sistema");
echo $html->LoadJquery("sistema");
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $html->PrintBodyOpen();
echo $html->PrintHeader();
//definimos los permisos para esta pantalla;
$permisos = array(1,2);
$rol = $_SESSION['k6']['RolId'];
$master = $_SESSION['k6']['MasterCompaniaId'];
$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
  echo"<script>
  swal({
          title: 'Wow!',
          text: 'Usted no tiene perisos para estar aqui!',
          type: 'error',
          icon: 'error'
  }).then(function() {
          window.location = 'accesoprohibido.php';
  });
</script>";
die();
}

$ListaPruebas = $_pruebas->ListaPruebas_Master();

if(empty($ListaPruebas)){
    $ListaPruebas =[];
}


?>



<div id='wrapper'>
<div id='main-nav-bg'></div>

<?php echo $html->PrintSideMenu();?>

                <section id='content'>
                  <div class='container-fluid'>
                    <div class='row-fluid' id='content-wrapper'>
                      <div class='span12'>
                        <div class='row-fluid'>
                          <div class='span12'>
                            <div class='page-header'>
                              <h1 class='pull-left'>
                                <!-- <i class='icon-bar-chart'></i> -->
                                <span>Lista de pruebas pendientes</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                       <!-- ----------------------------------------- -->
                       <!-- <form method="post">
                            <a class="btn btn-primary btn-large" href="nuevo_dispositivos_master.php" name="btnnuevo" > Registrar Dispositivo</a>
                           
                       </form> -->
                        <br>

                        
                            <div class='row-fluid'>
                                    <div class='span12 box bordered-box orange-border' style='margin-bottom:0;'>
                                    <div class="box-header blue-background">
                                      <div class="title">Dispositivos</div>
                                      <div class="actions">
                                        <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                                        </a>
                                      </div>
                                    </div>
                                    <div class='box-content box-no-padding'>
                                        <div class='responsive-table'>
                                        <div class='scrollable-area'>
                                            <table class='data-table table table-bordered table-striped' data-pagination-records='25' data-pagination-top-bottom='false' style='margin-bottom:10px;'>
                                            <thead>
                                                <tr>
                                                <th>
                                                   Paciente
                                                </th>
                                                <th>
                                                    Correo
                                                </th>
                                                <th>
                                                    Fecha de envio
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
                                                        $nombre = $value['Persona'];
                                                        $correo = $value['Correo'];
                                                        $estado= $value['Estado'];
                                                        $fc = $value['FC'];
                                                        $centro = $value['Centro'];
                                                        $id = $value['PruebaId'];
                                                     
                                           
                                                            echo "  
                                                            <tr>
                                                                <td>$nombre</td>
                                                                <td>$correo</td>
                                                                <td '>$fc</td>
                                                                <td '>$centro</td>
                                                                <td style='text-align:center'> 
                                                                <span class='label label-success'>$estado</span>
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



                       <!-- -------------------------------------------- -->
                      </div>
                    </div>
                  </div>
                </section>
</div>

<?php 

echo $html->loadJS("sistema");

echo $html->PrintBodyClose();

?>

    
