<?php
require_once("../clases/cargar_master.php");
require_once("../clases/roles_controller.php");
require_once("../clases/alertas_controller.php");
require_once("../clases/companias_controller.php");
require_once("../clases/dashboard_controller.php");
$html = new cargar;
$roles = new roles;
$_alertas = new alertas;
$_companias = new companias;
$_dashboard = new dashboard;

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

$MasterCompaniaId = $_GET['id'];
$datos = $_companias->Compania($MasterCompaniaId);
$Nombre = $datos[0]['Nombre'];


$inicio = date("Y-m-01"); //fecha inicio mes actual
$fin = date("Y-m-t");//fecha fin mes actual

// datos
$finalizadas = $_companias->ListaPruebasFinalizadasCompania($MasterCompaniaId);
$descartadas = $_companias->ListaPruebasDescartadasCompania($MasterCompaniaId);
$proceso = $_companias->ListaPruebasPendientesCompania($MasterCompaniaId);

$centros = $_companias->ListaCentros($MasterCompaniaId);
$personas = $_companias->ListaPersonas($MasterCompaniaId);
$usuarios = $_companias->ListaUsuarios($MasterCompaniaId);


$mes_finalizadas = $_dashboard->Emp_cantidad_pruebasFinalizadas_mes($MasterCompaniaId,$inicio ,$fin);
$mes_descartadas = $_dashboard->Emp_cantidad_pruebasDescartadas_mes($MasterCompaniaId,$inicio ,$fin);


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

                         <div class='row-fluid'>
                                                     <div class="span3">
                                                      <div class="box-header">
                                                          <div class="title">
                                                            <!-- <div class="icon-inbox"></div> -->
                                                            <h4>Pruebas este mes</h4>
                                                          </div>
                                                          <hr>
                                                      </div>
                                                      <div class="box-content box-statistic">
                                                        <h3 class="title text-success"><?=$mes_finalizadas?></h3>
                                                        <small>Pruebas finalizadas  </small>
                                                        <div class="text-success icon-ok align-right"></div>
                                                      </div>
                                                      <div class="box-content box-statistic">
                                                        <h3 class="title text-warning"><?=$mes_descartadas?></h3>
                                                        <small>Pruebas Descartadas </small>
                                                        <div class="text-warning icon-trash align-right"></div>
                                                      </div>
                                                    
                                                    </div>


                                                <div class="span3">
                                                      <div class="box-header">
                                                          <div class="title">
                                                            <!-- <div class="icon-inbox"></div> -->
                                                            <h4>Pruebas Historico</h4>
                                                          </div>
                                                          <hr>
                                                      </div>
                                                      <div class="box-content box-statistic">
                                                        <h3 class="title text-success"><?=$finalizadas?></h3>
                                                        <small>Pruebas finalizadas  </small>
                                                        <div class="text-success icon-ok align-right"></div>
                                                      </div>
                                                      <div class="box-content box-statistic">
                                                        <h3 class="title text-warning"><?=$descartadas?></h3>
                                                        <small>Pruebas Descartadas </small>
                                                        <div class="text-warning icon-trash align-right"></div>
                                                      </div>
                                                      <div class="box-content box-statistic">
                                                        <h3 class="title text-primary"><?=$proceso?></h3>
                                                        <small>Pruebas en proceso</small>
                                                        <div class="text-primary icon-exchange align-right"></div>
                                                      </div>
                                                    </div>
                                                  <div class="span3">
                                                    <div class="box-header">
                                                        <div class="title">
                                                          <!-- <div class="icon-inbox"></div> -->
                                                          <h4>Datos de la empresa</h4>
                                                        </div>
                                                        <hr>
                                                    </div>
                                                      <div class="box-content box-statistic">
                                                          <h3 class="title text-inverse"><?=$centros?></h3>
                                                          <small>Centros  </small>
                                                          <div class="text-inverse icon-hospital align-right"></div>
                                                        </div>
                                                        <div class="box-content box-statistic">
                                                          <h3 class="title text-info"><?=$usuarios?></h3>
                                                          <small>Usuarios  </small>
                                                          <div class="text-info icon-user align-right"></div>
                                                        </div>
                                                        <div class="box-content box-statistic">
                                                          <h3 class="title text-success"><?=$personas?></h3>
                                                          <small>Clientes creados  </small>
                                                          <div class="text-success icon-male align-right"></div>
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