<?php 
require_once("../clases/cargar.php");
require_once("../clases/roles_controller.php");
require_once("../clases/dashboard_controller.php");
$html = new cargar;
$dashboard = new dashboard;
$html->sessionDataSistem(); //iniciamos la sesion en el navegador
//Buscamos los permisos para entrar en esta pantalla
$roles = new roles;
//definimos los permisos para esta pantalla;
$permisos = array(4,5,6);
$rol = $_SESSION['k6']['RolId'];
$master = $_SESSION['k6']['MasterCompaniaId'];
$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
  header("Location: accesoprohibido.php");
}
echo $html->PrintHead(); //Cargamos en header
echo $html->LoadCssSystem("sistema"); // cargamos todas las librerias css del sistema interno
echo $html->LoadJquery("sistema"); //cargamos todas las librerias Jquery del sistema interno  
echo $html->PrintBodyOpen(); //cargamos la primera parte del body
echo $html->PrintHeader(); //cargamos el header
//----------------------------------------------------------------------------------------------------
$cantidad_centros = $dashboard->Emp_cantidad_centros($master);
$cantidad_usuarios = $dashboard->Emp_cantidad_usuarios($master);
$cantidad_aparatos = $dashboard->Emp_cantidad_aparatos($master);
$cantidad_total_fin = $dashboard->Emp_cantidad_pruebasFinalizadas($master);
$cantidad_total_pruebas = $dashboard->Emp_cantidad_pruebas($master);
$inicio = date("Y-m-01"); //fecha inicio mes actual
$fin = date("Y-m-t");//fecha fin mes actual
$cantidad_mes_pruebas = $dashboard->Emp_cantidad_pruebas_mes($master,$inicio,$fin);
$cantidad_mes_fin =$dashboard->Emp_cantidad_pruebasFinalizadas_mes($master,$inicio,$fin);

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
                        <?php
                              if($rol != 6){?>

                        
                                <!-- -------------------------------------------------------------------------- -->
                                <div class="row-fluid">
                                                <div class="span4 box">
                                                  <div class="box-header">
                                                    <div class="title">
                                                      <!-- <div class="icon-inbox"></div> -->
                                                      Datos de la empresa
                                                    </div>
                                                    <div class="actions">
                                                      <a class="btn box-remove btn-mini btn-link" href="#"><i class="icon-remove"></i>
                                                      </a>
                                                      
                                                      <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                                                      </a>
                                                    </div>
                                                  </div>
                                                  <div class="row-fluid">
                                                    <div class="span12">
                                                      <div class="box-content box-statistic">
                                                        <h3 class="title text-info"><?=$cantidad_centros?></h3>
                                                        <small>Centros  </small>
                                                        <div class="text-info icon-group align-right"></div>
                                                      </div>
                                                      <div class="box-content box-statistic">
                                                        <h3 class="title text-success"><?=$cantidad_usuarios?></h3>
                                                        <small>Usuarios </small>
                                                        <div class="text-success icon-user align-right"></div>
                                                      </div>
                                                      <div class="box-content box-statistic">
                                                        <h3 class="title text-primary"><?=$cantidad_aparatos?></h3>
                                                        <small>Dispositivos</small>
                                                        <div class="text-primary icon-bolt align-right"></div>
                                                      </div>
                                                    </div>
                                
                                                  </div>
                                                </div>
                                                <div class="span3 box">
                                                  <div class="box-header">
                                                    <div class="title">
                                                      <!-- <i class="icon-group"></i> -->
                                                      Pruebas este mes
                                                    </div>
                                                  </div>
                                                  <div class="row-fluid">
                                                    <div class="span12">
                                                      <div class="box-content box-statistic">
                                                        <h3 class="title text-error"><?=$cantidad_mes_pruebas?></h3>
                                                        <small>Enviadas</small>
                                                        <div class="text-error icon-envelope-alt align-right"></div>
                                                      </div>
                                                      <div class="box-content box-statistic">
                                                        <h3 class="title text-success"><?=$cantidad_mes_fin?></h3>
                                                        <small>Entregadas</small>
                                                        <div class="text-success icon-ok align-right"></div>
                                                      </div>
                                                      <!-- <div class="box-content box-statistic">
                                                        <h3 class="title text-primary">12:21</h3>
                                                        <small>Average time</small>
                                                        <div class="text-primary icon-time align-right"></div>
                                                      </div> -->
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="span3 box">
                                                  <div class="box-header">
                                                    <div class="title">
                                                      <!-- <i class="icon-comments"></i> -->
                                                      Pruebas historico
                                                    </div>
                                                  </div>
                                                  <div class="row-fluid">
                                                    <div class="span12">
                                                      <div class="box-content box-statistic">
                                                        <h3 class="title text-error"><?=$cantidad_total_pruebas?></h3>
                                                        <small>Enviadas</small>
                                                        <div class="text-error icon-envelope-alt align-right"></div>
                                                      </div>
                                                      <div class="box-content box-statistic">
                                                        <h3 class="title text-success"><?=$cantidad_total_fin?></h3>
                                                        <small>Entregadas</small>
                                                        <div class="text-success icon-ok align-right"></div>
                                                      </div>
                                                      <!-- <div class="box-content box-statistic">
                                                        <h3 class="title text-info">123</h3>
                                                        <small>Pending</small>
                                                        <div class="text-info icon-time align-right"></div>
                                                      </div>
                                                    </div> -->
                                                  </div>
                                                </div>
                                              </div>

                                <!-- ----------------------------------------------------------------------------------- -->

                            <?php  }else{

                              /* ---------------- aqui el dashboard del  asistente  ----------------------*/ 


                              /* ------------------------------------------------------------------------- */
                            }
                          ?>
                        <br>
                      </div>
                    </div>
                  </div>
                </section>
</div>

<?php 

echo $html->loadJS("sistema");

echo $html->PrintBodyClose();

?>

    
