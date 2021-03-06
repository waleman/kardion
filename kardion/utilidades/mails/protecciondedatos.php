<?php 

require_once ('../clases/conexion.php');
//instanciamos 
$con = new conexion;

if(isset($_SESSION['centromail'])){
$date = date("d-m-Y");
$centroid = $_SESSION['centromail'];

$query ="select Nombre from centros where CentroId = '$centroid'";

$datos = $con->ObtenerRegistros($query);
$nombreCentro = $datos[0]['Nombre'];

 
}


?>

<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
    <meta content='text/html; charset=utf-8' http-equiv='Content-Type'>
    <meta content='width=device-width, initial-scale=1.0' name='viewport'>
    <title>Kardion</title>
    <style>
            /*<![CDATA[*/
        #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
        body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
        .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing.  More on that: http://www.emailonacid.com/forum/viewthread/43/ */
        #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
        img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
        a img {border:none;}
        .image_fix {display:block;}
        Bring inline: Yes.
        p {margin: 1em 0;}
        h1, h2, h3, h4, h5, h6 {color: black !important;}
        h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: blue !important;}
        h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {
            color: red !important; /* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
        }
        h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
            color: purple !important; /* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */
        }
        table td {border-collapse: collapse;}
        table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
        a {color: orange;}
        a:link { color: orange; }
        a:visited { color: blue; }
        a:hover { color: green; }
            /*]]>*/
    </style>
</head>
<body style='background: #f4f7f9; font-family:Helvetica Neue, Helvetica, Arial;'>
<table align='center' bgcolor='#f4f7f9' border='0' cellpadding='0' cellspacing='0' id='backgroundTable' style='background: #f4f7f9;' width='100%'>
    <tr>
        <td align='center'>
            <center>
                <table border='0' cellpadding='50' cellspacing='0' style='margin-left: auto;margin-right: auto;width:600px;text-align:center;' width='600'>
                    <tr>
                          <td align='center' valign='top'>
                             <img  width='400' height='140' src='https://www.kardion.es/kardion/public/logos/kardionlateral.png' style='outline:none; text-decoration:none;border:none,display:block;' width='100' />
                          </td>
                    </tr>
                </table>
            </center>
        </td>
    </tr>
    <tr>
        <td align='center'>
            <center>
                <table border='0' cellpadding='30' cellspacing='0' style='margin-left: auto;margin-right: auto;width:900px;text-align:center;' width='600'>
                    <tr>
                        <td align='left' style='background: #ffffff; border: 1px solid #dce1e5;' valign='top' width=''>
                            <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                <tr>
                                    <td align='center' valign='top'>
                                        <h2 style='color: #2196F3  !important'>Protección de Datos</h2>
                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td  valign='top'>
                                        <p style='margin: 1em 0;'>
                                        <strong>Responsable :</strong> CENTRO MÉDICO SAN MARTÍN
                                        </p>
                                        <p style='margin: 1em 0;'>
                                        <strong>Finalidad :</strong> Proporcionarle un resultado exacto en las pruebas realizadas
                                        </p>
                                        <p style='margin: 1em 0;'>
                                        <strong>Destinatarios :</strong> Equipo de médicos interpretes. 
                                        </p>
                                        <p style='margin: 1em 0;'>
                                        <strong>Derechos :</strong> Acceder, rectificar y suprimir los datos, como se explica en la información adicional
                                        </p>
                                        <p style='margin: 1em 0;'>
                                        <strong>Información Adicional :</strong>  
                                           Pruede consultar la información adicional y detallada sobre la Protección de Datos  en el siguiente enlace <a href="http://www.rglopd.com/">www.rglopd.com/</a>
                                        </p>
                                        <br>
                                        <p>
                                        Hemos enviado un correo adicional para verificar su cuenta en KARDI-ON. Al verificar su cuenta, usted está aceptando las políticas de Protección de datos.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    
                                    <td align='center' bgcolor='#2196F3 ' align='top'>
                                        <h3><a href="https://kardion.es" style="color: #ffffff !important">Ir a KARDI-ON</a></h3>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </center>
        </td>
    </tr>
</table>
    <div style='margin-left: auto;margin-right: auto;width:600px;text-align:center;' width='600'>
            <p style="font-size:0.9em">
            La información contenida en este mensaje de correo electrónico es confidencial y puede revestir el carácter de reservada. Está destinada exclusivamente a su destinatario. El acceso o uso de este mensaje, por parte de cualquier otra persona que no esté autorizada, pueden ser ilegales. Si no es Ud. la persona destinataria, le rogamos que proceda a eliminar su contenido. En cumplimiento del Reglamento General de Protección de Datos (EU) 2016/679, le comunicamos que los datos personales que nos ha facilitado forman parte de nuestro fichero con el objetivo de poder mantener el contacto con Ud. Si desea oponerse, acceder, cancelar o rectificar sus datos, diríjase a <strong>CENTRO MÉDICO SAN MARTÍN </strong>, Responsable del Fichero, a la dirección de correo electrónico info@kardion.es
            </p>
    </div>
</body>
</html>
