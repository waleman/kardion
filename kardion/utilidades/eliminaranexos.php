<?php
require_once('../clases/archivos_controller.php');

$archivos = new archivos;


if(isset($_GET['pruebaid'])){
    $pruebaid= $_GET['pruebaid'];
    $listaarchivos  = $archivos->ObtenerAnexos($pruebaid);
    $i = 0;
    if(!empty($listaarchivos)){
        foreach ( $listaarchivos as $key => $value) {
            $direccion = "../public/anexos/";
            $nombre = $value['Archivo'];
            $aborrar = $direccion . $nombre;
            $borrar = unlink($aborrar);
            if($borrar){
                 $i++;
            }
        }
        if($i > 0){
            $eliminardb = $archivos->borrarAnexosBD($pruebaid);
            if($borrar){
                echo "
                <script>
                  swal({
                    title: 'Hecho!',
                    text: 'Los archivos anexosm han sido eliminados',
                    icon: 'success',
                    button: 'Ok',
                  });
                </script>
                ";
            }
        }
    }else{
                echo "
                <script>
                    swal({
                        title: 'Error!',
                        text: 'No hay anexos que borrar',
                        icon: 'error',
                        button: 'Ok',
                    });
                </script>
                ";
    }
  
    

}



?>