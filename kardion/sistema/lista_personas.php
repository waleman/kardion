<?php
require_once("../clases/cargar.php");
require_once("../clases/personas_controller.php");
require_once("../clases/roles_controller.php");
require_once("../clases/alertas_controller.php");
$html = new cargar;
$_personas= new personas;
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
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; 
$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
    echo $_alertas->errorRedirect("Error","No tiene permiso para acceder a esta pagina","accesoprohibido.php");
}


$ListaPersonas= $_personas->buscarPersonasPorCompania($master);
if(empty($ListaPersonas)){$ListaPersonas = array();}


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
                                <span> Lista de clientes</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -------------------------------------------- -->
                         <div class='row-fluid'>
                                    <div class='span12 box bordered-box orange-border' style='margin-bottom:0;'>
                                    <div class="box-header blue-background">
                                      <div class="title">Clientes pendientes de activación</div>
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
                                                      Correo
                                                  </th>
                                                  <th>
                                                      Fecha de creacion
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
                                         
                                                    foreach ($ListaPersonas as $key => $value) {
                                                        $Persona = $value['Nombre'];
                                                        $Correo = $value['Usuario'];
                                                        $estado = $value['Estado'];
                                                        $fecha = $value['FC'];
                                                        $usuario = $value['UsuarioId'];
                                                        $personaid = $value['PersonaId'];
                                                        
                                              
                                                            echo "  
                                                            <tr>
                                                              <td>$Persona</td>
                                                              <td>$Correo</td>
                                                              <td>$fecha</td>
                                                              ";
                                                              echo "<td> ";
                                                              if( $estado == 'Pendiente'){
                                                               echo "<span class='label label-important'>$estado</span</td>";
                                                              }else{
                                                                echo "<span class='label label-success'>$estado</span>";
                                                              }

                                                            echo"
                                                            </td> 
                                                            <td>
                                                            <a  class='btn btn-primary  btn-medium' name='btnregister' id='btnregister' href='editar_personas.php?usuario=$usuario&persona=$personaid'>Editar</a>
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