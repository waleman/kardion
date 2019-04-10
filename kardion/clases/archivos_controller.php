<?php 
require_once('conexion.php');

class archivos extends conexion{

    public function ObtenerAnexos($pruebaid){
        $query =" select * from pruebas_anexos where PruebaId = '$pruebaid'";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
             return false;
         }else{
             return $datos;
         }
    }

    public function borrarAnexosBD($pruebaid){
        $query =" delete from pruebas_anexos where PruebaId = '$pruebaid'";
        $resp = parent::NonQuery($query);
        if ($resp == 1 ){
            return true;
        }else{
            return false;
        }   
    }
}


?>