<?php
require_once '../clases/conexion.php';
require_once '../clases/servidor_archivos_controller.php';
require_once "../terceros/dropbox/vendor/autoload.php";
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\DropboxFile;
$conexion = new conexion;
$_servidor = new servidorArchivos;


$data = $_servidor->buscarConexion();
$dropboxKey = "";
$dropboxSecret = "";
$acessToken = "";
$appName= "";
$megas = "";

if(empty($data)){
    $megas = 2000;
}else{
    $dropboxKey = $data[0]['Keyapp'];
    $dropboxSecret = $data[0]['Secret'];
    $acessToken = $data[0]['Token'];
    $appName= $data[0]['Appname'];
    $megas = $data[0]['Megas'];
    $megas = ($megas * 1024) * 1024 ;
}

set_error_handler('error');

$app = new DropboxApp($dropboxKey,$dropboxSecret,$acessToken);
$dropbox = new Dropbox($app);


if (!empty($_FILES)) {
    /*generamos un codigo para guardar con el id de la persona y una cadena ramdom de  5 caracteres*/
    $id = $_GET['id'];
    $codigo = $_GET['codigo'];
    $savecodge= $id."_".$codigo;
   
        $nombre = uniqid();
        $tempFile = $_FILES['file']['tmp_name'];  
        $ext = explode(".", $_FILES['file']['name']); 
        $ext =end($ext);           
        $nombreCompleto =  $nombre .'.'.$ext; 
        $nombredropbox = "/". $nombre .'.'.$ext; 
        $tama = explode(".", $_FILES['file']['size']); 

        if($tama[0]>$megas){
            try{
                $file = $dropbox->simpleUpload($tempFile,$nombredropbox, ['autorename' => true]);
                $response = $dropbox->postToAPI("/sharing/create_shared_link_with_settings", ["path" => $nombredropbox, "settings" => ['requested_visibility' => 'public']]);
                $data = $response->getDecodedBody();
                print_r($data);
                $link = $data['url'];
                $query = "insert into pruebas_archivos (Codigo,Archivo,Ubicacion)values('$savecodge','$nombredropbox','2')";
                $datos=  $conexion->NonQuery($query); 
                http_response_code(200);
             }catch(\EXCEPTION $e){
                ERROR('001',$E);
                 http_response_code(400);
             }
        }else{
            $targetPath = "../public/pruebas/";  //creamos la direccion donde se guardara el archivo
            $targetFile =  $targetPath.$id ."_". $nombreCompleto;  //5
            move_uploaded_file($tempFile,$targetFile); //6
            $query = "insert into pruebas_archivos (Codigo,Archivo,Ubicacion)values('$savecodge','$nombreCompleto','1')";
            $conexion->NonQuery($query); 
            http_response_code(200); 
      
        }
}


function error($numero,$texto){
    $ddf = fopen('error.log','a');
    fwrite($ddf,"[".date("r")."] Error $numero: $texto\r\n");
    fclose($ddf);
  }
?>  