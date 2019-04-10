<?php

require_once("../clases/nominas_controller.php");
$Id = $_POST['cbonomina'];
$nomina = new nominas;
$listaDeducciones = $nomina->Perido_Deducciones($Id);


if($Id == 1){
    echo "
    
                                                     <div class='row-fluid'>
                                                        <div  class='span6 offset1'>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Dia de pago</label>
                                                                <div class='controls'>
                                                                <select id='cbodiapago' name='cbodiapago' >
                                                                    <option value='L' selected >Lunes</option>
                                                                    <option value='M'  >Martes</option>
                                                                    <option value='X'  >Miercoles</option>
                                                                    <option value='J'  >Jueves</option>
                                                                    <option value='V'  >Viernes</option>
                                                                    <option value='S'  >Sabado</option>
                                                                    <option value='D'  >Domingo</option>
                                                                </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                     </div> 
                                                  
                                                        <input type='hidden' name='txtprimerdiapago' id='txtprimerdiapago' value=''>
                                                        <input type='hidden' name='txtsegundodiapago' id='txtsegundodiapago' value=''>
                                                        <input type='hidden' name='txtmespago' id='txtmespago' value=''>
                                                        <input type='hidden' name='txtultimodiames' id='txtultimodiames' value=''>
                                                        <input type='hidden' name ='marked' id='marked'>
    
    
    ";
}else if($Id == 2){
    echo"
    
                    
                                                        <div class='row-fluid'>
                                                                <div  class='span4 offset1'>
                                                                    <div class='control-group'>
                                                                        <label class='control-label'>Primer dia de pago</label>
                                                                        <div class='controls'>
                                                                        <input class='span6' id='txtprimerdiapago' name='txtprimerdiapago' type='text' value='15'>
                                                                        <p class='help-block'></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div  class='span6 offset1'>
                                                                    <div class='control-group'>
                                                                        <label class='control-label'>Segundo dia de pago</label>
                                                                        <div class='controls'>
                                                                        <input class='span3' id='txtsegundodiapago' name='txtsegundodiapago' type='text' value='28'>
                                                                        <label class='checkbox inline'>
                                                                        <input id='txtultimodiames' type='checkbox' value='si'>
                                                                        Ultimo dia del mes
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                                <input type='hidden' name ='marked' id='marked'>
                                                                <input type='hidden' name='txtmespago' id='txtmespago' value=''>
                                                                <input type='hidden' name='cbodiapago' id='cbodiapago' value=''>
                                                                </div>
                                                        </div>
                                      

                                        
    
    
    ";

}else if($Id == 3){
    echo"
    
                    
                                                        <div class='row-fluid'>
                                                      
                                                                <div  class='span6 offset1'>
                                                                    <div class='control-group'>
                                                                        <label class='control-label'>Dia de pago</label>
                                                                        <div class='controls'>
                                                                        <input class='span3' id='cbodiapago' name='cbodiapago' type='text' value='28'>
                                                                        <label class='checkbox inline'>
                                                                        <input id='txtultimodiames' type='checkbox' value='si'>
                                                                        Ultimo dia del mes
                                                                        </div>
                                                                     </div>
                                                                         </label>
                                                                </div>
                                                                <input type='hidden' name='txtprimerdiapago' id='txtprimerdiapago' value=''>
                                                                <input type='hidden' name='txtsegundodiapago' id='txtsegundodiapago' value=''>
                                                                <input type='hidden' name='txtmespago' id='txtmespago' value=''>
                                                                <input type='hidden' name ='marked' id='marked'>
                                                        </div>
                                                     



";
}else if($Id == 4){
    echo " 
                                                        <div class='row-fluid'>
                                                        <div  class='span3 offset1'>
                                                            <div class='control-group'>
                                                                <label class='control-label'>Mes de pago</label>
                                                                <div class='controls'>
                                                                <select id='txtmespago' name='txtmespago' >
                                                                    <option value='Enero' selected >Enero</option>
                                                                    <option value='Febrero'  >Febrero</option>
                                                                    <option value='Marzo'  >Marzo</option>
                                                                    <option value='Abril'  >Abril</option>
                                                                    <option value='Mayo'  >Mayo</option>
                                                                    <option value='Junio'  >Junio</option>
                                                                    <option value='Agosto'  >Agosto</option>
                                                                    <option value='Septiembre'  >Septiembre</option>
                                                                    <option value='Octubre'  >Octubre</option>
                                                                    <option value='Noviembre'  >Noviembre</option>
                                                                    <option value='Diciembre'  >Diciembre</option>
                                                                </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div  class='span4 offset1'>
                                                            <div class='control-group'>
                                                                        <label class='control-label'>Dia de pago</label>
                                                                        <div class='controls'>
                                                                            <input class='span3' id='cbodiapagocboperiodo' name='cbodiapagocboperiodo' type='text' value='28'>
                                                                            <label class='checkbox inline'>
                                                                                <input id='txtultimodiames' type='checkbox' value='si'>
                                                                                Ultimo dia del mes
                                                                            </label>
                                                                        </div>                     
                                                            </div>
                                                        </div>
                                                        </div> 
                                                     
                                                        <input type='hidden' name='txtprimerdiapago' id='txtprimerdiapago' value=''>
                                                        <input type='hidden' name='txtsegundodiapago' id='txtsegundodiapago' value=''>
                                                        <input type='hidden' name ='marked' id='marked'>

";

}


?>


        <div class='row-fluid'>
        <div  class='span6 offset1'>
            <div class='control-group'>
                <label class='control-label'>Periodo de deducciones</label>
                <div class='controls'>
                <select id='cboperiodo' name='cboperiodo' >
                    <?php 
                            foreach ($listaDeducciones  as $key => $value) {
                                 $nom = $value['Nombre'];
                                 $id = $value['PeriodoDeduccionesId'];
                                 echo "<option value='$id' >$nom</option>";
                             }
                     ?>
                </select>
                </div>
            </div>
        </div>
        </div> 

