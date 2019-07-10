<?php
require_once("../clases/cargar.php");
require_once("../clases/problemas_controller.php");
require_once("../clases/roles_controller.php");
require_once("../clases/alertas_controller.php");
$html = new cargar;
$_problemas= new problemas;
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
$permisos = array(4,5,6);
$rol = $_SESSION['k6']['RolId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; 
$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
    echo $_alertas->errorRedirect("Error","No tiene permiso para acceder a esta pagina","accesoprohibido.php");
}

$Listaproblemas = $_problemas->ListaProblemas($usuarioId);
if(empty($Listaproblemas)){
  $Listaproblemas = array();
}



?>
<div id='wrapper'>
<div id='main-nav-bg'></div>
<?php
echo $html->PrintSideMenu();
?>
     <section id='content'>
                  <div class='container-fluid'>
                    <div class='row-fluid' id='content-wrapper'>
                      <div class='span12'>
                        <div class='row-fluid'>
                          <div class='span12'>
                            <div class='page-header'>
                              <h1 class='pull-left'>
                                <span> Notificaciones</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -------------------------------------------- -->
                         <div class='row-fluid'>
                                    <div class='span12 box bordered-box orange-border' style='margin-bottom:0;'>
                                    <div class="box-header blue-background">
                                      <div class="title">Lista de problemas reportados</div>
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
                                                   # referencia
                                                </th>
                                                <th>
                                                    Descripcion
                                                </th>
                                                <th>
                                                    Tipo de problema
                                                </th>
                                                <th>
                                                    Fecha
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
                                                    foreach ($Listaproblemas as $key => $value) {
                                                        $id = $value['ProblemaId'];
                                                        $texto = $value['Texto'];
                                                        $estado= $value['Estado'];
                                                        $fecha= $value['Fecha'];
                                                        $tipo = $value['ProblemaTipo'];                                                                                              
                                                            echo "  
                                                            <tr>
                                                                <td>PR00$id </td>
                                                                <td>$texto</td>
                                                                <td>$tipo  </td>
                                                                <td>$fecha  </td>
                                                                <td> ";
                                                                if( $estado == "Activo"){
                                                                echo "<span class='label label-success'>$estado</span>";
                                                                }else{
                                                                echo "<span class='label label-warning'>$estado</span>";
                                                                }
                                                                
                                                            echo"   </td>
                                                                <td>
                                                                    <div class='text-center'>
                                                                    <a class='btn btn-success btn-medium' href='problema_detalle.php?id=$id'>
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