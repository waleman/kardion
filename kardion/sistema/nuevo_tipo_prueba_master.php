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


if(isset($_POST['btnregister'])){
    $ext = explode(".", $_FILES['file']['name']);
    $ext =end($ext);
    $firmanombre = $_clasificacion->nombreramdom();
    $nombreCompleto =  $firmanombre .'.'.$ext;
    move_uploaded_file($_FILES['file']['tmp_name'], '../public/iconos/'.$nombreCompleto);
   $datos = array(
       'tipo' => $_POST['txttipo'],
       'titulo' => $_POST['txttitulo'],
       'icono' => $nombreCompleto,
       'texto' => $_POST['txtcuerpo']
   );
   $verificar = $_clasificacion->GuardarClasificacion($datos);
   if($verificar){
    $ultimo = $_clasificacion->UltimoRegistro();
     echo $_alertas->successRedirect('Hecho','Datos guardados correctamente',"editar_tipo_prueba_master.php?id=$ultimo");
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
                var file_extension = fileName.split('.').pop().toLowerCase(); // split function will split the filename by dot(.), and pop function will pop the last element from the array which will give you the extension as well. If there will be no extension then it will return the filename.
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
           }else if(!$('#file').val()){
             let val = "Debe seleccionar un icono";
             mostrar(val);
             return false;
           }else if(!$('#txttitulo').val()){
             let val = "Debe escribir un titulo";
             mostrar(val);
             return false;
           }else{
               let archivo = $('#file').val();
                if(!validate_fileupload(archivo)){
                    let val = "Tipo de archivo no valido solo se permiten PNG,ICO y JPG";
                    mostrar(val);
                    return false;
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
                                <span> Nuevo Tipo de prueba</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -----------------Formulario--------------------------- -->
                         <form method="post" enctype="multipart/form-data">
                            <!----------------------------------- Texto del documento -->
                            <fieldset>
                                         <div class='span12 '>

                                         <div class='row-fluid'>
                                                 <div  class='span6 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Tipo de prueba (Alias solo para administrador)</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txttipo' name="txttipo" type='text' >
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
                                                         <input class='span12' id='txttitulo' name="txttitulo" type='text' >
                                                         <p class='help-block'></p>
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



                            <h3>Esciba el cuerpo del documento</h3>
                            
                            <textarea id="txtcuerpo" name="txtcuerpo" class="input-block-level " rows="10" placeholder="Escriba el contenido "></textarea>
                             

                            <hr class='hr-normal'>
                       
                       <div class='text-center'>
                             <input class="btn btn-primary  btn-large" name="btnregister" id="btnregister" value="Guardar" type="submit" /> 
                             <a class="btn btn-danger  btn-large" name="btncancelar" id="btncancelar" href="lista_tipo_prueba_master.php">Cancelar </a>                   
                             <br><br>
                       </div>

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
