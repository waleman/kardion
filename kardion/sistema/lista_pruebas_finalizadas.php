<?php 
require_once("../clases/cargar.php");
require_once("../clases/roles_controller.php");
require_once("../clases/pruebas_controller.php");
$html = new cargar;
$roles = new roles;
$pruebas = new pruebas;
$html->sessionDataSistem();
echo $html->PrintHead();
echo $html->LoadCssSystem("sistema");
echo $html->LoadJquery("sistema");
echo $html->PrintBodyOpen();
echo $html->PrintHeader();

//definimos los permisos para esta pantalla;
$permisos = array(4,5,6);
$rol = $_SESSION['k6']['RolId'];
$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
  header("Location: accesoprohibido.php");
}
//buscamos el codigo master para seleccionar los centros asociados al usuario
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; // Codigo del usuario

if($rol == 6){
  $ListaPruebas = $pruebas->VerPruebasFinalizadasPorCentrosPermitidos($master,$usuarioId);
}else{
  $ListaPruebas = $pruebas->VerPruebasFinalizadas($master);
}
 // print_r($ListaPruebas);

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
                                <span>Lista de pruebas finalizadas </span>
                              </h1>
                            </div>
                          </div>
                        </div>
                       <!-- ----------------------------------------- -->
                       <!-- <form method="post">
                            <a class="btn btn-primary btn-large" href="nuevo_prueba.php" name="btnnuevo" >+ Nuevo</a>
                       </form> -->
                        <br>

                            <div class='row-fluid'>
                                    <div class='span12 box bordered-box orange-border' style='margin-bottom:0;'>
                                    <div class="box-header blue-background">
                                      <div class="title">Pruebas entregadas al paciente</div>
                                      <div class="actions">
                                        <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                                        </a>
                                      </div>
                                    </div>
                                    <div class='box-content box-no-padding'>
                                        <div class='responsive-table'>
                                        <div class='scrollable-area'>
                                            <table class='data-table table table-bordered table-striped' data-pagination-records='10' data-pagination-top-bottom='false' style='margin-bottom:10px;'>
                                            <thead>
                                                <tr>
                                                  <th>
                                                      Paciente
                                                  </th>
                                                  <th>
                                                      Fecha de envio
                                                  </th>
                                                  <th>
                                                      Fecha de entrega
                                                  </th>
                                                  <th>
                                                      Prioridad
                                                  </th>
                                                  <th>
                                                      Centro
                                                  </th>
                                                  <th>
                                                    Enviada por
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
                                                        $fechaentrega = $value['FM'];
                                                        $UC = $value['UC'];
                                                            echo "  
                                                            <tr>
                                                              <td>$Persona</td>
                                                              <td>$Fecha</td>
                                                              <td>$fechaentrega</td>
                                                              <td>$Prioridad</td>
                                                              <td>$Centro</td>
                                                              <td>$UC</td>
                                                              ";
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

    
