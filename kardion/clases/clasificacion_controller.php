<?php
require_once("conexion.php");


class clasificacion extends conexion{


    function nombreramdom($length = 6) { 
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
    } 

    public function todos(){
        $query = "select u.UsuarioId,u.Usuario,u.Estado,u.Verificado,dr.Nombre from usuarios  as u , dr_perfil as dr 
        where dr.UsuarioId = u.UsuarioId
        and u.RolId = 3";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
             return false;
         }else{
             return $datos;
         }
    }

    public function obtenerClasificaciones(){
        $query ="select ClasificacionId,Nombre,Estado from pruebas_clasificacion ";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
            return false;
        }else{
            return $datos;
        }
    }

    public function obtenerClasificacionesActivas(){
        $query ="select ClasificacionId,Titulo,Icono from pruebas_clasificacion where Estado= 'Activo'";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
            return false;
        }else{
            return $datos;
        }
    }

    public function ObtenerClasificacion($id){
        $query ="select * from pruebas_clasificacion where ClasificacionId = '$id'";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
            return false;
        }else{
            return $datos;
        }
    }


    public function NombreClasificacion($id){
        $query ="select Titulo from pruebas_clasificacion where ClasificacionId = '$id'";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
            return false;
        }else{
            return $datos[0]['Titulo'];
        }
    }

    
    public function UltimoRegistro(){
        $query ="select ClasificacionId from pruebas_clasificacion order by ClasificacionId desc limit 1";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
            return false;
        }else{
            return $datos[0]['ClasificacionId'];
        }
    }

    public function GuardarClasificacion($datos){
        $tipo = $datos['tipo'];
        $titulo = $datos['titulo'];
        $icono  = $datos['icono'];
        $texto = $datos['texto'];
        $query ="insert into pruebas_clasificacion (Nombre,Estado,Titulo,Icono,Texto)values('$tipo','Activo','$titulo','$icono','$texto')";
        $datos = parent::NonQuery($query);
            if ($datos == 1 ){
                return true;
            }else{
                return false;
            }
    }

    public function EditarClasificacion($datos){
        $id= $datos['id'];
        $tipo = $datos['tipo'];
        $titulo = $datos['titulo'];
        $icono  = $datos['icono'];
        $texto = $datos['texto'];
        $estado = $datos['estado'];
        if($icono == ""){
            $query ="update pruebas_clasificacion set Nombre = '$tipo',Estado ='$estado',Titulo ='$titulo',Texto ='$texto' where ClasificacionId ='$id'";
        }else{
            $query ="update pruebas_clasificacion set Nombre = '$tipo',Estado ='$estado',Titulo ='$titulo',Texto ='$texto',Icono ='$icono' where ClasificacionId ='$id'";
        }
        $datos = parent::NonQuery($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }

    }



}





?>