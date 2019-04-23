<?php
require_once("../clases/cargar_master.php");
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
$permisos = array(1);
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
//Datos comuner
$listaModelos = $dispositivos->modelos();
$listaEstados = $dispositivos->estados();
if(empty($listaModelos)){$listaModelos = array();}
if(empty($listaEstados)){$listaEstados = array();}


//buscamos el codigo master para seleccionar los centros asociados al usuario
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; 
//Datos iniciales



if(isset($_POST['btnregister'])){
  $nombre = $_POST['txtnombre'];
  $serie = $_POST['txtserie'] ;
  $modelo = $_POST['cbomodelo'];
  $estado = $_POST['cboestado'];
  $imagen = $_POST['txtimagen'];

  $cantidad = $dispositivos->verificar($serie);
  if($cantidad > 0){
        echo"<script>
        swal({
                title: 'Ya existe!',
                text: 'El dispositivo que intenta agrega ya existe!',
                type: 'info',
                icon: 'info'
        });
       </script>";
        

  }else{
    $verificar = $dispositivos->crearDispositivo($nombre,$serie,$modelo,$estado,$imagen,$usuarioId);
    if($verificar){
      echo"<script>
          swal({
                  title: 'Hecho!',
                  text: 'Dispositivo creado!',
                  type: 'success',
                  icon: 'success'
          }).then(function() {
                  window.location = 'lista_dispositivos_master.php';
          });
      </script>";
    }else{
      echo"<script>
          swal({
                  title: 'Error!',
                  text: 'Error al guardar!',
                  type: 'error',
                  icon: 'error'
          });
      </script>";
    }
  }

        
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
                    let val = "Todos los campos son obligatorios";
                    mostrar(val);
                    return false;   
                }else if(!document.getElementById('txtserie').value){
                  let val = "Todos los campos son obligatorios";
                    mostrar(val);
                    return false;
                }else if(document.getElementById('cbomodelo').value == 0){
                    let val = "Todos los campos son obligatorios";
                    mostrar(val);
                    return false;
                }else if(document.getElementById('cboestado').value == 0){
                    let val = "Todos los campos son obligatorios";
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
                                <span> Nuevo dispositivo</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -----------------Formulario--------------------------- -->

                         <form class='form' method="POST" id="frm_filtrar" style='margin-bottom: 0;'>
                                
                                <div class="span12 box bordered-box blue-border">
                                <div class="box-header blue-background">
                                  <div class="title">
                                      Datos del dispositivo
                                  </div>
                                  <div class="actions">
                                    <a class="btn box-remove btn-mini btn-link" href="#"><i class="icon-remove"></i>
                                    </a>
                                    
                                    <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                                    </a>
                                  </div>
                                </div>
                                <div class="box-content box-double-padding">
                                  <form class="form" style="margin-bottom: 0;">
                                    <fieldset>
                                      <div class="span4">
                                        <div class="box-content text-center">
                                          <img id="mainimage" name="mainimage" src="../assets/images/foto.png" style="width:200px;">
                                          <input type="hidden" name="txtimagen" id="txtimagen" value="">
                                      </div>
                                      </div>
                                      <div class="span7 offset1">
                                        <div class="control-group">
                                          <label class="control-label">Nombre del dispositivo</label>
                                          <div class="controls">
                                            <input name="txtnombre" id="txtnombre" class="span12" id="full-name" placeholder="Nombre" type="text" value="">
                                            <p class="help-block"></p>
                                          </div>
                                        </div>
              
                                        <div class="control-group">
                                          <label class="control-label">Numero de serie / SN</label>
                                          <div class="controls">
                                            <input id="txtserie" name="txtserie" class="span12" id="address-line2" placeholder="Numero de serie" type="text" value="" >
                                          </div>
                                        </div>
              
              
                                        <div class="control-group">
                                              <label class="control-label" for="cbomodelo">Modelo</label>
                                              <div class="controls">
                                                  <select id="cbomodelo" name="cbomodelo">
                                                      <option value='0' selected disabled>- Seleccione uno -</option>
                                                      <?php 
                                                          foreach ($listaModelos as $key => $value) {
                                                              $codigo = $value['ModeloId'];
                                                              $name = $value['Nombre'];
                                                            
                                                                  echo "<option  value='$codigo'>$name</option>";
                                                        
                                                          }
                                                      ?>
                                                  </select>
                                              </div>
                                              
                                      </div>
              
                                      <div class="control-group">
                                              <label class="control-label" for="cboestado">Estado del dispositivo</label>
                                              <div class="controls">
                                                  <select id="cboestado" name="cboestado">
                                                       <option value ='0' selected disabled>- Seleccione uno -</option>
                                                      <?php 
                                                          foreach($listaEstados as $k => $value){
                                                                  $codigo = $value["TipoEstadoId"];
                                                                  $name = $value["Nombre"];
                                                               
                                                                      echo "<option value ='$codigo' >$name</option>";
                                                                  
                                                          }
                                                      ?>
                                                  </select>
                                              </div>
                                              
                                      </div>
                                        
                                      <div class="control-group">
                                        <div class="box-content">
                                            <strong>Seleccione una foto acorde al modelo</strong>
                                            <div>
                                            <?php 
                                                    $ruta = "../public/aparatos/";
                                                    $directorio = opendir($ruta); //ruta actual
                                                    $contador = 0;
                                                    while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
                                                    {
                                                        if (is_dir($archivo)){
                                                          //si es  un directoriono hacemos nada
                                                        }else{
                                                            $direccion =   $ruta .$archivo;
                                                            echo "<div id='imagen$contador' name='imagen$contador' class='mous' style='display:inline-block;'><img src='$direccion' style='width:100px;'> </div>";
              
                                                            echo "<script>
                                                                    $('#imagen$contador').click(function (){
                                                                        $('#mainimage').attr('src','$direccion');
                                                                        $('#txtimagen').val('$direccion');
                                                                        return true;   
                                                                    });
                                                                  </script>";
                                                        }
                                                        $contador++;
              
                                                        
                                                    }
                                            ?>
                                            </div>
                                        </div>
                                      </div>
              
                                      </div>
                                    </fieldset>
              
                             
                                 
                                    
                                 
                                    <div class="form-actions" style="margin-bottom: 0;">
                                 
                                      <div class="text-center">                             
                                              <input class="btn btn-primary  btn-large" name="btnregister" id="btnregister" value="Guardar" type="submit" />  
                                              <a class="btn btn-danger  btn-large" name="btncancelar" id="btncancelar" href="lista_dispositivos_master.php">Cancelar </a>                   
                                              <br><br>
                                      </div>
                                    </div>
                                  </form>
                 

                        <!------------------------- Formulario -------------------------->
                  </div>
                </div>
                      

                                    <!-------------------------------------- Datos Generales -->
                        
                       
                       
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
