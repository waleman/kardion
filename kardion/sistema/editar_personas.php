<?php
require_once("../clases/cargar.php");
require_once("../clases/personas_controller.php");
require_once("../clases/usuario_controller.php");
require_once("../clases/roles_controller.php");
require_once("../clases/alertas_controller.php");
$html = new cargar;
$_personas= new personas;
$roles = new roles;
$_alertas = new alertas;
$_usuarios = new usuario;
$html->sessionDataSistem();
echo $html->PrintHead();
echo $html->LoadCssSystem("sistema");
echo $html->LoadJquery("sistema");
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $html->PrintBodyOpen();
echo $html->PrintHeader();

//definimos los permisos para esta pantalla;
$permisos = array(4,5,6);
$rol = $_SESSION['k6']['RolId'];
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; 
$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
    echo $_alertas->errorRedirect("Error","No tiene permiso para acceder a esta pagina","accesoprohibido.php");
}

if(!isset($_GET['usuario']) || !isset($_GET['persona'])){
    echo $_alertas->infoRedirect("Upss!","debe seleccionar primero un cliente para editar","lista_personas.php");
}

$usuarioeditar = $_GET['usuario'];
$personaeditar = $_GET['persona'];

$datospersona = $_personas->BuscarUna($personaeditar);
if(!empty($datospersona)){
    $primernombre = $datospersona['PrimerNombre'];
    $SegundoNombre = $datospersona['SegundoNombre'];
    $PrimerApellido = $datospersona['PrimerApellido'];
    $SegundoApellido = $datospersona['SegundoApellido'];
    $Correo = $datospersona['Correo'];
    $Sexo = $datospersona['Sexo'];
}else{
    $primernombre = "";
    $SegundoNombre = "";
    $PrimerApellido = "";
    $SegundoApellido = "";
    $Correo = "";
    $Sexo = "";
}

if(isset($_POST['btnsend'])){
    $_usuarios->eviarEmail($Correo);
    echo $_alertas->successRedirect("Hecho","Correo enviado exitosamente","lista_personas.php");
}


if(isset($_POST['btnregister'])){
    $mail = $_POST['txtemail'];
    $datos = array(
        'personaId' => $personaeditar,
        'genero'=>$_POST['cbogenero'],
        'primernombre'=>$_POST['txtprimernombre'],
        'segundonombre'=>$_POST['txtsegundonombre'],
        'primerapellido'=>$_POST['txtprimerapellido'],
        'segundoapellido'=>$_POST['txtsegundoapellido'],
        'segundoapellido'=>$_POST['txtsegundoapellido'],
        'correo'=>$mail,  
    );

    $verificar =$_personas->buscarCorreoenUso($mail);
    if ($verificar > 0 && $mail != $Correo){
        echo $_alertas->error("Error","No se puede editar el usuario por que el correo que ha escrito se encuentra en uso.");
    }else{
        $resp2= $_usuarios->EditarMail($mail,$usuarioeditar);
        if($resp2){
            $resp = $_personas->EditarPersona2($datos,$usuarioId);
            echo $_alertas->successRedirect("Hecho","Datos modificados exitosamente","editar_personas.php?usuario=$usuarioeditar&persona=$personaeditar");
        }else{
            $resp = $_personas->EditarPersona2($datos,$usuarioId);
            if(!$resp){
                echo $_alertas->error("Error","No se ha realizado ningún cambio");
            }else{
                echo $_alertas->successRedirect("Hecho","Datos modificados exitosamente","editar_personas.php?usuario=$usuarioeditar&persona=$personaeditar");
            }
        }
    }
}



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
                setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
            }

        $("#btnregister").click(function (){ 
            if(!document.getElementById('txtemail').value){
                mostrar("Debe escirbir el correo electronico");
                return false;
            }else if(!document.getElementById('txtprimernombre').value){
                mostrar("Debe escirbir almenos el primer nombre");
                return false;
            }else if(!document.getElementById('txtprimerapellido').value){
                mostrar("Debe escirbir almenos el primer apellido");
                return false;
            }else if(document.getElementById('cbogenero').value == 0){
                mostrar("Debe seleccionar un genero");
                return false;
            } else{
                let email = document.getElementById('txtemail').value;
                let verificarmail =isEmail(email);
                if(verificarmail){
                    return true
                }else{
                    mostrar("Debe escirbir un correo valido");
                    return false; 
                }
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
                                <span> Editar cliente</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -------------------------------------------- -->
                         <form action="" method="post">
                         <div class='span12 '>
                                             <div class='row-fluid'>
                                                 <div  class='span3 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Primer Nombre</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtprimernombre' name="txtprimernombre" type='text' value="<?=$primernombre?>" >
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
                                             </div>
                                             <div class="row-fluid">
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
                                                            
                                                                <input class='span12' id='txtemail' name="txtemail" type='email' autocomplete="off" value="<?=$Correo?>" >
                                                     
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div  class='span3 '>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Genero</label>
                                                                <div class='controls'>
                                                                        <select id="cbogenero" name="cbogenero" >
                                                                            <?php
                                                                                        if($Sexo == 'Masculino'){
                                                                                           echo " <option value='Masculino' selected>Masculino</option>";
                                                                                        }else{
                                                                                            echo " <option value='Masculino' >Masculino</option>";
                                                                                        }
                                                                                        if($Sexo == 'Femenino'){
                                                                                            echo " <option value='Femenino' selected>Femenino</option>";
                                                                                         }else{
                                                                                             echo " <option value='Femenino' >Femenino</option>";
                                                                                         }
                                                                            ?>
                                                  
                                                                        </select>
                                                                </div>
                                                            </div>
                                                </div>
                                               
                         </div>
                       
                         <div class="form-actions" style="margin-bottom: 0;">
                    
                        <div class="text-center">   
                                                        
                                <input class="btn btn-primary  btn-large" name="btnregister" id="btnregister" value="Guardar" type="submit" />  
                                <a class="btn btn-danger  btn-large" name="btncancelar" id="btncancelar" href="lista_personas.php">Cancelar </a>                   
                                <br><br>
                                <input class="btn btn-inverse btn-large" name="btnsend" id="btnsend" value="Enviar correo de activación" type="submit" />  
                                               

                        </div>
                      </div>
                         </form>
                         <!-- -------------------------------------------- -->
                       </div>
                    </div>
                  </div>
                </section>
                <div id="snackbar" style="background-color: #980a00 !important;">
                            <label id="errorMensaje"></label>
                </div>
</div>
<?php 
echo $html->loadJS("sistema");
echo $html->PrintBodyClose();
?>