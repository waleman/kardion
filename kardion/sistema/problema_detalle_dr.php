<?php
require_once("../clases/cargar_dr.php");
require_once("../clases/problemas_controller.php");
require_once("../clases/roles_controller.php");
require_once("../clases/alertas_controller.php");
$html = new cargardr;
$_problemas= new problemas;
$roles = new roles;
$_alertas = new alertas;
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
$usuarioId =$_SESSION['k6']['UsuarioId']; 
$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
    echo $_alertas->errorRedirect("Error","No tiene permiso para acceder a esta pagina","accesoprohibido.php");
    die();
}

if(!isset($_GET['id'])){
    echo $_alertas->infoRedirect("Wow","Debe seleccionar un problema primero","problema_notificaciones_master.php");
    die();
}
$ProblemaId=$_GET['id'];


if(isset($_POST['btnchat'])){
    $text = $_POST['message_body'];
    $verificar = $_problemas->guardar($text,$usuarioId,$ProblemaId);
}

$datos = $_problemas->porblema_master($ProblemaId);
$usuario = $datos[0]['Usuario'];
$tipo = $datos[0]['ProblemaTipo'];
$fecha = $datos[0]['Fecha'];
$estado = $datos[0]['Estado'];
$texto = $datos[0]['Texto'];

$listaRespuestas = $_problemas->respuestas($ProblemaId);
if(empty($listaRespuestas)){
  $listaRespuestas = array();
}



foreach($listaRespuestas  as $key => $value){
    $uUsuarioId = $value['UsuarioId'];
    $uVisto = $value['visto'];
    $uProblemaRespuestaId = $value['ProblemaRespuestaId'];
    if ($uVisto == 0 && $uUsuarioId != $usuarioId ){
        $_problemas->update($uProblemaRespuestaId);
    }

}

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
                                <span> Notificaciones</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -------------------------------------------- -->
                         <div class='row-fluid'>


                         
                                                                            <div  class='span3 '>
                                                                                    <div class='control-group'>
                                                                                                <label class='control-label'>Enviado por :</label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12 border border-primary' id='mtxtnombre' name="mtxtnombre" type='text' value="<?=$usuario?>" disabled >
                                                                                                </div>
                                                                                    </div>
                                                                                         
                                                                            </div>
                                                                            <div  class='span2 '>
                                                                                    <div class='control-group'>
                                                                                                <label class='control-label'>Tipo de problema</label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12' id='mtxtgenero' name="mtxtgenero" type='text' value="<?=$tipo?>" disabled >
                                                                                                </div>
                                                                                    </div>
                                                                                         
                                                                            </div>
                                                                            <div  class='span2 '>
                                                                                    <div class='control-group'>
                                                                                                <label class='control-label'>Fecha de envio</label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12' id='mtxtedad' name="mtxtedad" type='text' value="<?=$fecha?>" disabled >
                                                                                                </div>
                                                                                    </div>
                                                                                         
                                                                            </div>
                                                                            <div  class='span1 '>
                                                                                    <div class='control-group'>
                                                                                                <label class='control-label'>Estado</label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12' id='txtfechanac' name="txtfechanac" type='text' value="<?=$estado?>" disabled >
                                                                                                </div>
                                                                                    </div>
                                                                                         
                                                                            </div>                                 
                       
                         </div>
                         <div class="row-fluid">
                                                                                <div  class='span8 '>
                                                                                        <div class="control-group">
                                                                                                <label class="control-label" for="txtprediagnostico">Descripcion del problema </label>
                                                                                                <div class="controls">
                                                                                                <textarea   id="txtprediagnostico" name="txtprediagnostico" placeholder="Escriba el Diagnóstico" rows="5" style="margin: 0px; width: 100%; height: 110px;" disabled><?php echo$texto; ?></textarea>
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                          </div>
                         <!-- -------------------------------------------- -->
                        <div class="row-fluid">
                        <div class="span12 box">
                            <div class="chat row-fluid">
                                <div class="box box-nomargin span12">
                                    <div class="box-content box-no-padding">
                                        <div class="slimScrollBar ui-draggable" style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 244px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 54.5124px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
                                        <span>Escribe un mensaje</span>
                                            <form class="" method="post" action="#" accept-charset="UTF-8">
                                                 <div class="controls">
                                                  <input style="    border: 2px solid red;  border-radius: 4px !important;" class="span12" id="message_body" name="message_body" type="text">
                                                </div>
                                               
                                                    <button class="btn btn-success" type="submit" name="btnchat" id="btnchat">
                                                    <i class="icon-plus"></i>
                                                    </button>
                                            </form>
                                            <div class="responsive-table">
                                              <div class="scrollable-area">
                                                <table class="table table-hover table-striped" data="" style="margin-bottom:0;">
                                                  <thead>
                                                    <tr>
                                                      <th>
                                                      Lista de mensajes Mensajes
                                                      </th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>

                                                  <?php 
                                                                    foreach ($listaRespuestas  as $key => $value) {
                                                                            $usuario = $value["Usuario"];
                                                                            $fecha  = $value["Fecha"];
                                                                            $texto = $value["Texto"];
                                                                            $visto = $value["visto"];

                                                                            echo "<tr>
                                                                            <td>
                                                                              <b> $usuario :</b>
                                                                                  
                                                                                <div class='time pull-right'>
                                                                                  <small class='date pull-right muted'>
                                                                                    <span class='timeago fade has-tooltip in' data-placement='top' >$fecha</span>
                                                                                    <i class='icon-time'></i>
                                                                                  </small>
                                                                                </div>
                                                                              <br> 
                                                                                $texto
                                                                            
                                                                            </td>
                                                                          
                                                                            </tr
                                                                          ";

                                                                    }

                                                  ?>
                                                  </tbody>
                                                </table>
                                              </div>
                                          </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                         <!------------------------------------------------->
                       </div>
                    </div>
                  </div>
                </section>
</div>
<?php 
echo $html->loadJS("sistema");
echo $html->PrintBodyClose();
?>