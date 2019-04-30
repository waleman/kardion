<?php
require_once('../clases/cargar_dr.php');
require_once('../clases/roles_controller.php');
require_once('../clases/dashboard_controller.php');

$html = new cargardr;
$roles = new roles;
$_dashboard = new dashboard;
$html->sessionDataSistem(); //iniciamos la sesion en el navegador
//Buscamos los permisos para entrar en esta pantalla
        $permisos = array(3);
        $rol = $_SESSION['k6']['RolId'];
        $usuarioId = $_SESSION['k6']['UsuarioId'];
        $permiso = $roles->buscarpermisos($rol,$permisos);
        if(!$permiso){
        header("Location: accesoprohibido.php");
        die();
        }
echo $html->PrintHead(); //Cargamos en header
echo $html->LoadCssSystem("sistema"); // cargamos todas las librerias css del sistema interno
echo $html->LoadJquery("sistema"); //cargamos todas las librerias Jquery del sistema interno  
echo $html->PrintBodyOpen(); //cargamos la primera parte del body
echo $html->PrintHeader(); //cargamos el header

//Datos del dashboard
$inicio = date("Y-m-01"); //fecha inicio mes actual
$fin = date("Y-m-t");//fecha fin mes actual
$cantPruebasfinalizadas = $_dashboard->Dr_cantidad_pruebasfinalizadas($usuarioId);
$cantPruebasFinalizadasMes=$_dashboard->Dr_cantidad_pruebasfinalizadas_mes($usuarioId,$inicio,$fin);
?>
<div id='wrapper'>
<div id='main-nav-bg'></div>

<?php
//cargamos el menu lateral
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
                                <i class='icon-bar-chart'></i>
                                <span>Inicio</span>
                              </h1>
                                 
                            </div>
                          </div>
                        </div>
                        <!----------- Dashboar medico ------------------->

                        <div class="span12 box" style="margin-left:0px" >
                            
                                <div class="row-fluid">

                                    <div class="span3">
                                      <div class="box-header">
                                          <div class="title">Datos de la cuenta</div>
                                      </div>
                                        <div class="box-content box-statistic">
                                            <h3 class="title text-warning"><?=$cantPruebasfinalizadas?></h3>
                                            <small>Pruebas Finalizadas Historico</small>
                                            <div class="text-warning icon-file align-right"></div>
                                        </div>
                                        <div class="box-content box-statistic">
                                            <h3 class="title text-success"><?=$cantPruebasFinalizadasMes?></h3>
                                            <small>Pruebas Finalizadas Este Mes</small>
                                            <div class="text-success icon-flag-alt align-right"></div>
                                        </div>
                                    </div>
                                    <br>
                                    <br>

                                    <div class="span12" style="margin-left:0px;">
                                    <div class="box-header">
                                      <div class="title">Enlaces rapidos</div>
                                    </div>
                                      <div class="span3 box">
                                        <div class="box-content">
                                          <div class="row-fluid">
                                            <div class="span12 box-quick-link blue-background">
                                              <a>
                                                <div class="header">
                                                  <div class="icon-book"></div>
                                                </div>
                                                <div class="content">Manual de usuario</div>
                                              </a>
                                            </div>
                                      
                                          
                                          </div>
                                        </div>
                                      </div>
                                      <div class="span3 box">
                                        <div class="box-content">
                                          <div class="row-fluid">
                                            <div class="span12 box-quick-link green-background">
                                              <a>
                                                <div class="header">
                                                  <div class="icon-bullhorn"></div>
                                                </div>
                                                <div class="content">Contacta con nosotros</div>
                                              </a>
                                            </div>
                                      
                                          
                                          </div>
                                        </div>
                                      </div>
                                      <div class="span3 box">
                                        <div class="box-content">
                                          <div class="row-fluid">
                                            <div class="span12 box-quick-link red-background">
                                              <a>
                                                <div class="header">
                                                  <div class="icon-bug"></div>
                                                </div>
                                                <div class="content">Reportar un problema</div>
                                              </a>
                                            </div>
                                      
                                          
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                               
                                </div>
                            </div>
                        </div>

                        <!----------- Dashboar medico ------------------->



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