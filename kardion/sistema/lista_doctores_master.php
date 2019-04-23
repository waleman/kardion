<?php 
require_once("../clases/cargar_master.php");
require_once("../clases/dr.php");
require_once("../clases/roles_controller.php");
$html = new cargar;
$dr = new dr;
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
$ListaDoctores = $dr->todos();
if(empty($ListaDoctores)){
    $ListaDoctores =[];
}



if(isset($_POST['txtdrid'])){
    $codigo = $_POST['txtdrid'];
    $status = $_POST['txtvalor'];
    $conf = $dr->verificarDr($codigo,$status);
    if($conf){
        echo"<script>
        swal({
                title: 'Hecho!',
                text: 'Cambios realizados!',
                type: 'success',
                icon: 'success'
        }).then(function() {
                window.location = 'lista_doctores_master.php';
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

?>



<script>
    $(document).ready(function(){

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
                                <span>Lista de doctores</span>
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
                                      <div class="title">Doctores</div>
                                      <div class="actions">
                                        <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                                        </a>
                                      </div>
                                    </div>
                                    <div class='box-content box-no-padding'>
                                        <div class='responsive-table'>
                                        <div class='scrollable-area'>

                                        <form method="post">
                                        <input type="hidden" name="txtdrid" id="txtdrid">
                                        <input type="hidden" name="txtvalor" id="txtvalor">
                                            <table class='data-table table table-bordered table-striped' data-pagination-records='25' data-pagination-top-bottom='false' style='margin-bottom:10px;'>
                                            <thead>
                                                <tr>
                                                <th>
                                                   Usuario
                                                </th>
                                                <th>
                                                   Nombre
                                                </th>
                                                <th>
                                                    Estado
                                                </th>
                                                <th>
                                                   Permitir acceso
                                                </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                         
                                                    foreach ($ListaDoctores as $key => $value) {
                                                        $id = $value['UsuarioId'];
                                                        $usuario = $value['Usuario'];
                                                        $nombre = $value['Nombre'];
                                                        $verificado= $value['Verificado'];
                                                        $estado = $value['Estado'];
                                                            echo "  
                                                            <tr>
                                                                <td>$usuario</td>
                                                                <td>$nombre</td>
                                                                <td style='text-align:center'> ";
                                                                if( $estado == 'Activo'){//Activo
                                                                echo "<span class='label label-success'>$estado</span>";
                                                                }else if( $estado == 'Pendiente'){//Pendiente
                                                                  echo "<span class='label label-warning'>$estado</span>";
                                                                }else {//Otro
                                                                  echo "<span class='label label-important'>$estado</span>";
                                                                }
                                                            echo"<td style='text-align:center'> ";
                                                            
                                                            if($verificado == 0){
                                                                echo "<button class='btn btn-danger' style='margin-bottom:5px' id='btnact$id' name='btnact$id'>
                                                                <i class='icon-thumbs-down'> Sin Acceso</i> 
                                                              </button>";
                                                            }else{
                                                                echo "<button class='btn btn-success' style='margin-bottom:5px' id='btnact$id' name='btnact$id'>
                                                                <i class='icon-thumbs-up'> Con Acceso</i>
                                                              </button>";
                                                            }
                                                            echo"
                                                            </td>
                                                            </tr>
                                                            <script>
                                                                $('#btnact$id').click(function(){
                                                                    $('#txtdrid').val('$id');
                                                                    $('#txtvalor').val('$verificado');
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
</div>

<?php 

echo $html->loadJS("sistema");

echo $html->PrintBodyClose();

?>

    
