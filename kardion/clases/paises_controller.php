<?php
require_once('conexion.php');

class paises extends conexion{

    public function getPais($id=""){
        $query = "";
        if($id == ""){
                $query ="select PaisId,Nombre from paises";
        }else{
                $query ="select PaisId,Nombre from paises where PaisId='$id'";
        }
       $datos = parent::ObtenerRegistros($query);
       if(empty($datos)){
            return false;
        }else{
            return $datos;
        }
    }


    public function getProvincia($Paisid){

      $query ="select ProvinciaId,Nombre from provincias where PaisId='$Paisid'";
       
       $datos = parent::ObtenerRegistros($query);
       if(empty($datos)){
            return false;
        }else{
            return $datos;
        }
    }


    public function getCiudad($Provinciaid){

        $query ="select MunicipioId,Nombre from municipios where ProvinciaId = '$Provinciaid' order by Nombre";
         
         $datos = parent::ObtenerRegistros($query);
         if(empty($datos)){
              return false;
          }else{
              return $datos;
          }
      }

        public function getPaisNombre($id){
            $query ="select Nombre from paises where PaisId ='$id'";
            $datos = parent::ObtenerRegistros($query);
            if(empty($datos)){
                return false;
            }else{
                return $datos[0]['Nombre'];
            }
        }
        public function getProvinciaNombre($id){
            $query ="select Nombre from provincias where ProvinciaId ='$id'";
            $datos = parent::ObtenerRegistros($query);
            if(empty($datos)){
                return false;
            }else{
                return $datos[0]['Nombre'];
            }
        }
        public function getMunicipioNombre($id){
            $query ="select Nombre from municipios where MunicipioId ='$id'";
            $datos = parent::ObtenerRegistros($query);
            if(empty($datos)){
                return false;
            }else{
                return $datos[0]['Nombre'];
            }
        }
}



?>