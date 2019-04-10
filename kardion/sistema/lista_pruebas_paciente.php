<?php 
require_once("../clases/cargar_paciente.php");
require_once("../clases/roles_controller.php");
require_once("../clases/pruebas_controller.php");

$html = new cargar;
$roles = new roles;
$pruebas = new pruebas;

$html->sessionDataSistem();
echo $html->PrintHead();
echo $html->LoadCssSystem("sistema");
echo $html->LoadJquery("sistema");
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $html->PrintBodyOpen();
echo $html->PrintHeader();

//definimos los permisos para esta pantalla;

$permisos = array(7);
if(!isset($_SESSION['k6'])){
  
  echo"<script language='javascript'>window.location='../login.php'</script>;";
  exit();
}
$rol = $_SESSION['k6']['RolId'];
$personaId = $_SESSION['k6']['PersonaId'];
$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
  echo"<script language='javascript'>window.location='../login.php'</script>;";
  exit();  
}
//buscamos el codigo master para seleccionar los centros asociados al usuario
$usuarioId =$_SESSION['k6']['UsuarioId']; // Codigo del usuario


  $ListaPruebas = $pruebas->PruebasPersona($personaId);

  // print_r($ListaPruebas);

if(empty($ListaPruebas)){
    $ListaPruebas =[];
}


?>

<script>

</script>



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
                                <span>Mis pruebas realizadas </span>
                              </h1>
                            </div>
                          </div>
                        </div>


                            <div class='row-fluid'>
                                    <div class='span12 box bordered-box orange-border' style='margin-bottom:0;'>
                                    <div class='box-content box-no-padding'>
                                        <div class='responsive-table'>
                                        <div class='scrollable-area'>
                                            <table id="table" class='data-table table table-bordered table-striped' data-pagination-records='10' data-pagination-top-bottom='false' style='margin-bottom:10px;'>
                                            <thead>
                                                <tr>
                                                  <th>
                                                      Acciones
                                                  </th>
                                                  <th>
                                                      Fecha de envio
                                                  </th>
                                                  <th>
                                                      Prioridad
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
                                                        $PruebaId = $value['PruebaId'];
                                                        $archivo = $value['Archivo'];

                                                            echo "  
                                                            <tr style='text-align: center'>
                                                              <td style> ";
                                                              if( $estadoId == 5){ 
                                                                  echo "
                                                                  <a href='../utilidades/resultados/$archivo' target='_blank'>
                                                                    <img style='width:40px' src='../assets/images/pdfcheck.png' >
                                                                  </a>
                                                                  ";
                                                              }else{
                                                                   echo "
                                                                    
                                                                     <img id='btnerror$PruebaId' style='width:40px' src='../assets/images/pdficon.png' >
                                                                     <script>
                                                                            $('#btnerror$PruebaId').click(function(){
                                                                                swal({
                                                                                  title: 'Información ',
                                                                                  text: 'El resultado de la  prueba que ha seleccionado aún no está listo ',
                                                                                  icon: 'info',
                                                                                  button: 'Aceptar',
                                                                                });
                                                                            });
                                                                     </script>
                                                                    
                                                                   ";
                                                              }
                                                                 
                                                              echo"
                                                              </td>
                                                              <td>$Fecha</td>
                                                              <td>$Prioridad</td>
                                                              <td>$Centro</td>";
                                                              echo "<td> ";
                                                              if( $estadoId == 1){
                                                               echo "<span class='label label-danger'>$estado</span</td>";
                                                              }else if($estadoId== 2){
                                                                echo "<span class='label label-warning'>$estado</span>";
                                                              }else{
                                                                echo "<span class='label label-success'>$estado</span>";
                                                              }

                                                            echo"
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

    
