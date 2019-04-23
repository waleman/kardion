<?php 


//Documetacion 

//programador  Jose wilfredo Aleman Giron
//fecha : 22/08/2018
//ubicacion : Donostia San Sebastian  España
//pagina web : wc-solutions.net
//Todos los derechos reservados ®


//En caso de tener una diferente base de datos
//Cambia la ubicacion del servidor en el __constructor


//Para llamar la clase de conexion debes utilizar la siguente lineas de comando
//require_once("data/conexion.php");
// $db = new conexion();
//para llamar la funcion obtener registros 
// $resultado =  $db->ObtenerRegistros($TuQuery);
//para llamar la funcion noquery
//$db->NonQuery($TuQuery);

class conexion {

 private $server;
 private $user;
 private $pswd;
 private $database;
 private $port;
 private $conexion;



 public function __construct(){
    //  $this->server = "160.153.133.187";
    //  $this->server = "localhost";
    //  $this->user = "system";
    //  $this->pwsd = "Rxspo777*";
    //  $this->database ="kardion-demo";
    //  $this->port ="3306";


    //  $this->server = "160.153.133.187";
    //  $this->server = "localhost";
    //  $this->user = "system";
    //  $this->pwsd = "Rxspo777*";
    //  $this->database ="kardion-demo";
    //  $this->port ="3306";


    //  $this->server = "localhost";
    //  $this->user = "system";
    //  $this->pwsd = "Rxspo777*";
    //  $this->database ="kardi-on";
    //  $this->port ="3306";

     $this->server = "localhost";
     $this->user = "root";
     $this->pwsd = "";
     $this->database ="kardion";
     $this->port ="3306";

     $this->conexion =  new mysqli($this->server,$this->user,$this->pwsd,$this->database,$this->port);
     if( $this->conexion -> connect_errno){
         die($this->conexion -> connect_error);
     }
 }



 //guardar , modificar , eliminar 
 function NonQuery($sqlstr){
    $result = $this->conexion->query($sqlstr);
    return $this->conexion -> affected_rows;
 }

 //select
function ObtenerRegistros($sqlstr){
    $result = $this->conexion->query($sqlstr);
    $resultArray  = array();
    foreach( $result  as $registros ){
        $resultArray[] = $registros;
    }
    return $this->ConvertirUTF8($resultArray);
 }

 //utf-8
function ConvertirUTF8($array){
    array_walk_recursive($array,function(&$item,$key){
        if(!mb_detect_encoding($item,'utf-8', true)){
            $item = utf8_encode( $item);
        }
    });
    return  $array;
}


function security($texto){
    $texto=str_replace('"','',$texto);  
    $texto=str_replace("'",'',$texto);  
    return $texto;

}

function encriptar($texto){
    $texto = md5($texto);
    return $texto;
}


function encriptarUrl($string){
    $Key = "CLAVESUPERSECRETA";
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(Enigma::$Key), $string, MCRYPT_MODE_CBC, md5(md5(Enigma::$Key))));
}

function desencriptarUrl($string){
    $Key = "CLAVESUPERSECRETA";
    return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(Enigma::$Key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5(Enigma::$Key))), "\0");
}

public function generarCodigo($longitud) {
    $key = '';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ*#';
    $max = strlen($pattern)-1;
    for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
    return $key;
   }


public function get_random_string($length = 6){
    $cons = array('b','c','d','f','g','h','j','k','l',  
                  'm','n','p','r','s','t','v','w','x','y','z','B','C','D',
                  'F','G','H','I','J','K','L','M','N','P','Q','R','S','T','V','W','X',
                  'Y','Z','1','2','3','4','5','6','7','8','9');
    $voca = array('a','e','i','o','u','A','E','I','O','U','1','0','2','3','4','5','6','7','8','9');
    
    srand((double)microtime()*1000000);
    
    $max = $length/2;
    $password = '';
    for($i=1;$i<=$max;$i++){
        $password .= $cons[rand(0,count($cons)-1)];
        $password .= $voca[rand(0,count($voca)-1)];
    }
 
    if(($length % 2) == 1) $password .= $cons[rand(0,count($cons)-1)];
 
    return $password;
}

    
}






















?>