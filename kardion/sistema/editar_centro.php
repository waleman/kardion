<?php
require_once("../clases/cargar.php");
require_once("../clases/centros_controller.php");
require_once("../clases/roles_controller.php");
require_once("../clases/paises_controller.php");
$html = new cargar;
$centros= new centros;
$roles = new roles;
$paises = new paises;


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
    //redirigir  login
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
$usuarioId =$_SESSION['k6']['UsuarioId']; // Codigo del usuario
//modificar 



//centro a modificar
if(isset($_GET['id'])){
    $centromodificar = $_GET['id'];
    $datos = $centros->centroporid2($centromodificar);   
    $nombre = $datos[0]['Nombre'];
    $telefono1 = $datos[0]['Telefono1'];
    $telefono2 = $datos[0]['Telefono2'];
    $direccion = $datos[0]['Direccion'];
    $pais = $datos[0]['PaisId'];
    $provincia = $datos[0]['ProvinciaId'];
    $ciudad = $datos[0]['MunicipioId'];
    $codigopostal= $datos[0]['CodigoPostal'];
    $estado =$datos[0]['Estado'];
    $logo = $datos[0]['Logo'];

    $PaisNombre = $paises->getPaisNombre($pais);
    $ProvinciaNombre = $paises->getProvinciaNombre($provincia);
    $MunicipioNombre = $paises->getMunicipioNombre($ciudad);

}else{
    echo"<script>
    swal({
            title: 'error!',
            text: 'Error 404!',
            type: 'error',
            icon: 'error'
    }).then(function() {
            window.location = 'lista_centros.php';
    });
  </script>";
}



if(isset($_POST["btnregister"])){

     
  
    
   if(isset($_FILES['file']['tmp_name'])){
       $nombre = $centros->generateRandomString(); //generamos un string aleatorio
       $tempFile = $_FILES['file']['tmp_name'];  //seleccionamos el nombre temporal del archivo    
       $ext = explode(".", $_FILES['file']['name']); // Buscamos la extension del archivo
       $ext =end($ext);    
       if(!$logo || $logo == "")    {
         $nombreCompleto =  $nombre .'.'.$ext; //creamo un nombre para almacenar en la carpeta del servidor
       }else{
        $nombreCompleto =$logo;
       }
      $imagen = $nombreCompleto ;
       move_uploaded_file($_FILES['file']['tmp_name'], '../public/logos/'.$nombreCompleto);
   }else{
       $imagen =$logo;
   }
   

   $nombre = $_POST['txtnombre'];
   $telefono1 = $_POST['txttelefono1'];
   $telefono2 = $_POST['txttelefono2'];
   $direccion = $_POST['txtdireccion'];
   $estado = $_POST['cboestado'];
   $codigopostal = $_POST['txtcodigopostal'];

  $verificar = $centros->editarCentro($centromodificar,$nombre,$telefono1,$telefono2,$direccion,$estado,$codigopostal,$imagen,$usuarioId);
  
       echo"<script>
            swal({
                    title: 'Hecho!',
                    text: 'Datos modificados!',
                    type: 'success',
                    icon: 'success'
            }).then(function() {
                    window.location = 'editar_centro.php?id=$centromodificar';
            });
        </script>";
 
   

}

?>
<div id='wrapper'>
<div id='main-nav-bg'></div>
<?php
echo $html->PrintSideMenu();
?>
     <section id='content'>
                  <div class='container-fluid'>
                    <div class='row-fluid' id='content-wrapper'>
                      <div class='span12'>
                        <div class='row-fluid'>
                          <div class='span12'>
                            <div class='page-header color-azul''>
                              <h1 class='pull-left'>
                                <span> Modificar centro</span>
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
                                                 <div  class='span7 offset1'>
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
                                                 <div  class='span5 offset1'>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Telefono</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txttelefono1' name="txttelefono1" type='text' value="<?php echo $telefono1;?>">
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div  class='span5 offset1'>
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
                                                 <div  class='span12 offset1'>
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
                                                 <div  class='span3 offset1'>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Codigo Postal</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtcodigopostal' name="txtcodigopostal" type='text' value="<?php echo $codigopostal;?>">
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>

                                                 <div  class='span3 offset1'>
                                                         <div class='control-group'>
                                                                    <label class='control-label'>Estado</label>
                                                                    <div class='controls'>
                                                                    <select id="cboestado" name="cboestado" >
                                                                      <option value='Activo' <?php if($estado == 'Activo'){echo 'selected';} ?>  >Activo</option>
                                                                      <option value='Inactivo' <?php if($estado == 'Inactivo'){echo 'selected';} ?> >Inactivo</option>
                                                                    </select>
                                                                    </div>
                                                        </div>
                                                 </div>
                                             </div>

                                             <div class='row-fluid'>
                                                     <div  class='span3 offset1'>
                                                         <div class='control-group'>
                                                             <label class='control-label'>Pais</label>
                                                             <div class='controls'>
                                                             <input class='span12' id='txtpais' name="txtpais" type='text' value="<?php echo $PaisNombre;?>" disabled>
                                                             </div>
                                                         </div>
                                                     </div>
                                                     <div  class='span3 offset1'>
                                                         <div class='control-group'>
                                                             <label class='control-label'>Provincia</label>
                                                             <div class='controls'>
                                                             <input class='span12' id='txtprovincia' name="txtprovincia" type='text' value="<?php echo $ProvinciaNombre;?>" disabled>
                                                             </div>
                                                         </div>
                                                     </div>
                                                     <div  class='span3 offset1'>
                                                         <div class='control-group'>
                                                             <label class='control-label'>Ciudad</label>
                                                             <div class='controls'>
                                                             <input class='span12' id='txtmunicipio' name="txtmunicipio" type='text' value="<?php echo $MunicipioNombre;?>" disabled>
                                                             </div>
                                                         </div>
                                                     </div>

                                             </div>

                                             <div class="row-fluid">
                                             
                                                <div class="span6 offset1">
                                                     <strong>Logo del centro</strong>
                                                     <div>
                                                     <a class="file-input-wrapper btn"><input name="file" id="file" title="Seleccione una imagen" type="file" style="left: -209px; top: -4.40005px;"></a>
                                                     </div>
                                                 </div>
                                                 <div>

                                                        <?php 
                                                            if($logo){
                                                            echo "<img style='width: 230px;' src='../public/logos/$logo'>";
                                                            }else{
                                                            echo "<img style='width: 230px;' src='../assets/images/foto.png'>";
                                                            }
                                                        ?>
                                                 </div>
                                             
                                             </div>
                                         

                                   </div>     
                                 </fieldset>  



                     <hr class='hr-normal'>
                    
                         <div class='text-center'>
                             <input class="btn btn-primary  btn-large" name="btnregister" id="btnregister" value="Guardar" type="submit" />  
                             <!-- <a class="btn btn-danger  btn-large" name="btncancelar" id="btncancelar" href="lista_centros.php">Cancelar </a>                    -->
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