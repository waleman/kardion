<?php



 if(isset($_GET['id'])){
        $direccion= $_GET['id'];

    
            $path_a_tu_doc="../public/pruebas";
            $enlace = $path_a_tu_doc."/".$direccion;
            header ("Content-Disposition: attachment; filename=".$direccion." ");
            header ("Content-Type: application/octet-stream");
            header ("Content-Length: ".filesize($enlace));
            readfile($enlace);
       


    }else{
        echo"el archivo que intestas acceder no esta disponible";
    }

?>