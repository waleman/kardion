<?php
//Documetacion

//programador:  Jose wilfredo Aleman Giron
//fecha : 07/12/2018
//ubicacion : Donostia San Sebastian  España
//pagina web :www.wc-solutions.net
//Todos los derechos reservados ®

// class mastercompanias extiende de la clase conexion para poder utilzar sus metodos
//Esta clase fue desarrollada para manejar las compañias en la pagina mestra Wizard 

require_once('conexion.php');

class companias extends conexion{


    public function getCompanias($usuarioId){
        $query="select up.CompaniaId,c.NombreComercial 
                from usuarios_permisos_companias as up , companias as c
                where c.CompaniaId = up.CompaniaId
                and up.UsuarioId = '$usuarioId'";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
            return false;
        }else{
            return $datos;
        }
    }


    public function ultimaInsertada(){
        $query ="SELECT CompaniaId FROM companias ORDER BY CompaniaId DESC LIMIT 1; ";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
            return false;
        }else{
            return $datos[0]['CompaniaId'];
        }
    }


    
   public function register($nombrelegal,$nombrecomercial,$direccion,$telefono,$logo = "",$usuarioId){
    $date =$date = date('Y-m-d');
    $query= "insert into companias
    (NombreLegal,NombreComercial,Direccion,Telefono1,Logo,UC,FC)
    values
    ('$nombrelegal','$nombrecomercial','$direccion','$telefono','$logo','$usuarioId','$date')";
    //print_r($query);
    $datos = parent::NonQuery($query);
    if ($datos == 1 ){
            $companiaid = $this->ultimaInsertada();   
            $query= "insert into usuarios_permisos_companias
            (CompaniaId,UsuarioId,UC,FC)
            values
            ('$companiaid','$usuarioId','$usuarioId','$date')";
            $datos = parent::NonQuery($query);
            return true;
    }else{
        return false;
    }
  }


  public function ListaCompanias(){
      $query ="select MasterCompaniaId,Nombre,Estado from mastercompania order by Nombre ASC";
      $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
            return false;
        }else{
            return $datos;
        }
  }

  public function Compania($id){
    $query ="select * from mastercompania where MasterCompaniaId='$id'";
    $datos = parent::ObtenerRegistros($query);
      if(empty($datos)){
          return false;
      }else{
          return $datos;
      }
}

public function ListaPruebasFinalizadasCompania($master){
    $query= "select count(*) as Cantidad from pruebas 
    where UC in (select UsuarioId from usuarios where MasterCompaniaId = '$master')
    and PruebaEstadoId = 5";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
        return false;
    }else{
        return $datos[0]['Cantidad'];
   }
}

public function ListaPruebasDescartadasCompania($master){
    $query= "select count(*) as Cantidad from pruebas 
    where UC in (select UsuarioId from usuarios where MasterCompaniaId = '$master')
    and PruebaEstadoId = 1";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
        return false;
    }else{
        return $datos[0]['Cantidad'];
   } 
}

public function ListaPruebasPendientesCompania($master){
    $query= "select count(*) as Cantidad from pruebas 
    where UC in (select UsuarioId from usuarios where MasterCompaniaId = '$master')
    and (PruebaEstadoId = 2 or PruebaEstadoId = 3 or PruebaEstadoId = 4) ";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
        return false;
    }else{
        return $datos[0]['Cantidad'];
   }
}



public function ListaCentros($master){
    $query= "select count(*) as Cantidad from centros where MasterCompaniaId= '$master'";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
        return false;
    }else{
        return $datos[0]['Cantidad'];
   }

}

public function Listapersonas($master){
    $query= "select count(*) as Cantidad from usuarios where UC in (select UsuarioId from usuarios where MasterCompaniaId = '$master')";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
        return false;
    }else{
        return $datos[0]['Cantidad'];
   }

}

public function ListaUsuarios($master){
    $query= "select count(*) as Cantidad from usuarios where MasterCompaniaId = '$master'";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
        return false;
    }else{
        return $datos[0]['Cantidad'];
   }

}

 






}


?>