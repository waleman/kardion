<?php
require_once('../clases/cargar_paciente.php');
require_once('../clases/roles_controller.php');
require_once('../clases/personas_controller.php');

$html = new cargar;
$roles = new roles;
$_personas = new personas;
$html->sessionDataSistem(); //iniciamos la sesion en el navegador
//Buscamos los permisos para entrar en esta pantalla
        $permisos = array(7);
        $rol = $_SESSION['k6']['RolId'];
        $personaId = $_SESSION['k6']['PersonaId'];
        $usuarioId = $_SESSION['k6']['UsuarioId'];
        $permiso = $roles->buscarpermisos($rol,$permisos);
        if(!$permiso){
          header("Location: accesoprohibido.php");
        }
echo $html->PrintHead(); //Cargamos en header
echo $html->LoadCssSystem("sistema"); // cargamos todas las librerias css del sistema interno
echo $html->LoadJquery("sistema"); //cargamos todas las librerias Jquery del sistema interno  
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $html->PrintBodyOpen(); //cargamos la primera parte del body
echo $html->PrintHeader(); //cargamos el header

$ver = $_personas->Buscar_antecedentes($personaId);
if($ver){
  $Altura =  $ver[0]['Altura'];
  $Peso =  $ver[0]['Peso'];
  $Temperatura=  $ver[0]['Temperatura'];
  $Tension= $ver[0]['Tension'];
  $AntecedentesHiper= $ver[0]['AntecedentesHiper'];
  $AntecedentesDiabetes= $ver[0]['AntecedentesDiabetes'];
  $AntecedentesCardiopatia= $ver[0]['AntecedentesCardiopatia'];
  $AntecendentesOtro= $ver[0]['AntecendentesOtro'];
  $HabitosCafe= $ver[0]['HabitosCafe'];
  $HabitosTabaco= $ver[0]['HabitosTabaco'];
  $HabitosAlcohol= $ver[0]['HabitosAlcohol'];
  $HabitosOtro= $ver[0]['HabitosOtro'];
  $Comentarios= $ver[0]['Comentarios'];
  $Medicamento= $ver[0]['Medicamento'];
}else{
  $Altura = "";
  $Peso = "";
  $Temperatura= "";
  $Tension="";
  $AntecedentesHiper="0";
  $AntecedentesDiabetes="0";
  $AntecedentesCardiopatia="0";
  $AntecendentesOtro="";
  $HabitosCafe="0";
  $HabitosTabaco="0";
  $HabitosAlcohol="0";
  $HabitosOtro="";
  $Comentarios="";
  $Medicamento="";
}


$datos_personales = $_personas->BuscarUna($personaId);
$PrimerNombre = $datos_personales['PrimerNombre'];
$SegundoNombre = $datos_personales['SegundoNombre'];
$PrimerApellido = $datos_personales['PrimerApellido'];
$SegundoApellido = $datos_personales['SegundoApellido'];
$Direccion = $datos_personales['Direccion'];
$Telefono = $datos_personales['Telefono'];
$Movil = $datos_personales['Movil'];
$Correo = $datos_personales['Correo'];
$NumeroDocumento = $datos_personales['NumeroDocumento'];
$TipoDocumentoId = $datos_personales['TipoDocumentoId'];
$FechaNacimiento = $datos_personales['FechaNacimiento'];
$Sexo = $datos_personales['Sexo'];
$CodigoPostal = $datos_personales['CodigoPostal'];
$listaDocumentos = $_personas->TipoDocumento();

?>
<div id='wrapper'>
<div id='main-nav-bg'></div>

<?php
//cargamos el menu lateral
       echo $html->PrintSideMenu();
       $nombre="";
       //print_r($_SESSION['k6']);

      if(isset($_POST['btnenviar'])){
            if(isset($_POST['txthiper'])){ $hiper =$_POST['txthiper']; }else{ $hiper = "0";}
            if(isset($_POST['txtdiabetes'])){ $diabetes =$_POST['txtdiabetes']; }else{ $diabetes = "0";}
            if(isset($_POST['txtcadio'])){ $cardiopatia =$_POST['txtcadio']; }else{ $cardiopatia = "0";}
            if(isset($_POST['txtcafe'])){ $cafe =$_POST['txtcafe']; }else{ $cafe = "0";}
            if(isset($_POST['txttabaco'])){ $tabaco =$_POST['txttabaco']; }else{ $tabaco = "0";}
            if(isset($_POST['txtalcohol'])){ $alcohol =$_POST['txtalcohol']; }else{ $alcohol = "0";}

            $data= array(
              'Altura' => $_POST['txtaltura'],
              'Peso' => $_POST['txtpeso'],
              'Temperatura'=>$_POST['txttemperatura'],
              'Tension'=>$_POST['txttension'],
              'AntecedentesHiper'=>$hiper,
              'AntecedentesDiabetes'=>$diabetes,
              'AntecedentesCardiopatia'=>$cardiopatia,
              'AntecendentesOtro'=>$_POST['txtantecedentesotro'],
              'HabitosCafe'=>$cafe,
              'HabitosTabaco'=>$tabaco,
              'HabitosAlcohol'=>$alcohol,
              'HabitosOtro'=>$_POST['txthabitosotro'],
              'Comentarios'=>$_POST['txtcomentarios'],
              'Medicamento'=>$_POST['txtmedicamento'],
              'PersonaId'=>$personaId
            );

            $resp =  $_personas->Guardar_antecedentes($data);
            if($resp){

              $Altura = $_POST['txtaltura'];
              $Peso = $_POST['txtpeso'];
              $Temperatura= $_POST['txttemperatura'];
              $Tension=$_POST['txttension'];
              $AntecedentesHiper=$hiper;
              $AntecedentesDiabetes=$diabetes;
              $AntecedentesCardiopatia=$cardiopatia;
              $AntecendentesOtro=$_POST['txtantecedentesotro'];
              $HabitosCafe=$cafe;
              $HabitosTabaco=$tabaco;
              $HabitosAlcohol=$alcohol;
              $HabitosOtro=$_POST['txthabitosotro'];
              $Comentarios=$_POST['txtcomentarios'];
              $Medicamento=$_POST['txtmedicamento'];


                echo"
                  <script>
                      swal('Bien hecho', 'Sus datos han sido actualizados', 'success');
                  </script>
                ";
            }else{
                echo"
                <script>
                    swal('Ooops', 'Sus datos no han podido ser actualizados', 'error');
                </script>
              ";
            }

      }

      if(isset($_POST['btneditar'])){

        $tipodoc="";
        $genero="";
        if(isset($_POST['cbodocumento'])){$tipodoc=$_POST['cbodocumento'];}
        if(isset($_POST['cbogenero'])){$genero=$_POST['cbogenero'];}

        $datos = array(
          'personaId' => $personaId,
          'genero'=> $genero,
          'primernombre'=>$_POST['txtprimernombre'],
          'segundonombre'=>$_POST['txtsegundonombre'],
          'primerapellido'=>$_POST['txtprimerapellido'],
          'segundoapellido'=>$_POST['txtsegundoapellido'],
          'tipodocumento'=>$tipodoc,
          'numerodocumento'=>$_POST['txtnumerodocumento'],
          'movil'=>$_POST['txtmovil'],
          'telefono'=>$_POST['txttelefono'],
          'fechanac'=>$_POST['txtfechanac'],
          'codigopostal'=>$_POST['txtcodigopostal'],
          'direccion'=>$_POST['txtdireccion'],
         );

         $res = $_personas->EditarPersona($datos, $usuarioId);

         if($res){
          echo"<script>
               swal({
                       title: 'Hecho!',
                       text: 'Usuario creado exitosamente!',
                       type: 'success',
                       icon: 'success'
               }).then(function() {
                       window.location = 'portal_cliente.php';
               });
             </script>";
         }else{
          echo"
          <script>
              swal('Ooops', 'Sus datos no han podido ser actualizados', 'error');
          </script>
        ";
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


            function isEmail(email) {
                    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    return regex.test(email);
                }

  $("#btneditar").click(function (){ 
    if(!document.getElementById('txtprimernombre').value){
          mostrar("Debe escirbir almenos el primer nombre");
          return false;
      }else if(!document.getElementById('txtprimerapellido').value){
          mostrar("Debe escirbir almenos el primer apellido");
          return false;
      }else if(!document.getElementById('txtfechanac').value){
          mostrar("Debe escribir la fecha de nacimiento");
          return false;
      }else if(document.getElementById('cbogenero').value == 0){
          mostrar("Debe seleccionar un genero");
          return false;
      } else{   
              return true  
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
                              <h3 class='pull-left'>
                                
                                <span>Información médica y personal</span>
                              </h3>
                                 
                            </div>
                          </div>
                        </div>

                        <!---------------------- Datos medicos ----------->
                        <div class="span12 box bordered-box blue-border" style="margin-left:0px;">
                          <div class="box-header blue-background">
                            <div class="title">
                              Datos médicos
                            </div>
                            <div class="actions">                     
                              <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                              </a>
                            </div>
                          </div>
                          <div class="box-content">
                            <div class="row-fluid">
                            <form class='form' method="POST" id="frm_filtrar" style='margin-bottom: 0;' autocomplete="off">
                                          
                                          <div class='row-fluid'>   
                                                      <div  class='span2'>
                                                          <div class='control-group'>
                                                              <label class='control-label'>Altura (cm)</label>
                                                              <div class='controls'>
                                                              <input class='span12' id='txtaltura' name="txtaltura" type='text' value="<?=$Altura?>">
                                                              <p class='help-block'></p>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div  class='span2 '>
                                                          <div class='control-group'>
                                                              <label class='control-label'>Peso (Kg)</label>
                                                              <div class='controls'>
                                                              <input class='span12' id='txtpeso' name="txtpeso" type='text'value="<?=$Peso?>">
                                                              <p class='help-block'></p>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div  class='span2 '>
                                                          <div class='control-group'>
                                                              <label class='control-label'>Temperatura(C°)</label>
                                                              <div class='controls'>
                                                              <input class='span12' id='txttemperatura' name="txttemperatura" type='text'value="<?=$Temperatura?>">
                                                              <p class='help-block'></p>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div  class='span3 '>
                                                          <div class='control-group'>
                                                              <label class='control-label'>Tension arterial (mmHg)</label>
                                                              <div class='controls'>
                                                              <input class='span12' id='txttension' name="txttension" type='text' value="<?=$Tension?>">
                                                              <p class='help-block'></p>
                                                              </div>
                                                          </div>
                                                      </div>
                                          </div>


                                          <div class="row-fluid">
                                                    <div  class='span8 '>
                                                      <div class="control-group">
                                                        <div class="controls">
                                                        <b>Antecedentes patológicos personales y familiares </b> <br>
                                                          <label class="checkbox inline">
                                                            <input id="txthiper" name="txthiper" type="checkbox" value="1" <?php if($AntecedentesHiper == 1){ echo"checked";}?>>
                                                            Hipertensión arterial
                                                          </label>
                                                          <label class="checkbox inline">
                                                            <input id="txtdiabetes" name="txtdiabetes" type="checkbox" value="1" <?php if($AntecedentesDiabetes == 1){ echo"checked";}?>>
                                                            Diabetes Mellitus
                                                          </label>
                                                          <label class="checkbox inline">
                                                            <input id="txtcadio" name="txtcadio" type="checkbox" value="1" <?php if($AntecedentesCardiopatia == 1){ echo"checked";}?>>
                                                            Cardiopatía
                                                          </label>
                                                        </div>
                                                      </div>
                                                    
                                                          <div class='control-group'>
                                                              <label class='control-label'>Otro</label>
                                                              <div class='controls'>
                                                              <input class='span6' id='txtantecedentesotro' name="txtantecedentesotro" type='text' value="<?=$AntecendentesOtro?>">
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
                                                              <input id="txtcafe" name="txtcafe" type="checkbox" value="1" <?php if($HabitosCafe == 1){ echo"checked";}?>>
                                                              Café
                                                            </label>
                                                            <label class="checkbox inline">
                                                              <input id="txttabaco" name="txttabaco" type="checkbox" value="1" <?php if($HabitosTabaco == 1){ echo"checked";}?>>
                                                              Tabaco
                                                            </label>
                                                            <label class="checkbox inline">
                                                              <input id="txtalcohol" name="txtalcohol" type="checkbox" value="1" <?php if($HabitosAlcohol == 1){ echo"checked";}?>>
                                                              Alcohol
                                                          </label>
                                                        </div>
                                                        <div class='control-group'>
                                                              <label class='control-label'>Otro</label>
                                                              <div class='controls'>
                                                              <input class='span6' id='txthabitosotro' name="txthabitosotro" type='text' value="<?=$HabitosOtro?>">
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
                                                            <textarea id="txtcomentarios" name="txtcomentarios" placeholder="Escriba sus comentarios" rows="5" style="margin: 0px; width: 100%; height: 110px;"><?php echo $Comentarios; ?></textarea>
                                                          </div>
                                                        </div>
                                                    </div>
                                                    <div  class='span5 '>
                                                        <div class="control-group">
                                                          <label class="control-label" for="txtmedicamento"> <b>Medicamento actual</b> </label>
                                                          <div class="controls">
                                                            <textarea id="txtmedicamento" name="txtmedicamento" placeholder="Escriba sus comentarios" rows="5" style="margin: 0px; width: 100%; height: 110px;"><?php echo $Medicamento; ?></textarea>
                                                          </div>
                                                        </div>
                                                    </div>
                                            </div>
                                      <div class="form-actions" style="margin-bottom: 0;">
                                          <div class="text-center">
                                              <input type="submit" class="btn btn-primary btn-large" value="Guardar" name="btnenviar" id="btnenviar"/>
                                            
                                          </div>
                                      </div>

                          </form>
                            </div>
                        
                          </div>
                        </div>
                        <!---------------------- Datos medicos ------------>
                        <!---------------------- Datos personales --------->
                        <div class="span12 box bordered-box blue-border box-nomargin" style="margin-left:0px;">
                          <div class="box-header green-background">
                            <div class="title">
                              Datos personales
                            </div>
                            <div class="actions">
                                <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                              </a>
                            </div>
                          </div>
                          <div class="box-content">
                          <form class='form' method="POST" id="frm_filtrar" style='margin-bottom: 0;' autocomplete="off">
                             <fieldset>
                                <div class='span12 '>

                                         <div class='row-fluid'>
                                                 <div  class='span3 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Primer Nombre</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtprimernombre' name="txtprimernombre" type='text' value="<?=$PrimerNombre?>" >
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div  class='span3 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Segundo Nombre</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtsegundonombre' name="txtsegundonombre" type='text' value="<?=$SegundoNombre?>" >
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                            
                                                 <div  class='span3 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Primer Apellido</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtprimerapellido' name="txtprimerapellido" type='text' value="<?=$PrimerApellido?>" >
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div  class='span3 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Segundo Apellido</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtsegundoapellido' name="txtsegundoapellido" type='text' value="<?=$SegundoApellido?>" >
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>


                                             <div class='row-fluid'>
                                                 <div  class='span3 '>
                                                     <div class='control-group' id="colores">
                                                         <label class='control-label' name="algo1">Email *</label>
                                                         <div class='controls'>
                                                            
                                                                <input class='span12' id='txtemail' name="txtemail" type='email' autocomplete="off" value="<?=$Correo?>" disabled>
                                                     
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div  class='span3 '>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Genero</label>
                                                                <div class='controls'>
                                                                        <select id="cbogenero" name="cbogenero" >
                                                                            <option value='0'  disabled>-- Seleccione una --</option>
                                                                            <?php 
                                                                                if($Sexo == 'Masculino'){
                                                                                 echo " <option value='Masculino' selected >Masculino</option>";
                                                                                }else{
                                                                                  echo " <option value='Masculino'  >Masculino</option>";
                                                                                }
                                                                                if($Sexo == 'Femenino'){
                                                                                  echo " <option value='Femenino' selected >Masculino</option>";
                                                                                 }else{
                                                                                   echo " <option value='Femenino'  >Femenino</option>";
                                                                                 }
                                                                            ?>

                                                                        </select>
                                                                </div>
                                                            </div>
                                                </div>
                                         
                                                <div  class='span3'>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Tipo de documento</label>
                                                                <div class='controls'>
                                                                <select id="cbodocumento" name="cbodocumento" >
                                                                    
                                                                    <?php 

                                                                    if($TipoDocumentoId == ""){
                                                                      echo "<option value='0' selected disabled>-- Seleccione una --</option>";
                                                                    }
                                                                                foreach ($listaDocumentos as $key => $value) {
                                                                                    $nom = $value['Nombre'];
                                                                                    $id = $value['TipoDocumentoId'];
                                                                                    if($TipoDocumentoId == $id){
                                                                                      echo "<option value='$id'selected >$nom</option>";
                                                                                    }else{
                                                                                      echo "<option value='$id' >$nom</option>";
                                                                                    }
                                                                                    
                                                                                }
                                                                    ?>
                                                                </select>
                                                                </div>
                                                            </div>
                                                </div>
                                                 <div  class='span3 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Numero de documento</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtnumerodocumento' name="txtnumerodocumento" type='text' value="<?=$NumeroDocumento?>" >
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>

                                             <div class='row-fluid'>
                                                 <div  class='span3 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Movil</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtmovil' name="txtmovil" type='text' value="<?=$Movil?>" >
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div  class='span3'>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Telefono</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txttelefono' name="txttelefono" type='text' value="<?=$Telefono?>" >
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>

                                                     
                                                 <div  class='span3 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Codigo Postal</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtcodigopostal' name="txtcodigopostal" type='text' value="<?=$CodigoPostal?>" >
                                                         </div>
                                                     </div>
                                                 </div>

                                                 <div  class='span3'>
                                                     <div class='control-group'>
                                                        <label class='control-label'>Fecha de nacimiento</label>
                                                        <div class="controls">
                                                        <input  class='span12' name="txtfechanac"  id="txtfechanac" data-mask="99-99-9999" data-rule-dateiso="true" data-rule-required="true"  placeholder="DD-MM-YYYY" type="text" value="<?=$FechaNacimiento?>">
                                                    </div>
                                                </div>
                                               
                                            </div>


                                             <div class='row-fluid'>
                                                 <div  class='span9 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Direccion</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtdireccion' name="txtdireccion" type='text' value="<?=$Direccion?>">
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>
                                        
                             </fieldset>  
                             <div class="form-actions" style="margin-bottom: 0;">
                                          <div class="text-center">
                                              <input type="submit" class="btn btn-primary btn-large" value="Guardar" name="btneditar" id="btneditar"/>
                                            
                                          </div>
                              </div>
                         </form>
                          </div>
                        </div>
                        <!---------------------- Datos personales --------->
                        <br>
                      </div>
                    </div>
                  </div>
                </section>
</div>
<!------------------- Modal Nuevo paciente ----------------------------->
<div id="snackbar" style="background-color: #980a00 !important;">
             <label id="errorMensaje"></label>
 </div>

<?php 

echo $html->loadJS("sistema");

echo $html->PrintBodyClose();

?>