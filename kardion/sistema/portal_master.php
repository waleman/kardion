<?php 
require_once("../clases/cargar_master.php");
require_once("../clases/roles_controller.php");
//require_once("../clases/dashboard_controller.php");

$html = new cargar;
$roles = new roles;
$html->sessionDataSistem(); 
$permisos = array(1);
$rol = $_SESSION['k6']['RolId'];
$master = $_SESSION['k6']['MasterCompaniaId'];



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
//--------Permisos -----------------------------------


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


                        datos
                      </div>
                    </div>
                  </div>
                </section>
</div>

<?php 

echo $html->loadJS("sistema");

echo $html->PrintBodyClose();

?>

    
