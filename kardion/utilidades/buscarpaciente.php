<?php
 require_once('../clases/personas_controller.php');
 $_persona = new personas;



$lista = array();
if(isset($_POST['txtbuscartexto'])){
    $datos = $_POST['txtbuscartexto'];
    if($datos != ""){
        $lista = $_persona->Like($datos);

        if(empty($lista)){
            $lista = array();
        }
    }
    
}

?>

 <div class='responsive-table'>
<div class='scrollable-area'>
    <table class=' table table-bordered table-striped' data-pagination-records='10' data-pagination-top-bottom='false' style='margin-bottom:0;'>
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
            foreach ($lista as $key => $value) {
                $nombre = $value['Nombre'];
                $correo = $value['Correo'];
                $estado = $value['Estado'];
                $perid = $value['PersonaId'];
               
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
                                            window.location = 'nuevo_prueba_paso2.php?persona=$perid';
                                    });

                            });
                    </script>
                ";
            

            }
        
        ?>
    
    </tbody>
    </table>
</div>
</div> 


