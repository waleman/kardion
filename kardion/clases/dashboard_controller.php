<?php 
require_once("conexion.php");


class dashboard extends conexion{

   // Dashboard empresas 

    public function Emp_cantidad_centros($master){
        $query ="select count(*) as cantidad from centros where MasterCompaniaId='$master'";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
            return false;
        }else{
            return $datos[0]['cantidad'];
        }
    }

    public function Emp_cantidad_usuarios($master){
        $query ="select count(*) as cantidad from usuarios where MasterCompaniaId='$master'";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
            return false;
        }else{
            return $datos[0]['cantidad'];
        }
    }

    public function Emp_cantidad_aparatos($master){
        $query ="select count(*) as cantidad from aparatos_companias where MasterCompaniaId='$master'";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
            return false;
        }else{
            return $datos[0]['cantidad'];
        }
    }

    public function Emp_cantidad_pruebasFinalizadas($master){  
            $query="select count(*) as cantidad from pruebas  as pru , centros as ct
            where  pru.CentroId = ct.CentroId
            and pru.PruebaEstadoId  = '5'
            and MasterCompaniaId = '$master'";
            //print_r($query);
            $resp = parent::ObtenerRegistros($query);
            if(empty($resp)){
                return false;
            }else{
                return $resp[0]['cantidad'];
            }
    }
    public function Emp_cantidad_pruebas($master){  
        $query="select count(*) as cantidad from pruebas  as pru , centros as ct
        where  pru.CentroId = ct.CentroId
        and MasterCompaniaId = '$master'";
        //print_r($query);
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp[0]['cantidad'];
        }
    }

    public function Emp_cantidad_pruebas_mes($master,$inico ,$fin){  
        $query="select count(*) as cantidad from pruebas  as pru , centros as ct
        where  pru.CentroId = ct.CentroId
        and MasterCompaniaId = '$master'
        and pru.FC  between  '$inico' and '$fin'";
        // print_r($query);
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp[0]['cantidad'];
        }
    }

    public function Emp_cantidad_pruebasFinalizadas_mes($master,$inico ,$fin){  
        $query="select count(*) as cantidad from pruebas  as pru , centros as ct
        where  pru.CentroId = ct.CentroId
        and MasterCompaniaId = '$master'
        and pru.PruebaEstadoId  = '5'
        and pru.FC  between  '$inico' and '$fin'";
        // print_r($query);
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp[0]['cantidad'];
        }
    }

    // Dashboard doctores
    
    public function Dr_cantidad_pruebasfinalizadas($usuarioId){
        $query="
            select count(*) as cantidad from pruebas_asignadas as pa , pruebas as p
            where  pa.PruebaId = p.PruebaId
            and  UsuarioId = '$usuarioId'
            and AsignadasEstadoId=2
        ";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp[0]['cantidad'];
        }
    }

    public function Dr_cantidad_pruebasfinalizadas_mes($usuarioId,$incio,$fin){
        $query="
            select count(*) as cantidad from pruebas_asignadas as pa , pruebas as p
            where  pa.PruebaId = p.PruebaId
            and  pa.UsuarioId = '$usuarioId'
            and  pa.AsignadasEstadoId=2
            and  pa.Fecha between  '$incio' and '$fin'
        ";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp[0]['cantidad'];
        }
    }



    

}

?>
