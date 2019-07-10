<?php
require_once 'conexion.php';

class servidorArchivos extends conexion{

   public function buscarConexion(){
        $query = "select * from dropbox where Id = 1";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
                return false;
            }else{
                return $datos;
        }
   }


   public function guardar($key,$secret,$token,$appname,$megas){
    $query= "INSERT INTO dropbox
    (Id,Keyapp,Secret,Token,Appname,Megas)
    VALUES
    ('1','$key','$secret','$token','$appname','$megas')";

    
    $datos = parent::NonQuery($query);
    if ($datos == 1 ){
        return true;
    }else{
        return false;
    }
  }

  public function modificar($key,$secret,$token,$appname,$megas){
    $query= "UPDATE  dropbox SET
    Keyapp ='$key',Secret = '$secret',Token = '$token',Appname = '$appname',Megas = '$megas' where Id = 1 ";
    $datos = parent::NonQuery($query);
    if ($datos == 1 ){
        return true;
    }else{
        return false;
    }
  }

}

?>