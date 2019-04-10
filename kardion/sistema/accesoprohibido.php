

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>  
<?php 
require_once("../clases/cargar.php");
$html = new cargar;
$html->sessionDataSistem(); //iniciamos la sesion en el navegador

echo $html->LoadCssSystem("sistema"); // cargamos todas las librerias css del sistema interno
echo $html->LoadJquery("sistema"); //cargamos todas las librerias Jquery del sistema interno  
?>
</head>
<body class='contrast-blue application-error 404-error contrast-background'>
    <div id='wrapper'>
      <div class='error-type'>
        <i class='icon-question-sign'></i>
        <span>404</span>
      </div>
      <div class='error-message'>
        Ooops! Al parecer no tienes permisos para acceder a este sito.
      </div>

      <a class='btn btn-block' href='../login.php'>
        <i class='icon-chevron-left'></i>
       Regresar 
      </a>
    </div>
  </body>
</html>

