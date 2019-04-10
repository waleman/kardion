<?php 
require_once("conexion.php");

//Documetacion
//programador:  Jose wilfredo Aleman Giron
//fecha : 23/12/2018
//ubicacion : Donostia San Sebastian  España
//pagina web :www.wc-solutions.net
//Todos los derechos reservados ®
// class centros extiende de la clase conexion para poder utilzar sus metodos

class centros extends conexion{

        public function generateRandomString($length = 10) { 
            return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
        } 
    

        public function getCentros($master){
            $query = "select * from centros where MasterCompaniaId = '$master'";
            $datos = parent::ObtenerRegistros($query);
            if(empty($datos)){
                return false;
            }else{
                return $datos;
            }
        }

        public function getcentrosPermitidos($UsuarioId){
            $query = "select c.CentroId,c.Nombre from centros as c , usuarios_permisos_centros as uc
            where uc.CentroId = c.CentroId
            and UsuarioId = '$UsuarioId'";
            $datos = parent::ObtenerRegistros($query);
            if(empty($datos)){
                return false;
            }else{
                return $datos;
            }
        }

        public function DispositivosPorCentro($CentroId){
            $query= "select ac.AparatoId,a.Nombre,a.Serie from aparatos_companias as ac, aparatos as a
            where ac.AparatoId = a.AparatoId
            and ac.CentroId = '$CentroId'";
            $datos = parent::ObtenerRegistros($query);
            if(empty($datos)){
                return false;
            }else{
                return $datos;
            }
        }

        public function GetNominasTipo(){
          $query = "select * from tipo_planilla;";
          $datos = parent::ObtenerRegistros($query);
          if(empty($datos)){
              return false;
          }else{
              return $datos;
          }
        }


        public function GetVacacionesTipos(){
            $query = "select * from tipo_vacaciones;";
            $datos = parent::ObtenerRegistros($query);
            if(empty($datos)){
                return false;
            }else{
                return $datos;
            }
          }


        public function registerCentro($master,$nombre,$telefono1,$telefono2,$direccion,$pais,$provincia,$ciudad,$estado,$usuario,$codigopostal,$imagen){
            $date = date('Y-m-d');
            $query= "insert into centros
            (MasterCompaniaId,Nombre,Telefono1,Telefono2,Direccion,PaisId,ProvinciaId,MunicipioId,Estado,UC,FC,CodigoPostal,Logo)
            values('$master','$nombre','$telefono1','$telefono2','$direccion','$pais','$provincia','$ciudad','$estado','$usuario','$date','$codigopostal','$imagen')";
           // print_r($query);
            $datos = parent::NonQuery($query);
            if ($datos == 1 ){
                return true;
            }else{
                return false;
            }

        }

        public function editarCentro($id,$nombre,$telefono1,$telefono2,$direccion,$estado,$codigopostal,$imagen,$usuario){
            $date = date('Y-m-d');
            $query= "update  centros set Nombre='$nombre',Telefono1 ='$telefono1',Telefono2 ='$telefono2',Direccion = '$direccion',Estado ='$estado',UM ='$usuario',FM ='$date',CodigoPostal='$codigopostal',Logo='$imagen'
                where CentroId = '$id'
            ";
            //print_r($query);
            $datos = parent::NonQuery($query);
            if ($datos == 1 ){
                return true;
            }else{
                return false;
            }

        }


        // public function centroporid($id){
        //     $query ="select Nombre,Direccion,Telefono,Logo from centros where CentroId='$id'";
        //     $datos = parent::NonQuery($query);
        //     if ($datos == 1 ){
        //         return true;
        //     }else{
        //         return false;
        //     }
        // }

        public function centroporid2($id){
            $query ="select * from centros where CentroId='$id'";
            $datos = parent::ObtenerRegistros($query);
            if ($datos ){
                return $datos;
            }else{
                return false;
            }
        }

}

?>