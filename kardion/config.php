<?php
require_once('clases/config_controller.php');
$_config = new configuracion;

$_config->crearMasterUsuario('clases/data.txt');
?>