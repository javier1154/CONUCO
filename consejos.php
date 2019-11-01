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
          
      $alert = array("success", "El consejo ha sido <b>registrado</b> satisfactoriamente.");

    } 
  }

  if ( strlen( $_GET[ 'id' ] ) > 0 ) {

    $ide = $_GET[ 'id' ];
    
    $existe = mysql_num_rows(mysql_query("select * from consejo where id_consejo='$ide'"));
    
    if( $existe > 0 ){

      $productores = mysql_num_rows(mysql_query("select * from censo_productor where id_censo_consejo in (select id_censo_consejo from censo_consejo where id_consejo = '$id_consejo')"));

      if($productores == 0){
        $query1 = "delete from consejo where id_consejo = '$ide'";
        $query  = mysql_query( $query1 );
        if ( !$query ) {
          $alert = array("error", "No se pudo <b>eliminar</b> consejo. Verifique e int&eacute;ntelo nuevamente.");
        } else {
          $alert = array("success", "El consejo ha sido <b>eliminado</b> satisfactoriamente.");
        }
      }
    }
  }

  $rs1 = "select * from consejo where status = 'Habilitado' and id_consejo in (select id_consejo from censo_consejo where id_censo = '$id_censo')";
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
      <h1><i class="fa fa-cube"></i> 
        Consejos
        <small></small>
      </h1>
    </section>

    <section class="content">

      <div align="justify" id="titulo" class="titulo1 noprint4">CONSEJOS REGISTRADOS EN EL SISTEMA.</div>

      <div style="margin-bottom:-34px; position:absolute; z-index:1" class="opciones">
    
      <a href="registro_consejo.php" style="margin-top:0px;" class="btn btn-danger" title='Registrar Consejo'><i class="fa fa-plus"></i> <b>Registrar Consejo</b></a></div>

      <?php
      if ( $count > 0 ) {
      ?>
        <table class="table table-bordered table-hover" id="example">
          <thead>
            <tr>
              <th>N°</th>
              <th class="col-md-1">Código</th>
              <th class="col-md-1">RIF</th>
              <th class="col-md-2">Tipo</th>
              <th class="col-md-4">Consejo</th>
              <th class="col-md-1">Estado</th>
              <th class="col-md-1">Municipio</th>
              <th class="col-md-1">Parroquia</th>
              <th class="opciones col-md-1">Opci&oacute;nes</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 0;
            while ( $row = mysql_fetch_array( $rs ) ) {
            
              $i++;
              $id_consejo = $row[ "id_consejo" ];
              $id_tipo    = $row[ "id_tipo" ];
              $codigo     = $row[ "codigo" ];
              $rif          = $row[ "rif" ];
              $consejo          = $row[ "consejo" ];
              $id_parroquia          = $row[ "id_parroquia" ];
            
              $rt = mysql_fetch_array(mysql_query("select * from consejo_tipo where id_consejo_tipo = '$id_tipo'"));
              $tipo  = $rt[ "tipo" ];

              $rp = mysql_fetch_array(mysql_query("select * from parroquia where id_parroquia = '$id_parroquia'"));
              $parroquia  = $rp[ "parroquia" ];
              $id_municipio  = $rp[ "id_municipio" ];

              $rm = mysql_fetch_array(mysql_query("select * from municipio where id_municipio = '$id_municipio'"));
              $municipio  = $rm[ "municipio" ];
              $id_estado  = $rm[ "id_estado" ];

              $re = mysql_fetch_array(mysql_query("select * from estado where id_estado = '$id_estado'"));
              $estado  = $re[ "estado" ];

              $productores = mysql_num_rows(mysql_query("select * from censo_productor where id_censo_consejo in (select id_censo_consejo from censo_consejo where id_consejo = '$id_consejo')"));
              
              ?>
            <tr>
              <th><?php echo $i; ?></th>
              <td><?php echo $codigo; ?></td>
              <td><?php echo $rif; ?></td>
              <td><?php echo $tipo; ?></td>
              <td><?php echo $consejo; ?></td>
              <td><?php echo $estado; ?></td>
              <td><?php echo $municipio; ?></td>
              <td><?php echo $parroquia; ?></td>
              <td class="t-opciones" data-valor='{"id":"<?php echo $id_consejo; ?>", "nombre":"<?php echo $consejo; ?>"}'>
                <center>
                  <a href="gestion_consejo.php?consejo=<?php echo $id_consejo; ?>"><i class="fa fa-cogs" title="Gestionar consejo"></i></a>

                  <?php  
                  if($productores == 0){
                    ?>
                    <a href="#" class="eliminar"><i class="fa fa-trash" title="Eliminar consejo"></i></a>
                    <?php
                  }
                  ?>
                </center>
              </td>
            </tr>
            <?php
          }
          ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="9" class="opciones"><center>
                <i class="fa fa-cogs"></i>&nbsp;Gestionar&nbsp;
                <i class="fa fa-trash"></i>&nbsp;Eliminar&nbsp;
                </center></td>
            </tr>
          </tfoot>
        </table>
          <?php
      } else {
      ?>
      <div class="alert alert-info text-left" style="margin-top: 50px;">
        <i class="fa fa-exclamation-triangle" style="font-size: 40px; float:left; margin-right: 16px; margin-top: 16px;"></i>
        <h4>AVISO!</h4>
        No existen consejos registrados en el sistema. </div>
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

    $( "ul.sidebar-menu li.consejos" ).addClass('active');

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
      "columnDefs": [ { targets: 8, sortable: false }], "order": [ 1, 'desc' ],
    });

    $("select.form-control").css('width', '100%');
    $.fn.select2.defaults.set('language', 'es');
    $("select.form-control").select2();

    $("table.table").on('click', 'a.eliminar', function() {

      var id = $(this).parents('td').data('valor').id;
      var nombre = $(this).parents('td').data('valor').nombre;

        swal({
          title: "Aviso!",
          text: "¿Desea <b>eliminar</b> el consejo <b>"+nombre+"</b>?",
          type: "info",
          showCancelButton: true,
          closeOnConfirm: false
        },function(isConfirm){
          if (isConfirm){
            url = 'consejos.php?id='+id+'';
            $(location).attr('href', url);
          }
        });
      
    });

    busqueda();

    //COLOCAR TABLA DENTRO DE DIV TABLE RESPONSIVE
    $( "<div class='table-responsive'>" ).insertBefore( "table#example" );
    $('table#example').appendTo('.table-responsive');
  });
</script>
</body>
</html>
