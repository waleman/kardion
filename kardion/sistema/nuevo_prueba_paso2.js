$(document).ready(function(){
    console.log("Documento externo listo");

    
    function mostrar(texto) {
        // Get the snackbar DIV
        document.getElementById("errorMensaje").innerText= texto;
        var x = document.getElementById("snackbar");
        // Add the "show" class to DIV
        x.className = "show";
        // After 3 seconds, remove the show class from DIV
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
    }

    $('#cbocentro').on('change', function() {
            var url = "../utilidades/dispositivos.php";
            $.ajax({
                type:"POST",
                url: url,
                data: $("#frm_filtrar").serialize(),
                success: function(data){
                        $("#resp").html(data);
                }
            });
            return false;
    });

    $('#btnenviar').click(function(){
        if(document.getElementById('cbocentro').value == 0){
                let val = "Debe seleccionar un centro";
                mostrar(val);
                return false;
        }else if(document.getElementById('cbodispositivo').value == 0){
                let val = "Debe seleccionar un dispositivo";
                mostrar(val);
                return false;
        }else{
        return true;
        } 
    });

    $("#btnanexos").click(function(){
        $('#modaluploadanexos').modal('show');
            return false;
    });




});