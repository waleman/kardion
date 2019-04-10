<?php

require_once('../clases/conexion.php');

$conexion = new conexion;

function generateRandomString($length = 10) { 
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
} 


if (!empty($_FILES)) {
    /*codigo de la prueba*/
    $id = $_GET['id'];


        $nombre = generateRandomString(); //generamos un string aleatorio
        $tempFile = $_FILES['file']['tmp_name'];  //seleccionamos el nombre temporal del archivo      
        $ext = explode(".", $_FILES['file']['name']); // Buscamos la extension del archivo
        $ext =end($ext);           
        $nombreCompleto =  $nombre .'.'.$ext; //creamo un nombre para almacenar en la carpeta del servidor
        $targetPath = "../public/anexos/";  //creamos la direccion donde se guardara el archivo
        $targetFile =  $targetPath.$id ."_". $nombreCompleto;  //5
        $nombreguardar = $id ."_". $nombreCompleto;
        move_uploaded_file($tempFile,$targetFile); //6
        $query = "insert into pruebas_anexos (PruebaId,Archivo)values('$id','$nombreguardar')";
        $conexion->NonQuery($query);    

}
?>  