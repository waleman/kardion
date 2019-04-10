<?php 
    require '../terceros/vendor/autoload.php';
    require_once '../clases/pruebas_controller.php';

    use Spipu\Html2Pdf\Html2Pdf;
         
         $pruebaid = $_GET['id'];
         session_start();
         $_SESSION['prueba'] = $pruebaid;
         $pruebas = new pruebas;
        ob_start();
        
        require "pdf_vista2.php";
        $html = ob_get_clean();

        $html2pdf = new Html2Pdf();

   
       
        $html2pdf->writeHTML($html);

        $direccion ="/resultados/prueba_". $pruebaid.".pdf";
          //se guarda en esta direccion por poblemas con la libreria 
        $html2pdf->output(__DIR__ . $direccion , 'F');

      // Modificamos la tabla pruebas para almacenar el nombre y direccion del archivo pdf
        $archivo = "prueba_".$pruebaid.".pdf";
        $respuesta = $pruebas->ActualizarArchivo($pruebaid,$archivo) ;
    print_r("HECHO");

?> 