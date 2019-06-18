<?php 
require_once("../clases/cargar.php");
require_once("../clases/pruebas_controller.php");
require_once("../clases/roles_controller.php");
require_once("../clases/alertas_controller.php");
$html = new cargar;
$_pruebas = new pruebas;
$roles = new roles;
$_alertas = new alertas;
$html->sessionDataSistem();
echo $html->PrintHead();
echo $html->LoadCssSystem("sistema");
echo $html->LoadJquery("sistema");
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $html->PrintBodyOpen();
echo $html->PrintHeader();
//definimos los permisos para esta pantalla;
$permisos = array(4,5,6);
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


if($rol == 4 or $rol == 5)
$ListaPruebas = $_pruebas->ListaPruebasDescartadasEmpresa($master);
else{
$ListaPruebas = $_pruebas->ListaPruebasDescartadasPorUsuario($master,$usuarioid);
}

if(empty($ListaPruebas)){
    $ListaPruebas =[];
}


?>



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
                                <span>Lista de pruebas descartadas</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                       <!-- ----------------------------------------- -->
                       <!-- <form method="post">
                            <a class="btn btn-primary btn-large" href="nuevo_dispositivos_master.php" name="btnnuevo" > Registrar Dispositivo</a>
                           
                       </form> -->
                        <br>

                        
                            <div class='row-fluid'>
                                    <div class='span12 box bordered-box orange-border' style='margin-bottom:0;'>
                                    <div class="box-header blue-background">
                                      <div class="title">Pruebas descartadas</div>
                                      <div class="actions">
                                        <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                                        </a>
                                      </div>
                                    </div>
                                    <div class='box-content box-no-padding'>
                                        <div class='responsive-table'>
                                        <div class='scrollable-area'>
                                            <table class='data-table table table-bordered table-striped' data-pagination-records='25' data-pagination-top-bottom='false' style='margin-bottom:10px;'>
                                            <thead>
                                                <tr>
                                                <th>
                                                   Paciente
                                                </th>
                                                <th>
                                                    Correo
                                                </th>
                                                <th>
                                                    Fecha de envio
                                                </th>
                                                <th>
                                                   Fecha de descarte
                                                </th>
                                                <th>
                                                   Centro
                                                </th>
                                                <th>
                                                   Enviada por
                                                </th>
                                                <th>
                                                    Estado
                                                </th>
                                                <th>
                                                    Acciones
                                                </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                         
                                                    foreach ($ListaPruebas as $key => $value) {
                                                        $nombre = $value['Persona'];
                                                        $correo = $value['Correo'];
                                                        $estado= $value['Estado'];
                                                        $fc = $value['FC'];
                                                        $fm = $value['FM'];
                                                        $centro = $value['Centro'];
                                                        $id = $value['PruebaId'];
                                                        $enviadapor = $value['enviado'];
                                                     
                                           
                                                            echo "  
                                                            <tr>
                                                                <td>$nombre</td>
                                                                <td>$correo</td>
                                                                <td>$fc</td>
                                                                <td>$fm</td>
                                                                <td>$centro</td>
                                                                <td> $enviadapor</td>
                                                                <td style='text-align:center'>
                                                                 <span class='label label-warning'>Descartada</span>
                                                                </td>
                                                                <td>
                                                                   <div class='text-center'>
                                                                    <a class='btn btn-success btn-medium' href='detalles_pruebas_descartadas.php?id=$id'>
                                                                        Ver
                                                                    </a>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            ";
                                                        
                                                    }
                                            ?>
                                            
                                            </tbody>
                                            </table>
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
</div>

<?php 

echo $html->loadJS("sistema");

echo $html->PrintBodyClose();

?>

    
