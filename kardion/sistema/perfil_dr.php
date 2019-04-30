<?php
require_once('../clases/cargar_dr.php');
require_once('../clases/roles_controller.php');
require_once('../clases/dr.php');

$html = new cargardr;
$roles = new roles;
$dr = new dr;
$html->sessionDataSistem(); //iniciamos la sesion en el navegador
//Buscamos los permisos para entrar en esta pantalla
        $permisos = array(3);
        $rol = $_SESSION['k6']['RolId'];
        $permiso = $roles->buscarpermisos($rol,$permisos);
        if(!$permiso){
        header("Location: accesoprohibido.php");
        }
//buscamos el codigo master para seleccionar los centros asociados al usuario
//$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId'];


echo $html->PrintHead(); //Cargamos en header
echo $html->LoadCssSystem("sistema"); // cargamos todas las librerias css del sistema interno
echo $html->LoadJquery("sistema"); //cargamos todas las librerias Jquery del sistema interno  
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $html->PrintBodyOpen(); //cargamos la primera parte del body
echo $html->PrintHeader(); //cargamos el header

$datos = $dr->buscar($usuarioId);

if(isset($datos[0]['Nombre'])){$nombre=$datos[0]['Nombre'];}else{$nombre = "";}
if(isset($datos[0]['Titulo'])){$titulo=$datos[0]['Titulo'];}else{$titulo = "";}
if(isset($datos[0]['Telefono'])){$telefono=$datos[0]['Telefono'];}else{$telefono = "";}
if(isset($datos[0]['Correo'])){$correo=$datos[0]['Correo'];}else{$correo = "";}
if(isset($datos[0]['NoColegiado'])){$colegio=$datos[0]['NoColegiado'];}else{$colegio = "";}
if(isset($datos[0]['Firma'])){$firma=$datos[0]['Firma'];}else{$firma = "";}

if(isset($_POST['btnregister'])){
  $verificar = $dr->verificar($usuarioId);
  if($verificar == 1){
    //modificar
                //obtener la direccion de la firma. si ya tiene una
                if(isset($datos[0]['Firma'])){
                  $nombreCompleto=$datos[0]['Firma'];
                }

              //subir imagen
              if ( 0 < $_FILES['file']['error'] ) {
                  echo 'Error: ' . $_FILES['file']['error'] . '<br>';
              }else{
                  $ext = explode(".", $_FILES['file']['name']);
                  $ext =end($ext);
                  $firmanombre = $dr->nombreramdom();
                  $nombreCompleto =  $firmanombre .'.'.$ext;
                  move_uploaded_file($_FILES['file']['tmp_name'], '../public/firmas/'.$nombreCompleto);
              }
              //modificar
              $data = array(
                'nombre' => $_POST['txtnombre'],
                'titulo' => $_POST['txttitulo'],
                'correo' => $_POST['txtcorreo'],
                'telefono'=> $_POST['txttelefono'],
                'colegiado'=> $_POST['txtcolegiado'],
                'firma' => $nombreCompleto,
                'usuario' => $usuarioId
              );
              $editarresp = $dr->editar($data);
              if($editarresp){
                $nombre = $_POST['txtnombre'];
                $titulo = $_POST['txttitulo'];
                $telefono = $_POST['txttelefono'];
                $correo = $_POST['txtcorreo'];
                $colegio = $_POST['txtcolegiado'];
                $firma =  $nombreCompleto;
                echo "<script>
                        swal({
                          title: 'Hecho!',
                          text: 'Sus datos han sido actualizados',
                          icon: 'success',
                        });
                    </script>";
              }else{
                  // mostrar un error al guardar
                  echo "<script>
                  swal({
                    title: 'Error!',
                    text: 'Error al actualizar datos. No se ha realizado ningun cambio',
                    icon: 'error',
                  });
              </script>";
              }

  }else{
        //subir imagen
          $nombreCompleto ="";
          if ( 0 < $_FILES['file']['error'] ) {
          echo 'Error: ' . $_FILES['file']['error'] . '<br>';
          }
          else {
              $firmanombre = $dr->nombreramdom();
              $ext = explode(".", $_FILES['file']['name']);
              $ext =end($ext);
              $nombreCompleto =  $firmanombre .'.'.$ext;
              move_uploaded_file($_FILES['file']['tmp_name'], '../public/firmas/'.$nombreCompleto);
          }
      //guardar
      $data = array(
        'nombre' => $_POST['txtnombre'],
        'titulo' => $_POST['txttitulo'],
        'correo' => $_POST['txtcorreo'],
        'telefono'=> $_POST['txttelefono'],
        'colegiado'=> $_POST['txtcolegiado'],
        'firma' => $nombreCompleto,
        'usuario' => $usuarioId
      );
      $guardarresp = $dr->guardar($data);
      if($guardarresp){
        $nombre = $_POST['txtnombre'];
        $titulo = $_POST['txttitulo'];
        $telefono = $_POST['txttelefono'];
        $correo = $_POST['txtcorreo'];
        $colegio = $_POST['txtcolegiado'];
        $firma =  $nombreCompleto;
        echo "<script>
                swal({
                  title: 'Hecho!',
                  text: 'Sus datos han sido actualizados',
                  icon: 'success',
                });
            </script>";
      }else{
        echo "<script>
            swal({
              title: 'Error!',
              text: 'Error al guardar sus datos. no se ha realizado ningun cambio',
              icon: 'error',
            });
        </script>";
      }
  }
}



?>
<div id='wrapper'>
<div id='main-nav-bg'></div>

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

  $('#btnregister').click(function(){

      if(!$('#txtnombre').val()){
        let val = "Debe escribir un nombre";
        mostrar(val);
        return false;
      }else if(!$('#txttitulo').val()){
        let val = "Debe escribir un titulo medico";
        mostrar(val);
        return false;
      }else if(!$('#txtcolegiado').val()){
        let val = "Debe escribir su numero de colegiado";
        mostrar(val);
        return false;
      }else{
       
        return true;
      }
    
      
  });

});
</script>

<?php
//cargamos el menu lateral
       echo $html->PrintSideMenu();
?>

<section id='content'>
                  <div class='container-fluid'>
                    <div class='row-fluid' id='content-wrapper'>
                      <div class='span12'>
                        <div class='row-fluid'>
                          <div class='span12'>
                            <div class='page-header'>
                              <h1 class='pull-left'>
                                <span>Mi perfil medico</span>
                              </h1>
                                 
                            </div>
                          </div>
                        </div>
                         <!--- Formulario y datos --->

                         <form class='form' method="POST" id="frm_filtrar" style='margin-bottom: 0;' enctype="multipart/form-data">
                           <div class='row-fluid'>

                                 <!-------------------------------------- Datos Generales -->
                                  

                                <!--------------------------------------Datos Generales ----->

                                <div class="span12 box box-nomargin" style="margin-left:0px;">
                                  <div class="box-header blue-background">
                                    <div class="title">
                                      Datos que se muestran en los informes medicos
                                    </div>
                                    <div class="actions">
                                      <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                                      </a>
                                    </div>
                                  </div>
                                  <div class="box-content">
                                              <!-- contenido de la caja ---->
                                                 
                                                <div class='span7 offset1'>
                                                <br>
                                                   <div class="alert alert-info">
                                                      <a class="close" data-dismiss="alert" href="#">×</a>
                                                      Los siguientes datos serán mostrados en cada uno de los informes médicos
                                                      <strong>FINALIZADOS</strong> por su cuenta.
                                                     
                                                    </div>
                                                    <div class='row-fluid'>
                                                        <div  class='span4 offset1'>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Nombre y Apellido</label>
                                                                <div class='controls'>
                                                                <input class='span12' id='txtnombre' name="txtnombre" type='text' value="<?=$nombre?>">
                                                                <p class='help-block'></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div  class='span4 offset1'>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Titulo</label>
                                                                <div class='controls'>
                                                                <input class='span12' id='txttitulo' name="txttitulo" type='text' value="<?=$titulo?>">
                                                                <p class='help-block'></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class='row-fluid'>
                                                        <div  class='span4 offset1'>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Telefono</label>
                                                                <div class='controls'>
                                                                <input class='span12' id='txttelefono' name="txttelefono" type='text' value="<?=$telefono?>">
                                                                <p class='help-block'></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div  class='span4 offset1'>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Correo</label>
                                                                <div class='controls'>
                                                                <input class='span12' id='txtcorreo' name="txtcorreo" type='text' value="<?=$correo?>">
                                                                <p class='help-block'></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class='row-fluid'>
                                                        <div  class='span4 offset1'>
                                                            <div class='control-group'>
                                                                <label class='control-label'>No. colegiado</label>
                                                                <div class='controls'>
                                                                <input class='span12' id='txtcolegiado' name="txtcolegiado" type='text' value="<?=$colegio?>">
                                                                <p class='help-block'></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="span4 offset1">
                                                              <div class="box-content">
                                                                <strong>Firma</strong>
                                                                <div>
                                                                  <a class="file-input-wrapper btn">
                                                                    <input name="file" id="file" title="Buscar una imagen" type="file" style="left: -183px; top: 0.600021px;">
                                                                  </a>
                                                                </div>
                                                              </div>
                                                        </div>  
                                                    </div>

                                                    </div>

                                                    <div class="row-fluid">
                                                    <div class="box-content">
                                                      <?php 
                                                        if($firma){
                                                          echo "<img style='width: 230px;' src='../public/firmas/$firma'>";
                                                        }else{
                                                          echo "<img style='width: 230px;' src='../assets/images/foto.png'>";
                                                        }
                                                      ?>
                                                      <img src="">
                                                   
                                                      <br><br>
                                                   
                                                    </div>



                                                </div>
                           
                                              <!-- contenido de la caja ---->
                                  </div>
                                </div>

                                <!----------------------------------------------------------->
                           </div>

                           <hr class='hr-normal'>
                       
                            <div class='text-center'>
                                  <input class="btn btn-primary  btn-large" name="btnregister" id="btnregister" value="Guardar" type="submit" />  
                                  <br><br>
                            </div>
                        </form>
                        <div id="snackbar">
                        <label id="errorMensaje"></label>
                    </div>



                         <!--- Formulario y datos --->
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