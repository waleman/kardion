<?php
require_once('../clases/cargar_dr.php');
require_once('../clases/roles_controller.php');

$html = new cargardr;
$roles = new roles;
$html->sessionDataSistem(); //iniciamos la sesion en el navegador
//Buscamos los permisos para entrar en esta pantalla
        $permisos = array(3);
        $rol = $_SESSION['k6']['RolId'];
        $permiso = $roles->buscarpermisos($rol,$permisos);
        if(!$permiso){
        header("Location: accesoprohibido.php");
        }
echo $html->PrintHead(); //Cargamos en header
echo $html->LoadCssSystem("sistema"); // cargamos todas las librerias css del sistema interno
echo $html->LoadJquery("sistema"); //cargamos todas las librerias Jquery del sistema interno  
echo $html->PrintBodyOpen(); //cargamos la primera parte del body
echo $html->PrintHeader(); //cargamos el header

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
                        <p>Datos del sistema</p>
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