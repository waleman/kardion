<?php 
require_once('conexion.php');

class dr extends conexion{

    function buscar($id){
        $query = "select * from dr_perfil where UsuarioId = '$id'";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
             return false;
         }else{
             return $datos;
         }
    }

    function verificar($id){
        $query = "select count(*) as cantidad from dr_perfil where UsuarioId = '$id'";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
             return false;
         }else{
             return $datos[0]['cantidad'];
         }
    }

    function guardar($datos = array()){
        $nombre  =$datos['nombre'];
        $titulo  =$datos['titulo'];
        $correo  =$datos['correo'];
        $telefono =$datos['telefono'];
        $colegiado =$datos['colegiado'];
        $firma  =$datos['firma'];
        $usuario = $datos['usuario'];
        $query= "insert into dr_perfil (Nombre,Titulo,Correo,Telefono,NoColegiado,Firma,UsuarioId)values('$nombre','$titulo','$correo','$telefono','$colegiado','$firma','$usuario')";
       // print_r($query);
        $datos = parent::NonQuery($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }
    }

    function editar($datos = array()){
        $nombre  =$datos['nombre'];
        $titulo  =$datos['titulo'];
        $correo  =$datos['correo'];
        $telefono =$datos['telefono'];
        $colegiado =$datos['colegiado'];
        $firma  =$datos['firma'];
        $usuario = $datos['usuario'];
        $query= "update dr_perfil set Nombre='$nombre',Titulo='$titulo',Correo='$correo',Telefono='$telefono',NoColegiado='$colegiado',Firma='$firma'where UsuarioId='$usuario'";
        // print_r($query);
         $datos = parent::NonQuery($query);
         if ($datos == 1 ){
             return true;
         }else{
             return false;
         }
    }

    function nombreramdom($length = 10) { 
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


    public function verificarDr($id,$estado){
        if($estado == 0) {
            $estado = 1;
        }else{
            $estado = 0;
        }
        $query ="update usuarios set verificado = '$estado' where UsuarioId= '$id'";
        $datos = parent::NonQuery($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }
    }

}

?>