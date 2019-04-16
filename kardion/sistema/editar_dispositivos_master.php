<?php
require_once("../clases/cargar_master.php");
require_once("../clases/dispositivos_controller.php");
require_once("../clases/centros_controller.php");
require_once("../clases/roles_controller.php");
$html = new cargar;
$roles = new roles;
$dispositivos = new dispositivos;
$centros = new centros;
$html->sessionDataSistem();
echo $html->PrintHead();
echo $html->LoadCssSystem("sistema");
echo $html->LoadJquery("sistema");
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $html->PrintBodyOpen();
echo $html->PrintHeader();

//definimos los permisos para esta pantalla;
$permisos = array(1);
$rol = $_SESSION['k6']['RolId'];
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
//buscamos el codigo master para seleccionar los centros asociados al usuario
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; 
//seleccionamos el codigo del aparato enviado por url enviado 
$aparatoId =$_GET['id'];
$datosDispositivos = $dispositivos->dispositivo($aparatoId,$master);
$listaCetros = $centros->getCentros($master);

$serie = $datosDispositivos['0']["Serie"];
$nombre = $datosDispositivos['0']["Nombre"];
$centroGuardado = $datosDispositivos['0']["CentroId"];
$AsignadoId = $datosDispositivos['0']["AsignadoId"];

?>

<div id='wrapper'>
<div id='main-nav-bg'></div>

<?php echo $html->PrintSideMenu();?>



<script>
    
    $(document).ready(function(){

          function mostrar(texto) {
              // Get the snackbar DIV
              document.getElementById("errorMensaje").innerText= texto;
              var x = document.getElementById("snackbar");
              // Add the "show" class to DIV
              x.className = "show";
              // After 3 seconds, remove the show class from DIV
              setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
          }

          $("#btnregister").click(function (){
                if(!document.getElementById('txtnombre').value){
                    let val = "Debe escribir un nombre";
                    mostrar(val);
                    return false;   
                }else{
                    return true;
                }     
          });

   

          
     });
    
</script>
                <section id='content'>
                  <div class='container-fluid'>
                    <div class='row-fluid' id='content-wrapper'>
                      <div class='span12'>
                        <div class='row-fluid'>
                          <div class='span12'>
                            <div class='page-header'>
                              <h1 class='pull-left'>
                                <!-- <i class='icon-bar-chart'></i> -->
                                <span> Dispositivo  serie <?php echo $serie ; ?></span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -------------------------------------------- -->


                 
                
                  <form class='form' method="POST" id="frm_filtrar" style='margin-bottom: 0;'>
                                
                  <div class="span12 box bordered-box blue-border">
                  <div class="box-header blue-background">
                    <div class="title">
                        Datos del dispositivo
                    </div>
                    <div class="actions">
                      <a class="btn box-remove btn-mini btn-link" href="#"><i class="icon-remove"></i>
                      </a>
                      
                      <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                      </a>
                    </div>
                  </div>
                  <div class="box-content box-double-padding">
                    <form class="form" style="margin-bottom: 0;">
                      <fieldset>
                        <div class="span4">
                          <div class="lead">
                            <i class="icon-github text-contrast"></i>
                            Lorem ipsum
                          </div>
                          <small class="muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nisl est, vulputate at porttitor non, interdum a mauris. Phasellus imperdiet gravida pulvinar.</small>
                        </div>
                        <div class="span7 offset1">
                          <div class="control-group">
                            <label class="control-label">Nombre del dispositivo</label>
                            <div class="controls">
                              <input class="span12" id="full-name" placeholder="Este nombre sera mostrado a los usuarios" type="text">
                              <p class="help-block"></p>
                            </div>
                          </div>

                          <div class="control-group">
                            <label class="control-label">Numero de serie / SN</label>
                            <div class="controls">
                              <input class="span12" id="address-line2" placeholder="Numero de serie" type="number">
                              <small class="muted">Description for street field</small>
                            </div>
                          </div>
                          
                          <div class="control-group">
                            <label class="control-label">Company</label>
                            <div class="controls">
                              <div class="input-append">
                                <input class="span6" id="appendedInputButtons1" type="text">
                                <button class="btn" type="button">
                                  <i class="icon-building"></i>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                      <hr class="hr-normal">
                      <fieldset>
                        <div class="span4 box">
                          <div class="lead">
                            <i class="icon-ambulance contrast"></i>
                            Dapibus suscipit arcu
                          </div>
                          <small class="muted">Proin eu nibh ut urna tristique rhoncus. Sed euismod, quam sed dignissim imperdiet, nulla leo vehicula mi, a sagittis lacus augue nec sapien.</small>
                        </div>
                        <div class="span7 offset1">
                          <div class="control-group">
                            <label class="control-label">Disabled input</label>
                            <div class="controls">
                              <input class="span12" disabled="" id="full-name1" type="text">
                              <p class="help-block"></p>
                            </div>
                          </div>
                          <div class="control-group">
                            <div class="controls">
                              <label class="checkbox inline">
                                <input id="inlineCheckbox1" type="checkbox" value="option1">
                                Inline 1
                              </label>
                              <label class="checkbox inline">
                                <input id="inlineCheckbox2" type="checkbox" value="option2">
                                Inline 2
                              </label>
                              <label class="checkbox inline">
                                <input id="inlineCheckbox3" type="checkbox" value="option3">
                                Inline 3
                              </label>
                            </div>
                          </div>
                          <div class="control-group">
                            <label class="control-label">Money</label>
                            <div class="controls">
                              <div class="input-prepend input-append">
                                <span class="add-on">$</span>
                                <input class="span6 text-right" id="appendedPrependedInput" type="text">
                                <span class="add-on">.00</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                      <div class="form-actions" style="margin-bottom: 0;">
                        <div class="text-right">
                          <div class="btn btn-primary btn-large">
                            <i class="icon-save"></i>
                            Save
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                      

                                    <!-------------------------------------- Datos Generales -->
                        
                       
                            <div class='text-center'>
                                <input class="btn btn-primary  btn-large" name="btnregister" id="btnregister" value="Guardar" type="submit" />  
                                <a class="btn btn-danger  btn-large" name="btncancelar" id="btncancelar" href="lista_dispositivos.php">Cancelar </a>                   
                                <br><br>
            
                            </div>
                       
                     </form>
                  <div id="snackbar">
                        <label id="errorMensaje"></label>
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
