<?php 
require_once('../clases/cargar_dr.php');
require_once('../clases/roles_controller.php');
require_once('../clases/pruebas_controller.php');
require_once('../clases/personas_controller.php');


$html = new cargardr;
$roles = new roles;
$pruebas = new pruebas;
$personas = new personas;


$html->sessionDataSistem(); //iniciamos la sesion en el navegador
//Buscamos los permisos para entrar en esta pantalla
        $permisos = array(3);
        $rol = $_SESSION['k6']['RolId'];
        $permiso = $roles->buscarpermisos($rol,$permisos);
        if(!$permiso){
        header("Location: accesoprohibido.php");
        }
echo $html->PrintHead(); //Cargamos en header
echo $html->LoadCssSystem("sistema"); // cargamos todas las librerias css del sistema interno
echo $html->LoadJquery("sistema"); //cargamos todas las librerias Jquery del sistema interno  
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $html->loadFileuploadJS("sistema");
echo $html->loadFileuploadCSS("sistema");
echo $html->PrintBodyOpen(); //cargamos la primera parte del body
echo $html->PrintHeader(); //cargamos el header
$usuarioid = $_SESSION['k6']['UsuarioId'];

//Validar que tenga una prueba asignada

        //obtener el codigo de la prueba que tiene asignada
            $PruebaId = $pruebas->BuscarCodigoPruebaAsignada($usuarioid);
            //echo " el codigo de la prueba es $PruebaId";
            $pruebaidEncripted =  base64_encode($PruebaId);
        //obtenemos los datos de la prueba
            $Datos = $pruebas->BuscarPruebaAsignada($PruebaId);
            if($Datos){
                        $codigo = $Datos[0]['Codigo'];
                        $personaId = $Datos[0]['PersonaId'];
                        $archivos = $pruebas->BuscarArchivos($codigo);
                        $Persona =  $personas->BuscarUna($personaId);
                        $antecedentes = $personas->Buscar_antecedentes($personaId);
                        if(!$antecedentes || !empty($antecedentes)){
                                $AntecedentesHiper  = $antecedentes[0]['AntecedentesHiper'];
                                $AntecedentesDiabetes = $antecedentes[0]['AntecedentesDiabetes'];
                                $AntecedentesCardiopatia = $antecedentes[0]['AntecedentesCardiopatia'];
                                $AntecendentesOtro = $antecedentes[0]['AntecendentesOtro'];
                                $HabitosCafe = $antecedentes[0]['HabitosCafe'];
                                $HabitosTabaco = $antecedentes[0]['HabitosTabaco'];
                                $HabitosAlcohol = $antecedentes[0]['HabitosAlcohol'];
                                $HabitosOtro = $antecedentes[0]['HabitosOtro'];
                                $Comentarios = $antecedentes[0]['Comentarios'];
                                $Medicamento = $antecedentes[0]['Medicamento'];
                        }else{
                                $AntecedentesHiper  = 0;
                                $AntecedentesDiabetes = 0;
                                $AntecedentesCardiopatia = 0;
                                $AntecendentesOtro = "";
                                $HabitosCafe = 0;
                                $HabitosTabaco = 0;
                                $HabitosAlcohol = 0;
                                $HabitosOtro = "";
                                $Comentarios = "";
                                $Medicamento = "";

                        }

                        if(!$archivos || empty($archivos)){/*Error al obtener archivos*/}
                         //print_r($antecedentes);
                              
                    
            //Asignar datos
                $nombre = $Persona["PrimerNombre"]." ". $Persona["SegundoNombre"]." ".$Persona["PrimerApellido"]. " ". $Persona["SegundoApellido"] ; 
                $sexo = $Persona["Sexo"];
                $Fechanac = $Persona["FechaNacimiento"];
                $altura  = (int)$Persona["Altura"];
                $peso = (int)$Persona["Peso"];
                $mail = $Persona["Correo"];
        
                if($Fechanac){// calcular la edad del paciente
                    $cumpleanos = new DateTime($Fechanac);
                    $hoy = new DateTime();
                    $cal =$hoy->diff($cumpleanos);
                    $edad = $cal->y;
                }else{
                    $edad  ="No definida";
                }

                if($altura == 0 || $peso == 0){
                        /* peso y altura enviados en la prueba */
                        $altura =(int)$Datos[0]['Altura'] ;
                         $peso =(int)$Datos[0]['Peso'] ;
                }

            
                /* si no existe ninguno de los dos se asigna valor 0 */
                if($peso == 0 || $altura==0){
                        $masa = 0;
                }else{
                        $convertir = $altura/100;
                        $masa = round(($peso/($convertir*$convertir)),2);
                }
       
                $tension =$Datos[0]['Tension'] ; 
                $momentomaximo =$Datos[0]['MomentoMaximo'];
                $sintomas = $Datos[0]['Sintomas'];
                $dolorcabeza = $Datos[0]['DolorCabeza'];
                $mareo = $Datos[0]['Mareo'];
                $nauseas = $Datos[0]['Nauseas'];
                $faltaaire = $Datos[0]['FaltaAire'];
                $dolorpecho = $Datos[0]['DolorPecho'];
                $palpitaciones = $Datos[0]['Palpitaciones'];
                $desmayo = $Datos[0]['Desmayo'];

                //verificar si el usurio ya habia guardado algun dato anteriormente
                 $ver = $pruebas->verificar_resultado($PruebaId);

                if($ver == 1){
                        $datosguardados = $pruebas->Resultadoporid($PruebaId);
                        $frecuenciacardica=$datosguardados[0]['FrecuenciaCardiaca'];
                        $fcmomentomaximo =$datosguardados[0]['FCMomentoMaximo'];
                        $fcprimerminuto = $datosguardados[0]['FCPrimerMinuto'];
                        $fcsegundominuto = $datosguardados[0]['FCSegundoMinuto'];
                        $diagnostico = $datosguardados[0]['PreDiagnostico'];
                        $conclusiones = $datosguardados[0]['Conclusiones'];
                       echo "
                       <script>
                          $(document).ready(function(){
                              $('#btnfinalizar').show();
                              $('#btnvistaprevia').show(); 
                          });
                          </script>
                       
                       ";

                }else{
                        $frecuenciacardica=$Datos[0]['FrecuenciaCardiaca'];
                        $fcmomentomaximo =$Datos[0]['FCMomentoMaximo'];
                        $fcprimerminuto = $Datos[0]['FCPrimerMinuto'];
                        $fcsegundominuto = $Datos[0]['FCSegundoMinuto'];
                        $diagnostico = "";
                        $conclusiones = "";
                }

                if(!$fcmomentomaximo){ $fcmomentomaximo = 0;}
                if(!$frecuenciacardica){$frecuenciacardica =0;}
          


                //calcular tabla 
                if($fcmomentomaximo && $frecuenciacardica){
                        $a50 = ($fcmomentomaximo-$frecuenciacardica)*0.5+($frecuenciacardica);
                        $a60 = ($fcmomentomaximo-$frecuenciacardica)*0.6+($frecuenciacardica);
                        $a65 = ($fcmomentomaximo-$frecuenciacardica)*0.65+($frecuenciacardica);
                        $a70 = ($fcmomentomaximo-$frecuenciacardica)*0.7+($frecuenciacardica);
                        $a75 = ($fcmomentomaximo-$frecuenciacardica)*0.75+($frecuenciacardica);
                        $a80 = ($fcmomentomaximo-$frecuenciacardica)*0.8+($frecuenciacardica);
                        $a85 = ($fcmomentomaximo-$frecuenciacardica)*0.85+($frecuenciacardica);
                        $a90 = ($fcmomentomaximo-$frecuenciacardica)*0.9+($frecuenciacardica);
                        $a95 = ($fcmomentomaximo-$frecuenciacardica)*0.95+($frecuenciacardica);
                        $a100 = ($fcmomentomaximo-$frecuenciacardica)*1+($frecuenciacardica);
                        $primermin = $fcmomentomaximo - $fcprimerminuto;
                        $segundomin =$fcprimerminuto -$fcsegundominuto;

                         $indicecrono = round(((((int)$fcmomentomaximo)/(220-(int)$edad))*100),2);
                         $resumencrono = round(((((int)$fcmomentomaximo-(int)$frecuenciacardica)/((220-(int)$edad)-(int)$frecuenciacardica)) *100),2);
                        
                }else{
                        $a50 = 0;
                        $a60 = 0;
                        $a65 = 0;
                        $a70 = 0;
                        $a75 = 0;
                        $a80 = 0;
                        $a85 = 0;
                        $a90 = 0;
                        $a95 = 0;
                        $a100 =0;
                        $primermin = 0;
                        $segundomin =0;
                        $indicecrono = 0;
                        $resumencrono = 0;

                }
            }else{
                echo"<script>
                                swal({
                                        title: 'Wow!',
                                        text: 'Usted no tiene ninguna prueba asignada!',
                                        type: 'success'
                                }).then(function() {
                                        window.location = 'lista_pruebas_dr.php';
                                });
                   </script>";
                        die();

                
            }
        //Obtener las concluciones predefinidas
        $listaconcluciones = $pruebas->Conclusiones();
        if(empty($listaconcluciones)){$listaconcluciones = array();}
           
/* ---------------------------------*/   
//Guardar 

if(isset($_POST['btnguardar'])){
       $verificar = $pruebas->verificar_resultado($PruebaId);
       if($verificar == 0){
        // Guardar
               
                $data = array(
                        'pruebaid' => $PruebaId,
                        'usuarioid' => $usuarioid,
                        'codigo' => '',
                        'frecuenciacardiaca'=> $_POST['mtxtfc'],
                        'fcmomentomaximo'=> $_POST['mtxtfcmomento'],
                        'fcprimerminuto'=> $_POST['mtxtfcprimerminuto'],
                        'fcsegundominuto'=> $_POST['mtxtsegundomunuto'],
                        'prediagnostico'=> $_POST['txtprediagnostico'],
                        'conclusiones'=> $_POST['txtrecomendaciones'],
                        'propio' => '0',
                        'propiodireccion' => ''
                );
                $resp=$pruebas->guardar_resulado($data);
                echo"<script>
                swal({
                        title: 'Hecho!',
                        text: 'datos guardados correctamente!',
                        type: 'success',
                        icon: 'success'
                }).then(function() {
                        window.location = 'prueba_dr.php';
                });
               </script>";


       }else{
        //Modificar
                $data = array(
                        'pruebaid' => $PruebaId,
                        'usuarioid' => $usuarioid,
                        'codigo' => '',
                        'frecuenciacardiaca'=> $_POST['mtxtfc'],
                        'fcmomentomaximo'=> $_POST['mtxtfcmomento'],
                        'fcprimerminuto'=> $_POST['mtxtfcprimerminuto'],
                        'fcsegundominuto'=> $_POST['mtxtsegundomunuto'],
                        'prediagnostico'=> $_POST['txtprediagnostico'],
                        'conclusiones'=> $_POST['txtrecomendaciones'],
                        'propio' => '0',
                        'propiodireccion' => ''
                );
                $resp=$pruebas->modificar_resultado($data);
                echo"<script>
                swal({
                        title: 'Hecho!',
                        text: 'datos modificados correctamente!',
                        type: 'success',
                        icon: 'success'
                }).then(function() {
                        window.location = 'prueba_dr.php';
                });
               </script>";

       }
}
//finalizar 
if(isset($_POST['btnfin'])){
     $finalizar =  $pruebas->finalizarprueba($PruebaId);
      //$finalizar = true;
       if($finalizar){
          $finalizar2=  $pruebas->finalizarPruebaasignada($PruebaId);
          //$finalizar2 = true;
                if($finalizar2){
                        /* PRUEBA */

                        $mail = $pruebas->EnviarMaiilPruebaFinalizada($PruebaId,$mail);



                        echo"<script>
                             $.post( '../utilidades/pdf_finalizar.php?id=$PruebaId', function( data ) {
                                $( '.result' ).html( data );
                                console.log(data);
                              });

                                 swal({
                                         title: 'Hecho!',
                                         text: 'Prueba finalizada correctamente!',
                                         type: 'success'
                                 }).then(function() {
                                         window.location = 'lista_pruebas_dr.php';
                                 });
                         </script>";

                }else{
                        echo"<script>swal({
                                title: 'Error al modificar el estado de la prueba asignada !',
                                icon: 'error',
                              })</script>";
                }
       }else{
             echo"<script>swal({
                title: 'Error al modificar el estado de la prueba !',
                icon: 'error',
              })</script>";
       }
}




/* ---------------------------------*/

?>
<style>
.lista{
        border-color: #b1c3d1!important;
         border-width:0.01em;
          border-style: solid;
          margin-bottom: 3px;
           padding: 5px;
}

.lista:hover  {
        background-color: #b2e8ff;
}

.redimportant{
        background-color: #fef17c !important; 
}

.greennotimportant{
        background-color: #b9f5c0 !important;  
}



</style>

<script>
    
    $(document).ready(function(){


          $("#btnper").click(function(e){
                $('#modalperfil').modal('show');
                return false;
              
               
          });

          $("#btnanexos").click(function(){
                $('#modaluploadanexos').modal('show');
                    return false;
          });

        
          $("#btnconclusiones").click(function (){
                $('#modalconclusiones').modal('show');
                    return false;

          });

          
          $("#btbdiagnostico").click(function (){
                 $('#modaldiagnostico').modal('show');
                    return false;

          });

          $("#btnconclusioneslimpiar").click(function(){
                
                valor = '';
                 $('#txtrecomendaciones').val(valor);
                 return false;
          })
          $("#btnlimpiardiagnostico").click(function(){
                valor = '';
                 $('#txtprediagnostico').val(valor);
                 return false;
          })



      


          $("#btndeleteanexos").click(function(){
                var url = "../utilidades/eliminaranexos.php?pruebaid=<?=$PruebaId?>";
                     $.ajax({
                         type:"POST",
                         url: url,
                        //  data: $("#frm_filtrar").serialize(),
                         success: function(data){
                                 $("#resp").html(data);
                         }
                     });
                     return false;



                return false;  
          })

        
         function escribir(texto){
                let valor =  $('#txtrecomendaciones').val();
                let cadena = valor;
                caneda += texto;
                $('#txtrecomendaciones').val(caneda);  
                 return true;
          }


          $("#btnpropio").click(function(){
               $('#propio').show();
                return false;
          });

          $("#btnfinalizar").click(function(){
                $('#modalFinalizar').modal('show');
                return false;
          });

         


        

     });
    
</script>

<div id='wrapper'>
    <div id='main-nav-bg'></div>

                <?php
                //cargamos el menu lateral
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
                                <span>Datos de la prueba</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -------------------------------------------- -->
                            <form class='form' method="POST" id="frmshow" style='margin-bottom: 0;' autocomplete="off">
                                                    
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
                                                                            <div  class='span2 '>
                                                                                    <div class='control-group'>
                                                                                                <label class='control-label'>Altura(cm)</label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12' id='mtxtaltura' name="mtxtaltura" type='text' value="<?=$altura?>" disabled >
                                                                                                </div>
                                                                                    </div>
                                                                                         
                                                                            </div>
                                                                            <div  class='span2 '>
                                                                                    <div class='control-group'>
                                                                                                <label class='control-label'>Peso(Kg)</label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12' id='mtxtpeso' name="mtxtpeso" type='text' value="<?=$peso?>" disabled >
                                                                                                </div>
                                                                                    </div>   
                                                                            </div>
                                                                            <div  class='span2 '>
                                                                                    <div class='control-group'>
                                                                                                <label class='control-label'>Masa corporal(Kg/m²)</label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12' id='mtxtmasa' name="mtxtmasa" type='text' value="<?=$masa?>" disabled >
                                                                                                </div>
                                                                                    </div>   
                                                                            </div>
                                                                            <div  class='span2 '>
                                                                                    <div class='control-group'>
                                                                                                <label class='control-label'>Tensión arterial(mmHg)</label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12' id='mtxttension' name="mtxttension" type='text' value="<?=$tension?>"  disabled>
                                                                                                </div>
                                                                                    </div>
                                                                                         
                                                                            </div>
                                                                          

                                                                </div>
                                                                <div class='row-fluid'>
                                                                            <div  class='span2 '>
                                                                                    <div class='control-group'>
                                                                                                <label class='control-label'>Hora de máximo esfuerzo(H:m:s)</label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12' id='mtxtmomentomaximo' name="mtxtmomentomaximo" type='text' value="<?=$momentomaximo?>" disabled >
                                                                                                </div>
                                                                                    </div>
                                                                                         
                                                                            </div>
                                                                           <div  class='span2 '>
                                                                                    <div class='control-group'>
                                                                                                <label class='control-label'>Frecuencia cardiaca <strong>en reposo</strong>(lpm)</label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12 redimportant' id='mtxtfc' name="mtxtfc" type='text' value="<?=$frecuenciacardica?>"  >
                                                                                                </div>
                                                                                    </div>
                                                                                         
                                                                            </div>
                                                                            <div  class='span2 '>
                                                                                    <div class='control-group'>
                                                                                                <label class='control-label'>FC momento <strong>máximo esfuerzo</strong> (lpm)</label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12 redimportant' id='mtxtfcmomento' name="mtxtfcmomento" type='text' value="<?=$fcmomentomaximo?>"  >
                                                                                                </div>
                                                                                    </div>
                                                                                         
                                                                            </div>
                                                                            <div  class='span2 text-center'>
                                                                                <br>
                                                                              <i class="icon-male"></i><br>
                                                                              <button  class="btn btn-inverse" style="margin-bottom:5px"  name="btnper" id="btnper">Perfil del paciente</button>
                                                                            </div>
                                                                </div>


                                                                <div class='row-fluid'>
                                                                           
                                                                            <div  class='span2 '>
                                                                                    <div class='control-group'>
                                                                                                <label class='control-label'>FC primer minuto de recuperación(lpm)</label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12 redimportant' id='mtxtfcprimerminuto' name="mtxtfcprimerminuto" type='text' value="<?=$fcprimerminuto?>"  >
                                                                                                </div>
                                                                                    </div>
                                                                                         
                                                                            </div>
                                                                            <div  class='span2 '>
                                                                                    <div class='control-group'>
                                                                                                <label class='control-label'>FC segundo minuto de recuperación(lpm)</label>
                                                                                                <div class='controls'>
                                                                                                <input class='span12 redimportant' id='mtxtsegundomunuto' name="mtxtsegundomunuto" type='text' value="<?=$fcsegundominuto?>"  >
                                                                                                </div>
                                                                                    </div>
                                                                                         
                                                                            </div>

                                                                </div>

                                                                <div class="row-fluid">
                                                                                <div  class='span8 '>
                                                                                <div class="control-group">
                                                                                        <div class="controls">
                                                                                        Sintomas durante la sesión <br>
                                                                                                <label class="checkbox inline">
                                                                                                <input id="txtdolorcabeza" name="txtdolorcabeza" type="checkbox" value="1" <?php if($dolorcabeza == 1){ echo"checked";} ?> disabled>
                                                                                                Dolor de cabeza
                                                                                                </label>
                                                                                                <label class="checkbox inline">
                                                                                                <input id="txtmareo" name="txtmareo" type="checkbox" value="1" <?php if($mareo == 1){ echo"checked";} ?> disabled>
                                                                                                Mareo
                                                                                                </label>
                                                                                                <label class="checkbox inline">
                                                                                                <input id="txtnauseas" name="txtnauseas" type="checkbox" value="1" <?php if($nauseas == 1){ echo"checked";} ?> disabled>
                                                                                                Náusea
                                                                                                </label>
                                                                                                <label class="checkbox inline">
                                                                                                <input id="txtfaltaaire" name="txtfaltaaire" type="checkbox" value="1" <?php if($faltaaire == 1){ echo"checked";} ?> disabled>
                                                                                                Falta de aire
                                                                                                </label>
                                                                                               
                                                                                        </div>
                                                                                        </div>      
                                                                                </div>
                                                                </div>
                                                                <div class="row-fluid">
                                                                                <div  class='span8 '>
                                                                                <div class="control-group">
                                                                                        <div class="controls">
                                                                                     
                                                                                             
                                                                                                <label class="checkbox inline">
                                                                                                <input id="txtdolorpecho" name="txtdolorpecho" type="checkbox" value="1" <?php if($dolorpecho == 1){ echo"checked";} ?> disabled>
                                                                                                Dolor de pecho
                                                                                                </label>
                                                                                                <label class="checkbox inline">
                                                                                                <input id="txtpalpitaciones" name="txtpalpitaciones" type="checkbox" value="1" <?php if($palpitaciones == 1){ echo"checked";} ?> disabled>
                                                                                                Palpitaciones
                                                                                                </label>
                                                                                                <label class="checkbox inline">
                                                                                                <input id="txtdesmayo" name="txtdesmayo" type="checkbox" value="1" <?php if($desmayo == 1){ echo"checked";} ?> disabled>
                                                                                                Desmayo
                                                                                                </label>
                                                                                                <br><br>
                                                                                        </div>
                                                                                        </div>      
                                                                                </div>
                                                                </div>
                                                                <div class="row-fluid">
                                                                                <div  class='span8 '>
                                                                                <div class="control-group">
                                                                                        <label class="control-label" for="inputTextArea1">Incidencias durante la sesion</label>
                                                                                        <div class="controls">
                                                                                        <textarea id="mtxtsintomas" name="mtxtsintomas" placeholder="Incidencias durante la sesion" rows="5" style="margin: 0px; width: 100%; height: 110px;" disabled><?=$sintomas?></textarea>
                                                                                        </div>
                                                                                </div>
                                                                                </div>
                                                                </div>
                                                                <div class="row-fluid">
                                                                        <div class="span8">
                                                                                <div class="title">
                                                                                        Archivos adjuntos
                                                                                        <small class="muted">click en el archivo para descargar</small>
                                                                                </div>
                                                                                <div class="content" style="border: 1px solid #999999;padding: 10px;">

                                                                                <?php 
                                                                                        foreach ($archivos as $key => $value) {
                                                                                                $ruta = $value['Archivo'];
                                                                                                $direccion = "" . $personaId . "_". $ruta;
                                                                                                echo" <a href='download.php?id=$direccion'  target='_blank'><img src='../assets/images/file.png' style='width: 80px'></a>";
                                                                                               
                                                                                        }
                                                                                
                                                                                ?>
                                                                                        
                                                                                        
                                                                                    
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <br>
                                                                <h3>Descripcion del estudio</h3> 

                                                                <div class="row-fluid">
                                                                                <div  class='span8 '>
                                                                                        <div class="control-group">
                                                                                                <label class="control-label" for="txtprediagnostico">Diagnóstico </label>
                                                                                                <div class="controls">
                                                                                                <textarea   id="txtprediagnostico" name="txtprediagnostico" placeholder="Escriba el Diagnóstico" rows="5" style="margin: 0px; width: 100%; height: 110px;" ><?php echo$diagnostico; ?></textarea>
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                                                                                <div class="span1">
                                                                                         <br><br>     
                                                                                        <div class="btn-group-vertical">
                                                                                                <button class="btn btn-success" id="btbdiagnostico" name="btbdiagnostico">
                                                                                                        <i class="icon-edit"></i>
                                                                                                        editar
                                                                                                </button>   
                                                                                                <button class="btn btn-danger" id="btnlimpiardiagnostico" name="btnlimpiardiagnostico">
                                                                                                        <i class="icon-trash"></i>
                                                                                                        vaciar
                                                                                                </button>
                                                                                        </div>
                                                                                </div>
                                                                </div>

                                                                <div class="row-fluid">  
                                                                      <div class="span8">
                                                                          <!-------------------------- tabla calculada ---->
                                                                                <b>Aeróbicos / Anaeróbicos</b>  <br>

                                                                                        <div  class='span2'>
                                                                                                <div class='control-group'>
                                                                                                                <label class='control-label'>50%</label>
                                                                                                                <div class='controls'>
                                                                                                                <input class='span12' id='txta50' name="txta50" type='text' value="<?=$a50 ?>" disabled >
                                                                                                                </div>
                                                                                                </div>   
                                                                                        </div>
                                                                                        <div  class='span2 '>
                                                                                                <div class='control-group'>
                                                                                                                <label class='control-label'>60%</label>
                                                                                                                <div class='controls'>
                                                                                                                <input class='span12' id='txta60' name="txta60" type='text' value="<?=$a60?>" disabled >
                                                                                                                </div>
                                                                                                </div>   
                                                                                        </div>
                                                                                        <div  class='span2'>
                                                                                                <div class='control-group'>
                                                                                                                <label class='control-label'>65%</label>
                                                                                                                <div class='controls'>
                                                                                                                <input class='span12' id='txta65' name="txta65" type='text' value="<?=$a65?>" disabled >
                                                                                                                </div>
                                                                                                </div>   
                                                                                        </div>
                                                                                        <div  class='span2 '>
                                                                                                <div class='control-group'>
                                                                                                                <label class='control-label'>70%</label>
                                                                                                                <div class='controls'>
                                                                                                                <input class='span12' id='txta70' name="txta70" type='text' value="<?=$a70?>" disabled >
                                                                                                                </div>
                                                                                                </div>   
                                                                                        </div>
                                                                                        <div  class='span2 '>
                                                                                                <div class='control-group'>
                                                                                                                <label class='control-label'>75%</label>
                                                                                                                <div class='controls'>
                                                                                                                <input class='span12' id='txta75' name="txta75" type='text' value="<?=$a75?>" disabled >
                                                                                                                </div>
                                                                                                </div>   
                                                                                        </div>
                                                                                      
                                                                      </div>
                                                                </div>
                                                                <div class="row-fluid">
                                                                     <div class="span8">
                                                                      <br style="display:none">
                                                                                         <div  class='span2'>
                                                                                                <div class='control-group'>
                                                                                                                <label class='control-label'>80%</label>
                                                                                                                <div class='controls'>
                                                                                                                <input class='span12' id='txta80' name="txta80" type='text' value="<?=$a80?>" disabled >
                                                                                                                </div>
                                                                                                </div>   
                                                                                        </div>
                                                                                        <div  class='span2 '>
                                                                                                <div class='control-group'>
                                                                                                                <label class='control-label'>85%</label>
                                                                                                                <div class='controls'>
                                                                                                                <input class='span12' id='txta85' name="txta85" type='text' value="<?=$a85?>" disabled >
                                                                                                                </div>
                                                                                                </div>   
                                                                                        </div>
                                                                                        <div  class='span2'>
                                                                                                <div class='control-group'>
                                                                                                                <label class='control-label'>90%</label>
                                                                                                                <div class='controls'>
                                                                                                                <input class='span12' id='txta90' name="txta90" type='text' value="<?=$a90?>" disabled >
                                                                                                                </div>
                                                                                                </div>   
                                                                                        </div>
                                                                                        <div  class='span2'>
                                                                                                <div class='control-group'>
                                                                                                                <label class='control-label'>95%</label>
                                                                                                                <div class='controls'>
                                                                                                                <input class='span12' id='txta95' name="txta95" type='text' value="<?=$a95?>" disabled >
                                                                                                                </div>
                                                                                                </div>   
                                                                                        </div>
                                                                                        <div  class='span2'>
                                                                                                <div class='control-group'>
                                                                                                                <label class='control-label'>100%</label>
                                                                                                                <div class='controls'>
                                                                                                                <input class='span12' id='txta100' name="txta100" type='text' value="<?=$a100?>" disabled >
                                                                                                                </div>
                                                                                                </div>   
                                                                                        </div>

                                                                        </div>   
                                                                </div>

                                                                <div class="row-fluid">
                                                                     <div class="span8">
                                                                                 <b>Parámetros de recuperación </b> <br>
                                                                                <div  class='span2 '>
                                                                                        <div class='control-group'>
                                                                                                        <label class='control-label'>1 minuto (lpm)</label>
                                                                                                        <div class='controls'>
                                                                                                        <input class='span12' id='txtprimermin' name="txtprimermin" type='text' value="<?=$primermin ?>" disabled >
                                                                                                        </div>
                                                                                        </div>   
                                                                                </div>  
                                                                                <div  class='span2 '>
                                                                                        <div class='control-group'>
                                                                                                        <label class='control-label'>2 minuto (lpm)</label>
                                                                                                        <div class='controls'>
                                                                                                        <input class='span12' id='txtsegundomin' name="txtsegundomin" type='text' value="<?=$segundomin ?>" disabled >
                                                                                                        </div>
                                                                                        </div>   
                                                                                </div>    
                                                                                <div  class='span3 '>
                                                                                        <div class='control-group'>
                                                                                                        <label class='control-label'>Índice cronotrópico</label>
                                                                                                        <div class='controls'>
                                                                                                        <input class='span12' id='txtsegundomin' name="txtsegundomin" type='text' value="<?=$indicecrono?>%" disabled >
                                                                                                        </div>
                                                                                        </div>   
                                                                                </div>  
                                                                                <div  class='span3 '>
                                                                                        <div class='control-group'>
                                                                                                        <label class='control-label'>Reserva cronotrópica </label>
                                                                                                        <div class='controls'>
                                                                                                        <input class='span12' id='txtsegundomin' name="txtsegundomin" type='text' value="<?=$resumencrono?>%" disabled >
                                                                                                        </div>
                                                                                        </div>   
                                                                                </div>           
                                                                      </div>
                                                                </div>

                                                                <div class="row-fluid">
                                                                                <div  class='span8 '>
                                                                                        <div class="control-group">
                                                                                                <label class="control-label" for="txtprediagnostico">Conclusiones y recomendaciones</label>
                                                                                                <div class="controls">
                                                                                                <textarea class="char-max-length input-block-level" maxlength="580" id="txtrecomendaciones" name="txtrecomendaciones" placeholder="Escriba las conclusiones y recomendaciones" rows="5" style="margin: 0px; width: 100%; height: 110px;" ><?php echo$conclusiones; ?></textarea>
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                                                                                <div class="span1">
                                                                                         <br><br>     
                                                                                        <div class="btn-group-vertical">
                                                                                                <button class="btn btn-success" id="btnconclusiones">
                                                                                                        <i class="icon-edit"></i>
                                                                                                        editar
                                                                                                </button>   
                                                                                                <button class="btn btn-danger" id="btnconclusioneslimpiar">
                                                                                                        <i class="icon-trash"></i>
                                                                                                        vaciar
                                                                                                </button>
                                                                                        </div>
                                                                                </div>
                                                                </div>


                                                              
                     <!-------------------------AGREGAR ANEXOS-------------------------------- ----->
                     <br>                                                                  
                     <div class="row-fluid">
                                <div class="span8 box box-nomargin">
                                        <div class="box-header green-background">
                                                 <div class="title">Agregar archivos anexos</div>
                                                        <div class="actions">
                                                                  <a class="btn box-collapse btn-mini btn-link" href="#"><i></i></a>
                                                        </div>
                                         </div>
                                                        <div class="box-content">
                                                          <p>
                                                           Los archivos anexos seran agregados al final del informe como URL
                                                          </p>
                                                          <input  class="btn btn-inverse btn-large" name="btnanexos" id="btnanexos" value="Seleccionar archivos" type="submit" />
                                                          <input  class="btn btn-danger btn-large" name="btndeleteanexos" id="btndeleteanexos" value="Eliminar anexos" type="submit" />
                                                        <div>              
                                                </div>
                                        </div>
                                </div>
              
                   </div>
                   <br> 


                     <!----------------------------------------------------------------->



                                                                                               
                                                                        <div style="display:none" id="propio">
                                                                                        <div class="box-content text-center">
                                                                                                <br><br><br>
                                                                                                <strong>Está a punto de enviar un documento PDF de terceros:</strong>
                                                                                                 Al seleccionar un documento PDF de terceros como respuesta a la prueba, descarta totalmente los cambios efectuados en el formulario anterior.
                                                                                                <div>
                                                                                                <a class="file-input-wrapper btn">
                                                                                                <input name="file" id="file" title="Buscar una archivo" type="file" style="left: -183px; top: 0.600021px;">
                                                                                                </a>
                                                                                                </div>
                                                                                        </div>     
                                                                        </div>
                                                             

                                                                <div class="form-actions" style="margin-bottom: 0;">
                                                                        <div class="text-center">
                                                                                <!-- <input  class="btn btn-info btn-large" name="btnpropio" id="btnpropio" value="Utilizar un pdf de terceros" type="submit" /> -->
                                                                                <a style="display:none" id="btnvistaprevia"  class="btn btn-inverse btn-large" href="../utilidades/pdf.php?id=<?=$pruebaidEncripted?>" target="_blank">Vista previa</a>
                                                                                <input  class="btn btn-primary btn-large" name="btnguardar" id="btnguardar" value="Guardar datos" type="submit" />
                                                                                <input style="display:none"   class="btn btn-success btn-large" name="btnfinalizar" id="btnfinalizar" value="Finalizar" type="submit" />
                                                                                <!-- <div class="btn btn-danger btn-large">
                                                                                       Cancelar y devolver prueba
                                                                                </div> -->
                                                                        </div>
                                                                </div>

                                                             <!------------------- Modal Finalizar -------------------------------->
                                                                        <div class='modal hide fade' id='modalFinalizar' role='dialog' tabindex='-1'>
                                                                                <div class='modal-header'>
                                                                                        <button class='close' data-dismiss='modal' type='button'>&times;</button>
                                                                                        <h3>¿Está seguro que desea finalizar la prueba?</h3>
                                                                                </div>
                                                                                <div class='modal-body'>
                                                                                <div class="box-content">
                                                                                                                                                
                                                                                        Si finaliza no podrá modificarla ya que será entregada al paciente.
                                                        
                                                                                </div>
                                                                                </div>
                                                                                <div class='modal-footer'>
                                                                                                                                
                                                                                        <button class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
                                                                                        <input class='btn btn-success' name="btnfin" value="Ok" type="submit"/>
                                                                                </div>
                                                                        </div>
                                                        <!------------------- Modal Finalizar -------------------------------->

                            </form>
                         <!-- -------------------------------------------- -->
                      </div>
                    </div>
                  </div>
                </section>


<!------------------- Modal Diagnostico  ----------------------------->
<div class='modal hide fade' id='modaldiagnostico' role='dialog' tabindex='-1'>
                      <div class='modal-header'>
                        <button class='close' data-dismiss='modal' type='button'>&times;</button>
                        <h3>Seleccione y edite su  diagnostico</h3>
                      </div>
                      <div class='modal-body'>
                      <div class="box-content">
                                        <div class="dd">
                                                <ol class="dd-list">
                                                        <li  data-id='1'>
                                                           <a id='btndia1'>
                                                             <div class='lista'>        
                                                                   <p> Ritmo sinusal predominante durante el estudio. </p>
                                                              </div>
                                                            </a>
                                                        </li>
                                                        <li  data-id='2'>
                                                           <a id='btndia2'>
                                                             <div class='lista'>        
                                                                   <p> La frecuencia cardiaca mínima registrada de ____lpm y frecuencia máxima de ____ lpm. </p>
                                                              </div>
                                                            </a>
                                                        </li>
                                                        <li  data-id='3'>
                                                           <a id='btndia3'>
                                                             <div class='lista'>        
                                                                   <p>  Hubo varios picos de FC relacionados  con el tipo de actividad fisica registrada.</p>
                                                              </div>
                                                            </a>
                                                        </li>
                                                        <li  data-id='4'>
                                                           <a id='btndia4'>
                                                             <div class='lista'>        
                                                                   <p>  La tasa de recuperación de frecuencia cardíaca despues de finalizado el pico de esfuerzo final  fue de ____  lpm al 1er min y de ___  lpm al 2 min, considerada normal.</p>
                                                              </div>
                                                            </a>
                                                        </li>
                                                        <li  data-id='5'>
                                                           <a id='btndia5'>
                                                             <div class='lista'>        
                                                                   <p> No se constatan arritmias significativas durante el estudio.</p>
                                                              </div>
                                                            </a>
                                                        </li>
                                                        <li  data-id='6'>
                                                           <a id='btndia6'>
                                                             <div class='lista'>        
                                                                   <p> Extrasistoles ventriculares y supraventriculares aislados - frecuentes,  monomorfos,  durante el esfuerzo y la recuperacion.</p>
                                                              </div>
                                                            </a>
                                                        </li>
                                                        <li  data-id='7'>
                                                           <a id='btndia7'>
                                                             <div class='lista'>        
                                                                   <p> Salvas de taquicardia auricular autolimitada.</p>
                                                              </div>
                                                            </a>
                                                        </li>
                                                        <li  data-id='8'>
                                                           <a id='btndia8'>
                                                             <div class='lista'>        
                                                                   <p> Salva de TV no sostenida.</p>
                                                              </div>
                                                            </a>
                                                        </li>
                                                        <li  data-id='9'>
                                                           <a id='btndia9'>
                                                             <div class='lista'>        
                                                                   <p>  Bloqueo rama derecha frecuencia dependiente.</p>
                                                              </div>
                                                            </a>
                                                        </li>
                                                        <li  data-id='10'>
                                                           <a id='btndia10'>
                                                             <div class='lista'>        
                                                                   <p> Alteraciones de la repolarización significativos, con frecuencias elevadas y persisten despues de finalizado el esfuerzo.</p>
                                                              </div>
                                                            </a>
                                                        </li>



                                                                                        
                                                         <script>
                                                                 $('#btndia1').click(function (){
                                                                         var valor =  $('#txtprediagnostico').val();
                                                                         valor = valor + ' ' + 'Ritmo sinusal predominante durante el estudio. <br> \n';
                                                                         $('#txtprediagnostico').val(valor);
                                                                         swal({
                                                                                                title: 'Hecho!',
                                                                                                text: 'Texto agregado.',
                                                                                                type: 'success',
                                                                                                icon : 'success'
                                                                          })
                                                                         return false;
                                                                 });
                                                                 $('#btndia2').click(function (){
                                                                         var valor =  $('#txtprediagnostico').val();
                                                                         valor = valor + ' ' + 'La frecuencia cardiaca mínima registrada de <?=$frecuenciacardica?> lpm y frecuencia máxima de <?=$fcmomentomaximo?> lpm. <br> \n';
                                                                         $('#txtprediagnostico').val(valor);
                                                                         swal({
                                                                                                title: 'Hecho!',
                                                                                                text: 'Texto agregado.',
                                                                                                type: 'success',
                                                                                                icon : 'success'
                                                                          })
                                                                         return false;
                                                                 });
                                                                 $('#btndia3').click(function (){
                                                                         var valor =  $('#txtprediagnostico').val();
                                                                         valor = valor + ' ' + ' Hubo varios picos de FC relacionados  con el tipo de actividad fisica registrada. <br> \n';
                                                                         $('#txtprediagnostico').val(valor);
                                                                         swal({
                                                                                                title: 'Hecho!',
                                                                                                text: 'Texto agregado.',
                                                                                                type: 'success',
                                                                                                icon : 'success'
                                                                          })
                                                                         return false;
                                                                 });
                                                                 $('#btndia4').click(function (){
                                                                         var valor =  $('#txtprediagnostico').val();
                                                                         valor = valor + ' ' + ' La tasa de recuperación de frecuencia cardíaca despues de finalizado el pico de esfuerzo final  fue de <?=$primermin?>  lpm al 1er min y de <?=$segundomin?> lpm al 2 min, considerada normal.<br> \n';
                                                                         $('#txtprediagnostico').val(valor);
                                                                         swal({
                                                                                                title: 'Hecho!',
                                                                                                text: 'Texto agregado.',
                                                                                                type: 'success',
                                                                                                icon : 'success'
                                                                          })
                                                                         return false;
                                                                 });
                                                                 $('#btndia5').click(function (){
                                                                         var valor =  $('#txtprediagnostico').val();
                                                                         valor = valor + ' ' + ' No se constatan arritmias significativas durante el estudio. \n';
                                                                         $('#txtprediagnostico').val(valor);
                                                                         swal({
                                                                                                title: 'Hecho!',
                                                                                                text: 'Texto agregado.',
                                                                                                type: 'success',
                                                                                                icon : 'success'
                                                                          })
                                                                         return false;
                                                                 });
                                                                 $('#btndia6').click(function (){
                                                                         var valor =  $('#txtprediagnostico').val();
                                                                         valor = valor + ' ' + 'Extrasistoles ventriculares y supraventriculares aislados - frecuentes,  monomorfos,  durante el esfuerzo y la recuperacion. <br> \n';
                                                                         $('#txtprediagnostico').val(valor);
                                                                         swal({
                                                                                                title: 'Hecho!',
                                                                                                text: 'Texto agregado.',
                                                                                                type: 'success',
                                                                                                icon : 'success'
                                                                          })
                                                                         return false;
                                                                 });
                                                                 $('#btndia7').click(function (){
                                                                         var valor =  $('#txtprediagnostico').val();
                                                                         valor = valor + ' ' + 'Salvas de taquicardia auricular autolimitada. <br> \n';
                                                                         $('#txtprediagnostico').val(valor);
                                                                         swal({
                                                                                                title: 'Hecho!',
                                                                                                text: 'Texto agregado.',
                                                                                                type: 'success',
                                                                                                icon : 'success'
                                                                          })
                                                                         return false;
                                                                 });
                                                                 $('#btndia8').click(function (){
                                                                         var valor =  $('#txtprediagnostico').val();
                                                                         valor = valor + ' ' + 'Salva de TV no sostenida. <br> \n';
                                                                         $('#txtprediagnostico').val(valor);
                                                                         swal({
                                                                                                title: 'Hecho!',
                                                                                                text: 'Texto agregado.',
                                                                                                type: 'success',
                                                                                                icon : 'success'
                                                                          })
                                                                         return false;
                                                                 });
                                                                 $('#btndia9').click(function (){
                                                                         var valor =  $('#txtprediagnostico').val();
                                                                         valor = valor + ' ' + 'Bloqueo rama derecha frecuencia dependiente.<br> \n';
                                                                         $('#txtprediagnostico').val(valor);
                                                                         swal({
                                                                                                title: 'Hecho!',
                                                                                                text: 'Texto agregado.',
                                                                                                type: 'success',
                                                                                                icon : 'success'
                                                                          })
                                                                         return false;
                                                                 });
                                                                 $('#btndia10').click(function (){
                                                                         var valor =  $('#txtprediagnostico').val();
                                                                         valor = valor + ' ' + 'Alteraciones de la repolarización significativos, con frecuencias elevadas y persisten despues de finalizado el esfuerzo. <br> \n';
                                                                         $('#txtprediagnostico').val(valor);
                                                                         swal({
                                                                                                title: 'Hecho!',
                                                                                                text: 'Texto agregado.',
                                                                                                type: 'success',
                                                                                                icon : 'success'
                                                                          })
                                                                         return false;
                                                                 });

                                                         </script>  
                                                </ol>
                                        </div>
                  </div>
                      </div>
                      <div class='modal-footer'>
                        <button class='btn btn-danger' data-dismiss='modal'>Terminar</button>
                      </div>
                </div>
<!------------------- Modal Diagnostico  ----------------------------->
<!------------------- Modal Conclusiones ----------------------------->
                <div class='modal hide fade' id='modalconclusiones' role='dialog' tabindex='-1'>
                      <div class='modal-header'>
                        <button class='close' data-dismiss='modal' type='button'>&times;</button>
                        <h3>Seleccione y edite sus conclusiones</h3>
                      </div>
                      <div class='modal-body'>
                      <div class="box-content">
                                        <div class="dd">
                                                <ol class="dd-list">
                                                        <?php
                                                        $i= 0;
                                                                foreach ($listaconcluciones as $key => $value){
                                                                        $i++;
                                                                        $id = $value["ConclusionId"];
                                                                        $texto = $value["Texto"];
                                                                        echo"
                                                                        <li  data-id='$i'>
                                                                           <a id='btnconclusiones$id'>
                                                                             <div class='lista'>
                                                                                  <p> $texto </p> 
                                                                             </div>   
                                                                           </a>
                                                                        </li>
                                                                                
                                                                        <script>
                                                                                $('#btnconclusiones$id').click(function (){
                                                                                        var valor =  $('#txtrecomendaciones').val();
                                                                                        valor = valor + ' ' + '$texto';
                                                                                        $('#txtrecomendaciones').val(valor);

                                                                                        swal({
                                                                                                title: 'Hecho!',
                                                                                                text: 'Texto agregado.',
                                                                                                type: 'success',
                                                                                                icon : 'success'
                                                                                        })
                                                                                        return false;
                                                                                });
                                                                        </script>

                                                                     ";
                                                                }
                                                        ?>
                                                        
                                                </ol>
                                        </div>
                  </div>
                      </div>
                      <div class='modal-footer'>
                        <button class='btn btn-danger' data-dismiss='modal'>Terminar</button>
                      </div>
                </div>
<!------------------- Modal Conclusiones ----------------------------->
<!------------------- Modal Upload ----------------------------------->
                <div class='modal hide fade' id='modaluploadanexos' role='dialog' tabindex='-1'>
                      <div class='modal-header'>
                        <button class='close' data-dismiss='modal' type='button'>&times;</button>
                        <h3>Agregar anexos</h3>
                      </div>
                      <div class='modal-body'>
                        <div class="box-content">
                               <!-- cargar archivos -->
                                 
                                      <div class="control-group">
                                           <form id="myawesomedropzone" action="../utilidades/anexosupload.php?id=<?=$PruebaId?>" class="dropzone">
                                          
                                          </form>
                                      </div>
                           
                                <!-- cargar archivos -->
                        
                        </div>
                      </div>
                      <div class='modal-footer'>
                        <button class='btn btn-danger' data-dismiss='modal'>Finalizar</button>
                      </div>
                </div>
<!------------------- Modal Upload ----------------------------------->

<!------------------- Modal Perfil ----------------------------------->
<div class='modal hide fade' id='modalperfil' role='dialog' tabindex='-1'>
                      <div class='modal-header'>
                        <button class='close' data-dismiss='modal' type='button'>&times;</button>
                        <h3>Perfil del usuario</h3>
                      </div>
                      <div class='modal-body'>
                        <div class="box-content">
                               <!-- cargar antecedentes -->
                                 
                                      
                                        <div class="row-fluid">
                                                  <div  class='span9 '>
                                                    <div class="control-group">
                                                    <b>Antecedentes patológicos personales y familiares </b> <br>
                                                      <div class="controls">
                                                    
                                                      <label class="checkbox inline">
                                                          <input id="txtcadio" name="txtcadio" type="checkbox" value="1" <?php if($AntecedentesCardiopatia == 1){ echo"checked";}?> disabled>
                                                          Cardiopatía
                                                        </label>
                                                        <label class="checkbox inline">
                                                          <input id="txthiper" name="txthiper" type="checkbox" value="1" <?php if($AntecedentesHiper == 1){ echo"checked";}?> disabled>
                                                          Hipertensión
                                                        </label>
                                                   
                                                        <label class="checkbox inline">
                                                          <input id="txtdiabetes" name="txtdiabetes" type="checkbox" value="1" <?php if($AntecedentesDiabetes == 1){ echo"checked";}?> disabled>
                                                          Diabetes Mellitus
                                                        </label>
                                                   
                                                      </div>
                                                    </div>
                                                  
                                                        <div class='control-group'>
                                                            <label class='control-label'>Otro</label>
                                                            <div class='controls'>
                                                            <input class='span6' id='txtantecedentesotro' name="txtantecedentesotro" type='text' value="<?=$AntecendentesOtro?>" disabled>
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                         </div>

                                         <div class="row-fluid">
                                                  <div  class='span8 '>
                                                    <div class="control-group">
                                                      <div class="controls">
                                                        <b>Hábitos </b> <br>
                                                          <label class="checkbox inline">
                                                            <input id="txtcafe" name="txtcafe" type="checkbox" value="1" <?php if($HabitosCafe == 1){ echo"checked";}?> disabled>
                                                            Café
                                                          </label>
                                                          <label class="checkbox inline">
                                                            <input id="txttabaco" name="txttabaco" type="checkbox" value="1" <?php if($HabitosTabaco == 1){ echo"checked";}?> disabled>
                                                            Tabaco
                                                          </label>
                                                          <label class="checkbox inline">
                                                            <input id="txtalcohol" name="txtalcohol" type="checkbox" value="1" <?php if($HabitosAlcohol == 1){ echo"checked";}?> disabled>
                                                            Alcohol
                                                        </label>
                                                      </div>
                                                      <div class='control-group'>
                                                            <label class='control-label'>Otro</label>
                                                            <div class='controls'>
                                                            <input class='span6' id='txthabitosotro' name="txthabitosotro" type='text' value="<?=$HabitosOtro?>" disabled>
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                  </div>
                                         </div>

                                         <div class="row-fluid">
                                                  <div  class='span5 '>
                                                      <div class="control-group">
                                                        <label class="control-label" for="txtcomentarios"> <b>Comentarios</b> </label>
                                                        <div class="controls">
                                                          <textarea id="txtcomentarios" name="txtcomentarios" placeholder="Escriba sus comentarios" rows="5" style="margin: 0px; width: 100%; height: 110px;" disabled><?php echo $Comentarios; ?></textarea>
                                                        </div>
                                                      </div>
                                                  </div>
                                                  <div  class='span5 '>
                                                      <div class="control-group">
                                                        <label class="control-label" for="txtmedicamento"> <b>Medicamento actual</b> </label>
                                                        <div class="controls">
                                                          <textarea id="txtmedicamento" name="txtmedicamento" placeholder="Escriba sus comentarios" rows="5" style="margin: 0px; width: 100%; height: 110px;" disabled><?php echo $Medicamento; ?></textarea>
                                                        </div>
                                                      </div>
                                                  </div>
                                          </div>
                           
                                <!-- cargar antecedentes -->
                        
                        </div>
                      </div>
                      <div class='modal-footer'>
                        <button class='btn btn-success' data-dismiss='modal'>Ok</button>
                      </div>
                </div>
<!------------------- Modal Perfil ----------------------------------->
              <div id="resp">
              </div>

    </div>
</div>
       
<?php 
       echo $html->loadJS("sistema");
       echo $html->PrintBodyClose(); 
?>