<?php
require_once("../clases/cargar.php");
require_once("../clases/usuario_controller.php");
require_once("../clases/roles_controller.php");
require_once("../clases/centros_controller.php");
$html = new cargar;
$userdata = new usuario;
$roles = new roles;
$centros= new centros;
$html->sessionDataSistem();
echo $html->PrintHead();
echo $html->LoadCssSystem("sistema");
echo $html->LoadJquery("sistema");
echo $html->PrintBodyOpen();
echo $html->PrintHeader();
//definimos los permisos para esta pantalla;
$permisos = array(4,5);
$rol = $_SESSION['k6']['RolId']; // Rol del usuario que esta logado
$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
  header("Location: accesoprohibido.php");
}
//buscamos el codigo master para seleccionar los centros asociados al usuario
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; // Codigo del usuario

//Lista de centros 
$listacentros = $centros->getCentros($master);
if(empty($listacentros)){
    $listacentros = [];
}

$listaRoles = $userdata->Roles($rol); // mostramos todos los roles 
 // Creamos los tipos de estados 
$listaEstados = [
    'Activo',
    'Inactivo',
];


?>

<div id='wrapper'>
<div id='main-nav-bg'></div>

<?php echo $html->PrintSideMenu();?>



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

          $("#btnregister").click(function (){
                if(document.getElementById('txtpassword').value != document.getElementById('txtpassword2').value ){
                    let val = "Las contraseñas deben ser iguales";
                    mostrar(val);
                    return false;   
                }else if(!document.getElementById('txtusuario').value){
                    let val = "Debe escribir un nombre de Usuario(Correo electronico)";
                    mostrar(val);
                    return false;   
                }else if(!document.getElementById('txtpassword').value ){
                    let val = "Debe escribir una Contraseña";
                    mostrar(val);
                    return false; 
                }else{
                    let correo =document.getElementById('txtusuario').value;
                    let veri = isEmail(correo);
                    if(!veri){
                        let val = "Debe incresar un correo valido";
                        mostrar(val);
                        return false;
                    }else{
                        return true;
                    }
                    
                }
          });


          $("#cborol").change(function() {
                let valor = document.getElementById('cborol').value
               if(valor == 6){
                $("#panelpermisos").show();
               }else{
                $("#panelpermisos").hide();
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
                                <span> Nuevo Usuario</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -------------------------------------------- -->


                 
                
                <form class='form' method="POST" id="frm_filtrar" style='margin-bottom: 0;'>
                
                        <?php
                                if(isset($_POST['btnregister'])){
                                $usuario = $_POST['txtusuario'];
                                $pass = $_POST['txtpassword'];
                                $R = $_POST['cborol'];
                                $Estado = $_POST['cboestado'];


                                $check = $userdata->check_not_busy($usuario); //verificamos que el usuario que inteta crear no este guardado anteriomente
                                if($check == 0){
                                            $resp =  $userdata->registerbyOwner($usuario,$pass,$R,$Estado,$master);
                                            if($resp){
                                                $idinsertado = $userdata->ultimoId();
                                                    if($R == 6){
                                                        foreach($listacentros as $key => $value){
                                                            $idcentro = $value['CentroId'];
                                                            $permisonombre = "ct".$idcentro;
                                                            if(isset($_POST[$permisonombre])){
                                                                $userdata->InsertarPermisos($idinsertado,$idcentro,$usuarioId);
                                                            }
                                                        }
                                                    }
                                            }
                                }else{
                                    echo "
                                    <br/>
                                        <div class='alert alert-error'>
                                            <h4>
                                            Error
                                            </h4>
                                            El correo con el que esta intentando crear el usuario. Ya esta en uso.
                                            <br/>
                                        </div>
                                    ";
                                }
                                
                                }


                        ?>
                    <div class='row-fluid'>

                                        <!-------------------------------------- Datos Generales -->
                   
                                                <div class='span7 '>
                                                    <div class='row-fluid'>
                                                        <div  class='span7 offset1'>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Usuario</label>
                                                                <div class='controls'>
                                                                    <input class='span12' id='txtusuario' name="txtusuario" type='email' >
                                                                    <p class='help-block'></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class='row-fluid'>
                                                            <div  class='span3 offset1'>
                                                                <div class='control-group'>
                                                                    <label class='control-label'>Rol</label>
                                                                    <div class='controls'>
                                                                    <select id="cborol" name="cborol" >
                                                                        <?php 
                                                                                    foreach ($listaRoles as $key => $value) {
                                                                                        $nom = $value['Nombre'];
                                                                                        $id = $value['RolId'];
                                                                                        if($rol == $id){
                                                                                            echo " <option value='$id' selected >$nom</option>";
                                                                                        }else{
                                                                                            echo "<option value='$id' >$nom</option>";
                                                                                        } 
                                                                                    }
                                                                        ?>
                                                                    </select>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            <div  class='span3 offset2'>
                                                                <div class='control-group'>
                                                                    <label class='control-label'>Estado</label>
                                                                    <div class='controls'>
                                                                    <select id="cboestado" name="cboestado" >
                                                                        <?php           
                                                                                    foreach ($listaEstados as $key => $value) {
                                                                                        if($value == $estado){
                                                                                            echo " <option value='$value' selected >$value</option>";
                                                                                        }else{
                                                                                            echo "<option value='$value' >$value</option>";
                                                                                        } 
                                                                                    }
                                                                        ?>
                                                                    </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>
                                                

                                                    <div class='row-fluid'>
                                                        <div  class='span4 offset1'>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Contraseña</label>
                                                                <div class='controls'>
                                                                <input class='span12' id='txtpassword' name="txtpassword" type='password'>
                                                                <p class='help-block'></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div  class='span4 offset1'>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Confirmar Contraseña</label>
                                                                <div class='controls'>
                                                                <input class='span12' id='txtpassword2' name="txtpassword2" type='password'>
                                                                <p class='help-block'></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                
                    
                                        

                    
                
                    <div class='row-fluid' id="panelpermisos" style="display:none"> 
                    <h3>Permitir acceso a centros</h3>
                            <div class='span12 box bordered-box blue-border' style='margin-bottom:0;'>
                                        <div class='box-content box-no-padding'>
                                            <div class='responsive-table'>
                                                <div class='scrollable-area'>
                                                    <table class='table' data='' style='margin-bottom:0;'>
                                                    <thead>
                                                        <tr>
                                                        <th>
                                                            Nombre
                                                        </th>
                                                        <th class='text-center'> Permisos</th>
                                                        <th class='text-center'></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        
                                                        <?php 
                                                        foreach ($listacentros as $key => $value) {
                                                            $nombre = $value["Nombre"];
                                                            $Direccion= $value["Direccion"];
                                                            $Estado= $value["Estado"];
                                                            $centroId = $value["CentroId"]; 
                                                            echo "<tr>
                                                            <td>
                                                                $nombre
                                                            </td>";
                                                            ?>
                                                                <td>
                                                                        <div class='row-fluid'>  
                                                                        <div  class='span6 offset1'>
                                                                        <div class='control-group'>
                                                                        
                                                                            <div class='switch' data-off-label='&lt;i class="icon-remove"&gt;&lt;/i&gt;' data-on-label='&lt;i class="icon-ok"&gt;&lt;/i&gt;' data-on='primary'>
                                                                                <input  type='checkbox' name="ct<?php echo$centroId; ?>">
                                                                            </div>
                                                                        </div>
                                                                        </div>
                                                                </div>
                                                                </td>
                                                            
                                                                </tr>
                                                        
                                                        <?php        
                                                        }
                                                        
                                                        ?>


                                                    
                                                        </tr>
                                                    </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                            </div>
                        </div> 


                            <hr class='hr-normal'>
                        
                                <div class='text-center'>
                                    <input class="btn btn-primary  btn-large" name="btnregister" id="btnregister" value="Guardar" type="submit" />  
                                    <a class="btn btn-danger  btn-large" name="btncancelar" id="btncancelar" href="lista_usuarios.php">Cancelar </a>                   
                                    <br><br>
                
                                </div>
                        
                </form>
                <div id="snackbar">
                            <label id="errorMensaje"></label>
                </div>
                 
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
