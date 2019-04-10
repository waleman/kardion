<?php 
require_once("../clases/cargar_paciente.php");
require_once("../clases/roles_controller.php");
require_once("../clases/dispositivos_controller.php");

$_html = new cargar;
$_roles = new roles;
$_dispositivos = new dispositivos;


$_html->sessionDataSistem();
echo $_html->PrintHead();
echo $_html->LoadCssSystem("sistema");
echo $_html->LoadJquery("sistema");
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $_html->PrintBodyOpen();
echo $_html->PrintHeader();

//definimos los permisos para esta pantalla;

$permisos = array(7);
if(!isset($_SESSION['k6'])){
  
  echo"<script language='javascript'>window.location='../login.php'</script>;";
  exit();
}
$rol = $_SESSION['k6']['RolId']; // Rol de la persona
$personaId = $_SESSION['k6']['PersonaId']; // Codigo de la persona
$usuarioId =$_SESSION['k6']['UsuarioId']; // Codigo del usuario
$master = $_SESSION['k6']['MasterCompaniaId']; // codigo maestro
$permiso = $_roles->buscarpermisos($rol,$permisos);
if(!$permiso){
  echo"<script language='javascript'>window.location='../login.php'</script>;";
  exit();  
}
//buscamos el codigo master para seleccionar los centros asociados al usuario



$ListaDispositivos =$_dispositivos->Dispositivos_paciente($master);

  print_r($ListaDispositivos);

if(empty($ListaDispositivos)){
    $ListaDispositivos =[];
}


?>



<div id='wrapper'>
<div id='main-nav-bg'></div>

<?php echo $_html->PrintSideMenu();?>

                <section id='content'>
                  <div class='container-fluid'>
                    <div class='row-fluid' id='content-wrapper'>
                      <div class='span12'>
                        <div class='row-fluid'>
                          <div class='span12'>
                            <div class='page-header'>
                              <h1 class='pull-left'>
                                <!-- <i class='icon-bar-chart'></i> -->
                                <span>Dispositivos vinculados a mi cuenta</span>
                              </h1>
                            </div>
                          </div>
                        </div>

                      
                        <form action="" >
                                 <a class="btn btn-primary btn-large" href="nuevo_centro.php" name="btnnuevo" >+ Administrar dispositivos</a>
                         </form>

                        <!------------------------------------------------->
                    <div class='row-fluid'>
                        <div class='span12 box bordered-box blue-border' style='margin-bottom:0;'>
                            <div class='box-header blue-background'>
                                <div class='title'>Dispositivos</div>
                                <div class='actions'>
                                <a class="btn box-remove btn-mini btn-link" href="#"><i class='icon-remove'></i>
                                </a>
                                
                                <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                                </a>
                                </div>
                            </div>
                            <div class='box-content box-no-padding'>
                                <div class='responsive-table'>
                                <div class='scrollable-area'>
                                    <table class='table' data='' style='margin-bottom:0;'>
                                    <thead>
                                        <tr>
                                            <th>
                                                Serie
                                            </th>
                                            <th>
                                                Nombre
                                            </th>
                                            <th>
                                                Imagen
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Bella Bernhard</td>
                                            <td>alanna@yahoo.com</td>
                                            <td>
                                                <span class='label label-warning'>Warning</span>
                                            </td>
                                            <td>
                                                <div class='text-right'>
                                                <a class='btn btn-success btn-mini' href='#'>
                                                    <i class='icon-ok'></i>
                                                </a>
                                                <a class='btn btn-danger btn-mini' href='#'>
                                                    <i class='icon-remove'></i>
                                                </a>
                                                </div>
                                            </td>
                                        </tr>
                                      
                                    </tbody>
                                    </table>
                                </div>
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

echo $_html->loadJS("sistema");

echo $_html->PrintBodyClose();

?>

    
