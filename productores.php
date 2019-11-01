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

  if ( strlen( $_GET[ 'oper' ] ) > 0 ) {
      
    $oper = $_GET[ 'oper' ];
    
    if ( $oper == "reg" ) {
          
      $alert = array("success", "El productor ha sido <b>registrado</b> satisfactoriamente.");

    } 
  }

  $rs1 = "select * from censo_productor where id_censo_consejo in (select id_censo_consejo from censo_consejo where id_censo = '$id_censo') order by fecha_registro";

  $rs  = mysql_query( $rs1 );

  $count = mysql_num_rows( $rs );

}else{
  header( "location:inicio.php" );
}

?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="plugins/select2/select2.min.css">
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
  <?php include("partials/css.php"); ?>
</head>
<body class="hold-transition skin-red sidebar-mini fixed">
<div class="wrapper">
  <?php include("partials/header.php"); ?>
  <?php include("partials/aside.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1><i class="fa fa-users"></i> 
        Productores
        <small></small>
      </h1>
    </section>

    <section class="content">

      <div align="justify" id="titulo" class="titulo1 noprint4">PRODUCTORES REGISTRADOS EN EL SISTEMA.</div>

      <div style="margin-bottom:-34px; position:absolute; z-index:1" class="opciones">
    
      <a href="registro_consejo.php" style="margin-top:0px;" class="btn btn-danger" title='Registrar Consejo'><i class="fa fa-plus"></i> <b>Registrar Consejo</b></a></div>

      <?php
      if ( $count > 0 ) {
      ?>
        <table class="table table-bordered table-hover" id="example">
          <thead>
            <tr>
              <th>N°</th>
                <th class="col-md-1">Cédula</th>
                <th class="col-md-4">Nombres</th>
                <th class="col-md-1">Teléfono</th>
                <th class="col-md-3">Consejo</th>
                <th class="col-md-1">Rubro</th>
                <th class="col-md-1">Hectáreas Disponibles</th>
                <th class="col-md-1">¿Mecanizable?</th>
                <!--
                <th class="col-md-1">Opciones</th>
                -->
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 0;
            while ( $row = mysql_fetch_array( $rs ) ) {

              $i++;
              $id_productor = $row["id_productor"];
              $id_usuario = $row["id_usuario"];
              $id_rubro = $row["id_rubro"];
              $id_censo_consejo = $row["id_censo_consejo"];
              $hectareas = $row["hectareas"];
              $mecanizable = $row["mecanizable"];

              $row4 = mysql_fetch_array(mysql_query("select * from consejo where id_consejo in (select id_consejo from censo_consejo where id_censo_consejo = '$id_censo_consejo')"));
              $consejo = $row4["consejo"];
              $id_tipo = $row4["id_tipo"];

              $row5 = mysql_fetch_array(mysql_query("select * from consejo_tipo where id_consejo_tipo = '$id_tipo'"));
              $tipo = $row5["tipo"];

              $row3 = mysql_fetch_array(mysql_query("select * from usuario where id_usuario = '$id_usuario'"));
              $cedula = $row3["cedula"];
              $sexo = $row3["sexo"];
              $fecha_nacimiento = $row3["fecha_nacimiento"];
              $telefono1 = $row3["telefono1"];
              $nombres = $row3["nombres"];

              $row5 = mysql_fetch_array(mysql_query("select * from rubro where id_rubro = '$id_rubro'"));
              $rubro = $row5["rubro"];

              ?>  
              <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $cedula; ?></td>
                <td><?php echo $nombres; ?></td>
                <td><?php echo $telefono1; ?></td>
                <td><?php echo $tipo ." ".$consejo; ?></td>
                <td><?php echo $rubro; ?></td>
                <td class="text-center"><?php echo $hectareas; ?></td>
                <td class="text-center"><?php echo $mecanizable; ?></td>
                <!--
                <td class="t-opciones" data-valor='{"id":"<?php echo $id_productor; ?>", "nombre":"<?php echo $nombres; ?>"}'>
                  <center>
                    <a  class="gestionar" href="#!"><i class="fa fa-cogs" title="Gestionar Productor"></i></a>
                    <a  class="eliminar" href="#!"><i class="fa fa-trash" title="Eliminar Productor"></i></a>
                  </center>
                </td>
                -->
              </tr>
              <?php
            }
            ?>
          </tbody>
          <!--
          <tfoot>
            <tr>
              <td colspan="8" class="opciones"><center>
                <i class="fa fa-cogs"></i>&nbsp;Gestionar&nbsp;
                <i class="fa fa-trash"></i>&nbsp;Eliminar&nbsp;
              </center></td>
            </tr>
          </tfoot>
          -->
        </table>
        <?php
      } else {
      ?>
      <div class="alert alert-info text-left" style="margin-top: 50px;">
        <i class="fa fa-exclamation-triangle" style="font-size: 40px; float:left; margin-right: 16px; margin-top: 16px;"></i>
        <h4>AVISO!</h4>
        No existen productores registrados en el sistema. </div>
      <?php
    }
  ?>

  

    </section>
  </div>
</div>
<a href="#" title="Imprimir" class="print-top opciones">Imrimir</a>
<?php include("partials/footer.php"); ?> 
<?php include("partials/js.php"); ?>
<script src="plugins/select2/select2.full.min.js"></script>
<script src="plugins/select2/i18n/es.js"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
  $(document).ready(function() {

    $( "ul.sidebar-menu li.productores" ).addClass('active');

    $('#example').DataTable({
     
      "language": 
      { 
        "lengthMenu": '<div style="margin-left:200px;" class="opciones"><b>Ver</b> <select class="form-control">'+
        '<option value="10">10</option>'+
        '<option value="20">20</option>'+
        '<option value="50">50</option>'+
        '<option value="-1">Todos</option>'+
        '</select></div>',
      },
      //"columnDefs": [ { targets: 8, sortable: false }], "order": [ 1, 'desc' ],
    });

    $("select.form-control").css('width', '100%');
    $.fn.select2.defaults.set('language', 'es');
    $("select.form-control").select2();
    
    busqueda();

    //COLOCAR TABLA DENTRO DE DIV TABLE RESPONSIVE
    $( "<div class='table-responsive'>" ).insertBefore( "table#example" );
    $('table#example').appendTo('.table-responsive');
  });
</script>
</body>
</html>
