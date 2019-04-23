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
//Datos comunes
$listaModelos = $dispositivos->modelos();
$listaEstados = $dispositivos->estados();
if(empty($listaModelos)){$listaModelos = array();}
if(empty($listaEstados)){$listaEstados = array();}


//buscamos el codigo master para seleccionar los centros asociados al usuario
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; 
//seleccionamos el codigo del aparato enviado por url enviado 
$aparatoId =$_GET['id'];
//Obtener los datos del dispositivo
$datosDispositivos = $dispositivos->datosDispositivo($aparatoId);
    $serie = $datosDispositivos[0]["Serie"];
    $nombre = $datosDispositivos[0]["Nombre"];
    $modelo = $datosDispositivos[0]["ModeloId"];
    $estado = $datosDispositivos[0]["TipoEstadoId"];
    $imagen = $datosDispositivos[0]["Imagen"];
    if($image ="" || !$imagen){
        $imagen = "../assets/images/foto.png";
    }
    //Obtener los datos de la empresa si el dispositivo esta asignado
    if($estado == 2 || $estado == 3){
      $detalles = $dispositivos->verDatosdeAsignacion($aparatoId);
    }else{
      $detalles = array();
    }

   
    if(isset($_POST['btndesvincular'])){
      $resp= $dispositivos->desvincularDispositivo($aparatoId);
      if($resp){
        echo"<script>
        swal({
                title: 'Hecho!',
                text: 'Dispositivo desvinculado!',
                type: 'success',
                icon: 'success'
        }).then(function() {
                window.location = 'editar_dispositivos_master.php?id=$aparatoId';
        });
       </script>";
    }else{
      echo"<script>
        swal({
                title: 'Error!',
                text: 'No se ha modificado ningun dato!',
                type: 'error',
                icon: 'error'
        })
        </script>";
    }

    }


    if(isset($_POST['btnregister'])){
       $img =$_POST['txtimagen'];
       $nom =$_POST['txtnombre'];
       $model =$_POST['cbomodelo'];
       $status =$_POST['cboestado'];
       $verificar = $dispositivos->verificarNoVinculado($serie);
      if($verificar > 0 ){
            if($status == 3 || $status == 2 ){
              $respuesta = $dispositivos->editarDispositivo($aparatoId ,$nom,$model,$status,$img,$usuarioId);
              //$respuesta = true;
              if($respuesta){
                  echo"<script>
                  swal({
                          title: 'Hecho!',
                          text: 'Dispositivo modificado!',
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
                          text: 'No se ha modificado ningun dato!',
                          type: 'error',
                          icon: 'error'
                  })
                  </script>";
              }

            }else{
              echo"<script>
              swal({
                      title: 'Aviso!',
                      text: 'Debe desvincular el dispositivo primero!',
                      type: 'info',
                      icon: 'info'
              })
              </script>";
             
            }
       }else{
          $respuesta = $dispositivos->editarDispositivo($aparatoId ,$nom,$model,$status,$img,$usuarioId);
         //$respuesta = true;
          if($respuesta){
              echo"<script>
              swal({
                      title: 'Hecho!',
                      text: 'Dispositivo modificado!',
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
                      text: 'No se ha modificado ningun dato!',
                      type: 'error',
                      icon: 'error'
              })
              </script>";
          }
       }

     

    }

?>
<style>
  .mous:hover{
    background-color:#C6c6c6;
  }
</style>

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
                                <span> Dispositivo <strong style="color:#009688"><?php echo $nombre . " - " . $serie ; ?></strong> </span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -------------------------------------------- -->


                 
                
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
                            <img id="mainimage" name="mainimage" src="<?=$imagen?>" style="width:200px;">
                            <input type="hidden" name="txtimagen" id="txtimagen" value="<?=$imagen?>">
                        </div>
                        </div>
                        <div class="span7 offset1">
                          <div class="control-group">
                            <label class="control-label">Nombre del dispositivo</label>
                            <div class="controls">
                              <input name="txtnombre" id="txtnombre" class="span12" id="full-name" placeholder="Este nombre sera mostrado a los usuarios" type="text" value="<?=$nombre ?>">
                              <p class="help-block"></p>
                            </div>
                          </div>

                          <div class="control-group">
                            <label class="control-label">Numero de serie / SN</label>
                            <div class="controls">
                              <input id="txtserie" name="txtserie" class="span12" id="address-line2" placeholder="Numero de serie" type="text" value="<?=$serie?>" disabled>
                            </div>
                          </div>


                          <div class="control-group">
                                <label class="control-label" for="cbomodelo">Modelo</label>
                                <div class="controls">
                                    <select id="cbomodelo" name="cbomodelo">
                                        <option value='0' disabled>- Seleccione uno -</option>
                                        <?php 
                                            foreach ($listaModelos as $key => $value) {
                                                $codigo = $value['ModeloId'];
                                                $name = $value['Nombre'];
                                                if($codigo == $modelo){
                                                    echo "<option selected value='$codigo'>$name</option>";
                                                }else{
                                                    echo "<option  value='$codigo'>$name</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                
                        </div>

                        <div class="control-group">
                                <label class="control-label" for="cboestado">Estado del dispositivo</label>
                                <div class="controls">
                                    <select id="cboestado" name="cboestado">
                                         <option value ='0' disabled>- Seleccione uno -</option>
                                        <?php 
                                            foreach($listaEstados as $k => $value){
                                                    $codigo = $value["TipoEstadoId"];
                                                    $name = $value["Nombre"];
                                                    if($estado == $codigo){
                                                        echo "<option value ='$codigo' selected>$name</option>";
                                                    }else{
                                                        echo "<option value ='$codigo' >$name</option>";
                                                    }
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

                    <!-- estado del dispositivo --->
                    <?php 
                      if($estado == 2 || $estado ==3 && !empty($detalles)){
                          $compania = $detalles[0]['Nombre'];
                          $estado = $detalles[0]['Estado'] ;
                          $fc = $detalles[0]['FC'] ;
                        

                          echo "
                          <h3>Datos del aquiler o venta </h3>
                          <div class='row-fluid' id='list'>
                          <div class='span12 box'>
                            <div class='box-content'>
                              <div class='pull-left'>
                                <p>
                                  <a href=''>
                                    <strong>Vinculado actualmente a : </strong>
                                    $compania
                                  </a>
                                </p>
                                <p>
                                Estado : ";
                                  if($estado == "Asignado"){
                                   echo" <span class='label label-success'>$estado</span>";
                                  }else{
                                    echo" <span class='label label-warning'>$estado</span>";
                                  }
                                 

                                  echo "
                                </p>
                              </div>
                              <div class='text-right pull-right'>
                                <p>
                                  <span class='timeago fade has-tooltip in' data-placement='top' title='Fecha de vinculacion'>Viunculado desde : $fc </span>
                                  <i class='icon-time'></i>
                                </p>
                              </div>
                              <div class='clearfix'></div>
                              <hr class='hr-normal'>
                            </div>
                          </div>
                        </div>
                          
                          ";
                      }
                    ?>
                   
                      
                   
                      <div class="form-actions" style="margin-bottom: 0;">
                      <input class="btn btn-inverse  btn-large" name="btndesvincular" id="btndesvincular" value="Desvincular" type="submit" />  
                        <div class="text-center">                             
                                <input class="btn btn-primary  btn-large" name="btnregister" id="btnregister" value="Guardar" type="submit" />  
                                <a class="btn btn-danger  btn-large" name="btncancelar" id="btncancelar" href="lista_dispositivos_master.php">Cancelar </a>                   
                                <br><br>
                        </div>
                      </div>
                    </form>
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
