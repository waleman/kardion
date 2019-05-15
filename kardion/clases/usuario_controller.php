<?php

//Documetacion

//programador  Jose wilfredo Aleman Giron
//fecha : 02/09/2018
//ubicacion : Donostia San Sebastian  España
//pagina web :www.wc-solutions.net
//Todos los derechos reservados ®

// class usuario extiende de la clase conexion para poder utilzar sus metodos
// para  evitar las inyecciones SQL utilizamos el metodo security  de la clase padre conexion ubicado en la carpeta data/conexion.php
//  *** lo que hace es quitar las posibles comillas  y comillas simple que vengan en los campos usuaio y contraseña


require_once("conexion.php");


class usuario extends conexion{

   public function login($usuario,$password){
            $usuario = parent::security($usuario);
            $password = parent::security($password);
            $password = parent::encriptar($password);
            $query= "select * from usuarios where Usuario = '$usuario' and Password = '$password'";
            $datos = parent::ObtenerRegistros($query);
            if(empty($datos)){
                return false;
            }else{
                return $datos;
            }
   }


   public function login_fail_checkmail($usuario){
    $usuario = parent::security($usuario);
    $query= "select count(*) as cantidad from usuarios where Usuario = '$usuario'";
    $datos = parent::ObtenerRegistros($query);
        if($datos[0]['cantidad'] == 0){
            return false;
        }else{
            return true;
        }
     }

   public function register($correo,$password,$nombre){
            $password = parent::encriptar($password);
            $master = parent::get_random_string();
            $CodigoActivacion = parent::get_random_string();
            $query= "insert into usuarios 
            (Usuario,Password,RolId,Estado,MasterCompaniaId,CodigoActivacion)
            values
            ('$correo','$password','4','Pendiente','$master','$CodigoActivacion')";
            $datos = parent::NonQuery($query);
           // $this->test($master);
            if ($datos == 1 ){
                 $ultimo = $this->ultimoId();
                $query2="insert into mastercompania (MasterCompaniaId,Nombre,Estado,UsuarioId)
                values
                ('$master','$nombre','Activo','$ultimo')";
                $datos2 = parent::NonQuery($query2);
                if ($datos2 == 1 ){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
   }

/* QUITAR CUANDO ESTE EN PRODUCCION */
   public function test($master){
        $query ="insert into aparatos_companias (MasterCompaniaId,AparatoId,Estado)values('$master','1','Sin Asignar')";
        $datos = parent::NonQuery($query);
   }



   public function registerbyOwner($correo,$password,$rol,$estado,$master){
        $password = parent::encriptar($password);
        $query= "insert into usuarios 
        (Usuario,Password,RolId,Estado,MasterCompaniaId)
        values
        ('$correo','$password','$rol','$estado','$master')";
        $datos = parent::NonQuery($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }
    }


    public function InsertarPermisos($usuario,$centro,$uc){
        $date = date('Y-m-d');
        $query= "insert into usuarios_permisos_centros 
        (CentroId,UsuarioId,UC,FC)
        values
        ('$centro','$usuario','$uc','$date')";
        $datos = parent::NonQuery($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }
    }


    public function BuscarporCorreo($correo){
        $query = "SELECT UsuarioId fROM usuarios where Usuario ='$correo' ";
        $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
            return false;
         }else{
            return $datos[0]['UsuarioId'];
         }
       }



   public function ultimoId(){
    $query = "SELECT * fROM usuarios order by UsuarioId desc limit 1 ";
    $datos = parent::ObtenerRegistros($query);
    if(empty($datos)){
        return false;
     }else{
        return $datos[0]['UsuarioId'];
     }
   }

   public function check_not_busy($usuario){
        $usuario = parent::security($usuario);
        $query = "select count(*) as cantidad from usuarios where Usuario = '$usuario'";
        $datos = parent::ObtenerRegistros($query);
            if($datos[0]['cantidad'] >= 1){
               return true;
            }else{
               return false;
            }
   }


   public function ListarUsuarios($master){
    $query = "select  u.UsuarioId,u.Usuario,r.Nombre as Rol,u.Estado from usuarios as u , roles as r
    where r.RolId = u.RolId
    and MasterCompaniaId = '$master' ";
    $datos = parent::ObtenerRegistros($query);
        if(empty($datos)){
           return false;
        }else{
           return $datos;
        }
    }


    public function UsuarioDatos($UsuarioId){
        $query = "select Usuario,RolId,Estado,PersonaId,CodigoActivacion from usuarios where UsuarioId = $UsuarioId ";
        $datos = parent::ObtenerRegistros($query);
            if(empty($datos)){
               return false;
            }else{
               return $datos;
            }
        }

    public function Roles($rolId){
        $query = "select * from roles where RolId = '4' or  RolId = '5' or  RolId = '6'";
         $datos = parent::ObtenerRegistros($query);
             if(empty($datos)){
                  return false;
             }else{
                   return $datos;
             }
     }
    


    public function change_profile($usuario,$pnombre,$snombre,$papellido,$sapellido,$titulo){
        $query= "update usuarios set  primer_nombre = '$pnombre',segundo_nombre='$snombre',primer_apellido = '$papellido',segundo_apellido = '$sapellido',titulo = '$titulo'
         where usuario = '$usuario'";

        // print_r($query);
        $datos = parent::NonQuery($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }
    }

    public function change_profile_picture($usuario,$foto){
        $query= "update usuarios set  fotografia = '$foto' where usuario = '$usuario'";
       // print_r($query);
        $datos = parent::NonQuery($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }
    }



   public function change_roll($usuario,$nuevo_rol){
        $query = "update usuarios set  rol = '$nuevo_rol' where usuario = '$usuario'";
        $datos = parent::NonQuery($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }   
   }


   public function Change_password($usuario,$password){
    $password = parent::encriptar($password);
    $query= "update usuarios set Password = '$password' where UsuarioId = '$usuario'";
    $datos = parent::NonQuery($query);
    if ($datos == 1 ){
        return true;
    }else{
        return false;
    }
}

    public function verificarContraseña($usuarioid,$password){
        $password = parent::encriptar($password);
        $query= "select Password from usuarios where UsuarioId = '$usuarioid'";
        $datos = parent::ObtenerRegistros($query);
        if ($datos){
            if($datos[0]['Password'] == $password){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


   public function editarUsuario($usuario,$rol,$estado){
    $query = "update usuarios set RolId = '$rol',Estado ='$estado' where UsuarioId = '$usuario'";
    $datos = parent::NonQuery($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }   
   }

   public function limpiarPermisos($usuario){
       $query ="delete from usuarios_permisos_centros where UsuarioId='$usuario'";
       $datos = parent::NonQuery($query);
        if ($datos == 1 ){
            return true;
        }else{
            return false;
        }    
   }



 

   public function CrearUsuarioPaciente($correo,$master,$PersonaId,$uc){
        $date = date('Y-m-d');
        $master = parent::get_random_string();
        $CodigoActivacion = parent::get_random_string(10);
        $query= "insert into usuarios 
        (Usuario,RolId,Estado,MasterCompaniaId,PersonaId,CodigoActivacion,UC,FC)
        values
        ('$correo','7','Pendiente','$master','$PersonaId','$CodigoActivacion','$uc','$date')";
        $resp = parent::NonQuery($query);  
        if ($resp == 1 ){
            return true;
        }else{
            return false;
        }  
    }

    public function CrearUsuarioPacienteconPassword($correo,$master,$PersonaId,$uc,$password){
        $date = date('Y-m-d');
        $password = parent::encriptar($password);
        $master = parent::get_random_string();
        $CodigoActivacion = parent::get_random_string(10);
        $query= "insert into usuarios 
        (Usuario,RolId,Estado,MasterCompaniaId,PersonaId,CodigoActivacion,UC,FC,Password)
        values
        ('$correo','7','Pendiente','$master','$PersonaId','$CodigoActivacion','$uc','$date','$password')";
        $resp = parent::NonQuery($query);  
        if ($resp == 1 ){
            return true;
        }else{
            return false;
        }  
    }


    public function CrearUsuarioDoctor($correo,$password,$nombre){
        $password = parent::encriptar($password);
        $master = parent::get_random_string();
        $CodigoActivacion = parent::get_random_string();
        $query= "insert into usuarios 
        (Usuario,Password,RolId,Estado,MasterCompaniaId,CodigoActivacion)
        values
        ('$correo','$password','3','Pendiente','$master','$CodigoActivacion')";
        $datos = parent::NonQuery($query);
    
        if ($datos == 1 ){
             $ultimo = $this->ultimoId();
            $query2="insert into  dr_perfil(Nombre,UsuarioId)
            values
            ('$nombre','$ultimo')";
            $datos2 = parent::NonQuery($query2);
            if ($datos2 == 1 ){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
}



    public function eviarEmail($email){
        $query ="select UsuarioId,CodigoActivacion,Password from usuarios where Usuario = '$email'";

        $datos = parent::ObtenerRegistros($query);
        $codigo =$datos[0]['CodigoActivacion'];
        $password = $datos[0]['Password'];
        $UsuarioId = $datos[0]['UsuarioId'];
        //echo" La contraseña guardada es = $password";


        if(!$password || $password == "" ){
            $contra = parent::get_random_string(4);
            $this->establecerpassword($contra,$UsuarioId);
        }else{
            $contra = "la que has escrito al registrate";
        }
       


        $direccionweb = $_SERVER['HTTP_HOST'];
        $direccionpaginaactivacion = '/kardion/verificar.php';
        $url = "https://". $direccionweb . $direccionpaginaactivacion .'?id='.$codigo ;

        $para      = $email;
        $titulo    = 'Confirme su cuenta - Kardi-on';
        $mensaje   = "
                               <!DOCTYPE html>
                               <html xmlns='http://www.w3.org/1999/xhtml'>
                               <head>
                                   <meta content='text/html; charset=utf-8' http-equiv='Content-Type'>
                                   <meta content='width=device-width, initial-scale=1.0' name='viewport'>
                                   <title>Your Message Subject or Title</title>
                                   <style>
                                           /*<![CDATA[*/
                                       #outlook a {padding:0;} /* Force Outlook to provide a 'view in browser' menu link. */
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
                                               <table border='0' cellpadding='30' cellspacing='0' style='margin-left: auto;margin-right: auto;width:600px;text-align:center;' width='600'>
                                                   <tr>
                                                       <td align='left' style='background: #ffffff; border: 1px solid #dce1e5;' valign='top' width=''>
                                                           <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                                               <tr>
                                                                   <td align='center' valign='top'>
                                                                       <h2>  Bienvenido a Kardion !</h2>
                                                                   </td>
                                                               </tr>
                                                               <tr>
                                                                   <td align='center' valign='top'>
                                                                       <h4 style='color: #f34541 !important'>Confirme su  cuenta</h4>
                                                                   </td>
                                                               </tr>
                                                               <tr>
                                                                   <td align='center' style='border-top: 1px solid #dce1e5;border-bottom: 1px solid #dce1e5;' valign='top'>
                                                                       <p style='margin: 1em 0;'>
                                                                           <strong>Usuario:</strong>
                                                                           $email
                                                                       </p>
                                                                       <p style='margin: 1em 0;'>
                                                                           <strong>Contraseña:</strong>
                                                                           $contra
                                                                       </p>
                                                                   </td>
                                                               </tr>
                                                               <tr>
                                                                   <td align='center' bgcolor='#f34541' valign='top'>
                                                                       <h3><a href='$url' style='color: #ffffff !important'>Click aqui para confirmar su cuenta</a></h3>
                                                                   </td>
                                                               </tr>
                                                               <tr>
                                                                   <td align='center' valign='top'>
                                                                       <p style='margin: 1em 0;'>
                                                                           <br>
                                                                           Si el boton anterior no funciona haga click en este enlace : 
                                                                           $url
                                                                       </p>
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
                               </body>
                               </html>
                               





        
        ";
        $cabeceras = 'From: kardion@kardion.es' . "\r\n" .
                     'Reply-To: no-reply@kardion.es' . "\r\n" .
                     'Content-type:text/html'. "\r\n" .
                     'X-Mailer: PHP/' . phpversion();
        
        mail($para, $titulo, $mensaje, $cabeceras);

    }



    public function eviarEmail_forgetpassword($email){
        $query ="select UsuarioId,CodigoActivacion,Password from usuarios where Usuario = '$email'";

        $datos = parent::ObtenerRegistros($query);
        $codigo =$datos[0]['CodigoActivacion'];
        $password = $datos[0]['Password'];
        $UsuarioId = $datos[0]['UsuarioId'];
        //cambiamos la contraseña del usuario
         $contra = parent::get_random_string(4);
         $this->establecerpassword($contra,$UsuarioId);
      
        $direccionweb = $_SERVER['HTTP_HOST'];
        $url = "https://".$direccionweb . "/kardion/login.php" ;

        $para      = $email;
        $titulo    = 'Hemos recuperado tu contraseña - KARDI-ON';
        $mensaje   = "
                               <!DOCTYPE html>
                               <html xmlns='http://www.w3.org/1999/xhtml'>
                               <head>
                                   <meta content='text/html; charset=utf-8' http-equiv='Content-Type'>
                                   <meta content='width=device-width, initial-scale=1.0' name='viewport'>
                                   <title>Your Message Subject or Title</title>
                                   <style>
                                           /*<![CDATA[*/
                                       #outlook a {padding:0;} /* Force Outlook to provide a 'view in browser' menu link. */
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
                                               <table border='0' cellpadding='30' cellspacing='0' style='margin-left: auto;margin-right: auto;width:600px;text-align:center;' width='600'>
                                                   <tr>
                                                       <td align='left' style='background: #ffffff; border: 1px solid #dce1e5;' valign='top' width=''>
                                                           <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                                               <tr>
                                                                   <td align='center' valign='top'>
                                                                       <h2> Kardion !</h2>
                                                                   </td>
                                                               </tr>
                                                               <tr>
                                                                   <td align='center' valign='top'>
                                                                       <h4 style='color: #f34541 !important'>Hemos recibido una solicitud para una contraseña nueva</h4>
                                                                   </td>
                                                               </tr>
                                                               <tr>
                                                                   <td align='center' style='border-top: 1px solid #dce1e5;border-bottom: 1px solid #dce1e5;' valign='top'>
                                                                      
                                                                       <p style='margin: 1em 0;'>
                                                                           <strong>Contraseña:</strong>
                                                                           $contra
                                                                       </p>
                                                                   </td>
                                                               </tr>
                                                               <tr>
                                                                   <td align='center' bgcolor='#f34541' valign='top'>
                                                                       <h3><a href='$url' style='color: #ffffff !important'>Entra al sistema con tu usuario y la nueva contraseña</a></h3>
                                                                   </td>
                                                               </tr>
                                                               <tr>
                                                                   <td align='center' valign='top'>
                                                                       <p style='margin: 1em 0;'>
                                                                           <br>
                                                                           Si el boton anterior no funciona haga click en este enlace : 
                                                                           $url
                                                                       </p>
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
                               </body>
                               </html>
                               





        
        ";
        $cabeceras = 'From: kardion@kardion.es' . "\r\n" .
                     'Reply-To: no-reply@kardion.es' . "\r\n" .
                     'Content-type:text/html'. "\r\n" .
                     'X-Mailer: PHP/' . phpversion();
        
        mail($para, $titulo, $mensaje, $cabeceras);

    }


    public function EnviarMaiilProtecciondeDatos($email){
        ob_start();
        require ('../utilidades/mails/protecciondedatos.php');
        $html = ob_get_clean();
        $para      = $email;
        $titulo    = 'Proteccion de Datos - KARDI-ON';
        $mensaje   = $html;
        $cabeceras = 'From: kardion@kardion.es' . "\r\n" .
        'Reply-To: no-reply@kardion.com' . "\r\n" .
        'Content-type:text/html'. "\r\n" .
        'X-Mailer: PHP/' . phpversion();

        mail($para, $titulo, $mensaje, $cabeceras);
        return $mensaje;
    }



    public function activar($codigo){
        $query ="update usuarios set Estado = 'Activo' where CodigoActivacion = '$codigo'";
        $resp = parent::NonQuery($query);  
        if ($resp == 1 ){
            return true;
        }else{
            return false;
        }  
    }


 

    public function establecerpassword($contra,$UsuarioId){
        $password = parent::encriptar($contra);
        $query ="update usuarios set Password = '$password' where UsuarioId = '$UsuarioId'";
        $resp = parent::NonQuery($query);  
        if ($resp == 1 ){
            return true;
        }else{
            return false;
        }  
    }


 



}


?>