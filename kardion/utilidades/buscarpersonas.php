<?php
    require_once('../clases/personas_controller.php');
    $persona = new personas;
    $jsondata = array();


    if( isset($_GET['email']) ) {
       
        $correo = $_GET['email'];
        $respuesta = $persona->BuscarporEmail($correo);

        if(!$respuesta){
            $dat =array();
            $jsondata = array(
                'existe'  => false,
                'datos' => $dat  );
        }else{
            $jsondata = array(
                'existe'  => true,
                'datos' => $respuesta  );
        }
    }
      
    
     header('Content-type: application/json; charset=utf-8');
     echo json_encode($jsondata);
     exit();
?>