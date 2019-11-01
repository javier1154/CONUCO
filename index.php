<?php

include( "funciones/conexion bd mysql.php" );
include("funciones/fechas_barra.php");

conectar_bd_mysql();

// si la varibale enviada por url cmd es igual a cerrar, cierra la session del usuario.
if ( $_GET[ 'cmd' ] == 'cerrar' ) {

  if ( empty( $_SESSION[ 'CENSO_activa' ] ) ) {
    session_destroy();
    $alert = array("success", "Sesi&oacute;n Finalizada");

  } else {
    session_destroy();
    $alert = array("success", "Sesi&oacute;n Finalizada");
  }

}else{

	if(isset($_SESSION[ 'CENSO_activa' ])){
		header("location: inicio.php");
	}else{
		session_destroy();
	}
  
}



if ( $_GET[ 'cmd' ] == 'cerrar' ) {

  
  if ( empty( $_SESSION[ 'CENSO_activa' ] ) ) {
    session_destroy();
    $alert = array("success", "Sesi&oacute;n Finalizada");

  } else {
    session_destroy();
    $alert = array("success", "Sesi&oacute;n Finalizada");
  }

}elseif( $_GET["cmd"] == 'ure' ){

  $alert = array("success", "Usuario registrado satisfactoriamente.");

}else{

  if(isset($_SESSION[ 'CENSO_activa' ])){
    header("location: inicio.php");
  }else{
    session_destroy();
  }

}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// si la longitud de la variable usuario o la de clave es mayor a cero.
if ((isset( $_POST[ 'cedula' ] )) and (isset( $_POST[ 'clave' ] ))){


  $cedula = $_POST[ 'cedula' ];
  $clave = sha1($_POST[ 'clave' ]);

  $cons = mysql_query("select * from usuario where cedula = '$cedula' and id_usuario in (select id_usuario from permisologia where clave = '$clave')");

  $existe = mysql_num_rows($cons);

  if($existe > 0){

    $row = mysql_fetch_array($cons);
    $id_usuario = $row["id_usuario"];
    $nombres = $row["nombres"];
    $cedula = $row["cedula"];

    $cons2 = mysql_query("select * from permisologia where id_usuario = '$id_usuario' and status = 'Habilitado'");
    $existe_perm = mysql_num_rows($cons2);

    if($existe_perm > 0){
      
      $row2 = mysql_fetch_array($cons2);

      $tipo = $row2[ "tipo" ];

      if($tipo == "Administrador"){

        session_start();

        $_SESSION[ 'CENSOstatus_permisologia' ] = $row2[ "status" ];
        $_SESSION[ 'CENSOpermisologia' ]        = $row2[ "tipo" ];
        $_SESSION[ 'CENSOid_permisologia' ]     = $row2[ "id_permisologia" ];

        $_SESSION[ 'CENSO_activa' ]            = 'true';
        $_SESSION[ 'CENSOid_usuario' ]          = $id_usuario;
        $_SESSION[ 'CENSOnombres' ]              = $nombres;
        $_SESSION[ 'CENSOcedula' ]            = $cedula;
        $_SESSION[ 'CENSOimagen' ] = 'img/usuario.jpg';

        $_SESSION[ 'CENSOid_censo' ] = "1";

        header( "location:inicio.php" );

      }elseif($tipo == "Coordinador Consejo"){

        session_start();

        $_SESSION[ 'CENSOstatus_permisologia' ] = $row2[ "status" ];
        $_SESSION[ 'CENSOpermisologia' ]        = $row2[ "tipo" ];
        $_SESSION[ 'CENSOid_permisologia' ]     = $row2[ "id_permisologia" ];

        $_SESSION[ 'CENSO_activa' ]            = 'true';
        $_SESSION[ 'CENSOid_usuario' ]          = $id_usuario;
        $_SESSION[ 'CENSOnombres' ]              = $nombres;
        $_SESSION[ 'CENSOcedula' ]            = $cedula;
        $_SESSION[ 'CENSOimagen' ] = 'img/usuario.jpg';

        $_SESSION[ 'CENSOid_censo' ] = "1";

        header( "location:coord.php" );

      }elseif($tipo == "Coordinador Comuna"){

        session_start();

        $_SESSION[ 'CENSOstatus_permisologia' ] = $row2[ "status" ];
        $_SESSION[ 'CENSOpermisologia' ]        = $row2[ "tipo" ];
        $_SESSION[ 'CENSOid_permisologia' ]     = $row2[ "id_permisologia" ];

        $_SESSION[ 'CENSO_activa' ]            = 'true';
        $_SESSION[ 'CENSOid_usuario' ]          = $id_usuario;
        $_SESSION[ 'CENSOnombres' ]              = $nombres;
        $_SESSION[ 'CENSOcedula' ]            = $cedula;
        $_SESSION[ 'CENSOimagen' ] = 'img/usuario.jpg';

        $_SESSION[ 'CENSOid_censo' ] = "1";

        header( "location:coord_comuna.php" );

      }else{
        $alert = array("error", "Usted no se encuentra habilitado para ingresar a este sistema.");
      }
    }else{
      $alert = array("error", "Usted no se encuentra habilitado para ingresar a este sistema.");
    }
  }else{
    $alert = array("error", "Usuario o clave inválida.");
  }
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
<!DOCTYPE html>
<html style="">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CENSO | Ingresar al Sistema</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico" />
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="plugins/sweetalert/dist/sweetalert.min.css">
  <link rel="stylesheet" href="plugins/plantilla/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="css/app.min.css">
  <link rel="stylesheet" href="css/index.min.css">
  <style>
    body.login-page, .btn-primary{
      background-color: #008080;
    }

    .btn-primary:hover{
      background-color: #008080;
    }

    div.sweet-alert button.confirm{
      background-color: #008080 !important;;
    }

    #particles {
      width: 100%;
      height: 100%;
      overflow: hidden;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      position: absolute;
      z-index: -2;
    }
  </style>  
</head>
<body class="hold-transition login-page">



<div class="wrapper">

  <div class="presentacion">
    <img src="img/cintillo.jpg" style="width: 100%; height: 140px">
    <div class="mask"></div>
  </div>

  <div class="content-wrapper">

    <section class="content">

    <br><br><br><br><br>
    <div class="login-box">
      <!--<div class="login-logo" style="font-size: 60px; color: #FFF; -webkit-text-fill-color: #FFF; -webkit-text-stroke-color: #000; -webkit-text-stroke-width: 2px; ">
        <b>CONUCO</b>
      </div>-->
      <div class="login-logo">
        <img src="img/logo.png" style="width:240px; margin-top: 0px; margin-bottom: -106px;">
      </div>
      
      <div class="login-box-body" style="border-radius: 6px;">

        <p class="login-box-msg" style="font-weight: bold; margin-top: 80px; ">INGRESAR AL SISTEMA</p>

        <form action="index.php" method="POST">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="Cédula de identidad (V-00000000)" required name="cedula" value="<?php if(isset($_POST['cedula'])) echo $_POST['cedula'] ?>">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Clave" required name="clave">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <button type="submit" class="btn btn-danger btn-block btn-flat"><b><i class="fa fa-sign-in"></i> Ingresar</b></button>
            </div>
          </div>
        </form>
      </div>
    </div>

  </section>
  </div>
</div>


<div id="particles" style="z-index: 1"><canvas class="pg-canvas" width="1349" height="362"></canvas></div>

<script src="plugins/jQuery/jQuery-2.2.0.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="plugins/sweetalert/dist/sweetalert.min.js"></script>
<script src="js/particle.min.js"></script>
<script src="plugins/input-mask/inputmask.js" /></script>
<script src="plugins/input-mask/jquery.inputmask.js" /></script>

<script>

  document.addEventListener('DOMContentLoaded', function () {
    particleground(document.getElementById('particles'), {
      dotColor: '#FFF',
      lineColor: '#FFF',
      particleRadius: 2,
      lineWidth: 0.2,
      parallax: false,
    });
  }, false);


  $(document).ready(function() {

      $("input[name='cedula']").inputmask("A-9{6,9}", {
        definitions: {
          "A": {
            validator: "[VvEe]",
            cardinality: 1,
            casing: "upper"
          }
        }
      });

  });
</script>
</body>
</html>

<?php if(isset($alert)) alert($alert); ?>

<?php
if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE) {
  ?><script>alert("Su navegador no cumple con los requerimientos del sistema, para poder visualizar el sistema correctamente debe utilizar Firefox 4.0 o superior.");</script><?php
}else{
  ?>
  <script type="text/javascript">
    var uMatch = navigator.userAgent.match(/Firefox\/(.*)$/),ffVersion;
    if (uMatch && uMatch.length > 1) {
      ffVersion = uMatch[1];
      var version  = ffVersion.slice(0,2);
      version = version.replace(".","");

      if( version < 4 ){
        alert("Su navegador no cumple con los requerimientos del sistema, para poder visualizar el sistema correctamente debe utilizar Firefox 4.0 o superior.");
      }
    }
  </script>  
  <?php
}
?>
