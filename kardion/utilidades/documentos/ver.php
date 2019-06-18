<?php
 require_once ('../../clases/clasificacion_controller.php');
 require_once ('../../clases/personas_controller.php');
//instanciamos 
 $_clasificaicon = new clasificacion;
 $_personas = new personas;
//recuperamos el codigo de la prueba que enviamso por get
$id =$_GET['id'];
$personaid = $_GET['persona'];

$datosPersona = $_personas->BuscarUna($personaid);
if(!empty($datosPersona)){
    $nombre = $datosPersona['PrimerNombre'] . " ". $datosPersona['SegundoNombre'] . " " . $datosPersona['PrimerApellido'] . " ". $datosPersona['SegundoApellido'];

}else{
    $nombre = "Nombre del paciente";
}


//Buscamos los datos de la prueba
$datos = $_clasificaicon->ObtenerClasificacion($id);
$titulo = $datos[0]['Titulo'];
$texto = $datos[0]['Texto'];
$fecha = date('d-m-Y');
?>


<style>
        b{
           color:#000;

        }
        p{
            color: #544f4f;
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
            background-color:#1c83d6;
        }
        .azul{
            background-color:#4fd056;
        }

        .verde{
            background-color:#f7e545;
        }
        .amarillo{
            background-color:#ff9800;
        }
        .rojo{
            background-color:#f44336;
        }

        .letrapeque{
            font-size: 10pt;
        }

        

    
</style>

<div class="principal">
    <div class="encabezado">
        
    </div>
</div>
<div class="centro">
        <h3><?=$titulo?></h3>
</div>
<div class="principal">
    <h5>DATOS DEL PACIENTE</h5>
    <div class="cuadro">
        <b>Nombre</b> <?=$nombre?><br>
        <b>Fecha</b> <?=$fecha?>
    </div>
</div>



<div class='principal'>
  <p>
   <?=$texto?>
   </p> 
</div>
           
     

<div class='principal'>
  <table >
    <tr>
                              
                                <td  style="text-align:center; padding-left: 10px; padding-right: 30px;  padding-top:65px;">
                                    
                                </td>
                                <td style=" width:600px; padding-top:130px;text-align:center;color:#5b5858">


                                    __________________________________________________ <br>
                                    Firma del paciente
                                              <br><br> 

                             
                                </td>

                              
                                
    </tr>
  </table>
    
</div>


