<?php 
require '../terceros/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
ob_start();
require 'pdf_vista.php';
$html = ob_get_clean();

$html2pdf = new Html2Pdf();

//$pruebaid =base64_decode($_GET['persona']);
$pruebaid = $_GET['id'];

$html2pdf->writeHTML($html);
$html2pdf->output();


?>