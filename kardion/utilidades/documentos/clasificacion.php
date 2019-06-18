<?php 
require '../../terceros/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
ob_start();
require 'ver.php';
$html = ob_get_clean();
$html2pdf = new Html2Pdf();
$clasificacionId = $_GET['id'];
$html2pdf->writeHTML($html);
$html2pdf->output();
?>