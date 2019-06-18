<?php
require_once("../clases/cargar_master.php");
require_once("../clases/clasificacion_controller.php");
require_once("../clases/roles_controller.php");
require_once("../clases/alertas_controller.php");
$html = new cargar;
$roles = new roles;
$_alertas = new  alertas;
$_clasificacion = new clasificacion;
$html->sessionDataSistem();
echo $html->PrintHead();
echo $html->LoadCssSystem("sistema");
echo $html->LoadJquery("sistema");
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $html->PrintBodyOpen();
echo $html->PrintHeader();

//definimos los permisos para esta pantalla;
$permisos = array(1);
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; 
$rol = $_SESSION['k6']['RolId'];

$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
    echo $_alertas->errorRedirect("Autorización Denegada","Usted no tiene permisos para acceder a esta página.", "accesoprohibido.php");
}

$id = "";
if(isset($_GET['id'])){
    $id = $_GET['id'];
}else{
    echo $_alertas->infoRedirect("Wow","Usted debe seleccionar un objeto para editar.", "lista_tipo_prueba_master.php");
}


$ListaDatos = $_clasificacion->ObtenerClasificacion($id);
if(empty($ListaDatos)){
    $ListaDatos = array();
    $tipo = "";
    $estado = "";
    $titulo = "";
    $icono = "";
    $texto = "";
}else{
    $tipo = $ListaDatos[0]['Nombre'];
    $estado = $ListaDatos[0]['Estado'];
    $titulo = $ListaDatos[0]['Titulo'];
    $icono = $ListaDatos[0]['Icono'];
    $texto = $ListaDatos[0]['Texto'];
}


if(isset($_POST['btnregister'])){
      $nombreCompleto = "";
      //subir imagen
     if ( 0 < $_FILES['file']['error'] ) {
         echo 'Error: ' . $_FILES['file']['error'] . '<br>';
     }else{
       $ext = explode(".", $_FILES['file']['name']);
       $ext =end($ext);
       $firmanombre = $_clasificacion->nombreramdom();
       $nombreCompleto =  $firmanombre .'.'.$ext;
       move_uploaded_file($_FILES['file']['tmp_name'], '../public/iconos/'.$nombreCompleto);
    }

   $datos = array(
       'id' => $id,
       'tipo' => $_POST['txttipo'],
       'titulo' => $_POST['txttitulo'],
       'estado' => $_POST['cboestado'],
       'icono' => $nombreCompleto,
       'texto' => $_POST['txtcuerpo']
   );

   $verificar = $_clasificacion->EditarClasificacion($datos);
   if($verificar){
     echo $_alertas->successRedirect('Hecho','Datos guardados correctamente',"editar_tipo_prueba_master.php?id=$id");
   }else{
     echo $_alertas->error('Error','No se ha guardado ningun dato');
   }
}

?>

<div id='wrapper'>
<div id='main-nav-bg'></div>

<script>
    $(document).ready(function(){

        function validate_fileupload(fileName)
        {
                var allowed_extensions = new Array("jpg","png","ico","JPG","PNG","ICO");
                var file_extension = fileName.split('.').pop().toLowerCase(); 
                for(var i = 0; i <= allowed_extensions.length; i++)
                {
                    if(allowed_extensions[i]==file_extension)
                    {
                        return true; // valid file extension
                    }
                }
                return false;
        }

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
           if (!$('#txtcuerpo').val()){
             let val = "Debe escribir el cuerpo del documento";
             mostrar(val);
             return false;
           }else if(!$('#txttipo').val()){
             let val = "Debe escribir el tipo de prueba";
             mostrar(val);
             return false;
           }else if(!$('#txttitulo').val()){
             let val = "Debe escribir un titulo";
             mostrar(val);
             return false;
           }else{
               if($('#file').val()){
                    let archivo = $('#file').val();
                    if(!validate_fileupload(archivo)){
                        let val = "Tipo de archivo no valido solo se permiten PNG,ICO y JPG";
                        mostrar(val);
                        return false;
                    }else{
                        return true;
                    }
               }else{
                       return true; 
               }
             
               
           }   
        });


        $('#txtcuerpo').wysihtml5(
            {
            "font-styles": true, 
            "emphasis": true, 
            "lists": true, 
            "html": false, 
            "link": false, 
            "image": false, 
            "color": false 
           }
        );

        
    });
</script>

<?php echo $html->PrintSideMenu();?>


                <section id='content'>
                  <div class='container-fluid'>
                    <div class='row-fluid' id='content-wrapper'>
                      <div class='span12'>
                        <div class='row-fluid'>
                          <div class='span12'>
                            <div class='page-header'>
                              <h1 class='pull-left'>
                                <!-- <i class='icon-bar-chart'></i> -->
                                <span style="color:#009688">Editar :  <?=$tipo ?></span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -----------------Formulario--------------------------- -->
                         <form method="post" enctype="multipart/form-data">
                            <!----------------------------------- Texto del documento -->
                            <fieldset>
                                 <div class='span12 '>
                                      <div class="span3 box ">
                                       <div class="box-content">
                                            <img  src="../public/iconos/<?=$icono?>">
                                        </div>                 
                                     </div>
                                      <div class='span9 box'>
                                            <div class="box-content box-double-padding">
                                            <div class='row-fluid'>
                                                    <div  class='span6 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Tipo de prueba (Alias solo para administrador)</label>
                                                            <div class='controls'>
                                                            <input class='span12' id='txttipo' name="txttipo" type='text' value="<?= $tipo?>" >
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                            </div>
                                            <div class='row-fluid'>
                                                <div  class='span6 '>
                                                        <div class='control-group'>
                                                            <label class='control-label'>Titulo (Sera mostrado en el sistema) </label>
                                                            <div class='controls'>
                                                            <input class='span12' id='txttitulo' name="txttitulo" type='text' value="<?= $titulo?>" >
                                                            <p class='help-block'></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class='row-fluid'>
                                                        <div  class='span6 '>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Estado</label>
                                                                <div class='controls'>
                                                                <select id="cboestado" name="cboestado" >
                                                                        <?php
                                                                         if($estado == 'Activo'){
                                                                            echo " <option value='Activo' selected >Activo</option>";        
                                                                            echo " <option value='Inactivo' >Inactivo</option>";
                                                                         }else{
                                                                            echo " <option value='Inactivo' selected >Inactivo</option>";        
                                                                            echo " <option value='Activo'>Activo</option>";
                                                                         }
                                                                      
                                                                        ?>      
                                                                </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                            <div class="span4 ">
                                                 <div class="box-content">
                                                                    <strong>Icono</strong> (Sera mostrado en el sistema)
                                                                    <div>
                                                                    <a class="file-input-wrapper btn">
                                                                        <input name="file" id="file" title="Buscar una imagen" type="file" style="left: -183px; top: 0.600021px;">
                                                                    </a>
                                                                    </div>
                                                 </div>
                                            </div> <br> <br> <br> 
                                            <br> 
                                            <h3>Escriba el cuerpo del documento</h3>
                                            <textarea id="txtcuerpo" name="txtcuerpo" class="input-block-level " rows="15" placeholder="Escriba el contenido "> <?=$texto?> </textarea>
                                            <hr class='hr-normal'>

                                            <div class='text-center'>
                                            <a class="btn btn-inverse  btn-large" name="btnvista" id="btnvista" target="_blank" href="../utilidades/documentos/clasificacion.php?persona=1&id=<?=$id?>">Vista previa </a>   
                                            <input class="btn btn-primary  btn-large" name="btnregister" id="btnregister" value="Guardar" type="submit" /> 
                                            <a class="btn btn-danger  btn-large" name="btncancelar" id="btncancelar" href="lista_tipo_prueba_master.php">Cancelar </a>                   
                                            <br><br>
                                        </div>
                                        </div>
                                      </div>
                           </fieldset>
                              
                            
                        </form>
                        <br>

                             
                        <!------------------------- Formulario -------------------------->
                  </div>
                </div>
                <div id="snackbar" style="background-color: #ad2222;">
                        <label id="errorMensaje"></label>
                </div>
  
                       </div>
                    </div>
                  </div>
                </section>
</div>
<?php 
echo $html->loadJS("sistema");
echo $html->PrintBodyClose();
?>
