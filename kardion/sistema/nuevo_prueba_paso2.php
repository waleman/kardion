<?php 
require_once("../clases/cargar.php");
require_once("../clases/roles_controller.php");
require_once("../clases/personas_controller.php");
require_once("../clases/centros_controller.php");
require_once("../clases/pruebas_controller.php");
require_once("../clases/alertas_controller.php");
require_once("../clases/clasificacion_controller.php");
$pruebas = new pruebas;
$roles = new roles;
$html = new cargar;
$personas = new personas;
$centros = new centros;
$_alertas = new alertas;
$_clasificacion = new clasificacion;
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
$userData = $_SESSION['k6'];
$usuarioId =$userData['UsuarioId']; // Codigo del usuario

if(!$permiso){
  echo $_alertas->errorRedirect("Wow","No tienes permiso para acceder a esta pagina","accesoprohibido.php");
  die();
}
if(!isset($_GET['persona']) || !isset($_GET['id']) ){
  echo $_alertas->infoRedirect("Volvamos un paso atras","Aun no a seleccionado el tipo de prueba","nuevo_prueba.php");
  die();
}





//buscar los centros permitidos 
$listaCentros = array();
if($rol == 6){
  $listaCentros = $centros->getcentrosPermitidos($usuarioId);
}else{
  $listaCentros = $centros->getCentros($master);
}


   $clasificacionId = $_GET['id'];
   $nombreClasificacion = $_clasificacion->NombreClasificacion($clasificacionId);
   $personaId =$_GET['persona'];
   $datospersonales = $personas->BuscarUna($personaId);
   $nombre =  "". $datospersonales["PrimerNombre"] . " " . $datospersonales["SegundoNombre"] . " ". $datospersonales["PrimerApellido"] . " " . $datospersonales["SegundoApellido"] ;
   $correo = $datospersonales['Correo'];
   $fecha = date('Y-m-d');
   $codigo = $personas->get_random_string(5); // generamos el codigo para la pruebas
   $codigoArchivo = $personaId . "_".$codigo;



if(isset($_POST['btnenviar'])){

  $code = $_POST["singlecode"];

              $fcprimermin ="";
              if(isset($_POST['txtfcprimerminuto'])){
                $fcprimermin = $_POST['txtfcprimerminuto'];
              }
              $fcsegundomin = "";
              if(isset($_POST['txtfcsegundominuto'])){
                $fcsegundomin =$_POST['txtfcsegundominuto'];
              }
              $fcmomento = "";
              if(isset( $_POST['txtfcmaximo'])){
                $fcmomento = $_POST['txtfcmaximo'];
              }
              $tension = "";
              if(isset($_POST['txttension'])){
                $tension = $_POST['txttension'];
              }
              $peso = "";
              if(isset($_POST['txtpeso'])){
                $peso = $_POST['txtpeso'];
              }
              $altura ="";
              if(isset($_POST['txtaltura'])){
                $altura =$_POST['txtaltura'];
              }
              $dolorcabeza ="";
              if(isset($_POST['txtdolorcabeza'])){
                $dolorcabeza =$_POST['txtdolorcabeza'];
              }
              $mareo = "";
              if(isset($_POST['txtmareo'])){
                $mareo =$_POST['txtmareo'];
              }
              $nauseas ="";
              if(isset($_POST['txtnauseas'])){
                $nauseas =$_POST['txtnauseas'];
              }
              $faltaaire="";
              if(isset($_POST['txtfaltaaire'])){
                $faltaaire =$_POST['txtfaltaaire'];
              }
              $dolorpecho ="";
              if(isset($_POST['txtdolorpecho'])){
                $dolorpecho =$_POST['txtdolorpecho'];
              }
              $palpitaciones="";
              if(isset($_POST['txtpalpitaciones'])){
                $palpitaciones =$_POST['txtpalpitaciones'];
              }
              $desmayo ="";
              if(isset($_POST['txtdesmayo'])){
                $desmayo =$_POST['txtdesmayo'];
              }
              $centroId = $_POST['cbocentro'];
          
          $datos = array(
            "codigo" => $_POST["codigo"],
            "personaid" => $personaId,
            "centroid" => $_POST['cbocentro'],
            "aparatoid" => $_POST['cbodispositivo'],
            "fecha" => $fecha,
            "altura" => $altura,
            "peso" => $peso,
            "tension" => $tension,
            "frecuenciacardiaca" => $_POST['txtfc'],
            "momentomaximo" =>$_POST['txtmomento'] ,
            "fcmomemento" => $_POST['txtfcmaximo'],
            "fcprimerminuto" => $_POST['txtfcprimerminuto'],
            "fcsegudnominuto" => $_POST['txtfcsegundominuto'],
            "pruebatipoid" => $_POST['cboprioridad'],
            "usuarioid" => $usuarioId,
            "sintomas" => $_POST['txtsintomas'],
            "dolorcabeza" => $dolorcabeza,
            "mareo" => $mareo,
            "nauseas" => $nauseas,
            "faltaaire" => $faltaaire,
            "dolorpecho" => $dolorpecho,
            "palpitaciones" => $palpitaciones,
            "desmayo" => $desmayo,
            "comentario" => $_POST['txtentrenamiento'],
            "clasificacion" => $clasificacionId
          );


              $resp = $pruebas->CrearPrueba($datos);
              if($resp){
                echo $_alertas->successRedirect('Hecho!','Prueba enviada',"nuevo_prueba_paso3.php?personaid=$personaId&codigoprueba=$code&centroid=$centroId");
              }else{
                echo $_alertas->error("Error!","Error al enviar la prueba");
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
                                        Nueva prueba - <div style="display:inline; color:#009688"><?=$nombreClasificacion ?></div> 
                                    </h1>
                                    </div>
                                </div>
                            </div>
                            
                            <form class='form' method="POST" id="frm_filtrar" style='margin-bottom: 0;' autocomplete="off">
                            <input type="hidden" name="codigo" name="codigo" value="<?=$codigoArchivo?>">
                            <input type="hidden" name="singlecode" name="singlecode" value="<?=$codigo?>">
                               
                                    <div class='row-fluid'>
                                              <div  class='span3 '>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Centro</label>
                                                                <div class='controls'>
                                                                <select id="cbocentro" name="cbocentro" >
                                                                    <option value='0' selected disabled>-- Seleccione una --</option>
                                                                    <?php 
                                                                                foreach ($listaCentros as $key => $value) {
                                                                                    $nom = $value['Nombre'];
                                                                                    $id = $value['CentroId'];
                                                                                    echo "<option value='$id' >$nom</option>";
                                                                                }
                                                                    ?>
                                                                </select>
                                                                </div>
                                                            </div>
                                              </div>
                                              <div  class='span3'>
                                                            <div class='control-group' id="resp">
                                                                <label class='control-label'>Dispositivo</label>
                                                                <div class='controls' >
                                                                  <select id="cbodispositivo" name="cbodispositivo" >
                                                                      <option value='0' selected disabled>-- Seleccione una --</option>
                                                                  </select>
                                                                </div>
                                                            </div>
                                              </div>   

                                              <div  class='span2 '>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Prioridad (Entrega)</label>
                                                                <div class='controls' >
                                                                  <select id="cboprioridad" name="cboprioridad" >
                                                                      <option value='3' selected >Rutinaria - 72 horas</option>
                                                                      <!-- <option value='2'  >Preferente - 12 horas</option>
                                                                      <option value='1'  >Urgente - Lo antes posible</option> -->
                                                                  </select>
                                                                </div>
                                                            </div>
                                              </div>
                                    </div>
                                    <hr>

                                    <div class='row-fluid'>
                                                 <div  class='span4 '>
                                                           <div class='control-group'>
                                                                    <label class='control-label'>Paciente</label>
                                                                    <div class='controls'>
                                                                    <input class='span12' id='txtprimernombre' name="txtnombre" type='text' value="<?=$nombre?>" disabled >
                                                                    <p class='help-block'></p>
                                                                    </div>
                                                           </div>                                                        
                                                </div>
                                                <div  class='span3 '>
                                                           <div class='control-group'>
                                                                    <label class='control-label'>Correo</label>
                                                                    <div class='controls'>
                                                                    <input class='span12' id='txtmail' name="txtmail" type='text' value="<?=$correo?>" disabled >
                                                                    <p class='help-block'></p>
                                                                    </div>
                                                           </div>                                                        
                                                </div>
                                    </div> 



                                    <div class="row-fluid">
                                              <div  class='span1 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Altura</label>
                                                            <div class='controls'>
                                                            <input class='span12' placeholder="cm" id='txtaltura' name="txtaltura" type='text'>
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div  class='span1 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Peso</label>
                                                            <div class='controls'>
                                                            <input class='span12' placeholder="kg" id='txtpeso' name="txtpeso" type='text'>
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div  class='span2 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Tension arterial</label>
                                                            <div class='controls'>
                                                            <input data-mask="999/99" placeholder="120/80 (mmHg)" class='span12' id='txttension' name="txttension" type='text'>
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>     
                                    </div>


                                    <div class='row-fluid'>   
                                                   
                                                   
                                                    <div  class='span2 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'> Frecuencia cardiaca</label>
                                                            <div class='controls'>
                                                            <input class='span12' placeholder="lpm" id='txtfc' name="txtfc" type='text'>
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    
                                              

                                                    <div  class='span3 '>
                                                        Momento de maximo esfuerzo
                                                        <div>
                                                          <div class="timepicker input-append" id="timepicker">
                                                            <input class="input-medium" data-format="hh:mm:ss" id="txtmomento" name="txtmomento" placeholder="Seleccione la hora" type="text">
                                                            <span class="add-on">
                                                              <i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-time"></i>
                                                            </span>
                                                          </div>
                                                        </div>
                                                     </div>

                                    </div>
                                  
                                   
                                    <div class="row-fluid">
                                                    
                                                    <div  class='span2 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Fc al momento de máximo esfuerzo (lpm)</label>
                                                            <div class='controls'>
                                                            <input class='span12' id='txtfcmaximo' name="txtfcmaximo" type='text'>
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div  class='span2 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Fc primer minuto de recumperacion (lpm)</label>
                                                            <div class='controls'>
                                                            <input class='span12' id='txtfcprimerminuto' name="txtfcprimerminuto" type='text'>
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div  class='span2 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Fc segundo minuto de recuperacion (lpm)</label>
                                                            <div class='controls'>
                                                            <input class='span12' id='txtfcsegundominuto' name="txtfcsegundominuto" type='text'>
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                    </div>
                                    <br>
                                    <div class="row-fluid">
                                                  <div  class='span8 '>
                                                      <div class="control-group">
                                                        <label class="control-label" for="txtentrenamiento">Comentarios sobre la sesión de entrenamiento</label>
                                                        <div class="controls">
                                                          <textarea id="txtentrenamiento" name="txtentrenamiento" placeholder="Escriba comentarios sobre el metodo de entrenamiento" rows="5" style="margin: 0px; width: 100%; height: 110px;"></textarea>
                                                        </div>
                                                      </div>
                                                  </div>
                                    </div>

                                    <br>  
                                    <div class="row-fluid">
                                                  <div  class='span9 '>
                                                    <div class="control-group">
                                                      <div class="controls">
                                                      <b>¿Sintomas durante la sesión? </b> <br>
                                                        <label class="checkbox inline">
                                                          <input id="txtdolorcabeza" name="txtdolorcabeza" type="checkbox" value="1">
                                                          Dolor de cabeza
                                                        </label>
                                                        <label class="checkbox inline">
                                                          <input id="txtmareo" name="txtmareo" type="checkbox" value="1">
                                                          Mareo
                                                        </label>
                                                        <label class="checkbox inline">
                                                          <input id="txtnauseas" name="txtnauseas" type="checkbox" value="1">
                                                          Náusea
                                                        </label>
                                                        <label class="checkbox inline">
                                                          <input id="txtfaltaaire" name="txtfaltaaire" type="checkbox" value="1">
                                                          Falta de aire
                                                        </label>

                                                        <label class="checkbox inline">
                                                          <input id="txtdolorpecho" name="txtdolorpecho" type="checkbox" value="1">
                                                          Dolor de pecho
                                                        </label>
                                                        <label class="checkbox inline">
                                                          <input id="txtpalpitaciones" name="txtpalpitaciones" type="checkbox" value="1">
                                                          Palpitaciones
                                                        </label>
                                                        <label class="checkbox inline">
                                                          <input id="txtdesmayo" name="txtdesmayo" type="checkbox" value="1">
                                                          Desmayo
                                                        </label>
                                                      </div>
                                                    </div>
                                                  </div>
                                    </div>
                                    <br>
                                    <div class="row-fluid">
                                                  <div  class='span8 '>
                                                      <div class="control-group">
                                                        <label class="control-label" for="inputTextArea1">¿Incidencias durante la sesion? Describalas</label>
                                                        <div class="controls">
                                                          <textarea id="txtsintomas" name="txtsintomas" placeholder="Escriba aqui las incidnecias" rows="5" style="margin: 0px; width: 100%; height: 110px;"></textarea>
                                                        </div>
                                                      </div>
                                                  </div>
                                    </div>

                                    <br>                                                                  
                                    <br> 
                                    <div class="form-actions" style="margin-bottom: 0;">
                                       <div class="text-center">
                                              <input type="submit" class="btn btn-primary btn-large" value="Guardar" name="btnenviar" id="btnenviar"/>    
                                              <a href="lista_pruebas.php" class="btn btn-danger btn-large" >Cancelar</a>                                  
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

    