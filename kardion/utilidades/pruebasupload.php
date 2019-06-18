<?php
require_once '../clases/conexion.php';
require_once "../terceros/dropbox/vendor/autoload.php";
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\DropboxFile;
$conexion = new conexion;

$dropboxKey ='foos99p3kmwwj82';
$dropboxSecret ='leurwpvfj39reae';
$acessToken = "2aiX2w1ONaAAAAAAAAAAEpbdEoW5fJkmZEMBTfc4AZ6otH44jJ2zB9Jlv-kBL6wZ";
$appName='Kardion';
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

        if($tama[0]>4189792){
            try{
                $file = $dropbox->simpleUpload($tempFile,$nombredropbox, ['autorename' => true]);
                $response = $dropbox->postToAPI("/sharing/create_shared_link_with_settings", ["path" => $nombredropbox, "settings" => ['requested_visibility' => 'public']]);
                $data = $response->getDecodedBody();
                $link = $data['url'];
                $query = "insert into pruebas_archivos (Codigo,Archivo,Ubicacion)values('$savecodge','$link','2')";
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