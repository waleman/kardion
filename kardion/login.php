
<!DOCTYPE html>
<html>
  <head>
  <link rel='shortcut icon' href='assets/images/kardion.png' />
    <title>Kardi-on</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta content='text/html;charset=utf-8' http-equiv='content-type'>
    <meta content='Flat administration template for Twitter Bootstrap.' name='description'>
    <link href="assets/stylesheets/login.css" rel="stylesheet" >
    <link href="assets/stylesheets/toast.css" rel="stylesheet" >

    <!------ Include the above in your HEAD tag ---------->

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
      

    <?php 
         session_start();  
         require_once('clases/usuario_controller.php');
         require_once('clases/roles_controller.php');
         require_once('clases/cargar.php');
         $rol = new roles;
         $user  = new usuario;
         $Recursos = new cargar;
         $Recursos->salir();
         echo $Recursos->LoadJquery();
         echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
         
    ?>

           

    <script>
    
          $(document).ready(function(){

                function mostrar(texto) {
                    // Get the snackbar DIV
                    document.getElementById("errorMensaje").innerText= texto;
                    var x = document.getElementById("snackbar");
                    // Add the "show" class to DIV
                    x.className = "show";
                    // After 3 seconds, remove the show class from DIV
                    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
                }

              $("#btnlogin").click(function (){
                  if( !document.getElementById('txtemail').value){
                    let val = "Debe escribir un correo valido";
                    mostrar(val);
                    return false;
                  }else if( !document.getElementById('txtpassword').value){
                    let val = "Debe escribir su contraseña";
                    mostrar(val);
                    return false;
                  }else{
                    return true;
                  }
              });
           });
          
    </script>


  </head>

<body>
<?php
                      /*programacion del login */
                        if(isset($_POST["btnlogin"])){
                              $correo = $_POST["txtemail"];
                              $pass = $_POST["txtpassword"];
                              $auth =  $user ->login($correo,$pass);
                             if (!$auth || empty($auth)){
                                    $checkmail = $user->login_fail_checkmail($correo);
                                    if($checkmail == false){
                                        echo "
                                        <script>
                                            swal({
                                              title: 'Error!',
                                              text: '   El correo con el que esta intentando acceder no existe en nuestra base de datos. ',
                                              icon: 'warning',
                                            });
                                         
                                         </script>
                                        ";
                                    }else{
                                        echo "  
                                          <script>
                                          swal({
                                            title: 'Error!',
                                            text: '   La contraseña que ingreso no coincide con la que tenemos almacenda.',
                                            icon: 'warning',
                                          });
                                      
                                          </script>
                                        ";
                                    }
                                   
                             }else{
                                $estado = $auth[0]['Estado'];
                                $Correo = $auth[0]['Usuario'];
                                $Id = $auth[0]['UsuarioId'];
                                $Rol = $auth[0]['RolId'];
                                $Persona = $auth[0]['PersonaId'];
                                $MasterComp = $auth[0]['MasterCompaniaId'];
                                $verificado = $auth[0]['Verificado'];
                                    /*verificar que la cuenta este activa*/
                                    if($estado == 'Activo'){

                                      if($verificado == 0 && $Rol == 3){
                                        echo "
                                            <script>
                                            swal({
                                              title: 'Usuario pendiente!',
                                              text: '¡Su cuenta esta pendiente de activación! El personal de Kardion se pondra en contracto con usted',
                                              icon: 'error',
                                            });
                                        
                                            </script>

                                        ";

                                      }else{
                                        $datos = [
                                          'Usuario' => $Correo,
                                          'PersonaId' => $Persona,
                                          'UsuarioId' => $Id,
                                          'RolId' => $Rol,
                                          'Estado' => $estado,
                                          'MasterCompaniaId' =>$MasterComp 
                                          ];
                                          $_SESSION['k6'] = $datos;
                                        /* selecciona donde redirigir */
                                          $direccion = $rol->selectRol($Rol);
                                          if($direccion){
                                            header("Location: sistema/$direccion");
                                          }else{
                                            echo "<script language='javascript'
                                              swal({
                                                title: 'Error!',
                                                text: ' Existe un error relacionado con los roles .',
                                                icon: 'error',
                                              });
                                            </script>";
                                          }

                                      }
                                             

                                    }else if($estado == 'Pendiente'){
                                        echo "

                                            <script>
                                            swal({
                                              title: 'Revise su correo electrónico!',
                                              text: '¡Su cuenta esta pendiente de activación! le hemos enviado un correo electronico para activarla',
                                              icon: 'info',
                                            });
                                        
                                            </script>

                                        ";
                                    }else{
                                        echo "
                                            <script>
                                            swal({
                                              title: 'Su cuenta esta inactiva!',
                                              text: 'Póngase en contacto con soporte tecnico',
                                              icon: 'info',
                                            });
                                        
                                            </script>
                                        ";
                                    }
                             };
                        }
                ?>
  
<section class="login-block">
  <div class="container">
	  <div class="row">
	    	<div class="col-md-4 login-sec">
		      <h2 class="text-center">
          <img  width="200px" src="public/logos/kardionlateral.png" alt="">
          </h2>
              <form class="login-form"  method="POST">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="text-uppercase">Username</label>
                        <input type="email" class="form-control" placeholder=""  id="txtemail" name="txtemail">
                        
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword1" class="text-uppercase">Password</label>
                        <input type="password" class="form-control" placeholder="" name="txtpassword" id="txtpassword">
                      </div>
                      
                      
                        <div class="form-check">
                      
                        <button type="submit" class="btn btn-block float-right" name="btnlogin" id="btnlogin">Entrar</button>
                        <a href="registrarse.php" style="background-color: #2196F3; color: #FFF;" class="btn btn-block float-right">Registrate</a>
                        
                      </div>
              </form>
                  <div class="copy-text">
                  
                    <br>
                    Olvide mi contraseña<a href="recuperar.php"> Recuperar</a>
                    <!-- <br>
                   Correo de confirmacion<a href="recuperar.php"> Recuperar</a> -->
                  </div>
           <br>
        </div>
              <div class="col-md-8 banner-sec">
                      <div class="carousel-item ">
                        <img class="d-block img-fluid" src="assets/images/01.jpg" >
                        <div class="carousel-caption d-none d-md-block">
                                <div class="banner-text">
                                    <h2>
                                    <!-- <img  width="200px" src="public/logos/kardionwhite.png" alt="">
                                     -->
                                    </h2>
                                    <!-- <p>Lorem ipsum dolor sit amet, conset labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation</p> -->
                                </div>	
                        </div>
                      </div>
                      <div class="carousel-item ">
                        <img class="d-block img-fluid" src="assets/images/02.jpg" >
                        <div class="carousel-caption d-none d-md-block">
                                <div class="banner-text">
                                    <h2>
                                    <!-- <img  width="200px" src="public/logos/kardionwhite.png" alt="">
                                     -->
                                    </h2>
                                    <!-- <p>Lorem ipsum dolor sit amet, conset labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation</p> -->
                                </div>	
                        </div>
                      </div>
                      <div class="carousel-item active">
                        <img class="d-block img-fluid" src="assets/images/03.jpg" >
                        <div class="carousel-caption d-none d-md-block">
                                <div class="banner-text">
                                    <h2>
                                    <!-- <img  width="200px" src="public/logos/kardionwhite.png" alt="">
                                     -->
                                    </h2>
                                    <!-- <p>Lorem ipsum dolor sit amet, conset labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation</p> -->
                                </div>	
                        </div>
                      </div>
                 
              </div>
          
                      </div>	   
              </div>
      </div>
  </div>
</section>

              <div id="snackbar">
                     <label id="errorMensaje"></label>
             </div>

</body>

</html>


