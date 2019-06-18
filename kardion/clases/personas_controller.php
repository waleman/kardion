<?php 
require_once("conexion.php");

class personas extends conexion{


    public function BuscarporEmail($email){
        $query="select * from personas where Correo = '$email'";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
    }

    public function BuscarId($email){
        $query="select PersonaId from personas where Correo = '$email'";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp[0]['PersonaId'];
        }
    }

    public function BuscarUna($id){
        $query="select * from personas where PersonaId = '$id'";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp[0];
        }
    }

    public function TipoDocumento(){
        $query="select * from tipo_documento";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
    }


    public function BuscarSiExisteUsuario($email){
        $query="select * from usuarios where Usuario ='$email'";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
    }


    public function EditarPersona($datos = array() , $usuario){
        $date = date('d-m-Y');
        if(!empty($datos)){
            $personaId = $datos['personaId'];
            $genero = $datos['genero'];
            $primernombre = $datos['primernombre'];
            $segundonombre = $datos['segundonombre'];
            $primerapellido = $datos['primerapellido'];
            $segundoapellido = $datos['segundoapellido'];
            $tipodocumento = $datos['tipodocumento'];
            $numerodocumento = $datos['numerodocumento'];
            $movil = $datos['movil'];
            $telefono = $datos['telefono'];
            $fechanac = $datos['fechanac'];
            $codigopostal = $datos['codigopostal'];
            $direccion = $datos['direccion'];

            $query="UPDATE personas set
             PrimerNombre = '$primernombre',SegundoNombre = '$segundonombre',PrimerApellido = '$primerapellido', SegundoApellido = '$segundoapellido',
             Direccion = '$direccion',Telefono = '$telefono', Movil = '$movil', NumeroDocumento = '$numerodocumento', TipoDocumentoId = '$tipodocumento',
             FechaNacimiento = '$fechanac',Sexo = '$genero',CodigoPostal = '$codigopostal',FM = '$date', UM = '$usuario' where PersonaId ='$personaId'";
             //print_r($query);
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

    public function EditarPersona2($datos = array() , $usuario){
        $date = date('d-m-Y');
        if(!empty($datos)){
            $personaId = $datos['personaId'];
            $genero = $datos['genero'];
            $primernombre = $datos['primernombre'];
            $segundonombre = $datos['segundonombre'];
            $primerapellido = $datos['primerapellido'];
            $segundoapellido = $datos['segundoapellido'];
            $correo = $datos['correo'];
            $query="UPDATE personas set
             PrimerNombre = '$primernombre',SegundoNombre = '$segundonombre',PrimerApellido = '$primerapellido', SegundoApellido = '$segundoapellido',
             Sexo = '$genero',Correo = '$correo',FM = '$date', UM = '$usuario' where PersonaId ='$personaId'";
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

    public function GuardarPersona($datos = array()){
        $date = date('Y-m-d');
        if(!empty($datos)){
            $email = $datos['email'];
            $genero = $datos['genero'];
            $primernombre = $datos['primernombre'];
            $segundonombre = $datos['segundonombre'];
            $primerapellido = $datos['primerapellido'];
            $segundoapellido = $datos['segundoapellido'];
            $tipodocumento = $datos['tipodocumento'];
            $numerodocumento = $datos['numerodocumento'];
            $movil = $datos['movil'];
            $telefono = $datos['telefono'];
            $fechanac = $datos['fechanac'];
            $codigopostal = $datos['codigopostal'];
            $direccion = $datos['direccion'];
            $pais = $datos['pais'];
            $provincia = $datos['provincia'];
            $ciudad = $datos['ciudad'];
            $usuarioCrea = $datos['usuario'];

            $query="INSERT INTO personas
             (PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,Direccion,Telefono,Movil,Correo,NumeroDocumento,TipoDocumentoId,FechaNacimiento,Sexo,PaisId,ProvinciaId,MunicipioId,CodigoPostal,FC,UC)
            values('$primernombre','$segundonombre','$primerapellido','$segundoapellido','$direccion','$telefono','$movil','$email','$numerodocumento','$tipodocumento','$fechanac','$genero','$pais','$provincia','$ciudad','$codigopostal','$date','$usuarioCrea')";
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


    public function buscarCorreoenUso($correo){
        $query="select count(*) as cantidad from usuarios where Usuario ='$correo'";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp[0]['cantidad'];
        }
    }

 

    public function Guardar_antecedentes($_datos = array()){
              $Altura = $_datos['Altura'];
              $Peso = $_datos['Peso'];
              $Temperatura = $_datos['Temperatura'];
              $Tension = $_datos['Tension'];
              $AntecedentesHiper = $_datos['AntecedentesHiper'];
              $AntecedentesDiabetes = $_datos['AntecedentesDiabetes'];
              $AntecedentesCardiopatia = $_datos['AntecedentesCardiopatia'];
              $AntecendentesOtro = $_datos['AntecendentesOtro'];
              $HabitosCafe = $_datos['HabitosCafe'];
              $HabitosTabaco = $_datos['HabitosTabaco'];
              $HabitosAlcohol = $_datos['HabitosAlcohol'];
              $HabitosOtro = $_datos['HabitosOtro'];
              $Comentarios = $_datos['Comentarios'];
              $Medicamento = $_datos['Medicamento'];
              $PersonaId = $_datos['PersonaId'];
              $fecha = date('Y-m-d');
                $query = "insert into personas_antecedentes (Altura,Peso,Temperatura,Tension,AntecedentesHiper,AntecedentesDiabetes,AntecedentesCardiopatia,AntecendentesOtro,HabitosCafe,HabitosTabaco,HabitosAlcohol,HabitosOtro,Comentarios,Medicamento,UC,FC,PersonaId)
                values('$Altura','$Peso','$Temperatura','$Tension','$AntecedentesHiper','$AntecedentesDiabetes','$AntecedentesCardiopatia','$AntecendentesOtro','$HabitosCafe','$HabitosTabaco','$HabitosAlcohol','$HabitosOtro','$Comentarios','$Medicamento','$PersonaId','$fecha','$PersonaId')
                ";
            $resp = parent::NonQuery($query);
            if ($resp == 1 ){
                return true;
            }else{
                return false;
            }   

    } 
    
    public function Buscar_antecedentes($personaId){
        $query ="select * from personas_antecedentes where PersonaId = '$personaId'  order by AntecedenteId desc limit 1";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
    }

 
    public function nuevapersona_register($primernombre,$primerapellido,$correo,$fechanac,$genero){
        $query ="insert into personas (PrimerNombre,PrimerApellido,Correo,FechaNacimiento,Sexo)values('$primernombre','$primerapellido','$correo','$fechanac','$genero')";
        //print_r($query);
        $resp = parent::NonQuery($query);
            if ($resp == 1 ){
                return true;
            }else{
                return false;
            }   
    }

    public function buscarTodaslasPersonas(){
        $query="select  CONCAT(
            IFNULL(CONCAT(p.PrimerNombre, ' '), ''),
            IFNULL(CONCAT(p.SegundoNombre, ' '), ''),
            IFNULL(CONCAT(p.PrimerApellido, ' '), ''),
            IFNULL(CONCAT(p.SegundoApellido, ' '), ''))As Nombre ,p.Correo,u.Estado,p.PersonaId from personas as p , usuarios as u
            where p.PersonaId = u.PersonaId";
            $resp = parent::ObtenerRegistros($query);
            if(empty($resp)){
                return false;
            }else{
                return $resp;
            }
    }

    // para hacer busquedas de pacientes
    public function Like($parametro){
        $query="
        select  CONCAT(
            IFNULL(CONCAT(p.PrimerNombre, ' '), ''),
            IFNULL(CONCAT(p.SegundoNombre, ' '), ''),
            IFNULL(CONCAT(p.PrimerApellido, ' '), ''),
            IFNULL(CONCAT(p.SegundoApellido, ' '), ''))As Nombre ,p.Correo,u.Estado,p.PersonaId from personas as p , usuarios as u
            where p.PersonaId = u.PersonaId
            and (p.PrimerNombre like '%$parametro%'
			or  p.SegundoNombre like '%$parametro%'
            or  p.PrimerApellido like '%$parametro%'
            or p.SegundoApellido like '%$parametro%'
            or u.Usuario like '%$parametro%')
        ";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
    }


    public function buscarPersonasPorCompania($Master){
        $query="
        select u.UsuarioId,u.Usuario,u.Estado, CONCAT(
                    IFNULL(CONCAT(p.PrimerNombre, ' '), ''),
                    IFNULL(CONCAT(p.SegundoNombre, ' '), ''),
                    IFNULL(CONCAT(p.PrimerApellido, ' '), ''),
                    IFNULL(CONCAT(p.SegundoApellido, ' '), ''))As Nombre,
                    p.PersonaId,
                    u.FC
        from usuarios  as u , personas as p
        where u.UC in (select UsuarioId from usuarios where MasterCompaniaId = '$Master')
        and p.PersonaId = u.PersonaId
        and u.Estado = 'Pendiente'";
        //print_r($query);
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
    }

 
}


?>