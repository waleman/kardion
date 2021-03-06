<?php 
require_once("../clases/cargar.php");
require_once("../clases/roles_controller.php");
$html = new cargar;
$roles = new roles;
$html->sessionDataSistem();
echo $html->PrintHead();
echo $html->LoadCssSystem("sistema");
echo $html->LoadJquery("sistema");
echo $html->PrintBodyOpen();
echo $html->PrintHeader();

//definimos los permisos para esta pantalla;
$permisos = array(4,5,6);
$rol = $_SESSION['k6']['RolId'];
$permiso = $roles->buscarpermisos($rol,$permisos);
if(!$permiso){
  header("Location: accesoprohibido.php");
}
//buscamos el codigo master para seleccionar los centros asociados al usuario
$master = $_SESSION['k6']['MasterCompaniaId'];


$listaPacientes = [];

if(empty($ListaUsuairos)){
    $listaPacientes =[];
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
                                <span>Lista de pacientes</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                       <!-- ----------------------------------------- -->
                       <form method="post">
                            <a class="btn btn-primary btn-large" href="nuevo_usuario.php" name="btnnuevo" >+ Nuevo</a>
                       </form>
                        <br>

                        
                            <div class='row-fluid'>
                                    <div class='span12 box bordered-box orange-border' style='margin-bottom:0;'>
                                    <div class='box-content box-no-padding'>
                                        <div class='responsive-table'>
                                        <div class='scrollable-area'>
                                            <table class='data-table table table-bordered table-striped' data-pagination-records='50' data-pagination-top-bottom='false' style='margin-bottom:10px;'>
                                            <thead>
                                                <tr>
                                                <th>
                                                   Usuario
                                                </th>
                                                <th>
                                                    Rol
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
                                         
                                                    foreach ($ListaUsuairos as $key => $value) {
                                                        $usuario = $value['Usuario'];
                                                        $Rol = $value['Rol'];
                                                        $estado = $value['Estado'];
                                                        $UsuarioId = $value['UsuarioId'];
                                                            echo "  
                                                            <tr>
                                                                <td>$usuario</td>
                                                                <td>$Rol</td>
                                                                <td> ";
                                                                if( $estado == "Activo"){
                                                                echo "<span class='label label-success'>$estado</span>";
                                                                }else{
                                                                echo "<span class='label label-warning'>$estado</span>";
                                                                }
                                                                
                                                            echo"   </td>
                                                                <td>
                                                                    <div class='text-center'>
                                                                    <a class='btn btn-success btn-medium' href='editar_usuarios.php?id=$UsuarioId'>
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

    
