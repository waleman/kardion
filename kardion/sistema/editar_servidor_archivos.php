<?php
require_once("../clases/cargar_master.php");
require_once("../clases/roles_controller.php");
require_once("../clases/alertas_controller.php");
require_once("../clases/servidor_archivos_controller.php");
$html = new cargar;
$roles = new roles;
$_alertas = new  alertas;
$_servidor = new servidorArchivos;

$html->sessionDataSistem();
echo $html->PrintHead();
echo $html->LoadCssSystem("sistema");
echo $html->LoadJquery("sistema");
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $html->PrintBodyOpen();
echo $html->PrintHeader();

//definimos los permisos para esta pantalla;
$permisos = array(1);
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; 
$rol = $_SESSION['k6']['RolId'];

$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
    echo $_alertas->errorRedirect("Autorización Denegada","Usted no tiene permisos para acceder a esta página.", "accesoprohibido.php");
}


//verificamos si hay una conexion 
$datosconexion = $_servidor->buscarConexion();


$key ="";
$secret ="";
$token ="";
$appname="";
$megas = 4;
if(!empty($datosconexion)){
   $key = $datosconexion[0]['Keyapp'];
   $secret = $datosconexion[0]['Secret'];
   $token = $datosconexion[0]['Token'];
   $appname = $datosconexion[0]['Appname'];
   $megas = $datosconexion[0]['Megas'];
}


if(isset($_POST['btnregister'])){
    $key = $_POST['txtkey'];
    $secret =  $_POST['txtsecret'];
    $token = $_POST['txttoken'];
    $appname = $_POST['txtappname'];
    $megas = $_POST['txtmegas'];

    if(empty($datosconexion)){
        $resp  = $_servidor->guardar($key,$secret,$token,$appname,$megas);
        if($resp){
            echo $_alertas->successRedirect("Hecho","Datos guardados exitosamente","portal_master.php");
        }else{
            echo $_alertas->error("Error","No se guardaron los datos");
        }
    }else{
        $resp  = $_servidor->modificar($key,$secret,$token,$appname,$megas);
        if($resp){
            echo $_alertas->successRedirect("Hecho","Datos guardados exitosamente","portal_master.php");
        }else{
            echo $_alertas->error("Error","No se guardaron los datos");
        }
    }
}

?>

<div id='wrapper'>
<div id='main-nav-bg'></div>

<script>
    $(document).ready(function(){

        $('#txtmegas').on('input', function () { 
                this.value = this.value.replace(/[^0-9]/g,'');
            });

        function mostrar(texto) {
              // Get the snackbar DIV
              document.getElementById("errorMensaje").innerText= texto;
              var x = document.getElementById("snackbar");
              // Add the "show" class to DIV
              x.className = "show";
              // After 3 seconds, remove the show class from DIV
              setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
          }

        $('#btnregister').click(function(){
           if (!$('#txtkey').val()){
             let val = "Debe escribir la llave para conectar con el api de dropbox";
             mostrar(val);
             return false;
           }else if(!$('#txtsecret').val()){
             let val = "Debe escribir la llave secreta para conectar con el api de dropbox";
             mostrar(val);
             return false;
           }else if(!$('#txttoken').val()){
             let val = "Debe escribir el token para conectar con el api de dropbox";
             mostrar(val);
             return false;
           }else if(!$('#txtappname').val()){
             let val = "Debe escribir el nombre de la app para conectar con el api de dropbox";
             mostrar(val);
             return false; 
           }else{
               return true;
           } 
        });


        $('#txtcuerpo').wysihtml5(
            {
            "font-styles": true, 
            "emphasis": true, 
            "lists": true, 
            "html": false, 
            "link": false, 
            "image": false, 
            "color": false 
           }
        );

        
    });
</script>

<?php echo $html->PrintSideMenu();?>


                <section id='content'>
                  <div class='container-fluid'>
                    <div class='row-fluid' id='content-wrapper'>
                      <div class='span12'>
                        <div class='row-fluid'>
                          <div class='span12'>
                            <div class='page-header'>
                              <h1 class='pull-left'>
                                <!-- <i class='icon-bar-chart'></i> -->
                                <span style="color:#009688">Conexion a Dropbox</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -----------------Formulario--------------------------- -->
                         <form method="post" enctype="multipart/form-data">
                            <!----------------------------------- Texto del documento -->
                            <fieldset>
                                 <div class='span12 '>
                                  
                                      <div class='span12 box'>
                                            <div class="box-content box-double-padding">
                                            <div class='row-fluid'>
                                                    <div  class='span6 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Key</label>  
                                                            <div class='controls'>
                                                            <input class='span12' id='txtkey' name="txtkey" type='text' value="<?= $key?>" >
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div> 
                                            </div>
                                            <div class='row-fluid'>
                                                    <div  class='span6 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Secret</label>  
                                                            <div class='controls'>
                                                            <input class='span12' id='txtsecret' name="txtsecret" type='text' value="<?= $secret?>" >
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div> 
                                            </div>
                                            <div class='row-fluid'>
                                                <div  class='span6 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Token </label>
                                                            <div class='controls'>
                                                            <input class='span12' id='txttoken' name="txttoken" type='text' value="<?= $token?>" >
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class='row-fluid'>
                                                <div  class='span6 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Appname </label>
                                                            <div class='controls'>
                                                            <input class='span12' id='txtappname' name="txtappname" type='text' value="<?= $appname?>" >
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class='row-fluid'>
                                                <div  class='span4 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Megas </label>
                                                            <div class='controls'>
                                                            <input class='span12' id='txtmegas' name="txtmegas" type='number' value="<?= $megas?>" >
                                                            <p class='help-block'>Cantidad de megas maximos para hacer el cambio de servidor</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class='text-center'> 
                                            <input class="btn btn-primary  btn-large" name="btnregister" id="btnregister" value="Guardar" type="submit" /> 
                                            <a class="btn btn-danger  btn-large" name="btncancelar" id="btncancelar" href="portal_master.php">Cancelar </a>                   
                                            <br><br>
                                        </div>
                                        </div>
                                      </div>
                           </fieldset>
                              
                            
                        </form>
                        <br>

                             
                        <!------------------------- Formulario -------------------------->
                  </div>
                </div>
                <div id="snackbar" style="background-color: #ad2222;">
                        <label id="errorMensaje"></label>
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
