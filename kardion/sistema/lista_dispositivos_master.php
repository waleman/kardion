<?php 
require_once("../clases/cargar_master.php");
require_once("../clases/dispositivos_controller.php");
require_once("../clases/roles_controller.php");
$html = new cargar;
$dispositivos = new dispositivos;
$roles = new roles;
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





$ListaDispositivos = $dispositivos->Dispostivios();

if(empty($ListaDispositivos)){
    $ListaDispositivos =[];
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
                                <span>Lista de dispositivos</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                       <!-- ----------------------------------------- -->
                       <form method="post">
                            <a class="btn btn-primary btn-large" href="nuevo_dispostivo_master.php" name="btnnuevo" > Registrar Dispositivo</a>
                           
                       </form>
                        <br>

                        
                            <div class='row-fluid'>
                                    <div class='span12 box bordered-box orange-border' style='margin-bottom:0;'>
                                    <div class="box-header blue-background">
                                      <div class="title">Dispositivos</div>
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
                                                   # Serie
                                                </th>
                                                <th>
                                                    Dispositivo
                                                </th>
                                                <th>
                                                    Modelo
                                                </th>
                                                <th>
                                                   Foto
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
                                         
                                                    foreach ($ListaDispositivos as $key => $value) {
                                                        $nombre = $value['Nombre'];
                                                        $CodigoSerie = $value['Serie'];
                                                        $estado= $value['Estado'];
                                                        $Modelo = $value['Modelo'];
                                                        $aparatoId = $value['AparatoId'];
                                                        $tipoestado = $value['TipoEstadoId'];
                                                        $imagen = $value['Imagen'];
                                                     
                                           
                                                            echo "  
                                                            <tr>
                                                                <td>$CodigoSerie</td>
                                                                <td>$nombre</td>
                                                                <td>$Modelo  </td>
                                                                <td style='text-align:center'>
                                                                 <img src='$imagen' height='60' width='60'>
                                                                </td>
                                                                <td style='text-align:center'> ";
                                                                if( $tipoestado == 1){//En inventario
                                                                echo "<span class='label label-success'>$estado</span>";
                                                                }else if( $tipoestado == 2){//Alquilado
                                                                echo "<span class='label' style='background-color: #9C27B0'>$estado</span>";
                                                                }else if( $tipoestado == 3){//Vendido
                                                                  echo "<span class='label label-warning'>$estado</span>";
                                                                }else if( $tipoestado == 4){//En mal estado
                                                                  echo "<span class='label label-important'>$estado</span>";
                                                                }
                                                                
                                                            echo"   </td>
                                                                <td>
                                                                    <div class='text-center'>
                                                                    <a class='btn btn-success btn-medium' href='editar_dispositivos_master.php?id=$aparatoId'>
                                                                    <i class='icon-pencil'></i>
                                                                        Editar
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

    
