<?php

require_once('../clases/conexion.php');

$conexion = new conexion;

function generateRandomString($length = 10) { 
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
} 


if (!empty($_FILES)) {
    /*generamos un codigo para guardar con el id de la persona y una cadena ramdom de  5 caracteres*/
    $id = $_GET['id'];
    $codigo = $_GET['codigo'];
    $savecodge= $id."_".$codigo;

    $Q = "select count(*) as cantidad from pruebas_archivos where Codigo = '$savecodge'";
    $cantidad = $conexion->ObtenerRegistros($sqlstr);
    if($cantidad[0]['cantidad'] < 5){
        $nombre = generateRandomString(); //generamos un string aleatorio
        $tempFile = $_FILES['file']['tmp_name'];  //seleccionamos el nombre temporal del archivo      
        $ext = explode(".", $_FILES['file']['name']); // Buscamos la extension del archivo
        $ext =end($ext);           
        $nombreCompleto =  $nombre .'.'.$ext; //creamo un nombre para almacenar en la carpeta del servidor
        $targetPath = "../public/pruebas/";  //creamos la direccion donde se guardara el archivo
        $targetFile =  $targetPath.$id ."_". $nombreCompleto;  //5
        move_uploaded_file($tempFile,$targetFile); //6
        $query = "insert into pruebas_archivos (Codigo,Archivo)values('$savecodge','$nombreCompleto')";
        $conexion->NonQuery($query);    
    }else{
        echo "Erro al cargar";
        
    }
}
?>  