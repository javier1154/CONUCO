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

  if ( isset( $_POST[ "cedula" ] ) ) {

    $cedula = $_POST["cedula"];
    $nombres = mb_strtoupper($_POST["nombres"], "UTF-8");
    $sexo = $_POST["sexo"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $telefono1 = $_POST["telefono1"];
    $telefono2 = $_POST["telefono2"];
    $correo = $_POST["correo"];
    $ccp = $_POST["ccp"];
    $scp = $_POST["scp"];
    $tipo = $_POST["tipo"];
    $clave1 = $_POST["clave1"];
    $clave2 = $_POST["clave2"];

    $encargado_registro = $_SESSION["CENSOid_usuario"];
    $fecha_registro = date("Y-m-d H:i:s");

    if($clave1 === $clave2){

      $clave = sha1($clave1);

      $cu = mysql_query("select * from usuario where cedula = '$cedula'");
      $eu = mysql_num_rows($cu);

      if($eu > 0){
        $rowu = mysql_fetch_array($cu);
        $aid_usuario = $rowu["id_usuario"];
        $ausuario = $rowu["nombres"];

        $cons4 = mysql_query("update usuario set sexo = '$sexo', fecha_nacimiento = '$fecha_nacimiento', telefono1 = '$telefono1', telefono2 = '$telefono2', correo = '$correo', ccp = '$ccp', scp = '$scp' where id_usuario = '$aid_usuario'");

        if($cons4){

          $ep = mysql_num_rows(mysql_query("select * from permisologia where id_usuario = '$aid_usuario'"));

          if($ep == 0){

            $cip = mysql_query("insert into permisologia values (NULL, '$aid_usuario', '$clave', '$tipo', 'Habilitado', '$encargado_registro', '$fecha_registro')");

            if($cip){
              $alert = array("success", "La permisología del usuario <b>".$ausuario."</b> ha sido asignada satisfactoriamente.");
              unset($_POST);
            }else{
              $alert = array("error", "Ocurrió un error al intentar la permisología del usuario <b>".$ausuario."</b>.");
            }

          }else{
            $alert = array("error", "El usuario <b>".$ausuario."</b> ya posee una permisología asignada.");
          }

        }else{
          $alert = array("error", "No se pudo actualizar los datos del usuario <b>".$ausuario."</b>.");
        }
      }else{

        $cru = mysql_query("insert into usuario values (NULL, '$cedula', '$nombres', '$sexo', '$fecha_nacimiento', '$telefono1', '$telefono2', '$ccp', '$scp', '$correo', '$fecha_registro')");

        if($cru){

          $aid_usuario = mysql_insert_id();

          $cip = mysql_query("insert into permisologia values (NULL, '$aid_usuario', '$clave', '$tipo', 'Habilitado', '$encargado_registro', '$fecha_registro')");

          if($cip){
            $alert = array("success", "La permisología del usuario <b>".$nombres."</b> ha sido asignada satisfactoriamente.");
            unset($_POST);
          }else{
            $alert = array("error", "Ocurrió un error al intentar la permisología del usuario <b>".$nombres."</b>.");
          }
        }else{
          $alert = array("error", "No se pudo registrar los datos del usuario <b>".$nombres."</b>.");
        }
      }

    }else{
      $alert = array("error", "Las constraseñas no coinciden.");
    }
      
  }


  if(isset($_POST["cid_permisologia"])){

    $cid_permisologia = $_POST["cid_permisologia"];
    $clave1 = $_POST["cclave1"];
    $clave2 = $_POST["cclave2"];

    if($clave1 === $clave2){

      $clave = sha1($clave1);

      $existe = mysql_num_rows(mysql_query("select * from permisologia where id_permisologia='$cid_permisologia'"));
      
      if( $existe > 0 ){
        $cac = mysql_query("update permisologia set clave = '$clave' where id_permisologia = '$cid_permisologia'");
        if($cac){
          $alert = array("success", "La contraseña ha sido actualizada satisfactoriamente.");
        }else{
          $alert = array("error", "No se pudo actualizar la contraseña.");
        }
      }
    }else{
      $alert = array("error", "Las constraseñas no coinciden.");
    }

  }


  if ( strlen( $_GET[ 'ide' ] ) > 0 ) {

    if(($_SESSION[ "CENSOpermisologia" ] == "Administrador") and ($_SESSION[ "CENSOstatus_permisologia" ] == "Habilitado")){
    
      $ide = $_GET[ 'ide' ];
      
      $existe = mysql_num_rows(mysql_query("select * from permisologia where id_permisologia='$ide'"));
      
      if( $existe > 0 ){
      
        if($_GET[ 'ide' ] != $_SESSION[ "CENSOid_permisologia" ]){

            $sa = mysql_fetch_array(mysql_query("select status from permisologia where id_permisologia='$ide'"));

            $status_anterior = $sa["status"];

            if($status_anterior=="Habilitado"){
              $status_actual = "Deshabilitado";
              $msj_status1 = "deshabilitar";
              $msj_status2 = "deshabilitada";
            }else{
              $status_actual = "Habilitado";
              $msj_status1 = "habilitar";
              $msj_status2 = "habilitada";
            }

            $query1 = "update permisologia set status = '$status_actual' where id_permisologia = '$ide'";
            $query  = mysql_query( $query1 );
            
            if ( !$query ) {
             
              $alert = array("error", "No se pudo <b>".$msj_status1."</b> la permisolog&iacute;a. Verifique e int&eacute;ntelo nuevamente.");

            } else {
              
              $alert = array("success", "La permisolog&iacute;a ha sido <b>".$msj_status2."</b> satisfactoriamente.");

            }
        }
      }
    }
  }

  $rs1 = "select * from permisologia order by id_permisologia";
  $rs  = mysql_query( $rs1 );

  $count = mysql_num_rows( $rs );

}else{
  header( "location:inicio.php" );
}

?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="plugins/datepicker/datepicker3.min.css">
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
      <h1><i class="fa fa-tags"></i> 
        Permisologías
        <small></small>
      </h1>
    </section>

    <section class="content">

      <div align="justify" id="titulo" class="titulo1 noprint4">PERMISOLOGÍAS REGISTRADAS EN EL SISTEMA.</div>
      
      <div style="margin-bottom:-34px; position:absolute; z-index:1" class="opciones">
        <a href="#Mregistrar_permisologia" style="margin-top:0px;"  role="button" data-toggle="modal" class="btn btn-danger" title='Registrar permisología'><i class="fa fa-plus"></i> <b>Registrar Permisología</b></a>
      </div>


      <?php
      if ( $count > 0 ) {
      ?>

        <table class="table table-bordered table-hover" id="example">
          <thead>
            <tr>
              <th width="10%">C&eacute;dula</th>
              <th>Nombres y Apellidos</th>
              <th width="18%">Tipo</th>
              <th width="8%">Status</th>
              <th class="opciones" width="8%">Opci&oacute;n</th>
            </tr>
          </thead>
          <tbody>
            <?php
            while ( $row = mysql_fetch_array( $rs ) ) {
            
              $id_permisologia = $row[ "id_permisologia" ];
              $id_usuario          = $row[ "id_usuario" ];

              $status          = $row[ "status" ];
              $tipo          = $row[ "tipo" ];
            
              $cons2 = mysql_query('select * from usuario where id_usuario = '.$id_usuario.'');
              $cc = mysql_fetch_array($cons2);

              $usuario     = $cc[ "nombres" ]." ".$cc["apellidos"];
              $cedula          = $cc[ "cedula" ];
              
              ?>
            <tr <?php if($status == "Deshabilitado"){?> style="background-color: #eee;" <?php } ?>>
              <td><?php echo $cedula; ?></td>
              <td><?php echo $usuario; ?></td>
              <td><?php echo $tipo; ?></td>
              <td><?php

                if( $status == "Habilitado" ){
                  ?><label class="label label-success">Habilitado</label><?php
                }else{
                  ?><label class="label label-danger">Deshabilitado</label><?php
                }
                      
              ?></td>
              
              <td class="t-opciones" data-valor='{"id":"<?php echo $id_permisologia; ?>", "nombre":"<?php echo $usuario; ?>"}'>              
                
                <center>
                  <?php
                    
                      if($status == "Habilitado"){
                    ?>
                        <a class="deshabilitar" href="#!"><i class="fa fa-ban" title="Deshabilitar Permisología"></i></a>
                    <?php  
                      }else{ ?>
                        <a  class="habilitar" href="#!"><i class="fa fa-check-circle-o" title="Habilitar Permisología"></i></a>
                     <?php 
                      }
                    ?>

                    <a class="cambio" href="#Mcambio" role="button" data-toggle="modal"><i class="fa fa-cogs" title="Cambiar Clave"></i></a>

                </center></td>
            </tr>
            <?php
          }
          ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="5" class="opciones"><center>
                  <i class="fa fa-cogs"></i>&nbsp;Cambiar Clave&nbsp;
                  <i class="fa fa-check-circle-o"></i>&nbsp;Habilitar&nbsp;
                  <i class="fa fa-ban"></i>&nbsp;Deshabilitar&nbsp;
                </center></td>
            </tr>
          </tfoot>
        </table>


         <!-- ------------------------------------------------ REGISTRAR  ------------------------------ -->

        <div class="modal fade" role="dialog" aria-labelledby="Modal" id="Mcambio" style="display: none;">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="ModalLabel" style="font-weight: bold;"><i class="fa fa-cogs"></i> Cambio de Clave</h4>
              </div>

              <form action="permisologias.php" method="POST">
                
                <input type="hidden" name="cid_permisologia">
                
                <div class="modal-body">
                
                  <div class="row">

                      <div class="col-md-12">
                        <label class="col-sm-12 text-left">Usuario</label>
                        <div class="col-sm-12">
                          <input type="text" class="form-control" name="cusuario" readonly="readonly"><br/>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <label class="col-sm-12 text-left">Contraseña</label>
                        <div class="col-sm-12">
                          <input type="password" name="cclave1" class="form-control" required min="4" max="20"><br/>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <label class="col-sm-12 text-left">Repita la constraseña</label>
                        <div class="col-sm-12">
                          <input type="password" name="cclave2" class="form-control" required min="4" max="20"><br/><br/>
                        </div>
                      </div>


                    </div>


                  </div>


                    <div class="modal-footer">
                      <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
                      <button type="submit" class="btn btn-danger btn-flat"><i class="fa fa-floppy-o"></i> Cambiar</button>
                    </div>
                  </form>
              </div>
            </div>
          </div>

          <!-- ------------------------------------------------------------------------ -->
        <?php
      } else {
      ?>
      <div class="alert alert-info text-left" style="margin-top: 50px;">
        <i class="fa fa-exclamation-triangle" style="font-size: 40px; float:left; margin-right: 16px; margin-top: 16px;"></i>
        <h4>AVISO!</h4>
        No existen Permisolog&iacute;as registrados. </div>
      <?php
    }
  ?>
      
      <!-- ------------------------------------------------ REGISTRAR  ------------------------------ -->

        <div class="modal fade" role="dialog" aria-labelledby="Modal" id="Mregistrar_permisologia" style="display: none;">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="ModalLabel" style="font-weight: bold;"><i class="fa fa-plus"></i> Registrar Permisología</h4>
              </div>

              <form action="permisologias.php" method="POST">
                
                <div class="modal-body">
                
                  <div class="row">

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
                          <br/>
                        </div>
                      </div>

                      <div class="col-md-8">
                        <label class="col-sm-12 text-left">Nombres y Apellidos</label>
                        <div class="col-sm-12">
                          <input <?php if(isset($_POST["nombres"])) echo "readonly='readonly'" ?> type="text" name="nombres" class="form-control" value="<?php echo $_POST["nombres"] ?>" required><br/>
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
                          </select><br/>
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
                          <input type="text" name="telefono1" class="form-control" value="<?php echo $_POST["telefono1"] ?>" required><br/>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-4">
                        <label class="col-sm-12 text-left">Teléfono Hab. <small>(opcional)</small></label>
                        <div class="col-sm-12">
                          <input type="text" name="telefono2" class="form-control" value="<?php echo $_POST["telefono2"] ?>"><br/>
                        </div>
                      </div>

                      <div class="col-md-8">
                        <label class="col-sm-12 text-left">Correo <small>(opcional)</small></label>
                        <div class="col-sm-12">
                          <input type="emial" name="correo" class="form-control" value="<?php echo $_POST["correo"] ?>"><br/>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-6">
                        <label class="col-sm-12 text-left">Código del Carné de la Patria</label>
                        <div class="col-sm-12">
                          <input type="number" name="ccp" class="form-control" value="<?php echo $_POST["ccp"] ?>" required><br/>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <label class="col-sm-12 text-left">Serial del Carné de la Patria</label>
                        <div class="col-sm-12">
                          <input type="number" name="scp" class="form-control" value="<?php echo $_POST["scp"] ?>" required><br/>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-4">
                        <label class="col-sm-12 text-left">Tipo de Permisología</label>
                        <div class="col-sm-12">
                          <select class="form-control" name="tipo" required>
                            <option value="Administrador">Administrador</option>
                          </select><br/>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <label class="col-sm-12 text-left">Contraseña</label>
                        <div class="col-sm-12">
                          <input type="password" name="clave1" class="form-control" required min="4" max="20"><br/>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <label class="col-sm-12 text-left">Repita la constraseña</label>
                        <div class="col-sm-12">
                          <input type="password" name="clave2" class="form-control" required min="4" max="20"><br/><br/>
                        </div>
                      </div>


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

          <!-- ------------------------------------------------------------------------ -->









         



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
<script src="plugins/input-mask/inputmask.js" /></script>
<script src="plugins/input-mask/jquery.inputmask.js" /></script>
<script src="plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>

<script>
  $(document).ready(function() {

    $( "ul.sidebar-menu li.permisologias" ).addClass('active');

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

      "columnDefs": [ { targets: 4, sortable: false }], "order": [ 0, 'desc' ],

    });

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
          url: 'ajax/validar_usuario.php',
          type: 'POST',
          data: {
            'id_usuario' : usuario
          },
          success: function (data) {
            if(data.status){

              if(data.tipo == 1){
                $("input[name='cedula'], input[name='nombres']").attr('readonly', 'readonly');
                $("input[name='cedula']").val(data.cedula);
                $("input[name='nombres']").val(data.nombres);
                $("select[name='sexo']").val(data.sexo);
                $("input[name='fecha_nacimiento']").val(data.fecha_nacimiento);
                $("input[name='telefono1']").val(data.telefono1);
                $("input[name='telefono2']").val(data.telefono2);
                $("input[name='ccp']").val(data.ccp);
                $("input[name='scp']").val(data.scp);
                $("input[name='correo']").val(data.correo);
                $("select.form-control").select2();
              }else if(data.tipo == 2){
                $("input[name='cedula'], input[name='nombres']").attr('readonly', 'readonly');
                $("input[name='cedula']").val(data.cedula);
                $("input[name='nombres']").val(data.nombres);
              }

              $("div.input-group i.fa-spin, div.input-group i.fa-user").hide();
              $("div.input-group i.fa-close").show();

            }else{
              $("input[name='cedula']").attr('readonly', 'readonly');
              $("div.input-group i.fa-spin, div.input-group i.fa-user").hide();
              $("div.input-group i.fa-close").show();
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


    $("table.table").on('click', 'a.habilitar', function() {

      var id = $(this).parents('td').data('valor').id;
      var nombre = $(this).parents('td').data('valor').nombre;
      var perm = "<?php echo $_SESSION[ "CENSOid_permisologia" ]; ?>";

      if(id != perm){

        swal({
          title: "Aviso!",
          text: "¿Desea <b>habilitar</b> la permisología de <b>"+nombre+"</b>?",
          type: "info",
          showCancelButton: true,
          closeOnConfirm: false
        },function(isConfirm){
          if (isConfirm){
            url = 'permisologias.php?ide='+id+'';
            $(location).attr('href', url);
          }
        });

      }else{
        swal({
          title: "ERROR!",
          text: "Usted no puede gestionar su propia permisología",
          type: "error",
          showCancelButton: false,
          closeOnConfirm: true
        });
      }
      
    });

    $("table.table").on('click', 'a.deshabilitar', function() {

      var id = $(this).parents('td').data('valor').id;
      var nombre = $(this).parents('td').data('valor').nombre;
      var perm = "<?php echo $_SESSION[ "CENSOid_permisologia" ]; ?>";

      if(id != perm){

        swal({
          title: "Aviso!",
          text: "¿Desea <b>deshabilitar</b> la permisología de <b>"+nombre+"</b>?",
          type: "info",
          showCancelButton: true,
          closeOnConfirm: false
        },function(isConfirm){
          if (isConfirm){
            url = 'permisologias.php?ide='+id+'';
            $(location).attr('href', url);
          }
        });

      }else{
        swal({
          title: "ERROR!",
          text: "Usted no puede gestionar su propia permisología",
          type: "error",
          showCancelButton: false,
          closeOnConfirm: true
        });
      }
      
    });


    $("table.table").on('click', 'a.cambio', function() {

      var id = $(this).parents('td').data('valor').id;
      var nombre = $(this).parents('td').data('valor').nombre;

      $("input[name='cid_permisologia']").val(id);
      $("input[name='cusuario']").val(nombre);

    });


    busqueda();
    //COLOCAR TABLA DENTRO DE DIV TABLE RESPONSIVE
    $( "<div class='table-responsive'>" ).insertBefore( "table#example" );
    $('table#example').appendTo('.table-responsive');
  });
</script>
</body>
</html>
