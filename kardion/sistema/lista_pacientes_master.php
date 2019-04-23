<?php 
require_once("../clases/cargar_master.php");
require_once("../clases/personas_controller.php");
require_once("../clases/roles_controller.php");

$html = new cargar;
$personas = new personas;
$roles = new roles;
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
//buscamos el codigo master para seleccionar los centros asociados al usuario


$ListaPacientes = $personas->buscarTodaslasPersonas();
//print_r($ListaPacientes);
if(empty($ListaPacientes)){
    $ListaPacientes =[];
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
                                <span>Lista de pacientes</span>
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
                                      <div class="title">Pacientes</div>
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
                                                   Nombre del paciente
                                                </th>
                                                <th>
                                                    Correo
                                                </th>
                                                <th>
                                                    Estado de la cuenta
                                                </th>
                                                <th>
                                                   Acciones
                                                </th>
                                               
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                         
                                                    foreach ($ListaPacientes as $key => $value) {
                                                        $nombre = $value['Nombre'];
                                                        $correo = $value['Correo'];
                                                        $estado= $value['Estado'];
                                                        $id = $value['PersonaId'];
                                                   
                                                     
                                           
                                                            echo "  
                                                            <tr>
                                                                <td>$nombre</td>
                                                                <td>$correo  </td>
                                                                <td style='text-align:center'> ";
                                                                if( $estado == 'Activo'){//activo
                                                                echo "<span class='label label-success'>$estado</span>";
                                                                }else if( $estado == 'Pendiente'){//pendiente
                                                                echo "<span class='label' style='background-color: #9C27B0'>$estado</span>";
                                                                }else {//otro
                                                                  echo "<span class='label label-warning'>$estado</span>";
                                                                }
                                                                
                                                            echo"   </td>
                                                                <td>
                                                                    <div class='text-center'>
                                                                    <a class='btn btn-success btn-medium' href='detalles_pacientes_master.php?id=$id'>
                                                                    <i class='icon-eye-open'></i>
                                                                        Ver
                                                                    </a>
                                                                    </div>
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

    
