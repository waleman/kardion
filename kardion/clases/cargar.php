<?php
 
//programador:  Jose wilfredo Aleman Giron
//fecha : 07/12/2018
//ubicacion : Donostia San Sebastian  España
//pagina web :www.wc-solutions.net
//Todos los derechos reservados ®

require_once('conexion.php');

class cargar extends conexion {

    public function salir(){
        session_unset();
    }
    
    
    public function sessionDataSistem(){
        session_start();
    }

    

    public function compdata(){
        if(isset($_SESSION['k6'])){
            $master = $_SESSION['k6']['MasterCompaniaId'];
            $query="select Nombre from mastercompania where MasterCompaniaId = '$master'";
            $dato = parent::ObtenerRegistros($query);
            if(!empty($dato)){
                return $dato[0]['Nombre'];
            }
            
        }else{
            echo"<script language='javascript'>window.location='../login.php'</script>;";
        }
    }

    public function userdata(){
        if(isset($_SESSION['k6'])){
            $usuarioId = $_SESSION['k6']['UsuarioId'];
            $query="select Usuario from usuarios where UsuarioId = '$usuarioId'";
            $dato = parent::ObtenerRegistros($query);
            return $dato[0]['Usuario'];
        }else{
            echo"<script language='javascript'>window.location='../login.php'</script>;";
        }
    }

    public function roldata(){
        $rol = $_SESSION['k6'];
        if(isset($_SESSION['k6'])){
            $rolid = $_SESSION['k6']['RolId'];
            return $rolid;
        }else{
            echo"<script language='javascript'>window.location='../login.php'</script>;";
        }
    }

    public function loadJS($direccion= ''){
        $complemento ='';
        if ($direccion == "sistema"){
            $complemento ="../";
        }

        $ListaJs =array(
            'jquery.min.js',
            'jquery.mobile-events.min.js',
            'jquery-migrate.min.js',
            'jquery-ui.min.js',
            'jquery.ui.touch-punch.min.js',
            'bootstrap.min.js',
            'excanvas.js',
            'jquery.sparkline.min.js',
            'flot.min.js',
            'flot.resize.js',
            'flot.pie.js',
            'bootstrapSwitch.min.js',
            'fullcalendar.min.js',
            'jquery.dataTables.min.js',
            'jquery.dataTables.columnFilter.js',
           // 'wysihtml5.min.js',
            'bootstrap-wysihtml5.js',
            'select2.js',
            'bootstrap-colorpicker.min.js',
            'mention.min.js',
            'bootstrap-inputmask.min.js',
            'bootstrap-fileinput.js',
            'modernizr.min.js',
            //'retina.js',
            'tmpl.min.js',
            'load-image.min.js',
            'canvas-to-blob.min.js',
            'jquery.timeago.js',
            'jquery.slimscroll.min.js',
            'jquery.autosize-min.js',
            'charCount.js',
            'jquery.validate.min.js',
            'additional-methods.js',
            'naked_password-0.2.4.min.js',
            'jquery.nestable.js',
            'bootstrap-tabdrop.js',
            'jquery.jgrowl.min.js',
            'bootbox.min.js',
            'bootstrap-editable.min.js',
            'wysihtml5.js',
            'jquery.dynatree.min.js',
            'bootstrap-datetimepicker.js',
            'moment.min.js',
            'bootstrap-daterangepicker.js',
            'bootstrap-maxlength.min.js',
            'twitter-bootstrap-hover-dropdown.min.js',
            'slidernav-min.js',
            'wizard.js',
            'nav.js',
            'tables.js',
            'theme.js',
            'jquery.mockjax.js',
            'inplace_editing.js',
            'charts.js',
            'demo.js'
        );

        $script = "";
        foreach ($ListaJs as $key => $value) {
            $script .= "<script src='".$complemento."assets/javascripts/" .$value ."'". " type='text/javascript'></script>";
        }
        return $script;

    }

/* CSS y JS PARA LOS FILE UPLOAD */
    public function loadFileuploadJS($direccion= ''){
        $complemento ='';
        if ($direccion == "sistema"){
            $complemento ="../";
        }

        $ListaJs =array(
            'dropzone.js'      
    
        );

        $script = "";
        foreach ($ListaJs as $key => $value) {
            $script .= "<script src='".$complemento."assets/components/" .$value ."'". " type='text/javascript'></script>";
        }
        return $script;
    }


    public function loadFileuploadCSS($direccion=""){
        $complemento ="";
        if ($direccion == "sistema"){
            $complemento ="../";
        }

        $ListaCss =array(
            'dropzone.css',
        );

        
        $css = "";
        foreach ($ListaCss as $key => $value) {
            $css .= "<link href='".$complemento."assets/components/" .$value ."'". " media='all' rel='stylesheet' type='text/css'>";
        }

        return $css;


    }



 
/* ------------------------------------------- */

    public function LoadJquery($direccion=""){
        $complemento ="";
        if ($direccion == "sistema"){
            $complemento ="../";
        }

        $ListaJs =array(
            'jquery.min.js',
        );

        $script = "";
        foreach ($ListaJs as $key => $value) {
            $script .= "<script src='".$complemento."assets/javascripts/" .$value ."'". " type='text/javascript'></script>";
        }

        return $script;

    }

    public function LoadCssLogin($direccion=""){
        $complemento ="";
        if ($direccion == "sistema"){
            $complemento ="../";
        }

        $ListaCss =array(
            'bootstrap/bootstrap.css',
            'bootstrap/bootstrap-responsive.css',
            'jquery_ui/jquery.ui-1.10.0.custom.css',
            'jquery_ui/jquery.ui.1.10.0.ie.css',
            'light-theme.css',
            'colorines.css',
            'theme-colors.css',
            'demo.css',
            'toast.css',
            'myfileupload.css'
        );

        
        $css = "";
        foreach ($ListaCss as $key => $value) {
            $css .= "<link href='".$complemento."assets/stylesheets/" .$value ."'". " media='all' rel='stylesheet' type='text/css'>";
        }

        return $css;


    }


 
    public function LoadCssSystem($direccion=""){
        $complemento ="";
        if ($direccion == "sistema"){
            $complemento ="../";
        }

        $ListaCss =array(
            'bootstrap/bootstrap.css',
            'bootstrap/bootstrap-responsive.css',
            'jquery_ui/jquery.ui-1.10.0.custom.css',
            'jquery_ui/jquery.ui.1.10.0.ie.css',
            'light-theme.css',
            'theme-colors.css',
            'demo.css',
            'toast.css',
            'colorines.css',
            'myfileupload.css',
            'plugins/bootstrap_switch/bootstrap-switch.css',
            'plugins/xeditable/bootstrap-editable.css',
            'plugins/common/bootstrap-wysihtml5.css',
            'plugins/common/bootstrap-wysihtml5.css',
            'plugins/common/bootstrap-wysihtml5.css',
            'plugins/jquery_fileupload/jquery.fileupload-ui.css',
            'plugins/fullcalendar/fullcalendar.css',
            'plugins/select2/select2.css',
            'plugins/mention/mention.css',
            'plugins/tabdrop/tabdrop.css',
            'plugins/jgrowl/jquery.jgrowl.min.css',
            'plugins/datatables/bootstrap-datatable.css',
            'plugins/dynatree/ui.dynatree.css',
            'plugins/bootstrap_colorpicker/bootstrap-colorpicker.css',
            'plugins/bootstrap_datetimepicker/bootstrap-datetimepicker.min.css',
            'plugins/bootstrap_daterangepicker/bootstrap-daterangepicker.css',
            'plugins/flags/flags.css',
            'plugins/slider_nav/slidernav.css',
            'plugins/fuelux/wizard.css' );

            $css = "";
            foreach ($ListaCss as $key => $value) {
                $css .= "<link href='".$complemento."assets/stylesheets/" .$value ."'". " media='all' rel='stylesheet' type='text/css'>";
            }
    
            return $css;
    }


    public function PrintHead(){
        return "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <meta http-equiv='X-UA-Compatible' content='ie=edge'>
            <link rel='shortcut icon' href='../assets/images/kardion.png' />
            <title>Kardion</title>";
    }

    public function PrintBodyOpen(){
        return "
        </head>
        <body class='contrast-dark fixed-header fixed-navigation'>";
    }

    public function PrintBodyClose(){
        return "
        </body>
        </html>";
    }


    public function PrintHeader(){
        $compania = $this->compdata();
        $user = $this->userdata();
     return "    
      <header>
                <div class='navbar navbar-fixed-top'>
                <div class='navbar-inner'>
                    <div class='container-fluid'>
                    <a class='brand' href='#'>
                        <i class='icon-heart'></i>
                        <span class='hidden-phone'>Kardi-on</span>
                    </a>
                    <a class='toggle-nav btn pull-left' href='#'>
                        <i class='icon-reorder'></i>
                    </a>
                    <ul class='nav pull-right'>
                        <li class='dropdown light only-icon'>
                        <a name='btnperfil' id='btnperfil' class='dropdown-toggle' data-toggle='dropdown' href='perfil_empresa.php'>
                            <i class='icon-user'> </i> 
                            $user
                        </a>
                        </li>

                        <li class='dropdown light only-icon'>
                        <a class='dropdown-toggle' data-toggle='dropdown' href='#'>
                            <i class='icon-building'> </i> 
                            $compania
                        </a>
                        </li>

                        <li class='dropdown light only-icon'>
                        <a name='btnsalir' id='btnsalir' class='dropdown-toggle' data-toggle='dropdown' href='../login.php'>
                            <i class='icon-signout'> </i> 
                            Salir
                        </a>
                        </li>

                    </ul>
                    </div>
                </div>
                </div>
      </header>
      <script>
      $(document).ready(function(){
          $('#btnsalir').click(function(){
              window.location='../login.php';
          });

          $('#btnperfil').click(function(){
            window.location='perfil_empresa.php';
        }); 
      });
    </script>
      ";
    }


    public function PrintSideMenu(){
        $rol =$this->roldata();
        if($rol ==  4){ //propietario
            $dash ="dashboard.php";
            $centros ="lista_centros.php";
            $pruebas = "lista_pruebas.php";
            $finalizadas ="lista_pruebas_finalizadas.php";
            $usuarios ="lista_usuarios.php'";
            $aparatos ="lista_dispositivos.php";
            $lock = "";
        }else if($rol == 5){ //asistente
            $dash ="dashboard.php";
            $centros ="#";
            $pruebas = "lista_pruebas.php";
            $finalizadas ="lista_pruebas_finalizadas.php";
            $usuarios ="#'";
            $aparatos ="#";
            $lock = " <i class='icon-lock text-right' style ='color:#FFBF00'></i>";
        }else if($rol == 6){//recepcion
            $dash ="dashboard.php";
            $centros ="#";
            $pruebas = "lista_pruebas.php";
            $finalizadas ="lista_pruebas_finalizadas.php";
            $usuarios ="#'";
            $aparatos ="#";
            $lock =  "<i class='icon-lock text-right' style ='color:#FFBF00'></i>";
        }

        return "
        
                        <nav class='main-nav-fixed' id='main-nav'>
                        <div class='navigation'>
                  
                        <ul class='nav nav-stacked'>
                            <li class=''>
                                <a href='$dash' class='color-gris'>
                                    <i class='icon-dashboard'></i>
                                    <span>Inicio</span>
                                </a>
                            </li>
                            <li class=''>

                                <a href='$centros'  class='color-azul'>
                                    <i class='icon-group'></i>
                                    <span>Centros </span>
                                     $lock
                                </a>
                            </li>
                           

                            <li>
                            <a href='$pruebas'  class='color-verde'>
                               <i class='icon-file-alt'></i>
                               <span>Pruebas</span>
                            
                            </a>
                           </li>

                           <li>
                           <a href='$finalizadas' class='color-amarillo'>
                           <i class='icon-ok'></i>
                              <span>Pruebas finalizadas</span>
                             
                           </a>
                          </li>
                          <li class=''>
                          <a href='$usuarios  class='color-naranja'>
                              <i class='icon-user'></i>
                              <span>Usuarios</span>
                              $lock
                          </a>
                         </li>
                          <li class=''>
                          <a href='$aparatos'  class='color-rojo'>
                              <i class='icon-bolt'></i>
                              <span>Dispositivos</span>
                              $lock
                          </a>
                      </li>
                      
                        </ul>
                        </div>
                    </nav>
        
        ";
    }


}

?>