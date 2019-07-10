<?php 
require_once("../clases/cargar.php");
require_once("../clases/dispositivos_controller.php");
require_once("../clases/roles_controller.php");
$html = new cargar;
$dispositivos = new dispositivos;
$roles = new roles;
$html->sessionDataSistem();
echo $html->PrintHead();
echo $html->LoadCssSystem("sistema");
echo $html->LoadJquery("sistema");
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $html->PrintBodyOpen();
echo $html->PrintHeader();

//definimos los permisos para esta pantalla;
$permisos = array(4,5);
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
$usuarioid = $_SESSION['k6']['UsuarioId'];

$ListaModelos = $dispositivos->modelos($master);

if(empty($ListaModelos)){
    $ListaModelos =[];
}

if(isset($_POST["btnregister"])){

    $serie = $_POST['txtserie'];
    $modelo =$_POST['cbomodelo'];

    $existe = $dispositivos->VerificarSiExiste($serie,$modelo);
    $cantidad = $existe[0]['cantidad'];//debe ser = a 1 
    $aparatoid= $existe[0]['AparatoId'];// id del aparato

    if($cantidad > 0){ 
        $verificar = $dispositivos->verificarNoVinculado($serie);
        if($verificar == 0){
            $vincular = $dispositivos->vincular($master,$aparatoid,$usuarioid);
            if($vincular){
               $dispositivos->ActualizarEstado($aparatoid,$usuarioid);
                echo"<script>
                swal({
                        title: 'Hecho!',
                        text: 'Dispositivo Vinculado ',
                        type: 'success',
                        icon: 'success'
                }).then(function() {
                    window.location = 'lista_dispositivos.php';
                 });;
                </script>";
            }else{
              echo"<script>
            swal({
                    title: 'Error!',
                    text: 'Error al vincular , Póngase en contacto con servicio al cliente',
                    type: 'error',
                    icon: 'error'
            });
            </script>";
            }
        }else{
            echo"<script>
            swal({
                    title: 'Error!',
                    text: 'El dispositivo que intenta vincualar ya esta siendo utilizado. Si no esta siendo utilizado por usted , Póngase en contacto con servicio al cliente',
                    type: 'error',
                    icon: 'error'
            });
            </script>";
        }

    }else{
        echo"<script>
        swal({
                title: 'Error!',
                text: 'El Numero de serie y modelo no concuerdan  o lo esta escribiendo erroneamente.  Si el problema persiste póngase en contacto con servicio al cliente.',
                type: 'error',
                icon: 'error'
        });
        </script>";
    }

   




}

?>

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
                if(!document.getElementById('txtserie').value){
                    let val = "Debe escribir el numero de serie";
                    mostrar(val);
                    return false;   
                }else if(document.getElementById('cbomodelo').value == 0){
                    let val = "Debe seleccionar un modelo";
                    mostrar(val);
                    return false;   
                }else{
                     return true;      
                }
          });


          $("#cborol").change(function() {
                let valor = document.getElementById('cborol').value
               if(valor == 6){
                $("#panelpermisos").show();
               }else{
                $("#panelpermisos").hide();
               }
          });
     });

</script>

<div id='wrapper'>
<div id='main-nav-bg'></div>

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
                                <span>Vincular dipositivo</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                       <!-- ----------------------------------------- -->

                       <div class="span12 box bordered-box blue-border" style="margin-left: 0px;">
                            <div class="box-header blue-background">
                                <div class="title">
                                <i class="icon-bolt"></i>
                                Escriba los datos del dispositivo
                                </div>
                                <div class="actions">
                            
                                
                                <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                                </a>
                                </div>
                            </div>
                            <div class="box-content box-double-padding">
                                <form class="form" style="margin-bottom: 0;" method="POST">
                                <fieldset>
                                    <div class="span4">
                                    <div class="lead">
                                        <i class="icon-bolt text-contrast"></i>
                                        ¿Que es SN y Model?
                                    </div>
                                    <small class="muted">
                                    SN :es el numero de serie asignado por el fabricante a cada dispositivo. Este numero es unico e irrepetible.

                                    Model : es el modelo de dispositivo
                                    <br><br>
                                    Lo podras encontrar en la  en la parte posterior del dispositivo
                                    </small>
                                    <img src="../assets/images/sn.png" alt="">
                                    </div>
                                    <div class="span7 offset1">
                                        <div class="control-group">
                                            <label class="control-label">SN (Numero de serie)</label>
                                            <div class="controls">
                                            <input class="span6" id="txtserie" name="txtserie" type="text">
                                            </div>
                                        </div>
                                        <div class='control-group'>
                                          <label class='control-label'>Model (Modelo)</label>
                                          <div class='controls'>
                                          <select id="cbomodelo" name="cbomodelo" >
                                          <option value='0' selected disabled>--Seleccione uno --</option>
                                              <?php 
                                                          foreach ($ListaModelos as $key => $value) {
                                                              $nom = $value['Nombre'];
                                                               $id = $value['ModeloId'];
                                                                  echo "<option value='$id' >$nom</option>"; 
                                                          }
                                              ?>
                                          </select>
                                          </div>
                                         </div>
                                    </div>
                                </fieldset>
                        
                                    <div class="form-actions" style="margin-bottom: 0;">
                                        <div class="text-center">
                                        <input class="btn btn-primary  btn-large" name="btnregister" id="btnregister" value="Vincular" type="submit" />  
                                      
                                        <a class="btn btn-danger btn-large" href="lista_dispositivos.php" name="btnnuevo" >Cancelar</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                      </div>
                       <!-- -------------------------------------------- -->
                      </div>
                    </div>
                  </div>
                </section>
                <div id="snackbar">
                            <label id="errorMensaje"></label>
                </div>
</div>

<?php 

echo $html->loadJS("sistema");

echo $html->PrintBodyClose();

?>