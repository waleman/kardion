<?php

function generateRandomString($length = 10) { 
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
} 


$nombre = generateRandomString();


    if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
        $ext = explode(".", $_FILES['file']['name']);
        $ext =end($ext);
        $nombreCompleto =  $nombre .'.'.$ext;
        move_uploaded_file($_FILES['file']['tmp_name'], '../assets/temporales/img/'.$nombreCompleto);

        echo "
              <img src='../assets/temporales/img/$nombreCompleto'  style='width:200px; height:200px;'>
              <input type='hidden' id='txtimagem' name='txtimagen' value='$nombreCompleto'>
        ";
    }

?>