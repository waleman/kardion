<?php
require_once("../clases/cargar_master.php");
require_once("../clases/personas_controller.php");
require_once("../clases/roles_controller.php");
require_once("../clases/pruebas_controller.php");

$html = new cargar;
$roles = new roles;
$_persona = new personas;
$_pruebas = new pruebas;

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




//buscamos el codigo master para seleccionar los centros asociados al usuario
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; 
//seleccionamos el codigo de la persona enviado por url 
$personaId =$_GET['id'];

//Obtener los datos de la persona 

$datosPersona = $_persona->BuscarUna($personaId);
$primernombre =  $datosPersona['PrimerNombre'];
$segundonombre= $datosPersona['SegundoNombre'];
$primerapellido = $datosPersona['PrimerApellido'];
$segundoapellido= $datosPersona['SegundoApellido'];
$direccion = $datosPersona['Direccion'];
$cp = $datosPersona['CodigoPostal'];
$movil = $datosPersona['Movil'];
$telefono = $datosPersona['Telefono'];
//print_r($datosPersona); 

$ListaPruebas = $_pruebas->PruebasPersona($personaId);

if(empty($ListaPruebas)){
    $ListaPruebas =[];
}


?>


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
                                <span> Paciente : <strong style="color:#009688"><?php echo $primernombre . " " . $segundonombre . " " . $primerapellido ." " . $segundoapellido; ?></strong> </span>
                              </h1>
                            </div>
                          </div>
                        </div>
                         <!-- -------------------------------------------- -->


                 
                
                  <form class='form' method="POST" id="frm_filtrar" style='margin-bottom: 0;'>
                    <!--- Datos Personales -->            
                  <div class="span12 box bordered-box blue-border">
                        <div class="box-header blue-background">
                            <div class="title">
                                Datos personales
                            </div>
                            <div class="actions">
                                            
                            <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                            </a>
                            </div>

                        </div>
                        <div class="box-content box-double-padding">
                        
                            <fieldset>
                                <div class="span12 ">
                                <div class="control-group span3">
                                    <label class="control-label">Primer Nombre</label>
                                    <div class="controls">
                                    <input name="txtnombre" id="txtpnombre" class="span12" id="full-name" placeholder="Primer Nombre" type="text" value="<?=$primernombre ?>" disabled>
                                    <p class="help-block"></p>
                                    </div>
                                </div>

                                <div class="control-group span3">
                                    <label class="control-label">Segundo Nombre</label>
                                    <div class="controls">
                                    <input name="txtnombre" id="txtsnombre" class="span12" id="full-name" placeholder="Segundo Nombre" type="text" value="<?=$segundonombre ?>" disabled>
                                    <p class="help-block"></p>
                                    </div>
                                </div>

                                <div class="control-group span3">
                                    <label class="control-label">Primer Apellido</label>
                                    <div class="controls">
                                    <input name="txtnombre" id="txtpapellido" class="span12" id="full-name" placeholder="Primer Apellido" type="text" value="<?=$primerapellido ?>" disabled>
                                    <p class="help-block"></p>
                                    </div>
                                </div>

                                <div class="control-group span3">
                                    <label class="control-label">Segundo Apellido</label>
                                    <div class="controls">
                                    <input name="txtnombre" id="txtsapellido" class="span12" id="full-name" placeholder="Segundo Apellido" type="text" value="<?=$segundoapellido ?>" disabled>
                                    <p class="help-block"></p>
                                    </div>
                                </div>
                                
                                </div>
                                <div class="span12" style="margin-left: 0px;">
                                <div class="control-group span6">
                                    <label class="control-label">Direccion</label>
                                    <div class="controls">
                                    <input id="txtdireccion" name="txtdireccion" class="span12" id="address-line2" placeholder="Numero de serie" type="text" value="<?=$direccion?>" disabled>
                                    </div>
                                </div>
                                <div class="control-group span3">
                                    <label class="control-label">CP</label>
                                    <div class="controls">
                                    <input id="txtcp" name="txtcp" class="span12" id="address-line2" placeholder="codigo postal" type="text" value="<?=$cp?>" disabled>
                                    </div>
                                </div>
                                </div>
                                <div class="span12" style="margin-left: 0px;">
                                <div class="control-group span3">
                                    <label class="control-label">Movil</label>
                                    <div class="controls">
                                    <input id="txtmovil" name="txtmovil" class="span12" id="address-line2" placeholder="Numero de movil" type="text" value="<?=$movil?>" disabled>
                                    </div>
                                </div>
                                <div class="control-group span3">
                                    <label class="control-label">Telefono 2</label>
                                    <div class="controls">
                                    <input id="txttelefono" name="txttelefono" class="span12" id="address-line2" placeholder="Numero de telefono" type="text" value="<?=$telefono?>" disabled>
                                    </div>
                                </div>
                                </div>

                                <!-- <div class="span12" style="margin-left: 0px;">
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
                                        
                                </div> -->
                                </div>
                            </fieldset>

                            <!-- estado del dispositivo --->
                            <?php 
                            
                            ?>
                        
                            
                        
                     
                        </div>
                  </div>
                    <!-- Prueba --->
                    <div class="span12 box bordered-box green-border" style="margin-left: 0px;">
                        <div class="box-header green-background">
                            <div class="title">
                               Lista de Pruebas
                            </div>
                            <div class="actions">
                                            
                            <a class="btn box-collapse btn-mini btn-link" href="#"><i></i>
                            </a>
                            </div>

                        </div>
                        <div class="box-content box-double-padding">
                        
                            <fieldset>
                                <div class="span12 ">
                                <div class='row-fluid'>
                                    <div class='span12 box bordered-box orange-border' style='margin-bottom:0;'>
                                    <div class='box-content box-no-padding'>
                                        <div class='responsive-table'>
                                        <div class='scrollable-area'>
                                            <table id="table" class='data-table table table-bordered table-striped' data-pagination-records='10' data-pagination-top-bottom='false' style='margin-bottom:10px;'>
                                            <thead>
                                                <tr>
                                                  <th>
                                                      Acciones
                                                  </th>
                                                  <th>
                                                      Fecha de envio
                                                  </th>
                                                  <th>
                                                      Prioridad
                                                  </th>
                                                  <th>
                                                      Centro
                                                  </th>
                                                  <th>
                                                      Estado
                                                  </th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                            <?php
                                         
                                                    foreach ($ListaPruebas as $key => $value) {
                                                        $Persona = $value['Persona'];
                                                        $Fecha = $value['FC'];
                                                        $Prioridad = $value['Prioridad'];
                                                        $Centro = $value['Centro'];
                                                        $estado = $value['Estado'];
                                                        $estadoId = $value['PruebaEstadoId']; 
                                                        $PruebaId = $value['PruebaId'];
                                                        $archivo = $value['Archivo'];

                                                            echo "  
                                                            <tr style='text-align: center'>
                                                              <td style> ";
                                                              if( $estadoId == 5){ 
                                                                  echo "
                                                                  <a href='../utilidades/resultados/$archivo' target='_blank'>
                                                                    <img style='width:40px' src='../assets/images/pdfcheck.png' >
                                                                  </a>
                                                                  ";
                                                              }else{
                                                                   echo "
                                                                    
                                                                     <img id='btnerror$PruebaId' style='width:40px' src='../assets/images/pdficon.png' >
                                                                     <script>
                                                                            $('#btnerror$PruebaId').click(function(){
                                                                                swal({
                                                                                  title: 'Información ',
                                                                                  text: 'El resultado de la  prueba que ha seleccionado aún no está listo ',
                                                                                  icon: 'info',
                                                                                  button: 'Aceptar',
                                                                                });
                                                                            });
                                                                     </script>
                                                                    
                                                                   ";
                                                              }
                                                                 
                                                              echo"
                                                              </td>
                                                              <td>$Fecha</td>
                                                              <td>$Prioridad</td>
                                                              <td>$Centro</td>";
                                                              echo "<td> ";
                                                              if( $estadoId == 1){
                                                               echo "<span class='label label-danger'>$estado</span</td>";
                                                              }else if($estadoId== 2){
                                                                echo "<span class='label label-warning'>$estado</span>";
                                                              }else{
                                                                echo "<span class='label label-success'>$estado</span>";
                                                              }

                                                            echo"
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
                                </div>
                            </fieldset>

                            
                            <?php 
                            
                            ?>
                        </div>
                  </div>

                  <div class="form-actions" style="margin-bottom: 0;">
                                <div class="text-center">                             
                                    <!-- <input class="btn btn-primary  btn-large" name="btnregister" id="btnregister" value="Guardar" type="submit" />   -->
                                        <a class="btn btn-danger  btn-large" name="btncancelar" id="btncancelar" href="lista_pacientes_master.php">Cancelar </a>                   
                                        <br><br>
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
