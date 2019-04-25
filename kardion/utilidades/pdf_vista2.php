<?php
require_once ('../clases/conexion.php');
//instanciamos 
$con = new conexion;
//recuperamos el codigo de la prueba que enviamso por get

if(isset($_SESSION['prueba'])){

$pruebaid = $_SESSION['prueba'];


//Buscamos los datos de la prueba
    $query = "select * from pruebas where PruebaId='$pruebaid'";
    $datosPrueba = $con->ObtenerRegistros($query); 
          $dc = $datosPrueba[0]['DolorCabeza'];
          $ma = $datosPrueba[0]['Mareo'];
          $fa = $datosPrueba[0]['FaltaAire'];
          $na = $datosPrueba[0]['Nauseas'];
          $dp = $datosPrueba[0]['DolorPecho'];
          $de = $datosPrueba[0]['Desmayo'];
          $pa = $datosPrueba[0]['Palpitaciones'];
          $fecha = $datosPrueba[0]['Fecha'];
          $sintomas = $datosPrueba[0]['Sintomas'];
          $momentomaximo = $datosPrueba[0]['MomentoMaximo'];         
//codigo del centro   
          $centroid = $datosPrueba[0]['CentroId'];
//Obtener los datos del centro
    $query2 = "select Nombre,Direccion,Telefono1,Logo from centros where CentroId='$centroid'";
    $datosCentro = $con->ObtenerRegistros($query2);
          $centro_nombre = $datosCentro[0]['Nombre'] ;
          $centro_direccion = $datosCentro[0]['Direccion'] ;
          $centro_telefono = $datosCentro[0]['Telefono1'] ;
          $centro_logo = $datosCentro[0]['Logo'] ;
//codigo del paciente 
        $personaid = $datosPrueba[0]['PersonaId'];
//Obtener los datos del paciente
    $query3 = "select concat(PrimerNombre, ' ' , PrimerApellido) as Nombre,FechaNacimiento,Sexo from personas where PersonaId = '$personaid'";
    $datosPersona =  $con->ObtenerRegistros($query3);
          $persona_nombre = $datosPersona[0]['Nombre'] ;
          $persona_sexo =$datosPersona[0]['Sexo'] ;
          $persona_fechanac =$datosPersona[0]['FechaNacimiento'] ;
          $edad = "";
          if($persona_fechanac){// calcular la edad del paciente
            $cumpleanos = new DateTime($persona_fechanac);
            $hoy = new DateTime();
            $cal =$hoy->diff($cumpleanos);
            $edad = $cal->y;
        }
//Obtener los datos de la prueba guardados por el medico
    $query4 = "select * from pruebas_resueltas where PruebaId = '$pruebaid'";
    $datosResultado =  $con->ObtenerRegistros($query4);
        if($datosResultado){
            $resultado_prediagnostico = $datosResultado[0]['PreDiagnostico'];
            $frecuenciaCariacaReposo =$datosResultado[0]['FrecuenciaCardiaca'];
            $frecuenciaMomentoMaximo = $datosResultado[0]['FCMomentoMaximo'];
            $conclusiones = $datosResultado[0]['Conclusiones'];
            $doctorId =  $datosResultado[0]['UsuarioId'];
            $fcprimerminuto = $datosResultado[0]['FCPrimerMinuto'];
            $fcsegundominuto = $datosResultado[0]['FCSegundoMinuto'];
        }else{
            $resultado_prediagnostico = "";
            $frecuenciaCariacaReposo =0;
            $frecuenciaMomentoMaximo = 0;
            $conclusiones = "";
            $doctorId =  0;
            $fcprimerminuto = 0;
            $fcsegundominuto = 0;
        }
       
//obtener los datos del medico 
    $query5 = "select * from dr_perfil where UsuarioId='$doctorId'";
    $datosDoctor =  $con->ObtenerRegistros($query5);
        if(!empty($datosDoctor)){
            $doctor_nombre =  $datosDoctor[0]['Nombre'];
            $doctor_firma =  $datosDoctor[0]['Firma'];
            $doctor_correo = $datosDoctor[0]['Correo'];
            $doctor_titulo = $datosDoctor[0]['Titulo'];
            $doctor_nocolegiado = $datosDoctor[0]['NoColegiado'];
        }else{
            $doctor_nombre =  "Kardi-on";
            $doctor_firma =  "";
            $doctor_correo = "";
            $doctor_titulo = "";
            $doctor_nocolegiado = "";
        }
//Obtener los archivos anexos
$query6 = "select * from pruebas_anexos where PruebaId='$pruebaid'";
$datosanexos =$con->ObtenerRegistros($query6);
//calculos    
if($frecuenciaMomentoMaximo && $frecuenciaCariacaReposo){
    $a50 = ($frecuenciaMomentoMaximo-$frecuenciaCariacaReposo)*0.5+($frecuenciaCariacaReposo);
    $a60 = ($frecuenciaMomentoMaximo-$frecuenciaCariacaReposo)*0.6+($frecuenciaCariacaReposo);
    $a65 = ($frecuenciaMomentoMaximo-$frecuenciaCariacaReposo)*0.65+($frecuenciaCariacaReposo);
    $a70 = ($frecuenciaMomentoMaximo-$frecuenciaCariacaReposo)*0.7+($frecuenciaCariacaReposo);
    $a75 = ($frecuenciaMomentoMaximo-$frecuenciaCariacaReposo)*0.75+($frecuenciaCariacaReposo);
    $a80 = ($frecuenciaMomentoMaximo-$frecuenciaCariacaReposo)*0.8+($frecuenciaCariacaReposo);
    $a85 = ($frecuenciaMomentoMaximo-$frecuenciaCariacaReposo)*0.85+($frecuenciaCariacaReposo);
    $a90 = ($frecuenciaMomentoMaximo-$frecuenciaCariacaReposo)*0.9+($frecuenciaCariacaReposo);
    $a95 = ($frecuenciaMomentoMaximo-$frecuenciaCariacaReposo)*0.95+($frecuenciaCariacaReposo);
    $a100 = ($frecuenciaMomentoMaximo-$frecuenciaCariacaReposo)*1+($frecuenciaCariacaReposo);
    $primermin = $frecuenciaMomentoMaximo - $fcprimerminuto;
    $segundomin =$fcprimerminuto -$fcsegundominuto;
    $indicecrono = round(((($frecuenciaMomentoMaximo)/(220-(int)$edad))*100),2);
    $resumencrono = round(((($frecuenciaMomentoMaximo-$frecuenciaCariacaReposo)/((220-(int)$edad)-$frecuenciaCariacaReposo)) *100),2);
}else{
    $a50 = 0;
    $a60 = 0;
    $a65 = 0;
    $a70 = 0;
    $a75 = 0;
    $a80 = 0;
    $a85 = 0;
    $a90 = 0;
    $a95 = 0;
    $a100 = 0;
    $primermin = 0;
    $segundomin =0;
    $indicecrono =0;
    $resumencrono = 0;
}
$imagen = "";
if ($primermin <= 12){
    $imagen="../assets/images/triste.png";
}else if($primermin >=13 && $primermin <30 ){
    $imagen="../assets/images/normal.png";
}else if($primermin >= 30){
    $imagen="../assets/images/feliz.png";
}
?>


<style>
        b{
            color: #6f6a6a;
        }

        h5{
            margin-bottom: 1px;
        }
        .check{
            width:15px;
            height: 15px;
        }
        .logos{
            width:100px;
            height:100px;
        }
        .logoskardion{
            width:90px;
            height:100px;
        }
        .logoizquierda{
            position:absolute;
            right:15px;
            top: 20px
        }
        .centro{
            text-align: center;
        }
        .cuadro{
            border-color: #a0a0a0;
            border-style: solid;
            border-width: 1px;
            padding:5px 5px 5px 5px;
            margin-top:1px;
        }
        .principal{
            margin-left : 25px;
            margin-right : 25px;
        }
        .check{
            height: 20px;
            width: 20px;
            background-color: #eee;
        }

        #customers {
      
            border-collapse: collapse;
            min-width: 100%;
        }

        #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 5px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
           
            background-color: #A4A4A4;
            color: black;
        }
        .firma{
                font-size: 0.3em;
        }
        .firmaimg{
            width:110px
        }

        .izquierda{
            text-align:left;
        }

        .centro{
            text-align:center; 
        }

        .derecha{
            text-align:right; 
        }

        .encabezado{
            color: black;
        }
        .gris{
            background-color:#A9D0F5;
        }
        .azul{
            background-color:#0099cb;
        }

        .verde{
            background-color:#009971;
        }
        .amarillo{
            background-color:#f29d0a;
        }
        .rojo{
            background-color:#d6454b;
        }

        .letrapeque{
            font-size: 10pt;
        }

    
</style>
<page>
<div class="principal">
    <div class="encabezado">
        <?php 
            if($centro_logo){
                echo " <img class='logos' src='../public/logos/$centro_logo' alt='Logo del centro'>";
            }else{
                echo " <img class='logos' src='../public/logos/logogym.png' alt='Logo del centro'>";
            }
        ?>
     

        <img class="logoskardion logoizquierda" src="../public/logos/kardion.png" alt="">
    </div>
</div>
<div class="centro">
        <h3>INFORME DE MOTORIZACIÓN DEPORTIVA CARDÍACA REMOTA</h3>
</div>
<div class="principal">
    <h5>DATOS DEL PACIENTE</h5>
    <div class="cuadro">
     <b>Nombre: </b> <?= $persona_nombre ?> &nbsp; &nbsp;<b>Género :</b> <?= $persona_sexo ?>  &nbsp; &nbsp;  <b>Edad :</b> <?=$edad?>  &nbsp; &nbsp; <b> Fecha Nac:</b><?=$persona_fechanac?> 
 
    </div>
</div>
<div class="principal">
    <h5>DATOS DEL CENTRO QUE REFIERE</h5>
    <div class="cuadro">
     <b>Nombre: </b> <?= $centro_nombre ?> &nbsp;<b>Tel :</b> <?=$centro_telefono?> &nbsp; <b>Dirección :</b> <?= $centro_direccion ?>   
    </div>
</div>
<div class="principal">
    <h5>DATOS DEL LA PRUEBA</h5>
    <div class="cuadro">
     <b>Fecha de realización :</b>  <?=$fecha?> &nbsp; &nbsp;  <b>FC en reposo :</b>  <?=$frecuenciaCariacaReposo?> lpm &nbsp; &nbsp;  <b>Momento de máximo esfuerzo:</b><?=$momentomaximo?> &nbsp; &nbsp; 
    </div>
</div>   
<div class="principal">
    <h5>SINTOMAS DURANTE LA PRUEBA</h5>
    <div class="cuadro">
            <table>
                <tr>
                    <?php
                        $x = 0;
                    if($dc == 1){ echo" <td><img class='check' src='../assets/images/check.png'> Dolor de cabeza</td>";
                        $x++; }?>
                    <?php if($ma == 1){ echo" <td><img class='check' src='../assets/images/check.png'> Mareo</td>";  
                        $x++; }?>
                    <?php if($fa == 1){ echo" <td><img class='check' src='../assets/images/check.png'> Falta de aire</td>"; 
                        $x++; }?>
                    <?php if($na == 1){ echo" <td><img class='check' src='../assets/images/check.png'> Nauseas </td>"; 
                        $x++; }?>
                    <?php if($dp == 1){ echo" <td><img class='check' src='../assets/images/check.png'> Dolor de pecho</td>";
                        $x++;  }?>
                    <?php if($de == 1){ echo" <td><img class='check' src='../assets/images/check.png'> Desmayo</td>"; 
                        $x++; }?>
                    <?php if($pa == 1){ echo" <td><img class='check' src='../assets/images/check.png'> Palpitaciones</td>"; 
                        $x++; }
                        
                        if($x == 0){
                            echo" <td><img class='check' src='../assets/images/check.png'> Niguno</td>";
                        }
                    ?>
        
               </tr>
            </table>
    </div>
</div>
<?php 
    if($sintomas){
        echo "
        <div class='principal'>
            <h5>INCIDENCIAS DURANTE LA SESIÓN</h5>
            <div class='cuadro'>
                $sintomas
            </div>
        </div>
        ";
    }
?>
<div class='principal'>
    <h5>DIAGNÓSTICO</h5>
    <div class="cuadro">
    <?=$resultado_prediagnostico?>
    </div>
    <br>
</div>
<div class='principal' style="text-align:center">
    <table id="customers">
        <tr>
            <th>Umbrales</th>
            <th colspan="3">Aeróbicos</th>
            <th colspan="2">Anaeróbicos</th>
            <th>Recuperación</th>
        </tr>
        <tr>
            <td class="encabezado">Zonas de entrenamiento</td>
            <td class="gris">Zona 1</td>
            <td class="azul">Zona 2</td>
            <td class="verde">Zona 3</td>
            <td class="amarillo">Zona 4</td>
            <td class="rojo">Zona 5</td>
            <td class="gris">1 min : <?=$primermin?> lpm</td>
        </tr>
        <tr>
            <td class="izquierda encabezado"> % FC por zonas</td>
            <td class="gris">50 - 60</td>
            <td class="azul">65 - 70</td>
            <td class="verde">75 - 80</td>
            <td class="amarillo">85 - 90</td>
            <td class="rojo">95 - 100</td>
            <td class="gris">2 min : <?=$segundomin?> lpm </td>
        </tr>
        <tr>
            <td class="izquierda encabezado">FC lpm</td>
            <td class="gris"><?=$a50?> - <?=$a60?></td>
            <td class="azul"><?=$a65?> - <?=$a70?></td>
            <td class="verde"><?=$a75?> - <?=$a80?></td>
            <td class="amarillo"><?=$a85?> - <?=$a90?></td>
            <td class="rojo"><?=$a95?> - <?=$a100?></td>
            <td class="gris">
                <?php echo"<img class='check' src='$imagen' alt=''>"?>
            </td>
        </tr>
    </table>
</div>


<div class='principal'>
    <h5>CONCLUSIONES Y RECOMENDACIONES </h5>
    <div class="">
    <p><?=$conclusiones?></p> 
    </div>
    
</div>
           
     

<page_footer>
<div class='principal'>
  <table >
    <tr>
                              
                                <td  style="text-align:center; padding-left: 10px; padding-right: 30px;  padding-top:65px;">
                                    <?php 
                                        if($doctor_firma ){
                                            echo "<img class='firmaimg'  src='../public/firmas/$doctor_firma'>";
                                        }
                                        if($doctor_nombre){
                                            echo"<br>  Dr. $doctor_nombre <br>";
                                        }
                                        if($doctor_titulo){
                                            echo "$doctor_titulo <br>" ;
                                        }
                                        if($doctor_nocolegiado){
                                            echo "N° colegiado : $doctor_nocolegiado <br>";
                                        }
                                        if($doctor_correo){
                                            echo " $doctor_correo  ";
                                        }
                                     ?>
                                </td>
                                <td style="width:500px; padding-top:155px">
                                 <h6 style="color:#968f8f"><b>Nota:</b> La monitorización cardiaca remota es una prueba cardiológica complementaria en el estudio de alteraciones de ritmo cardiaco. Que aporta valor pronóstico y diagnóstico independiente. No sustituye a otras pruebas cardiológicas como el ECG de 12 derivaciones o la prueba de esfuerzo convencional. El resultado y las recomendaciones de esta prueba son específicas para este estudio y no deberian extrapolarse a otras. Para obtener un juicio clínico definitivo sobre su salud cardiovascular deberías consultar este resultado con tu médico.</h6>
                                </td>
                              
                                
    </tr>
  </table>
    
</div>
</page_footer>
</page>

<?php
if(!empty($datosanexos)){
?>
<br><br>
<page>
    <div class='centro'>   
        <h3>Anexos</h3> 
    </div>
    <div class="principal">
        <p> Este informe contiene los siguientes archivos anexos, puedes verlos en los siguientes enlaces. </p>
        <?php
             $direccionweb = $_SERVER['HTTP_HOST'];
             $direccionarchivo = "/kardion/public/anexos/";
             $i = 0;
             foreach ($datosanexos as $key => $value) {
                 $i ++;
                $archivo = $value['Archivo'];
                $direccion = $direccionweb . $direccionarchivo .$archivo;
                echo" <a href='https://$direccion'  target='_blank'>http://$direccion </a> <br>";
             }
        
        ?>
    </div>

</page> 
 <?php } 

}else{
    echo"Existe un error con Kardion porfavor porngase en contacto con servicio al cliente";
}
?>