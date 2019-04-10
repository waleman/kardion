<?php
require_once("../clases/cargar.php");
require_once("../clases/roles_controller.php");
require_once("../clases/usuario_controller.php");
$_html = new cargar;

$_roles = new roles;
$_usuarios = new usuario;
$_html->sessionDataSistem();
echo $_html->PrintHead();
echo $_html->LoadCssSystem("sistema");
echo $_html->LoadJquery("sistema");
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $_html->PrintBodyOpen();
echo $_html->PrintHeader();

//definimos los permisos para esta pantalla;
$permisos = array(4,5);
$rol = $_SESSION['k6']['RolId'];
$permiso = $_roles->buscarpermisos($rol,$permisos);
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
$usuarioId =$_SESSION['k6']['UsuarioId']; // Codigo del usuario
//datos del usuario
$datos = $_usuarios-> UsuarioDatos($usuarioId);
$correo =$datos[0]['Usuario'];
$estado= $datos[0]['Estado'];
$key = $datos[0]['CodigoActivacion'];

?>
<div id='wrapper'>
<div id='main-nav-bg'></div>
<?php
echo $_html->PrintSideMenu();

if(isset($_POST['btncambio'])){
  $actual = $_POST['txtactual'];
  $nueva = $_POST['txtnueva'];
  $verificar = $_usuarios->verificarContraseña($usuarioId,$actual);
  if($verificar){
      $verificar2 = $_usuarios->Change_password($usuarioId,$nueva);
      if($verificar2){
        echo"<script>
          swal({
                  title: 'Contraseña modificada!',
                  text: 'Debe inciar session nuevamente',
                  type: 'success',
                  icon: 'success'
          }).then(function() {
                  window.location = '../login.php';
          });
        </script>";
      }else{
          echo"<script>
          swal({
                  title: 'Error',
                  text: 'No hemos podido modificar su contraseña!',
                  type: 'error',
                  icon: 'error'
          });
        </script>";
      }
  }else{
    echo"<script>
      swal({
              title: 'Wow!',
              text: 'La contraseña actual no coincide con la que tenemos almacenada!',
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
                    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
                }

        $('#btncambio').click(function(){
            if($("#txtactual").val().length == 0){
                    let val = "Debe escribir la contraseña actual";
                    mostrar(val);
                    return false;
            }else if($("#txtnueva").val().length <= 7){
                    let val = "La contraseña nueva debe tener almenos 8 caracteres";
                    mostrar(val);
                    return false;
            }else if($("#txtnueva").val() != $("#txtnueva2").val()){
                    let val = "Las contraseñas deben coincidir";
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
                                <span>Datos de la cuenta</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -------------------------------------------- -->
                        
                         <div class="row-fluid">
                            <div class="span12 box ">
                              <div class="box-header blue-background">
                                <div class="title">
                                  <div class="icon-user"></div>
                                Informacion de la cuenta
                                </div>
                                <div class="actions">
                                  <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                                  </a>
                                </div>
                              </div>
                              <div class="box-content">
                                      <form class="form form-horizontal" style="margin-bottom: 0;" method="post" action="#" accept-charset="UTF-8"><input name="authenticity_token" type="hidden"><div class="control-group">
                                  <label class="control-label" for="txtcorreo">Correo</label>
                                  <div class="controls">
                                    <input id="txtcorreo" name="txtcorreo" placeholder="Correo / usuario" type="text" value="<?=$correo?>" readonly=”readonly” >
                                  </div>
                                </div>
                                <div class="control-group">
                                  <label class="control-label" for="inputPassword4">Estado de la cuenta</label>
                                  <div class="controls">
                                    <input id="txtestado" name="txtestado" placeholder="Activo / Inactivo" type="text" value="<?=$estado?>" readonly=”readonly” >
                                  </div>
                                </div>   
                                <div class="control-group">
                                  <label class="control-label" for="inputPassword4">Key</label>
                                  <div class="controls">
                                    <input id="txtkey" name="txtkey" placeholder="Codigo de activacion" type="text" value="<?=$key?>" readonly=”readonly” >
                                  </div>
                                </div>     
                                <!-- <div class="form-actions">
                                  <button class="btn btn-primary" type="submit">
                                   
                                    Guardar
                                  </button>
                                  <button class="btn" type="submit">Cancel</button>
                                </div> -->
                                </form>
                              </div>
                            </div>
                          </div>



                          <!-----------------Cambio de contraseña-------------------------------->
 
                          <div class="row-fluid">
                            <div class="span12 box ">
                              <div class="box-header purple-background">
                                <div class="title">
                                  <div class="icon-edit"></div>
                                Cambio de contraseña
                                </div>
                                <div class="actions">
                                  <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                                  </a>
                                </div>
                              </div>
                              <div class="box-content">
                              <form class="form form-horizontal" style="margin-bottom: 0;" method="post" accept-charset="UTF-8">
                                <div class="control-group">
                                  <label class="control-label" for="txtactual">Contraseña actual</label>
                                  <div class="controls">
                                    <input id="txtactual" name="txtactual" placeholder="Password field" type="password" value="">
                                  </div>
                                </div>  
                                 
                                <div class="control-group">
                                  <label class="control-label" for="txtnueva">Contraseña</label>
                                  <div class="controls">
                                    <input id="txtnueva" name="txtnueva" placeholder="Password field" type="password" value="">
                                  </div>
                                </div>    
                                <div class="control-group">
                                  <label class="control-label" for="txtnueva2">Repita la contraseña</label>
                                  <div class="controls">
                                    <input id="txtnueva2" name="txtnueva2" placeholder="Password field" type="password" value="">
                                  </div>
                                </div>        
                                <div class="form-actions">
                                  <button name="btncambio" id="btncambio" class="btn btn-primary" type="submit">
                                    <!-- <i class="icon-save"></i> -->
                                    Guardar
                                  </button>
                                  <!-- <button class="btn" type="submit">Cancel</button> -->
                                </div>
                                </form>
                              </div>
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
echo $_html->loadJS("sistema");
echo $_html->PrintBodyClose();
?>