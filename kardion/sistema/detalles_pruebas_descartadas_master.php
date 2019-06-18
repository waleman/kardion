<?php 
require_once("../clases/cargar_master.php");
require_once("../clases/pruebas_controller.php");
require_once("../clases/roles_controller.php");
require_once("../clases/alertas_controller.php");
$html = new cargar;
$_pruebas = new pruebas;
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
$permisos = array(1,2);
$rol = $_SESSION['k6']['RolId'];
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioid = $_SESSION['k6']['UsuarioId'];
$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
    echo $_alertas->errorRedirect("Wow!","Usted no tiene permisos para estar aqui","accesoprohibido.php");
die();
}
//buscamos el codigo master para seleccionar los centros asociados al usuario

if(!isset($_GET["id"])){
    echo $_alertas->infoRedirect("Aviso","Debe seleccionar la prueba descartada.","lista_pruebas_descartadas_master.php");
    die();
}

$id = $_GET["id"];
$datosPrueba = $_pruebas->pruebaporid2($id);

if(empty($datosPrueba)){
    $datosPrueba =[];
}

$nombre = $datosPrueba[0]["Persona"]; 
$sexo = $datosPrueba[0]["Sexo"];
$Fechanac = $datosPrueba[0]["FechaNacimiento"];
$mail = $datosPrueba[0]["Correo"];
$Fechanac =$datosPrueba[0]["FechaNacimiento"];
if($Fechanac){// calcular la edad del paciente
    $cumpleanos = new DateTime($Fechanac);
    $hoy = new DateTime();
    $cal =$hoy->diff($cumpleanos);
    $edad = $cal->y;
}else{
    $edad  ="No definida";
}
$correo =$datosPrueba[0]["Correo"];
$centro =$datosPrueba[0]["Centro"];
$FC =$datosPrueba[0]["FC"];
$FM =$datosPrueba[0]["FM"];
$Motivo =$datosPrueba[0]["Motivo"];

if(isset($_POST['btnguardar'])){
       $motivo = $_POST['txtmotivo'];
       $resp = $_pruebas->ReciclarPrueba($id,$motivo,$usuarioid);
       if($resp){
                echo $_alertas->successRedirect("Hecho","La prueba ha sido reciclada","lista_pruebas_descartadas_master.php");
       }else{
                echo $_alertas->error("Error","No hemos podido modificar la prueba");
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
                            <div class='page-header'>
                              <h1 class='pull-left'>
                                <span>Pruebas descartada</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                       <!-- ----------------------------------------- -->
                       <form method="post">
                                                                <div class='row-fluid'>
                                                                            <div  class='span4 '>
                                                                                    <div class='control-group'>
                                                                                                <label class='control-label'>Paciente</label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12 border border-primary' id='mtxtnombre' name="mtxtnombre" type='text' value="<?=$nombre?>" disabled >
                                                                                                </div>
                                                                                    </div>
                                                                                         
                                                                            </div>
                                                                            <div  class='span2 '>
                                                                                    <div class='control-group'>
                                                                                                <label class='control-label'>Género</label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12' id='mtxtgenero' name="mtxtgenero" type='text' value="<?=$sexo?>" disabled >
                                                                                                </div>
                                                                                    </div>
                                                                                         
                                                                            </div>
                                                                            <div  class='span1 '>
                                                                                    <div class='control-group'>
                                                                                                <label class='control-label'>Edad</label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12' id='mtxtedad' name="mtxtedad" type='text' value="<?=$edad?>" disabled >
                                                                                                </div>
                                                                                    </div>
                                                                                         
                                                                            </div>
                                                                            <div  class='span2 '>
                                                                                    <div class='control-group'>
                                                                                                <label class='control-label'>Fecha nacimiento</label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12' id='txtfechanac' name="txtfechanac" type='text' value="<?=$Fechanac?>" disabled >
                                                                                                </div>
                                                                                    </div>
                                                                                         
                                                                            </div>
                                                                </div>
                                                                <div class='row-fluid'>
                                                                            <div  class='span4 '>
                                                                                    <div class='control-group'>
                                                                                                <label class='control-label'>Correo</label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12 border border-primary' id='txtcorreo' name="txtcorreo" type='text' value="<?=$correo?>" disabled >
                                                                                                </div>
                                                                                    </div>
                                                                                         
                                                                            </div>
                                                                </div>
                                                                <div class='row-fluid'>
                                                                                    <div  class='span3 '>
                                                                                        <div class='control-group'>
                                                                                                <label class='control-label'>Enviada por </label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12 border border-primary' id='txtcorreo' name="txtcorreo" type='text' value="<?=$centro?>" disabled >
                                                                                                </div>
                                                                                         </div>
                                                                                    </div>
                                                                                    <div  class='span2 '>
                                                                                        <div class='control-group'>
                                                                                                    <label class='control-label'>Fecha de envio</label>
                                                                                                    <div class='controls'>
                                                                                                    <input class='span12' id='txtfechanac' name="txtfechanac" type='text' value="<?=$FC?>" disabled >
                                                                                                    </div>
                                                                                    </div>  
                                                                                     </div>
                                                                                    <div  class='span2 '>
                                                                                            <div class='control-group'>
                                                                                                        <label class='control-label'>Fecha de descarte</label>
                                                                                                        <div class='controls'>
                                                                                                        <input class='span12' id='txtfechanac' name="txtfechanac" type='text' value="<?=$FM?>" disabled >
                                                                                                        </div>
                                                                                            </div>
                                                                                                
                                                                                    </div>            
                                                                </div>
                                                                <div class='row-fluid'>
                                                                             <div  class='span8 '>
                                                                                        <div class="control-group">
                                                                                                <label class="control-label" for="txtmotivo">Motivo de descarte </label>
                                                                                                <div class="controls">
                                                                                                <textarea   id="txtmotivo" name="txtmotivo" placeholder="Escriba el Diagnóstico" rows="5" style="margin: 0px; width: 100%; height: 110px;" ><?php echo$Motivo; ?></textarea>
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                                                                </div>

                                                                <div class="form-actions" style="margin-bottom: 0;">
                                                                        <div class="text-center">
                                                                                
                                                                               
                                                                                <input  class="btn btn-primary btn-large" name="btnguardar" id="btnguardar" value="Reciclar prueba" type="submit" />
                                                                                <a style="" id="btncancelar"  class="btn btn-danger btn-large" href="lista_pruebas_descartadas_master.php" >Cancelar</a>
                                                                                
                                                                </div>                    
                      </form>
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

    
