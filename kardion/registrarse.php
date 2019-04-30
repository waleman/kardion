 
<!DOCTYPE html>
<html>
  <head>
  <link rel='shortcut icon' href='assets/images/kardion.png' />
    <title>K6 - Sistema de nominas</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta content='text/html;charset=utf-8' http-equiv='content-type'>
  
    <?php 
        require_once('clases/usuario_controller.php');
        require_once('clases/cargar.php');
        require_once('clases/personas_controller.php');
        $Recursos = new cargar;
        $personas = new personas;
        $usuarios  = new usuario;
        echo $Recursos->LoadJquery();
        echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
        echo "<script src='assets/javascripts/jquery.mask.js'></script>";
        echo $Recursos->LoadCssLogin();

         


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
                 if( !document.getElementById('txtcorreo').value){
                    let val = "Debe escribir un correo valido";
                    mostrar(val);
                    return false;
                  }else if( !document.getElementById('txtpassword').value){
                    let val = "Debe escribir su contraseña";
                    mostrar(val);
                    return false;
                  }else if( !document.getElementById('txtpassword2').value){
                    let val = "Debe confirmar la contraseña";
                    mostrar(val);
                    return false;
                  }else if( !document.getElementById('marked').value){
                    let val = "Debe aceptar los terminos y condiciones";
                    mostrar(val);
                    return false;
                  }else if( document.getElementById('txtpassword2').value != document.getElementById('txtpassword').value){
                    let val = "Las contraseñas no coinciden";
                    mostrar(val);
                    return false;
                  }else if( document.getElementById('cbosoy').value == 0){
                    let val = "Debe seleccionar que tipo de usuario es";
                    mostrar(val);
                    return false;
                  }else{
                    return true;
                  }
              });


                $('#terminos').on('change', function() {
                    var val = this.checked ? this.value : '';
                    document.getElementById('marked').value = val;

                });


                $('#cbosoy').on('change', function(){
                    if( document.getElementById('cbosoy').value == 7){
                       $("#datospaciente").show();
                       $("#datosdoctor").hide()
                       $("#datoscomp").hide()
                    }else if( document.getElementById('cbosoy').value == 3){
                       $("#datospaciente").hide();
                       $("#datosdoctor").show()
                       $("#datoscomp").hide()
                    }else if( document.getElementById('cbosoy').value == 4){
                       $("#datospaciente").hide();
                       $("#datosdoctor").hide()
                       $("#datoscomp").show()
                    }
                });


           });
          
    </script>


  </head>



  <body class='contrast-fb application-error 500-error contrast-background' style="padding-top:50px;">

  <?php 
            if (isset($_POST['btnregister'])){
             // print_r($_POST);
                $correo = $_POST['txtcorreo'];
                $password = $_POST['txtpassword'];
                $soy = $_POST['cbosoy'];
                $nombre ="";
                    if(isset($_POST['txtnombre'])){
                      $nombre = $_POST['txtnombre'];
                    }
                $primernombre = "";
                    if(isset($_POST['txtprimernombre'])){
                      $primernombre = $_POST['txtprimernombre'];
                    }
                $primerapellido ="";
                    if(isset($_POST['txtprimerapellido'])){
                      $primerapellido = $_POST['txtprimerapellido'];
                    }
                $doctornombre = "" ;
                    if(isset($_POST['txtdoctornombre'])){
                      $doctornombre = $_POST['txtdoctornombre'];
                    }
                $genero = "";
                if(isset($_POST['cbogenero'])){
                  $genero = $_POST['cbogenero'];
                }
                $fechanac="";
                if(isset($_POST['txtfechanac'])){
                  $fechanac = $_POST['txtfechanac'];
                }
                
                
                

              $verificar = $usuarios->check_not_busy($correo);
              if($verificar){
                 echo "
               
                    <script>
                       swal({
                         title: 'Error!',
                         text: ' El correo : $correo. Ya esta en uso! ',
                         icon: 'warning',
                       });
                    
                    </script>

                ";
              }else{

                  if($soy == 7){
                    //creamos el perfil de la persona 
                     $verpersona = $personas->nuevapersona_register($primernombre,$primerapellido,$correo,$fechanac,$genero);
                        if($verpersona){
                          $id = $personas->BuscarId($correo);
                          $check = $usuarios->CrearUsuarioPacienteconPassword($correo,'',$id,'0',$password);
           
                        }else{
                          $check = false;
                          echo "
                            <script>
                              swal({
                                title: 'Error!',
                                text: 'No se ha podido crear su perfil de paciente. Póngase en contacto con el servicio técnico ',
                                icon: 'error',
                              });
                          
                            </script>
                          ";
                        }

                    
                  }else if($soy ==3){
                    $check=  $usuarios->CrearUsuarioDoctor($correo,$password,$doctornombre);
              
                  }else if($soy ==4){
                    $check = $usuarios->register($correo,$password,$nombre);
                   
                  }else{
                    
                  }

                
                  if($check){
                    $usuarios-> eviarEmail($correo);

                    echo"<script>
                            swal({
                                    title: 'Su cuenta ha sido creada',
                                    text: 'Revise su correo electrónico para activarla ',
                                    icon: 'success',
                                    type: 'success'
                            }).then(function() {
                                    window.location = 'login.php';
                            });
                         </script>";


                  }else{
                     echo "
                          <script>
                            swal({
                              title: 'Error!',
                              text: ' No se ha podido crear su cuenta. Póngase en contacto con el servicio técnico ',
                              icon: 'error',
                            });
                        
                          </script>
                    ";
                  }




              }
         }else{
             $correo = "";
             $password = "";
         }


?>

<section class="container-fluid">
        <div class='row-fluid'>
                        <div class='span12 box bordered-box blue-border'>
                 
                        <div class='box-content box-double-padding'>
                            <form class='form' method="POST" style='margin-bottom: 0;'>
                            <fieldset>
                                <div class='span4 text-center' ><br><br>
                                   <img  width="200px" src="public/logos/kardion.png" alt="">
                             
                                </div>
                                <div class='span7 offset1'>
                                      <div class='row-fluid'>
                                        <div  class='span7 '>
                                            <div class='control-group'>
                                                <label class='control-label'>Correo electronico</label>
                                                <div class='controls'>
                                                <input class='span12' id='txtcorreo' name="txtcorreo" type='email' value="<?php echo $correo;?>">
                                                <p class='help-block'></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='row-fluid'>
                                        <div  class='span7 '>
                                            <div class='control-group'>
                                                <label class='control-label'>Contraseña</label>
                                                <div class='controls'>
                                                <input class='span12' id='txtpassword' name="txtpassword" type='password'>
                                                <p class='help-block'></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='row-fluid'>
                                        <div  class='span7 '>
                                            <div class='control-group'>
                                                <label class='control-label'>Repita la Contraseña</label>
                                                <div class='controls'>
                                                <input class='span12' id='txtpassword2' name="txtpassword2" type='password'>
                                                <p class='help-block'></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class='row-fluid' id="" >
                                        <div  class='span7 '>
                                            <div class='control-group'>
                                                <label class='control-label'>Soy</label>
                                                    <div class='controls'>
                                                                    <select id="cbosoy" name="cbosoy" >
                                                                        <option value='0' selected disabled>- Seleccione uno -</option>  
                                                                        <option value='7'  >Paciente</option>   
                                                                        <option value='3'  >Doctor</option>   
                                                                        <option value='4'  >Empresa</option>                                                                           
                                                                    </select>
                                                    </div>
                                             </div>
                                        </div>
                                    </div>


                                    <div class='row-fluid' id="datoscomp" style="display:none">
                                        <div  class='span7 '>
                                            <div class='control-group'>
                                                <label class='control-label'>Nombre de la compañia</label>
                                                <div class='controls'>
                                                <input class='span12' id='txtnombre' name="txtnombre" type='text'>
                                                <p class='help-block'></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class='row-fluid' id="datospaciente" style="display:none">
                                        <div  class='span7 '>
                                              <div  class='span6 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Primer Nombre</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtprimernombre' name="txtprimernombre" type='text' >
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div  class='span6 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Primer Apellido</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtprimerapellido' name="txtprimerapellido" type='text' >
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                        </div>
                                        <div  class='span7 ' style="margin-left:0px;">
                                               <div  class='span6'>
                                                     <div class='control-group'>
                                                        <label class='control-label'>Fecha de nacimiento</label>
                                                        <div class="controls">
                                                        <input  class='span12' name="txtfechanac"  id="txtfechanac" data-mask="99-99-9999" data-rule-dateiso="true" data-rule-required="true"  placeholder="DD-MM-YYYY" type="text">
                                                    </div>
                                                
                                                            <div class='control-group'>
                                                                <label class='control-label'>Genero</label>
                                                                <div class='controls'>
                                                                        <select id="cbogenero" name="cbogenero" >
                                                                            <option value='0' selected disabled>-- Seleccione una --</option>
                                                                            <option value='Masculino'  >Masculino</option>
                                                                            <option value='Femenino'  >Femenino</option>
                                                                        </select>
                                                                </div>
                                                            </div>
                                                </div>
                                        </div>
                                    </div>

                                    <div class='row-fluid' id="datosdoctor" style="display:none">
                                        <div  class='span7 '>
                                            <div class='control-group'>
                                                <label class='control-label'>Nombre del doctor</label>
                                                <div class='controls'>
                                                <input class='span12' id='txtdoctornombre' name="txtdoctornombre" type='text'>
                                                <p class='help-block'></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class='row-fluid'>
                                        <div  class='span7 '>
                                            <div class='control-group'>
                                            <label class='checkbox inline'>
                                                <input id='terminos' type='checkbox' value='si'>
                                                Acepto los terminos y condiciones en el <a href="avisolegal.php"  class="text-info">Aviso legal</a>
                                            </label>
                                            <input type="hidden" name ="marked" id="marked">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <hr class='hr-normal'>
                        
                            <div class='form-actions' style='margin-bottom: 0;'>
                                <div class='text-center'>
                                <input class="btn btn-primary  btn-large" name="btnregister" id="btnregister" value="Crear cuenta" type="submit" />                    
                      <br><br>
                       <a href="login.php" class="text-info">Ya tienes una cuenta? Inicia sesion</a>
                                </div>
                            </div>
                            </form>
                        </div>
                        </div>
        </div>
  </section>

                    <div id="snackbar">
                        <label id="errorMensaje"></label>
                    </div>

        
      </div>

    </div>


  </body>
</html>


