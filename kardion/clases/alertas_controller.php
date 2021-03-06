<?php 




class alertas {


public function success($titulo,$texto){
    return  "<script>
    swal({
            title: '$titulo',
            text: '$texto',
            type: 'success',
            icon: 'success'
    });
  </script>";
}

public function error($titulo,$texto){
    return "<script>
    swal({
            title: '$titulo',
            text: '$texto',
            type: 'error',
            icon: 'error'
    });
  </script>";
}

public function info($titulo,$texto){
    return  "<script>
    swal({
            title: '$titulo',
            text: '$texto',
            type: 'info',
            icon: 'info'
    });
  </script>";
}

public function warning($titulo,$texto){
    return  "<script>
    swal({
            title: '$titulo',
            text: '$texto',
            type: 'warning',
            icon: 'warning'
    });
  </script>";
}

public function successRedirect($titulo,$texto,$enlace){
   return  "<script>
    swal({
            title: '$titulo',
            text: '$texto',
            type: 'success',
            icon: 'success'
    }).then(function() {
            window.location = '$enlace';
    });
  </script>";
}

public function errorRedirect($titulo,$texto,$enlace){
    return  "<script>
    swal({
            title: '$titulo',
            text: '$texto',
            type: 'error',
            icon: 'error'
    }).then(function() {
            window.location = '$enlace';
    });
  </script>";
}

public function infoRedirect($titulo,$texto,$enlace){
    return  "<script>
    swal({
            title: '$titulo',
            text: '$texto',
            type: 'info',
            icon: 'info'
    }).then(function() {
            window.location = '$enlace';
    });
  </script>";
}

public function warningRedirect($titulo,$texto,$enlace){
    return  "<script>
    swal({
            title: '$titulo',
            text: '$texto',
            type: 'warning',
            icon: 'warning'
    }).then(function() {
            window.location = '$enlace';
    });
  </script>";
}


}




?>