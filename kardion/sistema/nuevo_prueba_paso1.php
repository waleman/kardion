<?php
require_once("../clases/cargar.php");
require_once("../clases/alertas_controller.php");
require_once("../clases/roles_controller.php");
require_once("../clases/clasificacion_controller.php");
require_once("../clases/alertas_controller.php");
require_once("../clases/personas_controller.php");

$html = new cargar;
$_alertas= new alertas;
$_clasificacion = new clasificacion;
$roles = new roles;
$_personas = new personas;

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
$permiso = $roles->buscarpermisos($rol,$permisos);
  if(!$permiso){
    echo $_alertas->errorRedirect("No tiene permisos","Usted no tiene permiso para acceder a esta pagina","accesoprohibido.php");
    die();
  }
//buscamos el codigo master para seleccionar los centros asociados al usuario
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; // Codigo del usuario
//Buscamos el Id que envian por parametro
 $personaId = "";
 $enlace = "";
if(isset($_GET['persona'])){
$personaId = $_GET['persona'];
}else{

  echo $_alertas->warningRedirect('Aviso!','Debe seleccionar un paciente primero','nuevo_prueba.php');
  die();

}
$ListaClasificaciones = $_clasificacion->obtenerClasificacionesActivas();
if(empty($ListaClasificaciones)){$ListaClasificaciones = array();}


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
                              <h1 class='text-center'>
                                <span style="color:#009688;" > Seleccione el tipo de prueba que desea realizar</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -------------------------------------------- -->
                         <div class="row-fluid">

                         <?php
                                foreach ($ListaClasificaciones as $key => $value) {
                                    $id = $value['ClasificacionId'];
                                    $titulo = $value['Titulo'];
                                    $icono = $value['Icono'];

                                      echo "
                                      <a href='nuevo_prueba.php?clasificacion=$id' class=''  id='btn$id' name='btn$id' >
                                            <div class='span3 box box-bordered' style=' margin-left: 10px'>
                                                <div class='box-header box-header-large test' style='font-size:12px;background-color:#fff;text-align:center; min-height: 160px;     max-height: 160px;'>
                                                            <img style='height:100px' src='../public/iconos/$icono' alt=''>
                                                            <h4>$titulo</h4>
                                                </div>
                                            </div>
                                      </a>

                                      ";

                                    echo "
                                      <script>
                                   
                                      
                                       $('#btn$id').click(function(e){
                                        $('#nextpage').attr('href','../utilidades/documentos/clasificacion.php?persona=$personaId&id=$id');
                                        $('#modaldocumentos').modal('show');
                                        $('#btnaceptar').attr('href','nuevo_prueba_paso2.php?persona=$personaId&id=$id');

                                        return false;
                                       });


                                      </script>
                                    ";
                                     
                                }

                                echo "
                                  <a href='nuevo_prueba.php' class=''  id='btn$id' name='btn$id' >
                                    <div class='span3 box box-bordered' style=' margin-left: 10px'>
                                        <div class='box-header box-header-large test' style='font-size:12px;background-color:#fff;text-align:center; min-height: 160px;     max-height: 160px;'>
                                                    <img style='height:100px' src='../assets/images/change.png' alt=''>
                                                    <h4>Cambiar de paciente</h4>
                                        </div>
                                    </div>
                                  </a>
                                ";
                            ?>                                 
                        </div>  
                         <!-- -------------------------------------------- -->

                       </div>
                    </div>
                  </div>
                </section>

               
                <!------------------- Modal Upload ----------------------------------->
                <div class='modal hide fade' id='modaldocumentos' role='dialog' tabindex='-1'>
                      <div class='modal-header'>
                        <button class='close' data-dismiss='modal' type='button'>&times;</button>
                        <h3>Informacion sobre la prueba</h3>
                      </div>
                      <div class='modal-body'>
                        <div class="box-content">
                               <!-- cargar archivos --> 
                                     
                              
                                      <div class="control-group text-center">
                                      <H3>CONSENTIMIENTO INFORMADO</H3>

                     
                                        <a href='' class='btn btn-inverse btn-large' target="_blank"  id='nextpage' name='netpage' >
                                          <i class=" icon-print"></i>
                                          Ver / Imprimir
                                        </a>

                                      </div>
                                <!-- cargar archivos -->
                        
                        </div>
                      </div>
                      <div class='modal-footer'>
                        <a class='btn btn-success' href="" id="btnaceptar" name="btnaceptar">Continuar</a>
                        <button class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
                      </div>
                </div>
             <!------------------- Modal Upload ----------------------------------->
</div>
<?php 
echo $html->loadJS("sistema");
echo $html->PrintBodyClose();
?>