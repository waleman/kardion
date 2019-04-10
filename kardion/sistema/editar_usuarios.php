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
$rol = $_SESSION['k6']['RolId'];
$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
  header("Location: accesoprohibido.php");
}
//buscamos el codigo master para seleccionar los centros asociados al usuario
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; // Codigo del usuario
$usuarioModificar = $_GET['id'];

//Lista de centros 
$listacentros = $centros->getCentros($master);
if(empty($listacentros)){
    $listacentros = [];
}
//Lista de centros con permiso
$listacentrospermitidos = $roles->buscarCentrosPermitidos($usuarioModificar);
if(empty($listacentrospermitidos)){
    $listacentrospermitidos = [];
}
//mesclar los dos array
$listamostrar = [];
foreach($listacentros as $k=>$value){
    $cont = 0;
    $ct = $value["CentroId"];
    $nombre = $value["Nombre"];
    foreach($listacentrospermitidos as $ke=>$valores){
        $cp =$valores["CentroId"];
        if($cp == $ct){
            $cont ++;
        }
    }
    if ($cont == 0){
        $guardar = array(
            "CentroId" => $ct,
            "Nombre" => $nombre,
            "Estado" => false
        );
    }else{
        $guardar = array(
            "CentroId" => $ct,
            "Nombre" => $nombre,
            "Estado" => true
        );
    }
    array_push ( $listamostrar , $guardar );
}



$datosUsuario = $userdata->UsuarioDatos($usuarioModificar); // Recoletamos los datos de usuarios 
$listaRoles = $userdata->Roles($rol); // mostramos todos los roles 
 // Creamos los tipos de estados 
$listaEstados = [
    'Activo',
    'Inactivo',
];
$usuario =$datosUsuario[0]['Usuario'];
$rol = $datosUsuario[0]['RolId'];
$estado = $datosUsuario[0]['Estado'];

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

          $("#btnregister").click(function (){
                if(document.getElementById('txtpassword').value != document.getElementById('txtpassword2').value ){
                    let val = "Las contraseñas deben ser iguales";
                    mostrar(val);
                    return false;   
                }else{
                    return true
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


          let rol =  <?php echo$rol ;?>;
          if(rol == 6){
            $("#panelpermisos").show();
          }
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
                                <span> Modificar Usuario</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -------------------------------------------- -->


                 
<form class='form' method="POST" id="frm_filtrar" style='margin-bottom: 0;'>
               
<?php
if(isset($_POST['btnregister'])){
    $pass = $_POST['txtpassword'];
    $R = $_POST['cborol'];
    $Estado = $_POST['cboestado'];
        //modificar rol y estado
       $userdata->editarUsuario($usuarioModificar,$R,$Estado);
       //si se escrito un password se modifica
        if($pass){
            $userdata->Change_password($usuarioModificar,$pass);
        }
        //si es rol #6
         if($R == 6){
             //borramos todos los registros anteriores
                $userdata-> limpiarPermisos($usuarioModificar);
                foreach($listacentros as $key => $value){
                    $idcentro = $value['CentroId'];
                    $permisonombre = "ct".$idcentro;
                    if(isset($_POST[$permisonombre])){
                        //insertamos otros nuevos
                        $userdata->InsertarPermisos($usuarioModificar,$idcentro,$usuarioId);
                    }
                }
        }
}


?>
                  <div class='row-fluid'>

                                    <!-------------------------------------- Datos Generales -->
                                       <fieldset>
                                            <div class='span7 offset1'>
                                                <div class='row-fluid'>
                                                    <div  class='span7 offset1'>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Usuario</label>
                                                            <div class='controls'>
                                                                <input class='span12' id='txtusuario' name="txtusuario" type='text' value="<?php echo $usuario;?>" disabled>
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
                                                        <div  class='span3 offset1'>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Estado</label>
                                                                <div class='controls'>
                                                                <select id="cbocentro" name="cboestado" >
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
                                                    <div  class='span3 offset1'>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Nueva Contraseña</label>
                                                            <div class='controls'>
                                                            <input class='span12' id='txtpassword' name="txtpassword" type='password'>
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div  class='span3 offset1'>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Confirmar Contraseña</label>
                                                            <div class='controls'>
                                                            <input class='span12' id='txtpassword2' name="txtpassword2" type='password'>
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                
                                    </fieldset>  

                
                 
                   <div class='row-fluid'  id="panelpermisos" style="display:none"> 
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
                                                    foreach ($listamostrar as $key => $value) {
                                                        $nombre = $value["Nombre"];
                                                        $Estadoc= $value["Estado"];
                                                        $centroId = $value["CentroId"]; 
                                                        echo "<tr>
                                                        <td>
                                                            $nombre
                                                        </td>";


                                                                if($Estadoc){
                                                                ?>
                                                                    <td>
                                                                            <div class='row-fluid'>  
                                                                                <div  class='span6 offset1'>
                                                                                <div class='control-group'>
                                                                                    <label class='control-label'>Permitir Acesso a esta compañia</label>
                                                                                    <div class='switch' data-off-label='&lt;i class="icon-remove"&gt;&lt;/i&gt;' data-on-label='&lt;i class="icon-ok"&gt;&lt;/i&gt;' data-on='primary'>
                                                                                        <input checked type='checkbox' name="ct<?php echo$centroId; ?>" >
                                                                                    </div>
                                                                                </div>
                                                                                </div>
                                                                            </div>
                                                                    </td>  
                                                                    
                                                                <?php        
                                                                }else{
                                                                ?>
                                                                <td>
                                                                            <div class='row-fluid'>  
                                                                                <div  class='span6 offset1'>
                                                                                <div class='control-group'>
                                                                                    <label class='control-label'>Permitir Acesso a esta compañia</label>
                                                                                    <div class='switch' data-off-label='&lt;i class="icon-remove"&gt;&lt;/i&gt;' data-on-label='&lt;i class="icon-ok"&gt;&lt;/i&gt;' data-on='primary'>
                                                                                        <input  type='checkbox' name="ct<?php echo$centroId; ?>" >
                                                                                    </div>
                                                                                </div>
                                                                                </div>
                                                                            </div>
                                                                    </td>  
                                                 <?php        
                                                                       }
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
