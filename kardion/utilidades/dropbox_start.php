<?php
require_once("../terceros/dropbox/vendor/autoload.php");


$dropboxKey ='q7dfrvc08zcux8r';
$dropboxSecret ='wu4sudc57qqc9n5';
$appName='Kardion';

$appInfo = new Dropbox\AppInfo($dropboxKey,$dropboxSecret);
//Store CSRF token
$csrfTokenStore = new Dropbox\ArrayEntryStore($_SESSION['k6'],'dropbox-auth');
//define auth details
$webAuth = new Dropbox\WebAuth($appInfo,$appName,'https://kardion.es',$csrfTokenStore);
$acessToken = "2aiX2w1ONaAAAAAAAAAAESdIYYP13FqKQ4_p3WI2vDlnrBtYmbR6qhvEBbLIzEk_";

$client = new Dropbox\client($acessToken,$appName,'UTF-8');

try{
   print_r( $client->getAccountInfo());
}catch(Dropbox\Exception_InvalidAccessToken $e){
    Echo "la verga herida";
}


?>