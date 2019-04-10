<?php
require_once("../clases/cargar.php");
require_once("../clases/dispositivos_controller.php");
require_once("../clases/centros_controller.php");
require_once("../clases/roles_controller.php");
$html = new cargar;
$roles = new roles;
$dispositivos = new dispositivos;
$centros = new centros;
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
            text: 'Usted no tiene perisos para estar aqui!',
            type: 'error',
            icon: 'error'
    }).then(function() {
            window.location = 'accesoprohibido.php';
    });
</script>";
}
//buscamos el codigo master para seleccionar los centros asociados al usuario
$master = $_SESSION['k6']['MasterCompaniaId'];
// Codigo del usuario
$usuarioId =$_SESSION['k6']['UsuarioId']; 


//seleccionamos el codigo del aparato enviado por url enviado 
$aparatoId =$_GET['id'];
$datosDispositivos = $dispositivos->dispositivo($aparatoId,$master);
$listaCetros = $centros->getCentros($master);

$serie = $datosDispositivos['0']["Serie"];
$nombre = $datosDispositivos['0']["Nombre"];
$centroGuardado = $datosDispositivos['0']["CentroId"];
$AsignadoId = $datosDispositivos['0']["AsignadoId"];

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
                }else{
                    return true;
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
                                <!-- <i class='icon-bar-chart'></i> -->
                                <span> Dispositivo  serie <?php echo $serie ; ?></span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -------------------------------------------- -->


                 
                
             <form class='form' method="POST" id="frm_filtrar" style='margin-bottom: 0;'>
               
<?php
if(isset($_POST['btnregister'])){
    $centro = "";
    $estado = "";
    if(isset($_POST['cbocentro'])){
        $centro =$_POST['cbocentro'];
    };
    if($centro == 0 || $centro==""){
        $estado = "Sin asignar";
    }else{
        $estado = "Asignado";
    }

    $usuario = $usuarioId;
    $verificar = $dispositivos->update_asignacion($AsignadoId,$centro,$estado,$usuarioId,$master );
    if($verificar == true){
  
        echo"<script>
        swal({
                title: 'Hecho!',
                text: 'Dispositivo asignado',
                type: 'success',
                icon: 'success'
        }).then(function() {
                window.location = 'lista_dispositivos.php';
        });
    </script>";
    }else{
        echo"<script>
        swal({
                title: 'Error!',
                text: 'Error al asignar el dipositivo',
                type: 'error',
                icon: 'error'
        });
    </script>";

   }
}


?>
                  <div class='row-fluid'>

                                    <!-------------------------------------- Datos Generales -->
                                       <fieldset>
                                            <div class='span5 offset1'>
                                                <div class='row-fluid'>
                                                    <div  class='span7 offset1'>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Numero de serie</label>
                                                            <div class='controls'>
                                                            <input class='span12' id='txtserie' name="txtserie" type='text' value="<?php echo $serie;?>" disabled>
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class='row-fluid'>
                                                    <div  class='span7 offset1'>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Nombre del dispositivo</label>
                                                            <div class='controls'>
                                                            <input class='span12' id='txtnombre' name="txtnombre" type='text' value="<?php echo $nombre;?>" disabled>
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class='row-fluid'>
                                                        <div  class='span6 offset1'>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Centro deportivo</label>
                                                                <div class='controls'>
                                                                <select id="cbocentro" name="cbocentro" >
                                                                    <?php 
                                                                                if($centroGuardado == "" || $centroGuardado == 0 ){
                                                                                    echo " <option value='0' selected >-- Sin asignar --</option>";
                                                                                }else{
                                                                                    echo " <option value='0'  >-- Sin asignar --</option>";
                                                                                }
                                                                                foreach ($listaCetros as $key => $value) {
                                                                                    $nom = $value['Nombre'];
                                                                                    $id = $value['CentroId'];
                                                                                    if($centroGuardado == $id){
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
                                                </div>

                                             
                                           
                                    </fieldset>  



                        <hr class='hr-normal'>
                       
                            <div class='text-center'>
                                <input class="btn btn-primary  btn-large" name="btnregister" id="btnregister" value="Guardar" type="submit" />  
                                <a class="btn btn-danger  btn-large" name="btncancelar" id="btncancelar" href="lista_dispositivos.php">Cancelar </a>                   
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
