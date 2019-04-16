<?php


require_once("conexion.php");

class roles extends conexion {


    public function selectRol($rol){
        $direccion = "";
        switch ($rol) {
            case 1: //superusuario
            $direccion = "portal_master.php";
                 break;
            case 2://administrador
            $direccion = "dashboard_.php";
                 break;
            case 3: //DR
                $direccion = "portal_dr.php";
                break;
            case 4: // popietario
                $direccion = "dashboard.php";
                break;
            case 5: //asistente
                $direccion = "dashboard.php";
                break;
            case 6://recepcion
                $direccion = "dashboard.php";
                break;
            case 7: //paciente
                $direccion = "portal_cliente.php";
                break;
        }
        return $direccion;
    }


    public function buscarpermisos($rol,$arraypermisos){
        if (in_array($rol, $arraypermisos)) {
            return true;
        }else{
            return false;
        }
    }


    public function buscarCentrosPermitidos($usuarioId){
        $query="select * from usuarios_permisos_centros where UsuarioId='$usuarioId'";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
            return false;
        }else{
            return $datos;
        }
    }





}


?>