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

?>



<section id='content'>
                  <div class='container-fluid'>
                    <div class='row-fluid' id='content-wrapper'>
                      <div class='span12'>
                        <div class='row-fluid'>
                          <div class='span12'>
                            <div class='page-header'>
                              <h4 class='pull-left'>
                                
                                <span>Actualice los campos con su información médica</span>
                              </h4>
                                 
                            </div>
                          </div>
                        </div>

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
                        <br>
                      </div>
                    </div>
                  </div>
                </section>
</div>

<?php 

echo $html->loadJS("sistema");

echo $html->PrintBodyClose();

?>