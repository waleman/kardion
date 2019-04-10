

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
                  }else{
                    return true;
                  }
              });
           });
          
    </script>


  </head>

<body>
<?php
    /*programacion de enviar correo */
      if(isset($_POST["btnlogin"])){
        $email = $_POST['txtemail'];
        $resp = $user->check_not_busy($email);

        if($resp){
          $user->eviarEmail_forgetpassword($email);
          echo"
          <script>
              swal({
                title: 'Hecho!',
                text: ' Te hemos enviado un correo electronico. ',
                icon: 'success',
              });
          </script>
          ";
        }else{
          echo"
          <script>
            swal({
              title: 'Error!',
              text: 'El correo que has escrito no se encuentra en nuestra base de datos',
              icon: 'error',
            }); 
            </script>        
          ";
        }

   

      }
 ?>
  
<section class="login-block">
  <div class="container">
	  <div class="row">
                <div class="col-md-4">
                <br><br><br><br><br>
                       <h2 class="text-center">
                         <img  width="200px" src="public/logos/kardionlateral.png" alt="">
                       </h2>
                </div>
                <div class="col-md-4 login-sec">
                                    
                       <form class="login-form"  method="POST">
                            <div class="form-group ">
                                  <h5 class="text-center">Si has olvidado tu contraseña</h5>
                                              <p> Te enviaremos un correo electronico para que puedas recuperarla. </p>
                            </div>
                               <div class="form-group">
                                  <label for="exampleInputEmail1" class="text-uppercase">Correo electronico</label>
                                  <input type="email" class="form-control" placeholder=""  id="txtemail" name="txtemail">              
                               </div>                                               
                                                  
                            <div  style="text-align:center">               
                               <button type="submit" class="btn btn-block " name="btnlogin" id="btnlogin">Enviar correo</button>  
                            </div>
                      </form>
                      <div class="copy-text">
                        <br>
                        He recordado mi contraseña<a href="login.php"> Regresar al sistema</a>
                      </div>
                                      
                </div>
                <div class="col-md-4">
                
                </div>
         
      </div>
  </div>
</section>

              <div id="snackbar">
                     <label id="errorMensaje"></label>
             </div>

</body>

</html>


