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
            $comentario = $datos['comentario'];
            $clasificacion = $datos['clasificacion'];
        $date = date('Y-m-d');
        $query= "insert into pruebas
        (codigo,PersonaId,CentroId,Fecha,PruebaEstadoId,PruebaTipoId,AparatoId,Altura,Peso,Tension,FrecuenciaCardiaca,MomentoMaximo,FCMomentoMaximo,FCPrimerMinuto,FCSegundoMinuto,FC,UC,Sintomas,DolorCabeza,Mareo,Nauseas,FaltaAire,DolorPecho,Palpitaciones,Desmayo,Comentario,ClasificacionId)
        values('$codigo','$personaid','$centroid','$fecha','2','$pruebatipoid','$aparatoid','$altura','$peso','$tension','$frecuenciacardiaca','$momentomaximo','$fcmomemento','$fcprimerminuto','$fcsegudnominuto','$date','$usuarioid','$sintomas','$dolorcabeza','$mareo','$nauseas','$faltaaire','$dolorpecho','$palpitaciones','$desmayo','$comentario','$clasificacion')";
        //print_r($query);
        $datos = parent::NonQuery($query);
        if ($datos == 1 ){
            $this->EnviarMailPruebaPublicada($centroid);
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
        and ( pru.PruebaEstadoId  = '2' or  pru.PruebaEstadoId  = '3')
        and c.MasterCompaniaId = '$master'";
    }

    public function VerPruebasPendientesPorCentro($master){
        $query="select pru.PruebaId,CONCAT(per.PrimerNombre,' ',per.PrimerApellido) as Persona,pru.FC,pt.Nombre as Prioridad ,c.Nombre as Centro,pe.Nombre as Estado, pru.PruebaEstadoId,us.Usuario, clas.Titulo
        from pruebas as pru,personas as per,pruebas_tipos as pt,centros as c,pruebas_estados as pe,usuarios as us, pruebas_clasificacion as clas
        where pru.PersonaId = per.PersonaId
        and pru.PruebaTipoId = pt.PruebaTipoId
        and pru.CentroId = c.CentroId
        and pru.PruebaEstadoId = pe.PruebaEstadoId
        and per.PersonaId = us.PersonaId
        and pru.ClasificacionId = clas.ClasificacionId
        and ( pru.PruebaEstadoId  = '2' or  pru.PruebaEstadoId  = '3')
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
        $query="select pru.PruebaId,CONCAT(per.PrimerNombre, ' ',per.PrimerApellido) as Persona,pru.FC,pt.Nombre as Prioridad ,c.Nombre as Centro,pe.Nombre as Estado, pru.PruebaEstadoId,pru.FM,us.Usuario as UC, clas.Titulo
        from pruebas as pru,personas as per,pruebas_tipos as pt,centros as c,pruebas_estados as pe , pruebas_resueltas as pr,usuarios as us , pruebas_clasificacion as clas
        where pru.PersonaId = per.PersonaId
        and pru.PruebaTipoId = pt.PruebaTipoId
        and pru.CentroId = c.CentroId
        and pru.PruebaEstadoId = pe.PruebaEstadoId
        and pru.PruebaId = pr.PruebaId
        and pru.UC = us.UsuarioId 
        and pru.PruebaEstadoId  = '5'
        and pru.ClasificacionId = clas.ClasificacionId
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
        select pru.PruebaId,CONCAT(per.PrimerNombre, ' ',per.PrimerApellido) as Persona,pru.FC,pt.Nombre as Prioridad ,c.Nombre as Centro,pe.Nombre as Estado,pru.PruebaEstadoId,pru.FM ,us.Usuario as UC, clas.Titulo
        from pruebas as pru,personas as per,pruebas_tipos as pt,centros as c,pruebas_estados as pe,usuarios as us , pruebas_clasificacion as clas
        where pru.PersonaId = per.PersonaId 
        where pru.PersonaId = per.PersonaId 
        and pru.PruebaTipoId = pt.PruebaTipoId 
        and pru.CentroId = c.CentroId 
        and pru.PruebaEstadoId = pe.PruebaEstadoId 
        and pru.UC = us.UsuarioId 
        and pru.PruebaEstadoId = '5' 
        and c.MasterCompaniaId = '$master' 
        and pru.ClasificacionId = clas.ClasificacionId
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
        $query="select pru.PruebaId,CONCAT(per.PrimerNombre, ' ',per.PrimerApellido) as Persona,pru.FC,pt.Nombre as Prioridad ,c.Nombre as Centro,pe.Nombre as Estado,pru.PruebaEstadoId,us.Usuario, clas.Titulo
        from pruebas as pru,personas as per,pruebas_tipos as pt,centros as c,pruebas_estados as pe,usuarios as us, pruebas_clasificacion as clas
        where pru.PersonaId = per.PersonaId
        and pru.PruebaTipoId = pt.PruebaTipoId
        and pru.CentroId = c.CentroId
        and pru.PruebaEstadoId = pe.PruebaEstadoId
        and per.PersonaId = us.PersonaId
        and ( pru.PruebaEstadoId  = '2' or  pru.PruebaEstadoId  = '3')
        and c.MasterCompaniaId = '$master'
        and pru.ClasificacionId = clas.ClasificacionId
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
        $query="select pru.PruebaId,CONCAT(per.PrimerNombre,  ' ',per.PrimerApellido) as Persona,pru.FC,pt.Nombre as Prioridad ,c.Nombre as Centro,pe.Nombre as Estado,pru.PruebaEstadoId,pru.PruebaTipoId, clas.Titulo
        from pruebas as pru,personas as per,pruebas_tipos as pt,centros as c,pruebas_estados as pe, pruebas_clasificacion as clas
        where pru.PersonaId = per.PersonaId
        and pru.PruebaTipoId = pt.PruebaTipoId
        and pru.CentroId = c.CentroId
        and pru.PruebaEstadoId = pe.PruebaEstadoId
        and pru.PruebaEstadoId  = '2'
        and pru.ClasificacionId = clas.ClasificacionId
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

    public function pruebaporid2($id){
        $query = "select pru.PruebaId,pru.FC,pru.FM,p.Sexo,p.FechaNacimiento,p.Correo,pe.Nombre as Estado,C.Nombre as Centro,CONCAT(p.PrimerNombre, ' ' ,p.PrimerApellido) as Persona,p.Correo,pru.Motivo,u.Usuario 
        from pruebas as pru , personas as p , centros as c,pruebas_estados as pe , usuarios as u
        where pru.PersonaId = p.PersonaId
        and u.UsuarioId = pru.UC
        and pru.CentroId = c.CentroId
        and pe.PruebaEstadoId = pru.PruebaEstadoId
        and pru.PruebaId ='$id'";
        $datos =  parent::ObtenerRegistros($query);
        if(empty($datos)){
            return false;
        }else{
            return $datos;
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

    public function EnviarMaiilPruebaFinalizada($pruebaid,$email){
        $date = date("d-m-Y");
        $query ="
            select c.Nombre as Centro,CONCAT(p.PrimerNombre, ' ', p.PrimerApellido) as Paciente,Correo
            from pruebas  as pru ,centros as c, personas as p
            where pru.CentroId = c.CentroId
            and pru.PersonaId = p.PersonaId
            and PruebaId = $pruebaid;
        ";
        $datos = parent::ObtenerRegistros($query);
        $nombreCentro = $datos[0]['Centro'];
        $nombrePaciente = $datos[0]['Paciente'];
        $email = $datos[0]['Correo'];
        $direccionweb = $_SERVER['HTTP_HOST'];
        $url = "https://".$direccionweb . "/kardion/login.php" ;


        // cuerpo del email

        $html ="    
                    <!DOCTYPE html>
                    <html xmlns='http://www.w3.org/1999/xhtml'>
                    <head>
                        <meta content='text/html; charset=utf-8' http-equiv='Content-Type'>
                        <meta content='width=device-width, initial-scale=1.0' name='viewport'>
                        <title>Kardion</title>
                        <style>
                                /*<![CDATA[*/
                            #outlook a {padding:0;} /* Force Outlook to provide a 'view in browser' menu link. */
                            body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
                            .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
                            .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing.  More on that: http://www.emailonacid.com/forum/viewthread/43/ */
                            #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
                            img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
                            a img {border:none;}
                            .image_fix {display:block;}
                            Bring inline: Yes.
                            p {margin: 1em 0;}
                            h1, h2, h3, h4, h5, h6 {color: black !important;}
                            h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: blue !important;}
                            h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {
                                color: red !important; /* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
                            }
                            h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
                                color: purple !important; /* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */
                            }
                            table td {border-collapse: collapse;}
                            table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
                            a {color: orange;}
                            a:link { color: orange; }
                            a:visited { color: blue; }
                            a:hover { color: green; }
                                /*]]>*/
                        </style>
                    </head>
                    <body style='background: #f4f7f9; font-family:Helvetica Neue, Helvetica, Arial;'>
                    <table align='center' bgcolor='#f4f7f9' border='0' cellpadding='0' cellspacing='0' id='backgroundTable' style='background: #f4f7f9;' width='100%'>
                        <tr>
                            <td align='center'>
                                <center>
                                    <table border='0' cellpadding='50' cellspacing='0' style='margin-left: auto;margin-right: auto;width:600px;text-align:center;' width='600'>
                                        <tr>
                                            <td align='center' valign='top'>
                                                <img  width='400' height='140' src='https://www.kardion.es/kardion/public/logos/kardionlateral.png' style='outline:none; text-decoration:none;border:none,display:block;' width='100' />
                                            </td>
                                        </tr>
                                    </table>
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <center>
                                    <table border='0' cellpadding='30' cellspacing='0' style='margin-left: auto;margin-right: auto;width:600px;text-align:center;' width='600'>
                                        <tr>
                                            <td align='left' style='background: #ffffff; border: 1px solid #dce1e5;' valign='top' width=''>
                                                <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                                    <tr>
                                                        <td align='center' valign='top'>
                                                            <h2 style='color: #49bf67 !important'>El resultado de su prueba esta listo</h2>
                                                        </td>
                                                    </tr>
                                                    <!-- <tr>
                                                        <td align='center' style='border-top: 1px solid #dce1e5;border-bottom: 1px solid #dce1e5;' valign='top'>
                                                            <p style='margin: 1em 0;'>
                                                                <strong>:</strong>
                                                                john.doe
                                                            </p>
                                                        </td>
                                                    </tr> -->
                                                    <tr>
                                                        <td align='center' valign='top'>
                                                            <p style='margin: 1em 0;'>
                                                            Estimado/a <?=$nombrePaciente?>,
                                                            <br>
                                                            
                                                                <br>
                                                            <strong><?=$nombreCentro?> </strong> ha publicado un nuevo documento en el portal del paciente el dia <?=$date?>. 
                                                            
                                                            <br> <br> Recuerde que puede ver la prueba haciendo click en el boton de abajo o en su defecto en la siguiente direccion <a href='<?=$direccionweb?>'><?=$direccionweb?></a>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align='center' bgcolor='#49bf67' valign='top'>
                                                            <h3><a href='<?=$direccionweb?>' style='color: #ffffff !important'>Ver prueba</a></h3>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </center>
                            </td>
                        </tr>
                    </table>
                        <div style='margin-left: auto;margin-right: auto;width:600px;text-align:center;' width='600'>
                                <p style='font-size:0.9em'>
                                La información contenida en este mensaje de correo electrónico es confidencial y puede revestir el carácter de reservada. Está destinada exclusivamente a su destinatario. El acceso o uso de este mensaje, por parte de cualquier otra persona que no esté autorizada, pueden ser ilegales. Si no es Ud. la persona destinataria, le rogamos que proceda a eliminar su contenido. En cumplimiento del Reglamento General de Protección de Datos (EU) 2016/679, le comunicamos que los datos personales que nos ha facilitado forman parte de nuestro fichero con el objetivo de poder mantener el contacto con Ud. Si desea oponerse, acceder, cancelar o rectificar sus datos, diríjase a <strong>CENTRO MÉDICO SAN MARTÍN </strong>, Responsable del Fichero, a la dirección de correo electrónico info@kardion.es
                                </p>
                        </div>
                    </body>
                    </html>
        ";

        // Datos 
        $para      = $email;
        $titulo    = 'El resultado de su prueba esta listo - KARDI-ON';
        $mensaje   = $html;
        $cabeceras = 'From: kardion@kardion.es' . "\r\n" .
        'Reply-To: no-reply@kardion.com' . "\r\n" .
        'Content-type:text/html'. "\r\n" .
        'X-Mailer: PHP/' . phpversion();

        mail($para, $titulo, $mensaje, $cabeceras);
    }

    public function EnviarMailPruebaPublicada($centroId){
        /*obeneter la lista de correos agregados para enviar */
        require_once('notificacion_controller.php');
        $_notificacion = new notificacion;
        $listacorreos = $_notificacion->ListaCorreos_enviar();
        if(empty($listacorreos)){
            $listacorreos = array();
        }
         /*obeneter la plantilla de mail a enviar */
        $_SESSION['centromail'] = $centroId;
        ob_start();
        require '../utilidades/mails/alerta_nuevaprueba_admin.php';
        $html =ob_get_clean(); 

        /* enviar los correos*/
        foreach($listacorreos as $key=>$value){
            $correo = $value['Correo'];
            $para      = $correo;
            $titulo    = 'Nueva prueba pendiente - KARDI-ON';
            $mensaje   = $html;
            $cabeceras = 'From: kardion@kardion.es' . "\r\n" .
            'Reply-To: no-reply@kardion.com' . "\r\n" .
            'Content-type:text/html'. "\r\n" .
            'X-Mailer: PHP/' . phpversion();
             mail($para, $titulo, $mensaje, $cabeceras); 
        }
        return $html;

    }



    public function DescartarPrueba($pruebaid,$motivo,$usuarioid){
        $date = date('Y-m-d');
        $query = "update pruebas set PruebaEstadoId='1',Motivo='$motivo',FM='$date',UM='$usuarioid' where PruebaId ='$pruebaid'";
        $datos = parent::NonQuery($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }
    }

    public function DescartarPrueba_asignada($pruebaid){
        $query = "update pruebas_asignadas set AsignadasEstadoId='5' where PruebaId ='$pruebaid'";
        $datos= parent::NonQuery($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }
    }


    public function ListaPruebasDescartadas_Master(){
        $query = "select pru.PruebaId,pru.FC,pru.FM,pe.Nombre as Estado,c.Nombre as Centro,CONCAT(p.PrimerNombre, ' ' ,p.PrimerApellido) as Persona,p.Correo
        from pruebas as pru , personas as p , centros as c,pruebas_estados as pe
        where pru.PersonaId = p.PersonaId
        and pru.CentroId = c.CentroId
        and pe.PruebaEstadoId = pru.PruebaEstadoId
        and pru.PruebaEstadoId = 1";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
    }
  


    public function ReciclarPrueba($pruebaid,$motivo,$usuarioid){
        $date = date('Y-m-d');
        $query = "update pruebas set PruebaEstadoId='2',Motivo='$motivo',FM='$date',UM='$usuarioid' where PruebaId ='$pruebaid'";
        $datos = parent::NonQuery($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }
    }


    public function ListaPruebasDescartadasEmpresa($master){
        $query = "select pru.PruebaId,pru.FC,pru.FM,pe.Nombre as Estado,c.Nombre as Centro,CONCAT(p.PrimerNombre, ' ' ,p.PrimerApellido) as Persona,p.Correo , u.Usuario as enviado
        from pruebas as pru , personas as p , centros as c,pruebas_estados as pe,usuarios as u
        where pru.PersonaId = p.PersonaId
        and u.UsuarioId = pru.UC
        and pru.CentroId = c.CentroId
        and pe.PruebaEstadoId = pru.PruebaEstadoId
        and pru.PruebaEstadoId = 1
        and u.MasterCompaniaId = '$master'";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
    }


    public function ListaPruebasDescartadasPorUsuario($master,$usuariId){
        $query = "select pru.PruebaId,pru.FC,pru.FM,pe.Nombre as Estado,c.Nombre as Centro,CONCAT(p.PrimerNombre, ' ' ,p.PrimerApellido) as Persona,p.Correo , u.Usuario as enviado
        from pruebas as pru , personas as p , centros as c,pruebas_estados as pe,usuarios as u
        where pru.PersonaId = p.PersonaId
        and u.UsuarioId = pru.UC
        and u.UsuarioId = pru.UC
        and pru.CentroId = c.CentroId
        and pe.PruebaEstadoId = pru.PruebaEstadoId
        and pru.PruebaEstadoId = 1
        and pru.UC = '$usuariId";
        $resp = parent::ObtenerRegistros($query);
        if(empty($resp)){
            return false;
        }else{
            return $resp;
        }
    }
  


}