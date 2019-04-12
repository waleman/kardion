<?php
require_once('conexion.php');

class configuracion extends conexion {


    public function crearMasterUsuario($direccion){
        $correo = "admin@kardion.es";
        $password = parent::encriptar("Kardion468*");
        $master= "kardion";
        $CodigoActivacion ="kardion";
        $verificar = $this->verificarOcupado($correo);
        if($verificar == 0){
            $query= "insert into usuarios 
            (Usuario,Password,RolId,Estado,MasterCompaniaId,CodigoActivacion)
            values
            ('$correo','$password','1','Activo','$master','$CodigoActivacion')";
            $datos = parent::NonQuery($query);
            if($datos){
                echo "Usuario creado exitosamente";
            }else{
                echo "Error al crear  usuario";
            }
        }else{
            echo "el usuario  ya esta creado";
        }
    }

    public function verificarOcupado($correo){
        $usuario = parent::security($correo);
        $query = "select count(*) as cantidad from usuarios where Usuario = '$usuario'";
        $datos = parent::ObtenerRegistros($query);
            if($datos[0]['cantidad'] >= 1){
               return true;
            }else{
               return false;
            }
   }


}


?>