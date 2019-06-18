<?php
require_once("../clases/cargar_master.php");
require_once("../clases/clasificacion_controller.php");
require_once("../clases/roles_controller.php");
require_once("../clases/alertas_controller.php");
$html = new cargar;
$roles = new roles;
$_alertas = new  alertas;
$_clasificacion = new clasificacion;
$html->sessionDataSistem();
echo $html->PrintHead();
echo $html->LoadCssSystem("sistema");
echo $html->LoadJquery("sistema");
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $html->PrintBodyOpen();
echo $html->PrintHeader();

//definimos los permisos para esta pantalla;
$permisos = array(1);
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; 
$rol = $_SESSION['k6']['RolId'];
$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
    echo $_alertas->errorRedirect("Autorización Denegada","Usted no tiene permisos para acceder a esta página.", "accesoprohibido.php");
}

$listaTipos = $_clasificacion->obtenerClasificaciones();
if(empty($listaTipos)){
    $listaTipos = array();
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
                                <span> Tipos de pruebas</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -----------------Formulario--------------------------- -->
                         <form method="post">
                            <a class="btn btn-primary btn-large" href="nuevo_tipo_prueba_master.php" name="btnnuevo" id="btnnuevo" > Nuevo tipo de prueba</a>
                       </form>
                        <br>

                        
                            <div class='row-fluid'>
                                    <div class='span12 box bordered-box orange-border' style='margin-bottom:0;'>
                                    <div class="box-header blue-background">
                                      <div class="title">Tipos de pruebas</div>
                                      <div class="actions">
                                        <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                                        </a>
                                      </div>
                                    </div>
                                    <div class='box-content box-no-padding'>
                                        <div class='responsive-table'>
                                        <div class='scrollable-area'>
                                        <form method="post">
                                        <input type="hidden" name="txtid" id="txtid">
                                        <input type="hidden" name="txtvalor" id="txtvalor">
                                            <table class='data-table table table-bordered table-striped' data-pagination-records='25' data-pagination-top-bottom='false' style='margin-bottom:10px;'>
                                                <thead>
                                                    <tr>
                                                    <th>
                                                    TIpo de prueba
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
                                            
                                                        foreach ($listaTipos as $key => $value) {
                                                            $id = $value['ClasificacionId'];
                                                            $estado = $value['Estado'];
                                                            $nombre = $value['Nombre'];
                                                                echo "  
                                                                <tr>
                                                                    <td>$nombre</td>
                                                                  
                                                               ";
                                                        
                                                            echo"<td style='text-align:center'> ";
                                                                
                                                            if( $estado == 'Activo'){//En inventario
                                                                echo "<span class='label label-success'>$estado</span>";
                                                                }else{
                                                                echo "<span class='label label-important' >$estado</span>";
                                                                }
                                                            echo"
                                                            </td>
                                                            <td>
                                                                <div class='text-center'>
                                                                <a class='btn btn-success btn-medium' href='editar_tipo_prueba_master.php?id=$id'>
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
                                            </form> 
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                            </div>
                        <!------------------------- Formulario -------------------------->
                  </div>
                </div>

  
                       </div>
                    </div>
                  </div>
                </section>
</div>
<?php 
echo $html->loadJS("sistema");
echo $html->PrintBodyClose();
?>
