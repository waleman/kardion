<?php 
require_once("conexion.php");

//Documetacion
//programador:  Jose wilfredo Aleman Giron
//fecha : 23/12/2018
//ubicacion : Donostia San Sebastian  España
//pagina web :www.wc-solutions.net
//Todos los derechos reservados ®
// class centros extiende de la clase conexion para poder utilzar sus metodos

class pruebas extends conexion{

    public function CrearPrueba($datos = array()){
            $codigo= $datos["codigo"];
            $personaid= $datos["personaid"];
            $centroid = $datos["centroid"];
            $aparatoid= $datos["aparatoid"];
            $fecha = $datos["fecha"];
            $altura= $datos["altura"];
            $peso= $datos["peso"];
            $tension= $datos["tension"];
            $frecuenciacardiaca= $datos["frecuenciacardiaca"];
            $momentomaximo= $datos["momentomaximo"];
            $fcmomemento= $datos["fcmomemento"];
            $fcprimerminuto= $datos["fcprimerminuto"];
            $fcsegudnominuto= $datos["fcsegudnominuto"];
            $pruebatipoid = $datos["pruebatipoid"];
            $usuarioid= $datos["usuarioid"];
            $sintomas =$datos['sintomas'];
            $dolorcabeza = $datos['dolorcabeza'];
            $mareo = $datos['mareo'];
            $nauseas = $datos['nauseas'];
            $faltaaire = $datos['faltaaire'];
            $dolorpecho  = $datos['dolorpecho'];
            $palpitaciones = $datos['palpitaciones'];
            $desmayo = $datos['desmayo'];
        $date = date('Y-m-d');
        $query= "insert into pruebas
        (codigo,PersonaId,CentroId,Fecha,PruebaEstadoId,PruebaTipoId,AparatoId,Altura,Peso,Tension,FrecuenciaCardiaca,MomentoMaximo,FCMomentoMaximo,FCPrimerMinuto,FCSegundoMinuto,FC,UC,Sintomas,DolorCabeza,Mareo,Nauseas,FaltaAire,DolorPecho,Palpitaciones,Desmayo)
        values('$codigo','$personaid','$centroid','$fecha','2','$pruebatipoid','$aparatoid','$altura','$peso','$tension','$frecuenciacardiaca','$momentomaximo','$fcmomemento','$fcprimerminuto','$fcsegudnominuto','$date','$usuarioid','$sintomas','$dolorcabeza','$mareo','$nauseas','$faltaaire','$dolorpecho','$palpitaciones','$desmayo')";
        //print_r($query);
        $datos = parent::NonQuery($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }
    }


    public function VerPruebasPendientes($master){
        $query="select pru.PruebaId,CONCAT(per.PrimerNombre,' ',per.PrimerApellido) as Persona,pru.Fecha,pt.Nombre as Prioridad ,c.Nombre as Centro,pe.Nombre as Estado,us.Usuario
        from pruebas as pru,personas as per,pruebas_tipos as pt,centros as c,pruebas_estados as pe,usuarios as us
        where pru.PersonaId = per.PersonaId
        and pru.PruebaTipoId = pt.PruebaTipoId
        and pru.CentroId = c.CentroId
        and pru.PruebaEstadoId = pe.PruebaEstadoId
        and per.PersonaId = us.PersonaId
        and (pru.PruebaEstadoId  = '1' or  pru.PruebaEstadoId  = '2' or  pru.PruebaEstadoId  = '3')
        and c.MasterCompaniaId = '$master'";
    }

    public function VerPruebasPendientesPorCentro($master){
        $query="select pru.PruebaId,CONCAT(per.PrimerNombre,' ',per.PrimerApellido) as Persona,pru.FC,pt.Nombre as Prioridad ,c.Nombre as Centro,pe.Nombre as Estado, pru.PruebaEstadoId,us.Usuario
        from pruebas as pru,personas as per,pruebas_tipos as pt,centros as c,pruebas_estados as pe,usuarios as us
        where pru.PersonaId = per.PersonaId
        and pru.PruebaTipoId = pt.PruebaTipoId
        and pru.CentroId = c.CentroId
        and pru.PruebaEstadoId = pe.PruebaEstadoId
        and per.PersonaId = us.PersonaId
        and (pru.PruebaEstadoId  = '1' or  pru.PruebaEstadoId  = '2' or  pru.PruebaEstadoId  = '3')
        and c.MasterCompaniaId = '$master'";
       // print_r($query);
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
 
    }

    
    public function VerPruebasFinalizadas($master){
        $query="select pru.PruebaId,CONCAT(per.PrimerNombre, ' ',per.PrimerApellido) as Persona,pru.FC,pt.Nombre as Prioridad ,c.Nombre as Centro,pe.Nombre as Estado, pru.PruebaEstadoId,pru.FM,us.Usuario as UC
        from pruebas as pru,personas as per,pruebas_tipos as pt,centros as c,pruebas_estados as pe , pruebas_resueltas as pr,usuarios as us 
        where pru.PersonaId = per.PersonaId
        and pru.PruebaTipoId = pt.PruebaTipoId
        and pru.CentroId = c.CentroId
        and pru.PruebaEstadoId = pe.PruebaEstadoId
        and pru.PruebaId = pr.PruebaId
        and pru.UC = us.UsuarioId 
        and pru.PruebaEstadoId  = '5'
        and c.MasterCompaniaId = '$master'";
       // print_r($query);
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
 
    }

    public function VerPruebasFinalizadasPorCentrosPermitidos($master,$usuario){
        $query="
        select pru.PruebaId,CONCAT(per.PrimerNombre, ' ',per.PrimerApellido) as Persona,pru.FC,pt.Nombre as Prioridad ,c.Nombre as Centro,pe.Nombre as Estado,pru.PruebaEstadoId,pru.FM ,us.Usuario as UC
        from pruebas as pru,personas as per,pruebas_tipos as pt,centros as c,pruebas_estados as pe,usuarios as us 
        where pru.PersonaId = per.PersonaId 
        and pru.PruebaTipoId = pt.PruebaTipoId 
        and pru.CentroId = c.CentroId 
        and pru.PruebaEstadoId = pe.PruebaEstadoId 
        and pru.UC = us.UsuarioId 
        and pru.PruebaEstadoId = '5' 
        and c.MasterCompaniaId = '$master' 
        and c.CentroId in (select CentroId from usuarios_permisos_centros where UsuarioId= '$usuario')";
        $resp = parent::ObtenerRegistros($query);
      // print_r($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
    }


    public function VerPruebasPendientesPorCentrosPermitidos($master,$usuario){
        $query="select pru.PruebaId,CONCAT(per.PrimerNombre, ' ',per.PrimerApellido) as Persona,pru.FC,pt.Nombre as Prioridad ,c.Nombre as Centro,pe.Nombre as Estado,pru.PruebaEstadoId,us.Usuario
        from pruebas as pru,personas as per,pruebas_tipos as pt,centros as c,pruebas_estados as pe,usuarios as us
        where pru.PersonaId = per.PersonaId
        and pru.PruebaTipoId = pt.PruebaTipoId
        and pru.CentroId = c.CentroId
        and pru.PruebaEstadoId = pe.PruebaEstadoId
        and per.PersonaId = us.PersonaId
        and (pru.PruebaEstadoId  = '1' or  pru.PruebaEstadoId  = '2' or  pru.PruebaEstadoId  = '3')
        and c.MasterCompaniaId = '$master'
        and c.CentroId in (select CentroId from usuarios_permisos_centros where UsuarioId= '$usuario')";
        $resp = parent::ObtenerRegistros($query);
        //print_r($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
    }

    public function VerPruebasDR(){
        $query="select pru.PruebaId,CONCAT(per.PrimerNombre,  ' ',per.PrimerApellido) as Persona,pru.FC,pt.Nombre as Prioridad ,c.Nombre as Centro,pe.Nombre as Estado,pru.PruebaEstadoId,pru.PruebaTipoId
        from pruebas as pru,personas as per,pruebas_tipos as pt,centros as c,pruebas_estados as pe
        where pru.PersonaId = per.PersonaId
        and pru.PruebaTipoId = pt.PruebaTipoId
        and pru.CentroId = c.CentroId
        and pru.PruebaEstadoId = pe.PruebaEstadoId
        and pru.PruebaEstadoId  = '2'
        order by pru.PruebaTipoId asc,pru.FC asc
        ";
        $resp = parent::ObtenerRegistros($query);
        //print_r($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
    }


    public function VerPruebasDRenrevision($drid){
        $query="select pru.PruebaId,CONCAT(per.PrimerNombre, ' ',per.PrimerApellido) as Persona,pru.FC,pt.Nombre as Prioridad ,c.Nombre as Centro,pe.Nombre as Estado,pru.PruebaEstadoId,pru.PruebaTipoId
        from pruebas as pru,personas as per,pruebas_tipos as pt,centros as c,pruebas_estados as pe ,pruebas_resueltas as pr
        where pru.PersonaId = per.PersonaId
        and pru.PruebaTipoId = pt.PruebaTipoId
        and pru.CentroId = c.CentroId
        and pru.PruebaEstadoId = pe.PruebaEstadoId
        and pru.PruebaId = pr.PruebaId
        and pru.PruebaEstadoId  = '5'
        and pr.UsuarioId = '$drid'
        order by pru.PruebaTipoId asc,pru.FC asc
        ";
        $resp = parent::ObtenerRegistros($query);
        //print_r($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
    }

    public function contarpruebas(){
        $query="select count(*) as cantidad from pruebas where PruebaEstadoId = 2";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp[0]["cantidad"];
        }
    }


    public function asignar($usuariId){
        $query="select PruebaId from pruebas where PruebaEstadoId =2 and (PruebaTipoId = 2 or PruebaTipoId = 3) order by PruebaTipoId asc,FC asc limit 1";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return "error al seleccionar prueba"; //error al seleccionar Prueba
        }else{
                $PruebaId =$resp[0]["PruebaId"];
                $query2 = "update pruebas set PruebaEstadoId = '3' where PruebaId= '$PruebaId'";
                parent::NonQuery($query2);
                $fechayhora = date('Y-m-d h:i:s a', time());
                $query3 = "INSERT INTO pruebas_asignadas (Fecha,UsuarioId,PruebaId,AsignadasEstadoId) values('$fechayhora','$usuariId','$PruebaId','1')";
               // print_r($query3);
                $datos3 = parent::NonQuery($query3);                 
        }
    }

    public function VerificarSoloUnaPrueba($usuariId){
        $query ="select count(*) as cantidad from pruebas_asignadas where UsuarioId='$usuariId' and AsignadasEstadoId = '1'";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp[0]["cantidad"];
        }
    }

    public function BuscarCodigoPruebaAsignada($usuariId){
        $query ="select PruebaId from pruebas_asignadas where UsuarioId='$usuariId' and AsignadasEstadoId = '1'";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp[0]["PruebaId"];
        }
    }


    public function BuscarPruebaAsignada($pruebaid){
        $query = "select * from pruebas where PruebaId='$pruebaid'";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
    }

    public function BuscarArchivos($codigo){
        $query = "select * from pruebas_archivos where Codigo='$codigo'";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
    }


    public function Conclusiones(){
        $query ="select * from conclusiones";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
    }


    public function verificar_resultado($id){
        $query = "select count(*) as cantidad from pruebas_resueltas where PruebaId ='$id'";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp[0]["cantidad"];
        }
    }

    public function modificar_resultado($datos= array()){
        $date = date('Y-m-d');
        $pruebaid = $datos['pruebaid'];
        $usuarioid = $datos['usuarioid'];
        $codigo = $datos['codigo'];
        $frecuenciacardiaca = $datos['frecuenciacardiaca'];
        $fcmomentomaximo = $datos['fcmomentomaximo'];
        $fcprimerminuto = $datos['fcprimerminuto'];
        $fcsegundominuto = $datos['fcsegundominuto'];
        $prediagnostico = $datos['prediagnostico'];
        $conclusiones = $datos['conclusiones'];
        $propio = $datos['propio'];
        $propiodireccion = $datos['propiodireccion'];
        $query = "update pruebas_resueltas set FrecuenciaCardiaca='$frecuenciacardiaca',FCMomentoMaximo='$fcmomentomaximo',FCPrimerMinuto='$fcprimerminuto',FCSegundoMinuto='$fcsegundominuto',PreDiagnostico='$prediagnostico',Conclusiones='$conclusiones',FM='$date',UM='$usuarioid',Propio='$propio',PropioDireccion='$propiodireccion' where PruebaId ='$pruebaid'";
        //print_r($query);
        parent::NonQuery($query);
    }

    public function guardar_resulado($datos = array()){
        $date = date('Y-m-d');
        $pruebaid = $datos['pruebaid'];
        $usuarioid = $datos['usuarioid'];
        $codigo = $datos['codigo'];
        $frecuenciacardiaca = $datos['frecuenciacardiaca'];
        $fcmomentomaximo = $datos['fcmomentomaximo'];
        $fcprimerminuto = $datos['fcprimerminuto'];
        $fcsegundominuto = $datos['fcsegundominuto'];
        $prediagnostico = $datos['prediagnostico'];
        $conclusiones = $datos['conclusiones'];
        $propio = $datos['propio'];
        $propiodireccion = $datos['propiodireccion'];

        $query = "insert into pruebas_resueltas (PruebaId,UsuarioId,Codigo,FrecuenciaCardiaca,FCMomentoMaximo,FCPrimerMinuto,FCSegundoMinuto,PreDiagnostico,Conclusiones,FC,UC,Propio,PropioDireccion)
        values('$pruebaid','$usuarioid','$codigo','$frecuenciacardiaca','$fcmomentomaximo','$fcprimerminuto','$fcsegundominuto','$prediagnostico','$conclusiones','$date','$usuarioid','$propio','$propiodireccion')";
        //print_r($query);
        parent::NonQuery($query);
    }


    public function pruebaporid($id){
        $query = "select * from pruebas where PruebaId='$id'";
        $datos =  parent::ObtenerRegistros($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }
    }


    public function Resultadoporid($pruebaId){
        $query ="select * from pruebas_resueltas where PruebaId ='$pruebaId'";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
    }


    public function finalizarPruebaasignada($pruebaid){
        $query = "update  pruebas_asignadas set AsignadasEstadoId = '2' where PruebaId = '$pruebaid'";
        $datos = parent::NonQuery($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }
    }

    public function finalizarprueba($pruebaid,$usuario){
        $date = date('d-m-Y');
        $query ="update pruebas set PruebaEstadoId = '5',UM ='$usuario',FM='$date' where PruebaId = '$pruebaid'";
        //print_r( $query);
        $datos = parent::NonQuery($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }
    }


    public function PruebasPersona($personaId){
        $query = "select pru.PruebaId,CONCAT(per.PrimerNombre, ' ' ,per.PrimerApellido) as Persona,pru.FC,pt.Nombre as Prioridad,
        c.Nombre as Centro,pe.Nombre as Estado,pru.PruebaEstadoId,pru.Archivo
        from pruebas as pru,personas as per,pruebas_tipos as pt,centros as c,pruebas_estados as pe
        where pru.PersonaId = per.PersonaId
        and pru.PruebaTipoId = pt.PruebaTipoId
        and pru.CentroId = c.CentroId
        and pru.PruebaEstadoId = pe.PruebaEstadoId
        and (pru.PruebaEstadoId  = '2' or  pru.PruebaEstadoId  = '5' or  pru.PruebaEstadoId= '3' or  pru.PruebaEstadoId= '4' )
        and pru.PersonaId = '$personaId'";
         //print_r($query);
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }

    }


    public function ActualizarArchivo($pruebaid,$archivo){
        $date = date('Y-m-d');
        $query ="update pruebas set Archivo = '$archivo',FM ='$date' where PruebaId = '$pruebaid'";
        $datos = parent::NonQuery($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }
    }

    public function VefiricarArchivo($codigo){
        $query ="select count(*) as cantidad from pruebas_archivos where Codigo = '$codigo'";
        $resp = parent::ObtenerRegistros($query);

        if(empty($resp)){
            return false;
        }else{
            return $resp[0]['cantidad'];
        }
    }

    public function EnviarMaiilPruebaFinalizada($pruebaId,$email){
        $_SESSION['pruebamail'] = $pruebaId;
        ob_start();
        require '../utilidades/mails/pruebafinalizada.php';
        $html = ob_get_clean();

        $para      = $email;
        $titulo    = 'El resultado de su prueba esta listo - KARDI-ON';
        $mensaje   = $html;

        $cabeceras = 'From: kardion@kardion.es' . "\r\n" .
        'Reply-To: no-reply@kardion.com' . "\r\n" .
        'Content-type:text/html'. "\r\n" .
        'X-Mailer: PHP/' . phpversion();

        mail($para, $titulo, $mensaje, $cabeceras);

      

    }

}