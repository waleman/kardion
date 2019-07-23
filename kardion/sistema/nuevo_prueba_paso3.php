<?php 
require_once("../clases/cargar.php");
require_once("../clases/roles_controller.php");
require_once("../clases/pruebas_controller.php");
require_once("../clases/alertas_controller.php");

$_pruebas = new pruebas;
$roles = new roles;
$html = new cargar;
$_alertas = new alertas;

$html->sessionDataSistem(); 
echo $html->PrintHead(); //Cargamos en header
echo $html->LoadCssSystem("sistema"); // cargamos todas las librerias css del sistema interno
echo $html->LoadJquery("sistema"); //cargamos todas las librerias Jquery del sistema interno 
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $html->loadFileuploadJS("sistema");
echo $html->loadFileuploadCSS("sistema");
echo $html->PrintBodyOpen(); //cargamos la primera parte del body
echo $html->PrintHeader(); //cargamos el header

//definimos los permisos para esta pantalla;
$permisos = array(4,5,6);
$rol = $_SESSION['k6']['RolId'];
$permiso = $roles->buscarpermisos($rol,$permisos);
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; // Codigo del usuario

    if(!$permiso){
      echo $_alertas->errorRedirect("Wow","No tienes permiso para acceder a esta pagina","accesoprohibido.php");
      die();
    }
    if(!isset($_GET['personaid']) || !isset($_GET['codigoprueba']) || !isset($_GET['centroid']) ){
      echo $_alertas->infoRedirect("Volvamos un paso atras","Aun no ha creado la prueba prueba","nuevo_prueba.php");
      die();
    }


   $personaid = $_GET['personaid'];
   $codigoprueba = $_GET['codigoprueba'];
   $centroid = $_GET['centroid'];
   $codigoArchivo = $personaid . "_".$codigoprueba;


    if(isset($_POST['btnenviar'])){
      $verificarArchivo = $_pruebas->VefiricarArchivo($codigoArchivo);
      if($verificarArchivo){
          $veri = $_pruebas->actualizarPrueba($codigoArchivo,$centroid);
          if($veri){
            echo $_alertas->successRedirect("Hecho!","Datos guardados exitosamente","lista_pruebas.php");
          }else{
            echo $_alertas->error("Error!","Error al guardar la prueba");
          }
      }else{
        echo $_alertas->info("Aviso!","Debe adjuntar el archivo ECG para enviar la prueba");
      }

    }

?>




<div id='wrapper'>
<div id='main-nav-bg'></div>

<?php echo $html->PrintSideMenu();?>

                <section id='content'>
                  <div class='container-fluid'>
                    <div class='row-fluid' id='content-wrapper'>
                      <div class='span12'>
                            <div class='row-fluid'>
                                <div class='span12'>
                                    <div class='page-header '>
                                    <h1 class='pull-left title'>
                                        <div style="display:inline; color:#009688">  Adjunta los archivos de la prueba</div> 
                                    </h1>
                                    </div>
                                </div>
                            </div>
                            
                         
                            <form id="cuadro" action="" class="dropzone">

                            </form>

                            <script type="text/javascript">
                                      var errors = false;
                                      var Dropzone = new Dropzone("#cuadro", {
                                                url: "../utilidades/pruebasupload.php?id=<?=$personaid?>&codigo=<?=$codigoprueba?>",
                                                acceptedFiles: ".EDF,.edf,.pdf,.PDF,.rar,.RAR,.jpg,.png,.gif",
                                                maxFiles: 1,
                                                error:function(){
                                                    errors = true;
                                                },
                                                processing:function(){
                                                    $('#texto_carga').show();
                                                    $("#texto_carga2").delay(10000).fadeIn(1000);
                                                    $("#texto_carga3").delay(15000).fadeIn(1000);
                                                    $("#texto_carga4").delay(40000).fadeIn(1000);
                                                },
                                                timeout: 1800000,
                                                complete:function(){
                                                    if(errors){
                                                      swal({
                                                            title: 'Error al cargar el achivo!',
                                                            text: 'Ha ocurrido un error al intentar cargar el archivo. Póngase en contacto con el administrador del sistema',
                                                            type: 'error',
                                                            icon: 'error'
                                                        });
                                                        $('#texto_carga').hide();
                                                        $('#texto_carga2').hide();
                                                        $('#texto_carga3').hide();
                                                        $('#texto_carga4').hide();
                                                        $("#texto_carga2").stop(false,true, true);
                                                        $("#texto_carga3").stop(false,true, true);
                                                        $("#texto_carga4").stop(false,true, true);

                                                    }else{
                                                      $(".dz-progress").remove();
                                                      swal({
                                                            title: 'Carga completa!',
                                                            text: 'Hemos cargado el archivo de la prueba exitosamente',
                                                            type: 'success',
                                                            icon: 'success'
                                                        });

                                                        $("#texto_carga2").stop(false,true, true);
                                                        $("#texto_carga3").stop(false,true, true);
                                                        $("#texto_carga4").stop(false,true, true);
                                                        $('#texto_carga').hide();
                                                        $('#texto_carga2').hide();
                                                        $('#texto_carga3').hide();
                                                        $('#texto_carga4').hide();
                                                        
                                                    }
                                                }
                                              });
                                      </script> 
    

                                      <p id="texto_carga" style="color: #009688; display:none">Espera mientras se procesa el archivo...</p> 
                                      <p id="texto_carga2" style="color: #009688; display:none">Esto puede tardar aporximadamente 1 minuto dependiendo el tamaño del archivo y la velocidad de internet.</p> 
                                      <p id="texto_carga3" style="color: #009688; display:none">Estamos procesando el archivo.</p> 
                                      <p id="texto_carga4" style="color: #009688; display:none">Compartiendo el archivo en la carpeta de los medicos.</p> 


                                  <form  method="post">
                                    <div class="form-actions" style="margin-bottom: 0;">
                                       <div class="text-center">
                                            <input type="submit" class="btn btn-primary btn-large" value="Guardar y enviar prueba" name="btnenviar" id="btnenviar"/>                            
                                        </div>
                                    </div>
                                    </form>

                </section>
         
                <div id="snackbar">
                     <label id="errorMensaje"></label>
               </div>
</div>
<script src="nuevo_prueba_paso2.js"></script>
<?php 

echo $html->loadJS("sistema");

echo $html->PrintBodyClose();

?>


