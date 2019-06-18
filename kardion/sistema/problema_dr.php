<?php
require_once("../clases/cargar_dr.php");
require_once("../clases/roles_controller.php");
require_once("../clases/alertas_controller.php");
require_once("../clases/problemas_controller.php");
$html = new cargardr;
$roles = new roles;
$_alertas = new alertas;
$_problema = new problemas;
$html->sessionDataSistem();
echo $html->PrintHead();
echo $html->LoadCssSystem("sistema");
echo $html->LoadJquery("sistema");
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $html->PrintBodyOpen();
echo $html->PrintHeader();

//definimos los permisos para esta pantalla;
$permisos = array(3);
$rol = $_SESSION['k6']['RolId'];
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; 
$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
  echo $_alertas->errorRedirect("Error","Usted no tiene permisos para entrar a esta pagina.","accesoprohibido.php");
  die();
}

if(isset($_POST['btnenviar'])){
      $datos = array(
          'TipoProblemaId' => $_POST['cbotipoproblema'],
          'Texto' => $_POST['txttexto'],
          'UsuarioId' => $usuarioId
      );
      $resp = $_problema->GuardarProblema($datos);
      if($resp){
         echo $_alertas->successRedirect("Hecho","Datos guardados correctamente","portal_dr.php");
      }else{
        echo $_alertas->error("Error","No hemos podido guardar su solicitud");
      }

}


$listaproblemas = $_problema->TipoProblemas();
if(empty($listaproblemas)){ $listaproblemas = array();}

?>
<div id='wrapper'>
<div id='main-nav-bg'></div>
<?php
echo $html->PrintSideMenu();
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

        $("#btnenviar").click(function(){
          if(document.getElementById('cbotipoproblema').value == 0){
                    let val = "Debe seleccionar una tipo de problema";
                    mostrar(val);
                    return false;
          }else if(!document.getElementById('txttexto').value){
                    console.log("todo Oj");  let val = "Debe describir el problema";
                    mostrar(val);
                    return false;
            
          }else{
            return true;
          }
        
        });
    })

</script>

     <section id='content'>
                  <div class='container-fluid'>
                    <div class='row-fluid' id='content-wrapper'>
                      <div class='span12'>
                        <div class='row-fluid'>
                          <div class='span12'>
                            <div class='page-header'>
                              <h1 class='pull-left'>
                                <span>Reportar un problema</span>
                              </h1>
                            </div>
                          </div>
                        </div>

                        <form action="" method="post">
                    
                         <!-- -------------------------------------------- -->
                         <div class="span12 box">
                            <div class="box-header red-background">
                                <div class="title">Describa el error</div>
                                <div class="actions">
                                <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                                </a>
                                </div>
                            </div>
                            <div class="box-content">
                                <div class="row-fluid">

                                             <div  class='span12'>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Que tipo de problema tiene ?</label>
                                                                <div class='controls'>
                                                                <select id="cbotipoproblema" name="cbotipoproblema" class="span6" >
                                                                    <option value='0' selected disabled>-- Seleccione una --</option>
                                                                    <?php 
                                                                                foreach ($listaproblemas as $key => $value) {
                                                                                    $nom = $value['Nombre'];
                                                                                    $id = $value['ProblemaTipoId'];
                                                                                    echo "<option value='$id' >$nom</option>";
                                                                                }
                                                                    ?>
                                                                </select>
                                                                </div>
                                                            </div>
                                              </div>

                                              <div class="span12" style="margin-left:0px;">
                                                <div class="control-group">
                                                  <label class="control-label" for="txttexto">Describa el problema</label>
                                                  <div class="controls">
                                                    <textarea class="input-block-level" id="txttexto" name="txttexto" placeholder="Escriba el problema" rows="10"></textarea>
                                                  </div>
                                                </div>
                                              </div>
                                 </div>    
                                 <div class="form-actions text-center" >
            
                                              <input type="submit" value="Enviar" name="btnenviar" id="btnenviar" class="btn btn-primary btn-large"/>
                                              <a href="portal_dr.php" name="btncancelar" id="btncancelar" class="btn btn-danger btn-large">Cancelar</a>
                                         
                                 </div>
                            </div>
                            </div>
                         <!-- -------------------------------------------- -->
                         </form>
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