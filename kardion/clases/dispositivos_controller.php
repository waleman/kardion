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
    $query = "select * from aparatos_modelos where Estado ='Activo'";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
         return false;
     }else{
         return $datos;
     }
}

public function estados(){
    $query = "select * from aparatos_estado";
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

public function ActualizarEstado($aparatoid,$usuario){
    $date = date('Y-m-d');
    $query= "update aparatos set TipoEstadoId='5',UM='$usuario',FM='$date' where AparatoId = '$aparatoid'";
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
    from aparatos as a,aparatos_modelos as am, aparatos_estado as ae 
    where  a.ModeloId = am.ModeloId
    and a.TipoEstadoId = ae.TipoEstadoId
    ";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
         return false;
     }else{
         return $datos;
     }
}

//solo para Master
public function datosDispositivo($id){
    $query= "select * from aparatos where AparatoId='$id'";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
         return false;
     }else{
         return $datos;
     }
}

// solo para master
public function editarDispositivo($id,$Nombre,$modelo,$estado,$imagen,$usuario){
    $date = date('Y-m-d');
        $query ="
        update aparatos set Nombre = '$Nombre',ModeloId='$modelo',TipoEstadoId='$estado',UM='$usuario',FM='$date' where AparatoId = '$id'
        ";
    $datos = parent::NonQuery($query);
    if ($datos == 1 ){
        return true;
    }else{
        return false;
    }
}

//solo para master

public function verDatosdeAsignacion($id){
    $query="select m.Nombre,ac.Estado,ac.FC from aparatos_companias as ac, mastercompania as m
    where ac.MasterCompaniaId = m.MasterCompaniaId
    and ac.AparatoId = '$id'";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
         return false;
     }else{
         return $datos;
     }
}

//solo para master 

public function desvincularDispositivo($id){
    $query = "delete from aparatos_companias where AparatoId='$id'";
    $datos = parent::NonQuery($query);
    if ($datos == 1 ){
        return true;
    }else{
        return false;
    }
}


public function crearDispositivo($Nombre,$serie,$modelo,$estado,$imagen,$usuario){
        $date = date('Y-m-d');
        $query ="
        insert into aparatos(Nombre,Serie,ModeloId,TipoEstadoId,Imagen,UC,FC)values('$Nombre','$serie','$modelo','$estado','$imagen','$usuario','$date');
        ";
    $datos = parent::NonQuery($query);
    if ($datos == 1 ){
        return true;
    }else{
        return false;
    }
}

public function verificar($serie){
    $query ="select count(*) as cantidad from aparatos where Serie = '$serie' ";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
         return false;
     }else{
         return $datos[0]['cantidad'];
     }
}





}

?>