<?php
require_once("../clases/cargar.php");
require_once("../clases/centros_controller.php");
require_once('../clases/paises_controller.php');
require_once("../clases/roles_controller.php");
$html = new cargar;
$ct = new centros;
$pais = new paises;
$roles = new roles;
$html->sessionDataSistem();
echo $html->PrintHead();
echo $html->LoadCssSystem("sistema");
echo $html->LoadJquery("sistema");
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $html->PrintBodyOpen();
echo $html->PrintHeader();


//definimos los permisos para esta pantalla;
$permisos = array(4,5);
$rol = $_SESSION['k6']['RolId'];
$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
    echo"<script>
    swal({
            title: 'Wow!',
            text: 'Usted no tiene permisos para entrar a esta pagina!',
            type: 'error'
    }).then(function() {
            window.location = 'accesoprohibido.php';
    });
    </script>";
}

//buscamos el codigo master para seleccionar los centros asociados al usuario
$master = $_SESSION['k6']['MasterCompaniaId'];

$userData = $_SESSION['k6'];
$usuarioId =$userData['UsuarioId']; // Codigo del usuario

$listapaises = $pais->getPais();


$nombre = "";
$telefono1 = "";
$telefono2 = "";
$direccion = "";
$codigopostal ="";


?>

<?php
    if(isset($_POST['btnregister'])){
       

        if(isset($_FILES['file']['tmp_name'])){
            $nombre = $ct->generateRandomString(); //generamos un string aleatorio
            $tempFile = $_FILES['file']['tmp_name'];  //seleccionamos el nombre temporal del archivo    
            $ext = explode(".", $_FILES['file']['name']); // Buscamos la extension del archivo
            $ext =end($ext);           
            $nombreCompleto =  $nombre .'.'.$ext; //creamo un nombre para almacenar en la carpeta del servidor
            move_uploaded_file($_FILES['file']['tmp_name'], '../public/logos/'.$nombreCompleto);
        }else{
            $nombreCompleto ="";
        }
    
        $nombre = $_POST['txtnombre'];
        $telefono1 = $_POST['txttelefono1'];
        $telefono2 = $_POST['txttelefono2'];
        $direccion = $_POST['txtdireccion'];
        $pais = $_POST['cbopais'];
        $provincia = $_POST['cboprovincia'];
        $ciudad = $_POST['cbociudad'];
        $codigopostal= $_POST['txtcodigopostal'];
        $estado = 'Activo';
        $usuario = $usuarioId;
        $verificar = $ct->registerCentro($master,$nombre,$telefono1,$telefono2,$direccion,$pais,$provincia,$ciudad,$estado,$usuario,$codigopostal,$nombreCompleto);
        if($verificar){
            echo"<script>
            swal({
                    title: 'Hecho!',
                    text: 'Centro creado exitosamente!',
                    type: 'success',
                    icon: 'success'
            }).then(function() {
                    window.location = 'lista_centros.php';
            });
            </script>";
            
        }else{
            echo"<script>
                swal({
                        title: 'Error!',
                        text: 'Error al guardar!',
                        type: 'error',
                        icon:'error'
                });
                </script>";
        }
        //print_r($_POST);
    }
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
                if(!document.getElementById('txtnombre').value){
                    let val = "Debe escribir un nombre";
                    mostrar(val);
                    return false;   
                }else if(document.getElementById('cbopais').value == 0){
                    let val = "Debe seleccionar un pais";
                    mostrar(val);
                    return false; 
                }
                else if(document.getElementById('cboprovincia').value == 0){
                    let val = "Debe seleccionar una provincia";
                    mostrar(val);
                    return false; 
                }
                else if(document.getElementById('cbociudad').value == 0){
                    let val = "Debe seleccionar una ciudad";
                    mostrar(val);
                    return false; 
                }else{
                    return true;
                }
           
                  
          });

          $('#cbopais').on('change', function() {
                     var url = "../utilidades/provincias.php";
                     $.ajax({
                         type:"POST",
                         url: url,
                         data: $("#frm_filtrar").serialize(),
                         success: function(data){
                                 $("#departamento").html(data);
                         }
                     });
                     return false;
          });

            $('#cboprovincia').on('change', function() {
                     var url = "../utilidades/ciudades.php";
                     $.ajax({
                         type:"POST",
                         url: url,
                         data: $("#frm_filtrar").serialize(),
                         success: function(data){
                                 $("#ciudad").html(data);
                         }
                     });
                     return false;
          });

          
     });
    
</script>
                <section id='content'>
                  <div class='container-fluid'>
                    <div class='row-fluid' id='content-wrapper'>
                      <div class='span12'>
                        <div class='row-fluid'>
                          <div class='span12'>
                            <div class='page-header color-azul''>
                              <h1 class='pull-left'>
                                <!-- <i class='icon-bar-chart'></i> -->
                                <span>Nuevo centro </span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -------------------------------------------- -->

                
             <form class='form' method="POST" id="frm_filtrar" enctype="multipart/form-data" style='margin-bottom: 0;'>
               
                  <div class='row-fluid'>

                                    <!-------------------------------------- Datos Generales -->
                                       <fieldset>
                                            <div class='span7 '>
                                                <div class='row-fluid'>
                                                    <div  class='span7 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Nombre del centro de trabajo</label>
                                                            <div class='controls'>
                                                            <input class='span12' id='txtnombre' name="txtnombre" type='text' value="<?php echo $nombre;?>">
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class='row-fluid'>
                                                    <div  class='span5 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Telefono</label>
                                                            <div class='controls'>
                                                            <input class='span12' id='txttelefono1' name="txttelefono1" type='text' value="<?php echo $telefono1;?>">
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div  class='span5 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Telefono</label>
                                                            <div class='controls'>
                                                            <input class='span12' id='txttelefono2' name="txttelefono2" type='text' value="<?php echo $telefono2;?>">
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class='row-fluid'>
                                                    <div  class='span12 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Direccion</label>
                                                            <div class='controls'>
                                                            <input class='span12' id='txtdireccion' name="txtdireccion" type='text' value="<?php echo $direccion;?>">
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='row-fluid'>
                                                    <div  class='span5 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Codigo Postal</label>
                                                            <div class='controls'>
                                                            <input class='span12' id='txtcodigopostal' name="txtcodigopostal" type='text' value="<?php echo $codigopostal;?>">
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class='row-fluid'>
                                                        <div  class='span2 '>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Pais</label>
                                                                <div class='controls'>
                                                                <select id="cbopais" name="cbopais" >
                                                                    <option value='0' selected disabled>-- Seleccione una --</option>
                                                                    <?php 
                                                                                foreach ($listapaises as $key => $value) {
                                                                                    $nom = $value['Nombre'];
                                                                                    $id = $value['PaisId'];
                                                                                    echo "<option value='$id' >$nom</option>";
                                                                                }
                                                                    ?>
                                                                </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div  class='span2 offset3'>
                                                            <div class='control-group' id="departamento">
                                                                <label class='control-label'>Provincia</label>
                                                                <div class='controls'>
                                                                <select id="cboprovincia" name="cboprovincia" >
                                                                    <option value='0' selected disabled>-- Seleccione una --</option>
                                                                </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div  class='span2 offset3'>
                                                            <div class='control-group' id="ciudad">
                                                                <label class='control-label'>Municipio</label>
                                                                <div class='controls'>
                                                                <select id="cbociudad" name="cbociudad" >
                                                                    <option value='0' selected disabled>-- Seleccione una --</option>
                                                                </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                </div>

                                                <div class="row-fluid">
                                                
                                                   <div class="span6 ">
                                                        <strong>Logo del centro</strong>
                                                        <div>
                                                        <a class="file-input-wrapper btn"><input name="file" id="file" title="Seleccione una imagen" type="file" style="left: -209px; top: -4.40005px;"></a>
                                                        </div>
                                                    </div>
                                                
                                                </div>


                                           
                                    </fieldset>  



                        <hr class='hr-normal'>
                       
                            <div class='text-center'>
                                <input class="btn btn-primary  btn-large" name="btnregister" id="btnregister" value="Guardar" type="submit" />  
                                <a class="btn btn-danger  btn-large" name="btncancelar" id="btncancelar" href="lista_centros.php">Cancelar </a>                   
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
