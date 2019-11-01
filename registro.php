<?php

include( "funciones/conexion bd mysql.php" );
include("funciones/fechas_barra.php");

conectar_bd_mysql();

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(isset($_POST["cedula"])){

  $cedula = $_POST["cedula"];
  $nombres = mb_strtoupper($_POST["nombres"], "UTF-8");
  $sexo = $_POST["sexo"];
  $fecha_nacimiento = $_POST["fecha_nacimiento"];
  $telefono1 = $_POST["telefono1"];
  $telefono2 = $_POST["telefono2"];
  $correo = $_POST["correo"];
  $ccp = $_POST["ccp"];
  $scp = $_POST["scp"];

  $clave1 = $_POST["clave1"];
  $clave2 = $_POST["clave2"];

  if($clave1 === $clave2){

    $clave = sha1($clave1);

    $fecha_registro = date("Y-m-d H:i:s");

    $cons2 = mysql_query("select * from usuario where cedula = '$cedula' and id_usuario in (select id_usuario from permisologia)");
    $existe_usuario = mysql_num_rows($cons2);

    if($existe_usuario > 0){
      $alert = array("error", "El usuario que intenta registrar ya existe.");
    }else{

      $ceu = mysql_query("select * from usuario where cedula = '$cedula'");
      $eu = mysql_num_rows($ceu);

      if($eu > 0){
        $reu = mysql_fetch_array($ceu);
        $id_usuario = $reu["id_usuario"];

        $cru = mysql_query("update usuario set sexo = '$sexo', fecha_nacimiento = '$fecha_nacimiento', telefono1 = '$telefono1', telefono2 = '$telefono2', correo = '$correo', ccp = '$ccp', scp = '$scp' where id_usuario = '$id_usuario'");

      }else{
        $cru = mysql_query("insert into usuario values (NULL, '$cedula', '$nombres', '$sexo', '$fecha_nacimiento', '$telefono1', '$telefono2', '$ccp', '$scp', '$correo', '$fecha_registro')");
      }

      if($cru){

        if($eu == 0){
          $id_usuario = mysql_insert_id();
        }

        $crp = mysql_query("insert into permisologia values (NULL, '$id_usuario', '$clave', 'Coordinador Consejo', 'Habilitado', '$id_usuario', '$fecha_registro')");

        if($crp){
          //unset($_POST);
          //header("location: index.php?cmd=ure");

          session_start();

          $_SESSION[ 'CENSOstatus_permisologia' ] = 'Habilitado';
          $_SESSION[ 'CENSOpermisologia' ]        = 'Coordinador Consejo';
          $_SESSION[ 'CENSOid_permisologia' ]     = mysql_insert_id();

          $_SESSION[ 'CENSO_activa' ]            = 'true';
          $_SESSION[ 'CENSOid_usuario' ]          = $id_usuario;
          $_SESSION[ 'CENSOnombres' ]              = $nombres;
          $_SESSION[ 'CENSOcedula' ]            = $cedula;
          $_SESSION[ 'CENSOimagen' ] = 'img/usuario.jpg';

          $_SESSION[ 'CENSOid_censo' ] = "1";

          header( "location:coord.php?oper=reg" );


        }else{
          $alert = array("error", "No se pudo realizar el registro.");
        }
      }else{
        $alert = array("error", "No se pudo realizar el registro.");
      }

    }
  }else{
    $alert = array("error", "Las constraseñas no coinciden.");
  }
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CENSO | Registro</title>
  <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="plugins/sweetalert/dist/sweetalert.min.css">
  <link rel="stylesheet" href="plugins/plantilla/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="plugins/datepicker/datepicker3.min.css">
  <link rel="stylesheet" href="plugins/select2/select2.min.css">
  <link rel="stylesheet" href="css/app.min.css">
  <link rel="stylesheet" href="css/index.min.css">

 <style>
   body.login-page, .btn-primary{
    background-color: #42822e;
   }

   .btn-primary:hover{
    background-color: #42822e;
   }

   div.sweet-alert button.confirm{
    background-color: #42822e !important;;
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
      <div class="mask"></div>
    </div>

    <div class="content-wrapper">

      <section class="content">
        <div class="row">


          <div class="col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1 box-registrar" style="margin-top: 140px">

            <div class="login-logo">
              <img src="img/logo.png" style="width:300px; margin-top: -134px; margin-bottom: -36px">
            </div>

            <div class="row">
              <div class="col-md-12">
                <section class="content-header">
                  <h1 style="margin-top: -60px"><i class="fa fa-edit"></i> 
                    <b>Registro</b>
                    <small></small>
                  </h1>
                </section>
              </div>
              <!--
              <div class="col-md-9">
                <div class="alert alert-info" style="padding: 10px; font-size: 16px; font-weight: bold;">
                  <i class="fa fa-exclamation-triangle" style="font-size: 40px; float:right; margin-top: -6px; margin-right: 4px !important;"></i>
                  Por favor, complete y actualice sus datos para un mejor desempeño del sistema.
                </div>
              </div>
            -->
            </div>

            <form class="form-horizontal" method="POST" action="registro.php">
              <br>
              <div class="row">

                <div class="col-md-12">

                    <div class="form-group">
                      <div class="col-md-4">
                        <label class="col-sm-12 text-left">Cédula</label>
                        <div class="col-sm-12">
                          <div class="input-group">
                            <input <?php if(isset($_POST["cedula"])) echo "readonly='readonly'" ?> type="text" name="cedula" class="form-control" value="<?php echo $_POST["cedula"] ?>" required>
                            <span class="input-group-addon">

                              <i class="fa fa-close cancelar" <?php if(!isset($_POST["cedula"])){ echo ' style="color: red; cursor: pointer; display: none"'; }else{ echo 'style="color: red; cursor: pointer;'; } ?>></i>

                              <i class="fa fa-user" <?php if(isset($_POST["cedula"])) echo 'style="display:none"'  ?>></i>
                              <i class="fa fa-spinner fa-spin" style="display: none"></i>
                            </span>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-8">
                        <label class="col-sm-12 text-left">Nombres y Apellidos</label>
                        <div class="col-sm-12">
                          <input <?php if(isset($_POST["nombres"])) echo "readonly='readonly'" ?> type="text" name="nombres" class="form-control" value="<?php echo $_POST["nombres"] ?>" required>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-4">
                        <label class="col-sm-12 text-left">Sexo</label>
                        <div class="col-sm-12">
                          <select class="form-control" name="sexo" required>
                            <option value="">Seleccione</option>
                            <option <?php if($_POST["sexo"] == "Masculino") echo "selected = 'selected'" ?> value="Masculino">Masculino</option>
                            <option <?php if($_POST["sexo"] == "Femenino") echo "selected = 'selected'" ?> value="Femenino">Femenino</option>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <label class="col-sm-12 text-left">Fecha de Nacimiento</label>
                        <div class="col-sm-12">
                          <div class="input-group date form_datetime"  data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" autocomplete="off">
                            <input class="form-control" id="mes" name="fecha_nacimiento" type="text" style="background-color:#FFF; cursor: not-allowed;" required="required" value="<?php echo $_POST["fecha_nacimiento"] ?>">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <label class="col-sm-12 text-left">Teléfono Cel.</label>
                        <div class="col-sm-12">
                          <input type="text" name="telefono1" class="form-control" value="<?php echo $_POST["telefono1"] ?>" required>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-4">
                        <label class="col-sm-12 text-left">Teléfono Hab. <small>(opcional)</small></label>
                        <div class="col-sm-12">
                          <input type="text" name="telefono2" class="form-control" value="<?php echo $_POST["telefono2"] ?>">
                        </div>
                      </div>

                      <div class="col-md-8">
                        <label class="col-sm-12 text-left">Correo <small>(opcional)</small></label>
                        <div class="col-sm-12">
                          <input type="emial" name="correo" class="form-control" value="<?php echo $_POST["correo"] ?>">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-6">
                        <label class="col-sm-12 text-left">Código del Carné de la Patria</label>
                        <div class="col-sm-12">
                          <input type="number" name="ccp" class="form-control" value="<?php echo $_POST["ccp"] ?>" required>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <label class="col-sm-12 text-left">Serial del Carné de la Patria</label>
                        <div class="col-sm-12">
                          <input type="number" name="scp" class="form-control" value="<?php echo $_POST["scp"] ?>" required>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-6">
                        <label class="col-sm-12 text-left">Contraseña</label>
                        <div class="col-sm-12">
                          <input type="password" name="clave1" class="form-control" required min="4" max="20">
                        </div>
                      </div>

                      <div class="col-md-6">
                        <label class="col-sm-12 text-left">Repita la constraseña</label>
                        <div class="col-sm-12">
                          <input type="password" name="clave2" class="form-control" required min="4" max="20">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <br>
                      <div class="col-md-12">
                        <button style="margin-right: 14px; float: right; margin-left: 14px" class="btn btn-danger" type="submit"><i class="fa fa-save"></i><b> Continuar</b></button>
                        <a href="index.php" class="btn btn-default" style="float: right;"><i class="fa fa-close"></i> Cancelar</a>
                      </div>
                    </div>

                    

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
<script src="plugins/select2/select2.full.min.js"></script>
<script src="plugins/select2/i18n/es.js"></script>
<script type="text/javascript" charset="utf-8" src="js/jquery.validate.min.js" /></script>
<script src="plugins/input-mask/inputmask.js" /></script>
<script src="plugins/input-mask/jquery.inputmask.js" /></script>
<script src="plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>

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
</script>


<script>
    $(document).ready(function() {

      $("select.form-control").css('width', '100%');
      $.fn.select2.defaults.set('language', 'es');
      $("select.form-control").select2();

      $("input[name='telefono1'], input[name='telefono2']").inputmask("9999-999-9999");

      $("input[name='cedula']").inputmask("A-9{6,9}", {
        definitions: {
          "A": {
            validator: "[VvEe]",
            cardinality: 1,
            casing: "upper"
          }
        }
      });

      $(".form_datetime").datepicker({
        autoclose: true,
        todayBtn: false,
        startView: 2,
        language: 'es',
        minViewMode: 4,
        maxViewMode: 1,
      });


      $("input[name='cedula']").change(function(){

        var usuario = $(this).val();

        if(usuario.length > 2){

            $("div.input-group i.fa-user, div.input-group i.fa-close").hide();
            $("div.input-group i.fa-spin").show();

            $.ajax({
              url: 'ajax/validar_usuario_registro.php',
              type: 'POST',
              data: {
                'id_usuario' : usuario
              },
              success: function (data) {
                if(data.status){

                  if(data.tipo == 2){
                    $("input[name='cedula'], input[name='nombres']").attr('readonly', 'readonly');
                    $("input[name='cedula']").val(data.cedula);
                    $("input[name='nombres']").val(data.nombres);
                  }

                  $("div.input-group i.fa-spin, div.input-group i.fa-user").hide();
                  $("div.input-group i.fa-close").show();

                }else{

                  if(data.tipo == 1 ){

                    swal({
                      title: "ERROR!",
                      text: "La cédula ya se encuentra registrada en el sistema.",
                      type: "error",
                      showCancelButton: false,
                      closeOnConfirm: true,
                    });

                    $("input[name='cedula'], input[name='nombres']").removeAttr('readonly');
                    $("input[name='cedula']").val('');
                    $("input[name='nombres']").val('');
                    $("select[name='sexo']").val('');
                    $("input[name='fecha_nacimiento']").val('');
                    $("input[name='telefono1']").val('');
                    $("input[name='telefono2']").val('');
                    $("input[name='ccp']").val('');
                    $("input[name='scp']").val('');
                    $("input[name='correo']").val('');
                    $("select.form-control").select2();

                    $("div.input-group i.fa-spin, div.input-group i.fa-close").hide();
                    $("div.input-group i.fa-user").show();

                  }else{
                    $("input[name='cedula']").attr('readonly', 'readonly');
                    $("div.input-group i.fa-spin, div.input-group i.fa-user").hide();
                    $("div.input-group i.fa-close").show();
                  }
                }
              },
              error: function (data) {
                if ( console && console.log ) {
                  console.log( "La solicitud ha fallado");
                  alert("Fallo");
                }
              }
            });
          }else{
            swal({
              title: "ERROR!",
              text: "Complete los campos.",
              type: "error",
              showCancelButton: false,
              closeOnConfirm: true,
            });
          }

      });

      $("i.cancelar").click(function(){

        $("input[name='cedula'], input[name='nombres']").removeAttr('readonly');
        //$("input[name='cedula']").val('');
        $("input[name='nombres']").val('');
        $("select[name='sexo']").val('');
        $("input[name='fecha_nacimiento']").val('');
        $("input[name='telefono1']").val('');
        $("input[name='telefono2']").val('');
        $("input[name='ccp']").val('');
        $("input[name='scp']").val('');
        $("input[name='correo']").val('');
        $("select.form-control").select2();

        $("div.input-group i.fa-spin, div.input-group i.fa-close").hide();
        $("div.input-group i.fa-user").show();
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
