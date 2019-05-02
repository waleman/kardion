<?php 
require_once("../clases/cargar_master.php");
require_once("../clases/roles_controller.php");
require_once("../clases/dashboard_controller.php");

$html = new cargar;
$roles = new roles;
$html->sessionDataSistem(); 
$permisos = array(1);
$rol = $_SESSION['k6']['RolId'];
$master = $_SESSION['k6']['MasterCompaniaId'];
$_dashboard = new dashboard;

//----------------------------------------------------------------------------------------------------
echo $html->PrintHead(); //Cargamos en header
echo $html->LoadCssSystem("sistema"); // cargamos todas las librerias css del sistema interno
echo $html->LoadJquery("sistema"); //cargamos todas las librerias Jquery del sistema interno  
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $html->PrintBodyOpen(); //cargamos la primera parte del body
echo $html->PrintHeader(); //cargamos el header
//----------------------------------------------------------------------------------------------------
//--------Permisos -----------------------------------
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
}
//--------dashboard -----------------------------------
$inicio = date("Y-m-01"); //fecha inicio mes actual
$fin = date("Y-m-t");//fecha fin mes actual
$cantidad_pruebas_finalizadas = $_dashboard->Master_cantidad_pruebasfinalizadas();
$cantidad_pruebas_finalizadas_mes = $_dashboard->Master_cantidad_pruebasfinalizadas_mes($inicio,$fin);
$cantidad_pacientes = $_dashboard->Master_cantidad_pacientes();
$cantidad_companias = $_dashboard->Master_cantidad_empresas();
$cantidad_dr = $_dashboard->Master_cantidad_dr();
$cantidad_dr_verificados = $_dashboard->Master_cantidad_dr_verificados();
?>

<div id='wrapper'>
<div id='main-nav-bg'></div>

<?php echo $html->PrintSideMenu();?>

                <section id='content'>
                  <div class='container-fluid'>
                    <div class='row-fluid' id='content-wrapper'>
                      <div class='span12 '>
                        <div class='row-fluid'>
                          <div class='span12 ' >
                            <div class='page-header color-gris'>
                              <h1 class='pull-left'>
                                <i class='icon-dashboard'></i>
                                <span>Inicio</span>
                              </h1>   
                            </div>
                          </div>
                        </div>

                        <h3> Datos globales</h3>
                      
                          <div class="span4 box">
                           <div class="box-header">
         
                           </div>
                           <div class="row-fluid">
                              <div class="span12">
                                <div class="box-content box-statistic">
                                  <h3 class="title text-info"><?=$cantidad_pruebas_finalizadas?></h3>
                                  <small>Pruebas finalizadas Historico  </small>
                                  <div class="text-info icon-flag align-right"></div>
                                </div>
                                <div class="box-content box-statistic">
                                  <h3 class="title text-success"><?=$cantidad_pruebas_finalizadas_mes?></h3>
                                  <small>PRuebas finalizadas este mes </small>
                                  <div class="text-success icon-flag-alt align-right"></div>
                              </div>
                           </div>
                             </div>
                          </div>
                           <div class="span3 box">
                                                  <div class="box-header">
                                                   
                                                  </div>
                                                  <div class="row-fluid">
                                                    <div class="span12">
                                                      <div class="box-content box-statistic">
                                                        <h3 class="title text-error"><?=$cantidad_pacientes?></h3>
                                                        <small>Pacientes creados</small>
                                                        <div class="text-error icon-user align-right"></div>
                                                      </div>
                                                      <div class="box-content box-statistic">
                                                        <h3 class="title text-success"><?=$cantidad_companias - 1?></h3>
                                                        <small>Empresas creadas</small>
                                                        <div class="text-success icon-building align-right"></div>
                                                      </div>
                                                
                                                    </div>
                                                  </div>
                          </div>
                           <div class="span3 box">
                                                  <div class="box-header">
                                                 
                                                  </div>
                                                  <div class="row-fluid">
                                                    <div class="span12">
                                                      <div class="box-content box-statistic">
                                                        <h3 class="title text-warning"><?=$cantidad_dr?></h3>
                                                        <small>Doctores Registrados</small>
                                                        <div class="text-warning icon-user-md align-right"></div>
                                                      </div>
                                                      <div class="box-content box-statistic">
                                                        <h3 class="title text-success"><?=$cantidad_dr_verificados?></h3>
                                                        <small>Doctores Activos</small>
                                                        <div class="text-success icon-ok align-right"></div>
                                                      </div>
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

    
