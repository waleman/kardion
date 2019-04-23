<?php 
require_once("../clases/cargar.php");
require_once("../clases/roles_controller.php");
require_once('../clases/personas_controller.php');
require_once('../clases/paises_controller.php');
require_once("../clases/usuario_controller.php");
$pais = new paises;
$html = new cargar;
$roles = new roles;
$persona = new personas;
$userdata = new usuario;
$html->sessionDataSistem();
echo $html->PrintHead();
echo $html->LoadCssSystem("sistema");
echo $html->LoadJquery("sistema");
echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
echo $html->PrintBodyOpen();

echo $html->PrintHeader();

//buscamos el codigo master para seleccionar los centros asociados al usuario
$master = $_SESSION['k6']['MasterCompaniaId'];
$usuarioId =$_SESSION['k6']['UsuarioId']; 

//listas 
$listapaises = $pais->getPais();
$listaDocumentos = $persona->TipoDocumento();

// $listaPersonas = [];

/*Esta lineas de codigo muestran los nombres de los pacientes en la tabla buscar paciente*/
    $listaPersonas = $persona->buscarTodaslasPersonas();
    if(empty($listaPersonas)){$listaPersonas = [];}

//Valida que el usuario tenga los permisos para esta pantalla;
$permisos = array(4,5,6);
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




if(isset($_POST['btnregister'])){
        $correo = $_POST['txtemail'];
        $pais= "";
        $provincia="";
        $ciudad="";
        $tipodoc="";
        $genero="";
        if(isset($_POST['cbopais'])){$pais=$_POST['cbopais'];}
        if(isset($_POST['cboprovincia'])){$provincia=$_POST['cboprovincia'];}
        if(isset($_POST['cbociudad'])){$ciudad=$_POST['cbociudad'];}
        if(isset($_POST['cbodocumento'])){$tipodoc=$_POST['cbodocumento'];}
        if(isset($_POST['cbogenero'])){$genero=$_POST['cbogenero'];}
        //Creamos el array para guardar
        $datos = array(
            'email' => $correo,
            'genero'=> $genero,
            'primernombre'=>$_POST['txtprimernombre'],
            'segundonombre'=>$_POST['txtsegundonombre'],
            'primerapellido'=>$_POST['txtprimerapellido'],
            'segundoapellido'=>$_POST['txtsegundoapellido'],
            'tipodocumento'=>$tipodoc,
            'numerodocumento'=>$_POST['txtnumerodocumento'],
            'movil'=>$_POST['txtmovil'],
            'telefono'=>$_POST['txttelefono'],
            'fechanac'=>$_POST['txtfechanac'],
            'codigopostal'=>$_POST['txtcodigopostal'],
            'direccion'=>$_POST['txtdireccion'],
            'pais'=>$pais,
            'provincia'=>$provincia,
            'ciudad'=>$ciudad,
            'usuario'=>$usuarioId
        );

                $resp = $persona->GuardarPersona($datos);
                if($resp){
                    //crear usuario
                    //bucamos el Id de la persona que hemos guardado anteriomente
                    $personaId = $persona->BuscarId($correo);
                    //creamos el usuario apartir de los datos (el usuario estara pendiente de activacion)
                    $resp2 =$userdata->CrearUsuarioPaciente($correo,$master,$personaId,$usuarioId);
                    // eviar email al usuario  para que active su cuenta

                    $userdata->eviarEmail($correo);

                    if($resp2){
                        $peronaencript =base64_encode($personaId);// encriptamos los id para enviarlos por parametro
                        echo"<script>
                        swal({
                                title: 'Hecho!',
                                text: 'Datos Guardados correctamente!',
                                type: 'success',
                                icon: 'success'
                        }).then(function() {
                                window.location = 'nuevo_prueba_paso2.php?persona=$peronaencript';
                        });
                      </script>";
                      
                    }else{
                            echo"<script>
                            swal({
                                    title: 'Errpr!',
                                    text: 'Error al crear el usuario!',
                                    type: 'error',
                                    icon: 'error'
                            });
                        </script>";
                    }
                }else{
                    echo"<script>
                    swal({
                            title: 'Errpr!',
                            text: 'Error al guardar la persona!',
                            type: 'error',
                            icon: 'error'
                    });
                </script>";
                }
    }
 


?>


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


            function isEmail(email) {
                    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    return regex.test(email);
                }

             $("#btnbuscarpersona").click(function (){   
                         var mail = document.getElementById('txtemail').value;
                         var url = "../utilidades/buscarpersonas.php?email="+mail;    
                        
                         $.ajax({
                            data: {"email" : mail, },
                            type: "GET",
                            dataType: "json",
                            url: url,
                        })
                        .done(function( data, textStatus, jqXHR ) {
                            if ( console && console.log ) {
                                if(data.existe){
                                    for(var datos of data.datos){
                                        $("#colores").removeClass( "warning" ).addClass( "info" );
                                        document.getElementById('txtprimernombre').value =datos.PrimerNombre;
                                        document.getElementById('txtsegundonombre').value =datos.SegundoNombre;
                                        document.getElementById('txtprimerapellido').value =datos.PrimerApellido;
                                        document.getElementById('txtsegundoapellido').value =datos.SegundoApellido;
                                        document.getElementById('txtnumerodocumento').value =datos.NumeroDocumento;
                                        document.getElementById('txtmovil').value =datos.Movil;
                                        document.getElementById('txttelefono').value =datos.Telefono;
                                        document.getElementById('txtcodigopostal').value =datos.CodigoPostal;
                                        document.getElementById('txtfechanac').value =datos.FechaNacimiento;
                                        document.getElementById('txtdireccion').value =datos.Direccion;
                                        document.getElementById('txthidden').value = "1";
                                        document.getElementById('txtpersona').value = datos.PersonaId;

                                        $("#cbogenero").val(datos.Sexo);
                                        $("#cbodocumento").val(datos.TipoDocumentoId);
                                        $("#divcbos").hide();                           	
                                       
                                    }
                                }else{
                                        document.getElementById('txthidden').value = "0";
                                        document.getElementById('txtpersona').value = 0;
                                        $("#colores").removeClass( "info" ).addClass( "control-group warning" );
                                        document.getElementById('txtprimernombre').value = "";
                                        document.getElementById('txtsegundonombre').value = "";
                                        document.getElementById('txtprimerapellido').value = "";
                                        document.getElementById('txtsegundoapellido').value = "";
                                        document.getElementById('txtnumerodocumento').value = "";
                                        document.getElementById('txtmovil').value = "";
                                        document.getElementById('txttelefono').value = "";
                                        document.getElementById('txtcodigopostal').value = "";
                                        document.getElementById('txtfechanac').value = "";
                                        document.getElementById('txtdireccion').value = "";
                                        $("#cbogenero").val(0);
                                        $("#cbodocumento").val(0);
                                        $("#divcbos").show();
                                }
                                                  
                            }
                        })
                        .fail(function( jqXHR, textStatus, errorThrown ) {
                            if ( console && console.log ) {
                                console.log( "La solicitud a fallado: " +  textStatus);
                            }
                        });
             });


             $("#btnbuscar2").click(function (){
                  var enviado = document.getElementById('txtbuscartexto').value
                  var url = "../utilidades/buscarpaciente.php";
                     $.ajax({
                         type:"POST",
                         url: url,
                         data: {txtbuscartexto :enviado},
                         success: function(data){
                                 $("#tabla").html(data);
                         },
                        error: function(){
                            console.log( "Error con el servidor" );
                        } 
                     });

             
                 return false;

             });

             
             $("#btnregister").click(function (){ 

                    if(!document.getElementById('txtemail').value){
                        mostrar("Debe escirbir el correo electronico");
                        return false;
                    }else if(!document.getElementById('txtprimernombre').value){
                        mostrar("Debe escirbir almenos el primer nombre");
                        return false;
                    }else if(!document.getElementById('txtprimerapellido').value){
                        mostrar("Debe escirbir almenos el primer apellido");
                        return false;
                    }else if(!document.getElementById('txtfechanac').value){
                        mostrar("Debe escribir la fecha de nacimiento");
                        return false;
                    }else{
                       
                        let email = document.getElementById('txtemail').value;
                        let verificarmail =isEmail(email);
                        if(verificarmail){
                            return true
                        }else{
                            mostrar("Debe escirbir un correo valido");
                            return false; 
                        }
                    }
                   
             });

             $('#cbopais').on('change', function() {
                     var url = "../utilidades/provincias.php";
                     $.ajax({
                         type:"POST",
                         url: url,
                         data: $("#frm_filtrar").serialize(),
                         success: function(data){
                                 $("#departamento").html(data);
                         }
                     });
                     return false;
            });

            $('#cboprovincia').on('change', function() {
                        var url = "../utilidades/ciudades.php";
                        $.ajax({
                            type:"POST",
                            url: url,
                            data: $("#frm_filtrar").serialize(),
                            success: function(data){
                                    $("#ciudad").html(data);
                            }
                        });
                        return false;
            });

            $("#btnnuevo").click(function (){
                $('#modalnuevo').modal('show');
                return false;
            });
            $("#btnbuscar").click(function (){
                $('#modalbuscar').modal('show');
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
                                <span>Nueva prueba</span>
                              </h1>
                            </div>
                          </div>
                        </div>
                       <!-- ----------------------------------------- -->
                       <div class="row-fluid">
                                    <a href="#" class=""  id="btnbuscar" name="btnbuscar" >
                                            <div class="span3 box box-bordered  offset1 ">
                                                <div class="box-header box-header-large test" style="font-size:12px;background-color:#fff;text-align:center;">
                                                            <img style="height:100px" src="../assets/images/buscar.png" alt="">
                                                            <h4>Buscar paciente</h4>
                                                </div>
                                            </div>
                                    </a>
                                    <a href="#" class="" id="btnnuevo" name="btnnuevo">
                                            <div class="span3 box box-bordered offset1 ">
                                                <div class="box-header box-header-small test" style="font-size:12px;background-color:#fff;text-align:center;">
                                                        <img style="height:100px" src="../assets/images/nuevo.png" alt="">
                                                            <h4>Crear paciente</h4>
                                                </div>
                                            </div>
                                    </a>
                        </div>
                        <br>
                       <!-- -------------------------------------------- -->
                      </div>
                    </div>
                  </div>
                </section>
</div>

<!------------------- Modal buscar ----------------------------->
                <div class='modal hide fade' id='modalbuscar' role='dialog' tabindex='-1'>
                      <div class='modal-header'>
                        <button class='close' data-dismiss='modal' type='button'>&times;</button>
                        <h3>Buscar un paciente</h3>
                      </div>
                      <div class='modal-body'>
                        <div class="box-content">
                        <form class='form' method="POST" id="frm_buscar" style='margin-bottom: 0;' autocomplete="off">
                         <fieldset>
                            <div class='span12 '>
                                             <div class='row-fluid '>
                                                 <div  class='span4 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Nombre o correo </label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtbuscartexto' name="txtbuscartexto" type='text' >
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <button class="btn btn-inverse" style="margin-top:25px" name="btnbuscar2" id="btnbuscar2">
                                                      <i class="icon-search"></i>
                                                      Buscar
                                                </button>                                      
                                             </div>
                            </div>
                            </fieldset>
                        </form>

                                <!----------------------- Tabla ------------------------------------->   
                                <div id="tabla">
                                <div style="text-align:center">
                                <h2>Escriba el nombre o el correo del paciente que busca.</h2>
                                <img style="width:400px" src="../assets/images/running.png">
                                </div>
                               
                                    <!-- <div class='responsive-table'>
                                        <div class='scrollable-area'>
                                            <table class='data-table table table-bordered table-striped' data-pagination-records='10' data-pagination-top-bottom='false' style='margin-bottom:0;'>
                                            <thead>
                                                <tr>
                                                <th>
                                                    Nombre
                                                </th>
                                                <th>
                                                    Correo
                                                </th>
                                                <th>
                                                    Estado
                                                </th>
                                                <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                                <?php 
                                                    foreach ($listaPersonas as $key => $value) {
                                                        $nombre = $value['Nombre'];
                                                        $correo = $value['Correo'];
                                                        $estado = $value['Estado'];
                                                        $perid = $value['PersonaId'];
                                                        $peronaencript = base64_encode($perid);// encriptamos los id para enviarlos por parametro
                                                        echo "
                                                        <tr>
                                                            <td>$nombre</td>
                                                            <td>$correo</td>
                                                            <td> ";
                                                                if($estado == 'Activo'){
                                                                    echo "<span class='label label-success'>$estado</span>";
                                                                }else{
                                                                    echo "<span class='label label-important'>$estado</span>";
                                                                }
                                                            
                                                        echo " </td>
                                                            <td>
                                                                    <div class='text-center'>
                                                                    <a class='btn btn-primary' id='btn$perid' href='#'>
                                                                        <i class='icon-ok'></i>
                                                                        Seleccionar 
                                                                    </a>
                                                                    </div>
                                                                </td>      
                                                            </tr>
                                                            
                                                            <script>
                                                                    $('#btn$perid').click(function (){
                                                                            swal({
                                                                                    title: 'Hecho!',
                                                                                    text: 'Datos Guardados correctamente!',
                                                                                    type: 'success',
                                                                                    icon: 'success'
                                                                            }).then(function() {
                                                                                    window.location = 'nuevo_prueba_paso2.php?persona=$peronaencript';
                                                                            });

                                                                    });
                                                            </script>
                                                        ";
                                                    

                                                    }
                                                
                                                ?>
                                            
                                            </tbody>
                                            </table>
                                        </div>
                                    </div>  -->
                                </div>                                    
                                <!----------------------- Tabla ------------------------------------->   
                        </div>
                      </div>
                      <div class='modal-footer'>
                        <button class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
                      </div>
                </div>
<!------------------- Modal Conclusiones ----------------------------->

<!------------------- Modal Nuevo paciente ----------------------------->
<div class='modal hide fade' id='modalnuevo' role='dialog' tabindex='-1'>
                      <div class='modal-header'>
                        <button class='close' data-dismiss='modal' type='button'>&times;</button>
                        <h3>Complete los datos del paciente</h3>
                      </div>
                      <div class='modal-body'>
                        <div class="box-content">

                                <!----------------------- Formulario ------------------------------------->   

                                <form class='form' method="POST" id="frm_filtrar" style='margin-bottom: 0;' autocomplete="off">
                                 <input id='txthidden' name="txthidden" type='hidden'  >
                                 <input id='txtpersona' name="txtpersona" type='hidden'  >
                                         <div class='row-fluid'>

                                 <!-------------------------------------- Datos Generales -->
                                    <fieldset>
                                         <div class='span12 '>

                                         <div class='row-fluid'>
                                                 <div  class='span3 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Primer Nombre</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtprimernombre' name="txtprimernombre" type='text' >
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div  class='span3 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Segundo Nombre</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtsegundonombre' name="txtsegundonombre" type='text' >
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                            
                                                 <div  class='span3 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Primer Apellido</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtprimerapellido' name="txtprimerapellido" type='text' >
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div  class='span3 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Segundo Apellido</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtsegundoapellido' name="txtsegundoapellido" type='text' >
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>


                                             <div class='row-fluid'>
                                                 <div  class='span3 '>
                                                     <div class='control-group' id="colores">
                                                         <label class='control-label' name="algo1">Email *</label>
                                                         <div class='controls'>
                                                            
                                                                <input class='span12' id='txtemail' name="txtemail" type='email' autocomplete="off" >
                                                     
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div  class='span3 '>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Genero</label>
                                                                <div class='controls'>
                                                                        <select id="cbogenero" name="cbogenero" >
                                                                            <option value='0' selected disabled>-- Seleccione una --</option>
                                                                            <option value='Masculino'  >Masculino</option>
                                                                            <option value='Femenino'  >Femenino</option>
                                                                        </select>
                                                                </div>
                                                            </div>
                                                </div>
                                         
                                                <div  class='span3'>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Tipo de documento</label>
                                                                <div class='controls'>
                                                                <select id="cbodocumento" name="cbodocumento" >
                                                                    <option value='0' selected disabled>-- Seleccione una --</option>
                                                                    <?php 
                                                                                foreach ($listaDocumentos as $key => $value) {
                                                                                    $nom = $value['Nombre'];
                                                                                    $id = $value['TipoDocumentoId'];
                                                                                    echo "<option value='$id' >$nom</option>";
                                                                                }
                                                                    ?>
                                                                </select>
                                                                </div>
                                                            </div>
                                                </div>
                                                 <div  class='span3 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Numero de documento</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtnumerodocumento' name="txtnumerodocumento" type='text' >
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>

                                             <div class='row-fluid'>
                                                 <div  class='span3 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Movil</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtmovil' name="txtmovil" type='text' >
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div  class='span3'>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Telefono</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txttelefono' name="txttelefono" type='text' >
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>

                                                     
                                                 <div  class='span3 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Codigo Postal</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtcodigopostal' name="txtcodigopostal" type='text' >
                                                         </div>
                                                     </div>
                                                 </div>

                                                 <div  class='span3'>
                                                     <div class='control-group'>
                                                        <label class='control-label'>Fecha de nacimiento</label>
                                                        <div class="controls">
                                                        <input  class='span12' name="txtfechanac"  id="txtfechanac" data-mask="99-99-9999" data-rule-dateiso="true" data-rule-required="true"  placeholder="DD-MM-YYYY" type="text">
                                                    </div>
                                                </div>
                                               
                                            </div>


                                             <div class='row-fluid'>
                                                 <div  class='span9 '>
                                                     <div class='control-group'>
                                                         <label class='control-label'>Direccion</label>
                                                         <div class='controls'>
                                                         <input class='span12' id='txtdireccion' name="txtdireccion" type='text'>
                                                         <p class='help-block'></p>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>
                                        <div id="divcbos">
                                             <div class='row-fluid' >
                                                     <div  class='span2 '>
                                                         <div class='control-group'>
                                                             <label class='control-label'>Pais</label>
                                                             <div class='controls'>
                                                             <select id="cbopais" name="cbopais" >
                                                                 <option value='0' selected disabled>-- Seleccione una --</option>
                                                                 <?php 
                                                                             foreach ($listapaises as $key => $value) {
                                                                                 $nom = $value['Nombre'];
                                                                                 $id = $value['PaisId'];
                                                                                 echo "<option value='$id' >$nom</option>";
                                                                             }
                                                                 ?>
                                                             </select>
                                                             </div>
                                                         </div>
                                                     </div>
                                                     <div  class='span2 offset1'>
                                                         <div class='control-group' id="departamento">
                                                             <label class='control-label'>Provincia</label>
                                                             <div class='controls'>
                                                             <select id="cboprovincia" name="cboprovincia" >
                                                                 <option value='0' selected disabled>-- Seleccione una --</option>
                                                             </select>
                                                             </div>
                                                         </div>
                                                     </div>
                                                
                                             <div  class='span2 offset1'>
                                                         <div class='control-group' id="ciudad">
                                                             <label class='control-label'>Municipio</label>
                                                             <div class='controls'>
                                                             <select id="cbociudad" name="cbociudad" >
                                                                 <option value='0' selected disabled>-- Seleccione una --</option>
                                                             </select>
                                                             </div>
                                                         </div>
                                                     </div>
                                             </div>
                                     </div>

                                        
                                 </fieldset>  

                                        <!-- <hr class='hr-normal'>
                                        
                                            <div class='text-center'>
                                                <input class="btn btn-primary  btn-large" name="btnregister" id="btnregister" value="Guardar" type="submit" />  
                                                <a class="btn btn-danger  btn-large" name="btncancelar" id="btncancelar" href="lista_centros.php">Cancelar </a>                   
                                                <br><br>
                            
                                            </div>
                                         -->
                           
                                              
                                <!----------------------- Formulario ------------------------------------->   
                        </div>
                      </div>
                      <div class='modal-footer'>
                      <input class="btn btn-primary " name="btnregister" id="btnregister" value="Guardar" type="submit" />  
                        <button class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
                      </div>
                      </form>
                </div>
<!------------------- Modal Nuevo paciente ----------------------------->
                            <div id="snackbar" style="background-color: #980a00 !important;">
                                        <label id="errorMensaje"></label>
                            </div>

<?php 

echo $html->loadJS("sistema");

echo $html->PrintBodyClose();

?>

    
