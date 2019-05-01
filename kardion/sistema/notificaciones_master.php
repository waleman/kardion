<?php 
require_once("../clases/cargar_master.php");
require_once("../clases/roles_controller.php");
require_once("../clases/notificacion_controller.php");
$html = new cargar;
$roles = new roles;
$_notificacion = new notificacion;
$html->sessionDataSistem();
echo $html->PrintHead();
echo $html->LoadCssSystem("sistema");
echo $html->LoadJquery("sistema");
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $html->PrintBodyOpen();
echo $html->PrintHeader();
//definimos los permisos para esta pantalla;
$permisos = array(1,2);
$rol = $_SESSION['k6']['RolId'];
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioid = $_SESSION['k6']['UsuarioId'];
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
die();
}
//buscamos el codigo master para seleccionar los centros asociados al usuario
$ListaCorreos = $_notificacion->ListaCorreos();
if(empty($ListaCorreos)){
    $ListaCorreos =[];
}



if(isset($_POST['txtid'])){
    $codigo = $_POST['txtid'];
    $status = $_POST['txtvalor'];
    if ($status == 'Activo'){
        $status = 'Inactivo';
    }else{
        $status = 'Activo';
    }
   
   $conf = $_notificacion->CambiarEstado($codigo,$status);
    if($conf){
        echo"<script>
        swal({
                title: 'Hecho!',
                text: 'Cambios realizados!',
                type: 'success',
                icon: 'success'
        }).then(function() {
                window.location = 'notificaciones_master.php';
        });
        </script>";
    }else{
        echo"<script>
        swal({
                title: 'Error!',
                text: 'No se ha podido realizar el cambio',
                type: 'error',
                icon: 'error'
        });
      </script>"; 
    }
}

if(isset($_POST['btnguardar'])){
    $correo = $_POST['txtcorreo'];
    $resp = $_notificacion->AgregarCorreo($correo,$usuarioid);
    if($resp){
        echo"<script>
        swal({
                title: 'Hecho!',
                text: 'Cambios Correo agregado!',
                type: 'success',
                icon: 'success'
        }).then(function() {
                window.location = 'notificaciones_master.php';
        });
        </script>";
    }else{
        echo"<script>
        swal({
                title: 'Error!',
                text: 'No se ha podido insertar',
                type: 'error',
                icon: 'error'
        });
      </script>"; 
    }
}

?>


<script>
    $(document).ready(function(){

        $("#btnnuevo").click(function(){
            $("#modalnuevo").modal("show");
            return false;
        });


    });
</script>

<div id='wrapper'>
<div id='main-nav-bg'></div>

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
                                <span>Lista de correos para notificar</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                       <!-- ----------------------------------------- -->
                       <form method="post">
                            <a class="btn btn-primary btn-large" href="" name="btnnuevo" id="btnnuevo" > Nuevo correo</a>
                       </form>
                        <br>

                        
                            <div class='row-fluid'>
                                    <div class='span12 box bordered-box orange-border' style='margin-bottom:0;'>
                                    <div class="box-header blue-background">
                                      <div class="title">Correos a los que Kardion notifica cuando se crea una nueva prueba</div>
                                      <div class="actions">
                                        <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                                        </a>
                                      </div>
                                    </div>
                                    <div class='box-content box-no-padding'>
                                        <div class='responsive-table'>
                                        <div class='scrollable-area'>
                                        <form method="post">
                                        <input type="hidden" name="txtid" id="txtid">
                                        <input type="hidden" name="txtvalor" id="txtvalor">
                                            <table class='data-table table table-bordered table-striped' data-pagination-records='25' data-pagination-top-bottom='false' style='margin-bottom:10px;'>
                                                <thead>
                                                    <tr>
                                                    <th>
                                                    Correo
                                                    </th>

                                                    <th>
                                                    Estado
                                                    </th>
                                                
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                            
                                                        foreach ($ListaCorreos as $key => $value) {
                                                            $Correo = $value['Correo'];
                                                            $id = $value['NotificacionId'];
                                                            $estado = $value['Estado'];
                                                                echo "  
                                                                <tr>
                                                                    <td>$Correo</td>
                                                                  
                                                               ";
                                                        
                                                            echo"<td style='text-align:center'> ";
                                                                
                                                            if($estado == 'Activo'){
                                                                echo "<button class='btn btn-success' style='margin-bottom:5px' id='btnact$id' name='btnact$id'>
                                                                <i class='icon-thumbs-up'> Notificando</i> 
                                                            </button>";
                                                            }else{
                                                                echo "<button class='btn btn-danger' style='margin-bottom:5px' id='btnact$id' name='btnact$id'>
                                                                <i class='icon-thumbs-down'> No notificar</i>
                                                            </button>";
                                                            }
                                                            echo"
                                                            </td>
                                                        
                                                            </tr>
                                                            <script>
                                                                $('#btnact$id').click(function(){
                                                                    $('#txtid').val('$id');
                                                                    $('#txtvalor').val('$estado');
                                                                    return true
                                                                });
                                                            </script>

                                                            ";
                                                            
                                                        }
                                                ?>
                                                </tbody>
                                            </table>
                                            </form> 
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                            </div>
                       <!-- -------------------------------------------- -->
                      </div>
                    </div>
                  </div>
                </section>

                <!---- MODAL-------------------------->

                <div class='modal hide fade' id='modalnuevo' role='dialog' tabindex='-1'>
                      <div class='modal-header'>
                        <button class='close' data-dismiss='modal' type='button'>&times;</button>
                        <h3>Agregar un correo</h3>
                      </div>
                      <div class='modal-body'>
                        <div class="box-content">
                        <form class='form' method="POST" id="frm_buscar" style='margin-bottom: 0;' autocomplete="off">
                         <fieldset>
                            <div class='span12 '>
                                             <div class='row-fluid '>
                                                 <div  class='span4 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Correo para notificar </label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtcorreo' name="txtcorreo" type='email' >
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                                                            
                                             </div>
                            </div>
                            </fieldset>
                     

                        </div>
                      </div>
                      <div class='modal-footer'>
                        <button class='btn btn-success' name="btnguardar" id="btnguardar">Guardar</button>
                        <button class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
                      </div>
                      </form>
                </div>


                <!----- MODAL --------------------->
                
</div>

<?php 

echo $html->loadJS("sistema");

echo $html->PrintBodyClose();

?>

    
