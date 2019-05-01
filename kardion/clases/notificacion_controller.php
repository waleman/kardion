<?php 
require_once('conexion.php');

class notificacion extends conexion {

    public function ListaCorreos(){
        $query ="select * from notificacion";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
            return false;
        }else{
            return $datos;
        }
    }

    public function ListaCorreos_enviar(){
        $query ="select Correo from notificacion where Estado='Activo'";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
            return false;
        }else{
            return $datos;
        }
    }

    public function CambiarEstado($id,$estado){
        $query ="update notificacion set Estado = '$estado' where NotificacionId = '$id'";
        $datos = parent::NonQuery($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }
    }

    public function AgregarCorreo($correo,$usuario){
        $date = date('d-m-Y');
        $query="insert into notificacion (Correo,Estado,UC,FC)values('$correo','Activo','$usuario','$date') ";
        $datos = parent::NonQuery($query);
        //print_r($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }
    }



}


?>