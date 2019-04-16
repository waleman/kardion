<?php
require_once('conexion.php');

class dispositivos extends conexion{


public function dispositivos_companias($master){
    $query ="select a.Nombre,a.AparatoId,a.Serie,ac.Estado,ac.CentroId from aparatos_companias as ac,aparatos as a 
    where a.AparatoId = ac.AparatoId
    and ac.MasterCompaniaId = '$master'";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
         return false;
     }else{
         return $datos;
     }
}

public function dispositivos_centros($CentroId){
    $query ="select Nombre from centros
    where CentroId = '$CentroId'";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
         return false;
     }else{
         return $datos;
     }
}


public function dispositivo($aparatoId,$master){
    $query ="select ac.AsignadoId,a.Nombre,a.AparatoId,a.Serie,ac.Estado,ac.CentroId from aparatos_companias as ac,aparatos as a 
    where a.AparatoId = ac.AparatoId
    and ac.AparatoId = '$aparatoId'
    and ac.MasterCompaniaId = '$master'
    ";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
         return false;
     }else{
         return $datos;
     }
}


public function update_asignacion($AsignadoId,$CentroId,$Estado,$Usuario,$master ){
    $date =$date = date('Y-m-d');
    $query ="UPDATE aparatos_companias SET CentroId ='$CentroId',Estado = '$Estado',UM ='$Usuario',FM ='$date'  where AsignadoId ='$AsignadoId' and MasterCompaniaId='$master'";
    $datos = parent::NonQuery($query);
    if(empty($datos)){
         return false;
     }else{
         return $datos;
     }
}


public function Dispositivos_paciente($masterid){
    $query = "select a.AparatoId,a.Nombre,a.Serie,a.Imagen
    from aparatos_companias as ac, aparatos as a
     where ac.AparatoId =  a.AparatoId
    and ac.MasterCompaniaId = '$masterid'";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
         return false;
     }else{
         return $datos;
     }
}

public function modelos(){
    $query = "select * from aparatos_modelos";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
         return false;
     }else{
         return $datos;
     }
}


public function VerificarSiExiste($serie,$modelo){
    $query ="select count(*) as cantidad,AparatoId from aparatos where Serie = '$serie' and ModeloId ='$modelo'";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
         return false;
     }else{
         return $datos;
     }
}

public function verificarNoVinculado($serie){
    $query="select count(*) as cantidad from aparatos as a , aparatos_companias as ac
    where a.AparatoId = ac.AparatoId
    and a.Serie='$serie'";
    //print_r($query);
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
         return false;
     }else{
         return $datos[0]['cantidad'];
     }
}


public function vincular($master,$aparatoid,$usuario){
    $date = date('Y-m-d');
    $query= "insert into aparatos_companias
    (MasterCompaniaId,AparatoId,Estado,UC,FC)
    values('$master','$aparatoid','Sin asignar','$usuario','$date')";

    $datos = parent::NonQuery($query);
    if ($datos == 1 ){
        return true;
    }else{
        return false;
    }
}


//solo para Master usuario
public function Dispostivios(){
    $query ="
    select a.AparatoId,a.Nombre,a.Serie,a.Imagen,am.Nombre as Modelo,ae.Nombre as Estado,ae.TipoEstadoId
    from aparatos as a,aparatos_companias as ac,aparatos_modelos as am, aparatos_estado as ae 
    where a.AparatoId = ac.AparatoId
    and a.ModeloId = am.ModeloId
    and a.TipoEstadoId = ae.TipoEstadoId
    ";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
         return false;
     }else{
         return $datos;
     }
}



}

?>