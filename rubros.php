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
      
      $alert = array("success", "El rubro ha sido <b>registrado</b> satisfactoriamente.");

    } 
  }

  if(isset($_POST["rubro"])){

    $clasificacion = $_POST["clasificacion"];
    $rubro = ucwords( mb_strtolower($_POST["rubro"],'UTF-8'));
    
    $existe_rubro = mysql_num_rows(mysql_query("select * from rubro where rubro = '$rubro' and id_clasificacion = '$clasificacion'"));

    if($existe_rubro == 0){
      $cons2 = mysql_query("insert into rubro values (NULL, '$clasificacion', '$rubro', 'Habilitado')");
      if($cons2){
         $alert = array("success", "El rubro ha sido <b>registrado</b> satisfactoriamente.");
      }else{
        $alert = array("error", "Ocurrió un error al intentar registrar el rubro.");
      }
    }else{
      $alert = array("error", "El rubro ya se encuentra registrado en el sistema.");
    }
  }

  if ( strlen( $_GET[ 'ide' ] ) > 0 ) {

    $ide = $_GET[ 'ide' ];
    
    $existe = mysql_num_rows(mysql_query("select * from rubro where id_rubro='$ide'"));
    
    if( $existe > 0 ){
      
      $sa = mysql_fetch_array(mysql_query("select status from rubro where id_rubro='$ide'"));

      $status_anterior = $sa["status"];

      if($status_anterior=="Habilitado"){
        $status_actual = "Deshabilitado";
        $msj_status1 = "deshabilitar";
        $msj_status2 = "deshabilitado";
      }else{
        $status_actual = "Habilitado";
        $msj_status1 = "habilitar";
        $msj_status2 = "habilitado";
      }

      $query1 = "update rubro set status = '$status_actual' where id_rubro = '$ide'";
      $query  = mysql_query( $query1 );
      
      if ( !$query ) {
        $alert = array("error", "No se pudo <b>".$msj_status1."</b> el rubro. Verifique e int&eacute;ntelo nuevamente.");
      } else {
        $alert = array("success", "El rubro ha sido <b>".$msj_status2."</b> satisfactoriamente.");
      }
    }
  }

  if ( strlen( $_GET[ 'id' ] ) > 0 ) {

    $id = $_GET[ 'id' ];
    
    $existe = mysql_num_rows(mysql_query("select * from rubro where id_rubro='$id'"));

    $productores = mysql_num_rows(mysql_query("select * from censo_productor where id_rubro = '$id'"));

    if($productores == 0){
      
      if( $existe > 0 ){
        
        $query1 = "delete from rubro where id_rubro = '$id'";
        $query  = mysql_query( $query1 );
        
        if ( !$query ) {
          $alert = array("error", "No se pudo <b>eliminar</b> el rubro. Verifique e int&eacute;ntelo nuevamente.");
        } else {
          $alert = array("success", "El rubro ha sido <b>eliminado</b> satisfactoriamente.");
        }
      }
    }
  }

  $rs1 = "select * from rubro order by rubro";
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
      <h1><i class="fa fa-shopping-basket"></i> 
        Rubros
        <small></small>
      </h1>
    </section>

    <section class="content">

      <div align="justify" id="titulo" class="titulo1 noprint4">RUBROS REGISTRADOS EN EL SISTEMA.</div>

      <div style="margin-bottom:-34px; position:absolute; z-index:1" class="opciones">
    
      <a href="#Mregistrar_rubro" style="margin-top:0px;"  role="button" data-toggle="modal" class="btn btn-danger" title='Registrar Rubro'><i class="fa fa-plus"></i> <b>Registrar Rubro</b></a></div>

      <?php
      if ( $count > 0 ) {
      ?>
        <table class="table table-bordered table-hover" id="example">
          <thead>
            <tr>
              <th>N°</th>
              <th class="col-md-3">Clasificación</th>
              <th class="col-md-7">Rubro</th>
              <th class="col-md-1">Status</th>
              <th class="opciones col-md-1">Opci&oacute;nes</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 0;
            while ( $row = mysql_fetch_array( $rs ) ) {
            
              $i++;
              $id_rubro = $row[ "id_rubro" ];
              $rubro          = $row[ "rubro" ];
              $id_clasificacion          = $row[ "id_clasificacion" ];
              $status          = $row[ "status" ];
            
              $rp = mysql_fetch_array(mysql_query("select * from clasificacion where id_clasificacion = '$id_clasificacion'"));
              $clasificacion  = $rp[ "clasificacion" ];

              $productores = mysql_num_rows(mysql_query("select * from censo_productor where id_rubro = '$id_rubro'"));

              ?>
            <tr <?php if($status == "Deshabilitado"){?> style="background-color: #eee;" <?php } ?>>
              <th><?php echo $i; ?></th>
              <td><?php echo $clasificacion; ?></td>
              <td><?php echo $rubro; ?></td>
              <td><?php
                if( $status == "Habilitado" ){
                  ?><label class="label label-success">Habilitado</label><?php
                }else{
                  ?><label class="label label-danger">Deshabilitado</label><?php
                }
              ?></td>
              <td class="t-opciones" data-valor='{"id":"<?php echo $id_rubro; ?>", "nombre":"<?php echo $rubro; ?>"}'>
                <center>
                  <?php
                  if($status == "Habilitado"){
                  ?>
                    <a class="deshabilitar" href="#!"><i class="fa fa-ban" title="Deshabilitar Rubro"></i></a>
                  <?php  
                  }else{ ?>
                    <a  class="habilitar" href="#!"><i class="fa fa-check-circle-o" title="Habilitar Rubro"></i></a>
                  <?php 
                  }

                  if($productores == 0){
                    ?>
                    <a  class="eliminar" href="#!"><i class="fa fa-trash" title="Eliminar Rubro"></i></a>
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
              <td colspan="5" class="opciones"><center>
                <i class="fa fa-trash"></i>&nbsp;Eliminar&nbsp;
                <i class="fa fa-check-circle-o"></i>&nbsp;Habilitar&nbsp;
                <i class="fa fa-ban"></i>&nbsp;Deshabilitar&nbsp;
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
        No existen rubros registrados en el sistema. </div>
      <?php
    }
  ?>

    <!-- -------------------------------------- REGISTRAR Vendedor --------------------------------- -->


  <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="Modal" id="Mregistrar_rubro" style="display: none;">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="ModalLabel" style="font-weight: bold;"><i class="fa fa-plus"></i> Registrar Rubro</h4>
        </div>
        <form action="rubros.php" method="POST">
          <div class="modal-body">
            <div class="container-fluid">
              <div class="row">

                <div class="col-md-12">
                  <div class="form-group">
                    <br><label>Clasificación</label>
                    <select name="clasificacion" class="form-control" required>
                      <option value="">Seleccione</option>
                      <?php  
                      $ct = mysql_query("select * from clasificacion where status = 'Habilitado' order by clasificacion");
                      $et = mysql_num_rows($ct);

                      if($et > 0){
                        while($rt = mysql_fetch_array($ct)){
                          $id_clasificacion = $rt["id_clasificacion"];
                          $clasificacion = $rt["clasificacion"];
                          ?>
                          <option value="<?php echo $id_clasificacion ?>"><?php echo $clasificacion ?></option>
                          <?php
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <br><label>Rubro</label>
                    <input type="text" class="form-control" name="rubro" value="<?php echo $POST['rubro']; ?>" required>
                  </div>
                </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
                <button type="submit" class="btn btn-danger btn-flat"><i class="fa fa-floppy-o"></i> Registrar</button>
              </div>
            </form>
          </div>
        </div>
      </div>


          <!-- --------------------------------------------------------------------------------- -->

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

    $( "ul.sidebar-menu li.rubros" ).addClass('active');

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
      "columnDefs": [ { targets: 4, sortable: false }], "order": [ 1, 'desc' ],
    });

    $("select.form-control").css('width', '100%');
    $.fn.select2.defaults.set('language', 'es');
    $("select.form-control").select2();

    $("table.table").on('click', 'a.habilitar', function() {

      var id = $(this).parents('td').data('valor').id;
      var nombre = $(this).parents('td').data('valor').nombre;

        swal({
          title: "Aviso!",
          text: "¿Desea <b>habilitar</b> el rubro <b>"+nombre+"</b>?",
          type: "info",
          showCancelButton: true,
          closeOnConfirm: false
        },function(isConfirm){
          if (isConfirm){
            url = 'rubros.php?ide='+id+'';
            $(location).attr('href', url);
          }
        });
      
    });

    $("table.table").on('click', 'a.deshabilitar', function() {

      var id = $(this).parents('td').data('valor').id;
      var nombre = $(this).parents('td').data('valor').nombre;
      
        swal({
          title: "Aviso!",
          text: "¿Desea <b>deshabilitar</b> el rubro <b>"+nombre+"</b>?",
          type: "info",
          showCancelButton: true,
          closeOnConfirm: false
        },function(isConfirm){
          if (isConfirm){
            url = 'rubros.php?ide='+id+'';
            $(location).attr('href', url);
          }
        });

    });


    $("table.table").on('click', 'a.eliminar', function() {

      var id = $(this).parents('td').data('valor').id;
      var nombre = $(this).parents('td').data('valor').nombre;
      
        swal({
          title: "Aviso!",
          text: "¿Desea <b>eliminar</b> el rubro <b>"+nombre+"</b>?",
          type: "info",
          showCancelButton: true,
          closeOnConfirm: false
        },function(isConfirm){
          if (isConfirm){
            url = 'rubros.php?id='+id+'';
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
