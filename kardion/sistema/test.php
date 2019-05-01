<?php
require_once("../clases/cargar.php");
require_once("../clases/centros_controller.php");
require_once("../clases/roles_controller.php");
require_once("../clases/pruebas_controller.php");
$html = new cargar;
$centros= new centros;
$roles = new roles;
$_pruebas = new pruebas;
$html->sessionDataSistem();
echo $html->PrintHead();
echo $html->LoadCssSystem("sistema");
echo $html->LoadJquery("sistema");
echo $html->PrintBodyOpen();
echo $html->PrintHeader();

//definimos los permisos para esta pantalla;
$permisos = array(1,2,3,4,5,6);
$rol = $_SESSION['k6']['RolId'];
$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
  header("Location: accesoprohibido.php");
}
//buscamos el codigo master para seleccionar los centros asociados al usuario
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; // Codigo del usuario

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
                                <span> Modificar centro</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -------------------------------------------- -->
                        <?php
                            echo $_pruebas->EnviarMailPruebaPublicada("1");
                        ?>    
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