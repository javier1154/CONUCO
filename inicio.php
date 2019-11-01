<?php

##---- REVISADO ----##
// Inclusión del archivo que contiene la conexion con la base de datos.
include( "funciones/conexion bd mysql.php" );

// Inclusión del archivo que contiene la función de inicio de la sesión.
include( "funciones/session.php" );

// Inclusión del archivo que contiene la función de la barra de navegación y las funciones para dar formatos a las fechas.
include( "funciones/fechas_barra.php" );

// Conexión a la base de datos.
conectar_bd_mysql();

if ( ( $_SESSION[ "CENSOpermisologia" ] == "Administrador" ) and ( $_SESSION[ "CENSOstatus_permisologia" ] == "Habilitado" ) ) {

  $id_censo = $_SESSION["CENSOid_censo"];
  $rc = mysql_fetch_array(mysql_query("select * from censo where id_censo = '$id_censo'"));
  $anio = $rc["anio"];
  $periodo = $rc["periodo"];

}else{
  header("location: coord.php");
}

?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
  <?php include("partials/css.php"); ?>
  <style>
    span.label{
      font-size: 12px;
    }

    .box.box-solid.box-danger > .box-header{
      background-color: #D64040;
    }
  </style>
</head>
<body class="hold-transition skin-red sidebar-mini fixed">
<div class="wrapper">
  <?php include("partials/header.php"); ?>
  <?php include("partials/aside.php"); ?>
  <div class="content-wrapper">

    <section class="content-header">
      <h1><i class="fa fa-globe"></i> 
        CENSO <?php echo $anio."-".$periodo ?>
        <small></small>
      </h1>
    </section>

    <section class="content">
    
    </section>
  </div>
  
  <?php include("partials/footer.php"); ?>
</div>
<?php include("partials/js.php"); ?>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {

  $( "ul.sidebar-menu li.inicio" ).addClass('active');

});
</script>
</body>
</html>