<?php 
require_once("../clases/cargar.php");
require_once("../clases/centros_controller.php");
require_once("../clases/roles_controller.php");
$html = new cargar;
$ct = new centros;
$roles = new roles;
$html->sessionDataSistem();
echo $html->PrintHead();
echo $html->LoadCssSystem("sistema");
echo $html->LoadJquery("sistema");
echo $html->PrintBodyOpen();
echo $html->PrintHeader();

//definimos los permisos para esta pantalla;
$permisos = array(4,5);
$rol = $_SESSION['k6']['RolId'];
$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
  header("Location: accesoprohibido.php");
}

//buscamos el codigo master para seleccionar los centros asociados al usuario
$master = $_SESSION['k6']['MasterCompaniaId'];
//cargamos los centros guardados
$ListaCentros = $ct->getCentros($master);

if(empty($ListaCentros)){
    $ListaCentros =[];
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
                          <div class='span12 '>
                            <div class='page-header color-azul'>
                              <h1 class='pull-left'>
                                <!-- <i class='icon-bar-chart'></i> -->
                                <span>Lista de centros </span>
                              </h1>
                            </div>
                          </div>
                        </div>
                       <!-- ----------------------------------------- -->
                       <form method="post">
                            <a class="btn btn-primary btn-large" href="nuevo_centro.php" name="btnnuevo" >+ Nuevo</a>
                       </form>
                        <br>
                       <div class='row-fluid'>
                            <div class='span12 box bordered-box orange-border' style='margin-bottom:0;'>
                            <div class="box-header blue-background">
                              <div class="title">Centros creados</div>
                              <div class="actions">
                                <a class="btn box-remove btn-mini btn-link" href="#"><i class="icon-remove"></i>
                                </a>
                                
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
                                            Centro de trabajo
                                        </th>
                                        <th>
                                            Codigo Postal
                                        </th>
                                        <th>
                                            Telefono
                                        </th>
                                        <th>
                                            Estado
                                        </th>
                                        <th>
                                            Acciones
                                        </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                            foreach ($ListaCentros as $key => $value) {
                                                $nombre = $value['Nombre'];
                                                $codigoPostal = $value['CodigoPostal'];
                                                $telefono= $value['Telefono1'];
                                                $estado= $value['Estado'];
                                                $id=$value['CentroId'];
                                                    echo "
                                                    
                                                    <tr>
                                                        <td>$nombre</td>
                                                        <td>$codigoPostal</td>
                                                        <td>$telefono</td>
                                                        <td> ";
                                                        if( $estado == "Activo"){
                                                           echo "<span class='label label-success'>$estado</span>";
                                                        }elseif($estado == "Pendiente"){
                                                           echo "<span class='label label-warning'>$estado</span>";
                                                        }else{
                                                           echo "<span class='label label-important'>$estado</span>";
                                                        }
                                                        
                                                     echo"   </td>
                                                        <td>
                                                            <div class='text-center'>
                                                            <a class='btn btn-success btn-medium' href='editar_centro.php?id=$id'>
                                                            <i class='icon-pencil'></i>
                                                                Editar
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

    
