<?php
require_once("../clases/cargar.php");
require_once("../clases/centros_controller.php");
require_once("../clases/roles_controller.php");
$html = new cargar;
$centros= new centros;
$roles = new roles;
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
                            $file = fopen("../config.txt", "r") or exit("Unable to open file!");
                            $con = 0 ;
                            while(!feof($file))
                            {
                              //simbolo identificador de las cadenas 
                               $signo_inicio = '#';
                               $signo_separador = ':';
                               $signo_finValor = ';';
                              // String actual
                              $cadena_actual = fgets($file);
                              //obtener el titulo del valor
                              $incio_posicion = strpos($cadena_actual, $signo_inicio);
                              $separacion = strpos($cadena_actual, $signo_separador);
                              $fin_cadena = strpos($cadena_actual,$signo_finValor);
                              //obtener el valor

                              $titulo = substr ($cadena_actual,$incio_posicion +1 ,$separacion -1);
                              $valor = substr($cadena_actual,$separacion +1,$fin_cadena -2 );
                              print_r($titulo . "<br>");
                              print_r( $valor . "<br>");
                                 $con ++;
                            }
                            fclose($file);
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