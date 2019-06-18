<?php
require_once("../clases/cargar_master.php");
require_once("../clases/roles_controller.php");
require_once("../clases/alertas_controller.php");
require_once("../clases/companias_controller.php");
$html = new cargar;
$roles = new roles;
$_alertas = new alertas;
$_companias = new companias;

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
$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
    echo $_alertas->errorRedirect("Error","No tiene permiso para acceder a esta pagina","accesoprohibido.php");
}
//buscamos el codigo master para seleccionar los centros asociados al usuario
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; // Codigo del usuario

if(!$_GET['id']){
    echo $_alertas->infoRedirect("WooW","Debe seleccionar la empresa primero","lista_empresas_master.php");
}

$CompaniaId = $_GET['id'];

$datos = $_companias->Compania($CompaniaId);
$Nombre = $datos[0]['Nombre'];

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
                                <span><?=$Nombre?></span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -------------------------------------------- -->

                         <!-- <div class='row-fluid'>
                                    <div class='span12 box bordered-box orange-border' style='margin-bottom:0;'>
                                    <div class="box-header blue-background">
                                      <div class="title">Compañias</div>
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
                                                   Codigo
                                                </th>
                                                <th>
                                                    Compañia
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
                                         
                                                    foreach ($ListaCompanias as $key => $value) {
                                                        $nombre = $value['Nombre'];
                                                        $Codigo = $value['MasterCompaniaId'];
                                                        $estado= $value['Estado'];
                  
                                                            echo "  
                                                            <tr>
                                                                <td>$Codigo</td>
                                                                <td>$nombre</td>";
                                                                if( $estado == 'Activo'){//En inventario
                                                                    echo "<td><span class='label label-success'>$estado</span></td>";
                                                                    }else {
                                                                    echo "<td><span class='label' style='background-color: #9C27B0'>$estado</span></td>";
                                                                    }
                                                                
                                                            echo"   
                                                            
                                                            
                                                                <td>
                                                                    <div class='text-center'>
                                                                    <a class='btn btn-success btn-medium' href='editar_companias_master.php?id=$Codigo'>
                                                                    <i class='icon-eye-open'></i>
                                                                        Administrar
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
                            </div> -->



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