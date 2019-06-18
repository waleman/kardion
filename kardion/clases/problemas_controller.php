<?php 
require_once('conexion.php');

class problemas extends conexion{
 

    public function TipoProblemas(){
         $query="select * from problemas_tipo";
         $resp = parent::ObtenerRegistros($query);
         if(empty($resp)){
             return false;
         }else{
             return $resp;
         }
    }

    public function GuardarProblema($datos = array()){
        $date = date('Y-m-d');
        if(!empty($datos)){
            $Texto = $datos['Texto'];
            $UsuarioId = $datos['UsuarioId'];
            $TipoProblemaId = $datos['TipoProblemaId'];

            $query="INSERT INTO problemas
             (Texto,UsuarioId,TipoProblemaId,Fecha)
            values('$Texto','$UsuarioId','$TipoProblemaId','$date')";
            $resp = parent::NonQuery($query);
            if ($resp == 1 ){
                return true;
            }else{
                return false;
            }   

        }else{
            return false;
        }
    }

    public function ProblemaRespuestasEstado($usuarioId){
        $query = "select count(*) as cantidad from problemas_respuestas as pr , problemas as p
        where p.ProblemaId = pr.ProblemaId
        and p.UsuarioId = '$usuarioId'
        and pr.Visto = 0 ";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return 0;
        }else{
            return $resp[0]['cantidad'];
        }
    }

    public function ProblemasPorUsuario($usuarioId){
        $query = "select * from problemas
        where UsuarioId = '$usuarioId'";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return 0;
        }else{
            return $resp[0]['cantidad'];
        }
    }


    public function ListaProblemas($usuarioId){
        $query="select p.ProblemaId,p.Texto,p.Estado,p.Fecha,pt.Nombre as ProblemaTipo
        from problemas as p,problemas_tipo as pt
        where  p.TipoProblemaId = pt.ProblemaTipoId
        and p.UsuarioId = '$usuarioId'
        order by p.Fecha desc
        ";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return 0;
        }else{
            return $resp;
        }
    }



}
?>