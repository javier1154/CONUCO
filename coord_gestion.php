<?php
// Inclusión del archivo que contiene la conexion con la base de datos.
include( "funciones/conexion bd mysql.php" );

// Inclusión del archivo que contiene la función de inicio de la sesión.
include( "funciones/session.php" );

// Inclusión del archivo que contiene la función de la barra de navegación y las funciones para dar formatos a las fechas.
include( "funciones/fechas_barra.php" );


// Conexión a la base de datos.
conectar_bd_mysql();

if ( ( $_SESSION[ "CENSOpermisologia" ] == "Coordinador Consejo" ) and ( $_SESSION[ "CENSOstatus_permisologia" ] == "Habilitado" ) ) {

  $id_productor = $_GET["productor"];

  if ( strlen( $_GET[ 'oper' ] ) > 0 ) {
    $oper = $_GET[ 'oper' ];
    if ( $oper == "reg" ) {
      $alert = array("success", "El productor ha sido <b>registrado</b> satisfactoriamente.");
    } 
  }

  $id_censo = $_SESSION["CENSOid_censo"];
  $rc = mysql_fetch_array(mysql_query("select * from censo where id_censo = '$id_censo'"));
  $anio = $rc["anio"];
  $periodo = $rc["periodo"];

  $id_permisologia = $_SESSION[ 'CENSOid_permisologia' ];

  $cons = mysql_query("select * from censo_consejo where id_censo_consejo in (select id_censo_consejo from censo_consejo_coordinador where id_permisologia = '$id_permisologia') and id_censo = '$id_censo'");
  $existe = mysql_num_rows($cons);

  if($existe > 0){

    $rcc = mysql_fetch_array($cons);
    $id_consejo = $rcc["id_consejo"];
    $id_censo_consejo = $rcc["id_censo_consejo"];

    $cep = mysql_query("select * from censo_productor where id_censo_consejo = '$id_censo_consejo' and id_productor = '$id_productor'");
    $eep = mysql_num_rows($cep);

    if($eep > 0){

      $rc = mysql_fetch_array(mysql_query("select * from consejo where id_consejo = '$id_consejo'"));
      $id_consejo = $rc[ "id_consejo" ];
      $id_tipo    = $rc[ "id_tipo" ];
      $codigo     = $rc[ "codigo" ];
      $rif          = $rc[ "rif" ];
      $consejo          = $rc[ "consejo" ];
      $id_parroquia          = $rc[ "id_parroquia" ];
      $comunidad    = $rc[ "comunidad" ];
      $direccion    = $rc[ "direccion" ];

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

      if(strlen($rif) == 0){
        $rif = "SINF.";
      }
      if(strlen($codigo) == 0){
        $codigo = "SINF.";
      }

    }else{
      header("location: coord.php");
    }
  }else{
    header("location: coord.php");
  }



  if($_POST["comuna"]){

    $id_comuna = $_POST["comuna"];

    $ccc = mysql_query("select * from censo_comuna where id_comuna = '$id_comuna' and id_censo = '$id_censo'");
    $eccc = mysql_num_rows($ccc);

    if($eccc > 0){

      $rccc = mysql_fetch_array($ccc);
      $id_censo_comuna = $rccc["id_censo_comuna"];

      $ercc = mysql_num_rows(mysql_query("select * from censo_comuna_consejo where id_censo_consejo = '$id_censo_consejo'"));

      if($ercc == 0){

        $cie = mysql_query("insert into censo_comuna_consejo values (NULL, '$id_censo_comuna', '$id_censo_consejo')");

        if($cie){
          $alert = array("success", "Comuna vinculada satisfactoriamente.");
        }else{
          $alert = array("error", "No se pudo vincular la comuna.");
        }

      }
    }
  }



  if($_POST["rubro"]){

    $id_rubro = $_POST["rubro"];
    $hectareas = $_POST["hectareas"];
    $observacion = $_POST["observacion"];

    $er = mysql_num_rows(mysql_query("select * from rubro where id_rubro = '$id_rubro'"));

    if($er > 0){

      $err = mysql_num_rows(mysql_query("select * from censo_productor_siembra where id_productor = '$id_productor' and id_rubro = '$id_rubro' and hectareas = '$hectareas' and observacion = '$observacion'"));

      if($err == 0){

        $crs = mysql_query("insert into censo_productor_siembra values (NULL, '$id_productor', '$id_rubro', '$hectareas', '$observacion')");

        if($crs){
          $alert = array("success", "Siembra registrada satisfactoriamente.");
        }else{
          $alert = array("error", "No se pudo registrar la siembra.");
        }

      }

    }
  }


  if($_GET["id"]){

    $id_siembra = $_GET["id"];

    $er = mysql_num_rows(mysql_query("select * from censo_productor_siembra where id_siembra = '$id_siembra' and id_productor = '$id_productor'"));

    if($er > 0){

      $crs = mysql_query("delete from censo_productor_siembra where id_siembra = '$id_siembra'");

      if($crs){
        $alert = array("success", "Siembra eliminada satisfactoriamente.");
      }else{
        $alert = array("error", "No se pudo eliminar la siembra.");
      }


    }
  }



  $productores = mysql_num_rows(mysql_query("select * from censo_productor where id_censo_consejo in (select id_censo_consejo from censo_consejo where id_consejo = '$id_consejo')"));

}else{
  header("location: coord.php");
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
  <link rel="stylesheet" href="plugins/select2/select2.min.css">
  <link rel="stylesheet" href="plugins/datepicker/datepicker3.min.css">
  <?php include("partials/css.php"); ?>
  <link rel="stylesheet" href="css/app-det.css">
  <style type="text/css">
  body{
    font-size: 14px !important;
  }
  div#table_wrapper{
    margin-top: -8px;
  }

  div#table_filter{
    display: none;
  }
  div.table-responsive{
    margin-right: -20px;
  }
  a.btn-primary{
    background-color: #337ab7;
  }
  a i.fa-search{
    background-color: #f39c12;
  }

  div#detalles label{
    text-align: right;
  }

  table.perfil tr th{
    text-align: right;
  }
</style>
</head>
<body class="hold-transition skin-red sidebar-mini fixed">

  <?php include("partials/header.php"); ?>

  <section class="content">

    <section class="content-header" style=" margin-top: 0px; margin-bottom: 0px; float: left">
      <h1><i class="fa fa-user"></i> 
        <b><?php echo $_SESSION["CENSOnombres"]  ?></b>
      </h1>
    </section>

    <section class="content-header text-right" style=" margin-top: 36px; margin-bottom: 0px; float: right; margin-right: -14px">
      <button id="salir-sistema" style="float:right" class="btn btn-danger btn-flat salir-focal"><b><i class="fa fa-sign-out"></i></b></button>
    </section>

    <?php
    if($existe > 0){
      ?>

      <br><br><br><br><br>

      <div class="panel panel-danger">
        <div class="panel-heading" style="text-transform: uppercase;">

          <span>
          <b><i class="fa fa-cube"></i>  <?php echo $tipo  ?></b> <span style="font-weight: lighter;"><?php echo $consejo ?></span>
          </span>

          <span style="float: right">

            <?php echo $estado." - ".$municipio." - ".$parroquia; ?> <i class="fa fa-globe"></i> 
          </span>
        </div>

        <table class="table table-striped">
          <tr>
            <th class="text-right col-md-1">Código SITUR</th><td class="col-md-3"><?php if(strlen($codigo) > 0){ echo $codigo; }else{ echo "SINF."; } ?></td>
            <th class="text-right col-md-1">RIF</th><td class="col-md-3"><?php if(strlen($rif) > 0){ echo $rif; }else{ echo "SINF."; } ?></td>
            <th rowspan=3 class="col-md-4 text-center">
              <div class="col-md-12 text-center"><b>Productores</b></div>
              <div class="col-md-12 text-center" style="font-size: 60px; padding: 0px; margin-bottom: -12px; font-weight: bold;"><?php echo $productores ?></div>
            </th>
          </tr>
          <tr>
            <th class="text-right">Comunidad</th><td><?php echo $comunidad ?></td>
            <th class="text-right">Dirección</th><td><?php echo $direccion ?></td>
          </tr>
          <tr>
            <th class="text-right">Comuna</th><td colspan="3">

              <?php  
              $cc = mysql_query("select * from censo_comuna_consejo where id_censo_consejo = '$id_censo_consejo'");
              $ecc = mysql_num_rows($cc);

              if($ecc > 0){

                $recc = mysql_fetch_array($cc);
                $id_censo_comuna = $recc["id_censo_comuna"];

                $rcm = mysql_fetch_array(mysql_query("select * from comuna where id_comuna in (select id_comuna from censo_comuna where id_censo_comuna = '$id_censo_comuna')"));
                $comuna = $rcm["comuna"];

                echo $comuna;

              }else{
                ?>
                <span style="color: red">El consejo no se encuentra vinculado a ninguna comuna</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary btn-xs" href="#Mvincular" role="button" data-toggle="modal"><i class="fa fa-plus"></i> Vincular</button>


                <!-- ------------------------ REGISTRAR  ------------------------ -->

                <div class="modal fade" role="dialog" aria-labelledby="Modal" id="Mvincular" style="display: none;">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="ModalLabel" style="font-weight: bold;"><i class="fa fa-cogs"></i> Vincular a comuna</h4>
                      </div>

                      <form action="coord_gestion.php" method="POST">
                        <input type="hidden" name="cid_permisologia">
                        <div class="modal-body">
                          <div class="row">
                            <div class="col-md-12">
                              <label class="col-sm-12 text-left">Comuna</label>
                              <div class="col-sm-12">

                                <select class="form-control" name="comuna" required>
                                  <option value="">Seleccione</option>
                                  <?php  
                                  $cc = mysql_query("select * from comuna where id_comuna in (select id_comuna from censo_comuna where id_censo = '$id_censo') and id_municipio = '$id_municipio'");

                                  $ecc = mysql_num_rows($cc);
                                  if($ecc > 0){
                                    while($recc = mysql_fetch_array($cc)){
                                      $id_comuna = $recc["id_comuna"];
                                      $comuna = $recc["comuna"];
                                      ?>
                                      <option value="<?php echo $id_comuna; ?>"><?php echo $comuna ?></option>
                                      <?php
                                    }
                                  } 
                                  ?>
                                </select>

                                <br/>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
                          <button type="submit" class="btn btn-danger btn-flat"><i class="fa fa-floppy-o"></i> Vincular</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                <!-- --------------------------------------------- -->


                <?php
              }
              ?>

            </td>
          </tr>
        </table>

      </div>

      <legend><i class="fa fa-users"></i> <b>Productor</b>
        <a style="float:right; margin-top: -4px;" href="coord.php?oper=regu" class="btn btn-danger" role="button" data-toggle="modal"><i class="fa fa-plus"></i> Agregar Otro</a>
        <a style="float:right; margin-top: -4px; margin-right: 10px; background-color: #222d32 !important; color: #FFF;" href="coord.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Volver</a>
      </legend>

      <?php

      $rowp = mysql_fetch_array($cep);
      $id_usuario = $rowp["id_usuario"];
      $id_rubro = $rowp["id_rubro"];
      $hectareas = $rowp["hectareas"];
      $hectareas_plan = $rowp["hectareas_plan"];
      $mecanizable = $rowp["mecanizable"];
      $semillas = $rowp["semillas"];
      $tipo_semilla = $rowp["tipo_semilla"];
      $cantidad = $rowp["cantidad"];

      $rowu = mysql_fetch_array(mysql_query("select * from usuario where id_usuario = '$id_usuario'"));
      $cedula = $rowu["cedula"];
      $nombres = $rowu["nombres"];
      $sexo = $rowu["sexo"];
      $fecha_nacimiento = $rowu["fecha_nacimiento"];
      $telefono1 = $rowu["telefono1"];
      $telefono2 = $rowu["telefono2"];
      $ccp = $rowu["ccp"];
      $scp = $rowu["scp"];
      $correo = $rowu["correo"];
      $fecha_registro = $rowp["fecha_registro"];

      $rowr = mysql_fetch_array(mysql_query("select * from rubro where id_rubro = '$id_rubro'"));
      $id_clasificacion = $rowr["id_clasificacion"];
      $rubro = $rowr["rubro"];

      $rowc = mysql_fetch_array(mysql_query("select * from clasificacion where id_clasificacion = '$id_clasificacion'"));
      $clasificacion = $rowc["clasificacion"];

      ?>

      <div class="row">
        <div class="col-md-8">
          <div class="panel-danger">
            <div class="panel-heading" style="color: #FFF; font-weight: bold"><i class="fa fa-user"></i> <?php echo $nombres ?></div>
          </div>
          <table class="table table-striped perfil">
            <tr>
              <th class="col-md-2">Cédula</th>
              <td><?php echo $cedula ?></td>

              <th class="col-md-2">Sexo</th>
              <td><?php echo $sexo ?></td>

              <th class="col-md-2">Fecha de Nacimiento</th>
              <td><?php echo traducefecha($fecha_nacimiento) ?></td>
            </tr>

            <tr>
              <th>Teléfono Cel.</th>
              <td><?php echo $telefono1 ?></td>

              <th>Teléfono Hab.</th>
              <td><?php if(strlen($telefono2) > 0) echo $telefono2; else echo "SINF."; ?></td>

              <th>Correo</th>
              <td><?php if(strlen($correo) > 0) echo $correo; else echo "SINF."; ?></td>
            </tr>

            <tr>
              <th>Código del Carné de la Patria</th>
              <td><?php echo $ccp ?></td>

              <th>Serial del Carné de la Patria</th>
              <td><?php echo $scp ?></td>

              <th>Fecha de Registro</th>
              <td><?php echo traducefechahorac($fecha_registro) ?></td>
            </tr>

            <tr>
              <th class="tit" colspan="6" style="background-color: #008080 !important; text-align: left !important; color: #FFF"><i class="fa fa-shopping-basket"></i> DATOS PARA EL PLAN DE SIEMBRA</th>
            </tr>

            <tr>
              <th>Tipo de Rubro</th>
              <td><?php echo $clasificacion ?></td>

              <th>Rubro</th>
              <td><?php echo $rubro ?></td>

              <th>Hectáreas Disponibles</th>
              <td><?php echo $hectareas ?></td>
            </tr>

            <tr>
              <th>Hectáreas Plan</th>
              <td><?php echo $hectareas_plan ?></td>

              <th>¿Mecanizable?</th>
              <td><?php echo $mecanizable ?></td>

              <th>¿Posee Semillas?</th>
              <td><?php echo $semillas ?></td>

            </tr>

            <?php  
            if($semillas == "SI"){
              ?>
              <tr>
                <th>Cantidad</th>
                <td><?php echo $cantidad ?> Kg.</td>

                <th>Tipo de Semillas</th>
                <td colspan="3"><?php echo $tipo_semilla ?></td>
              </tr>
              <?php
            }
            ?>

            

          </table>
        </div>


        <div class="col-md-4">
          <div class="panel-default">
            <div class="panel-heading" style="background-color: #222d32 !important; color: #FFF"><b><i class="fa fa-shopping-basket"></i> Rubros Sembrados</b> <button class="btn btn-danger btn-xs" style="float: right" href="#Msiembra" role="button" data-toggle="modal"><i class="fa fa-plus"></i> Agregar</button></div>
            <?php
            $cs = mysql_query("select * from censo_productor_siembra where id_productor = '$id_productor'");
            $ecs = mysql_num_rows($cs);

            if($ecs > 0){

              ?>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>N°</th>
                    <th class="sol-sm-5">Rubro</th>
                    <th class="sol-sm-1">Hectáreas</th>
                    <th class="sol-sm-6">Observación</th>
                    <th>Opción</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                while ($rcs = mysql_fetch_array($cs)) {
                  $i++;
                  $id_siembra = $rcs["id_siembra"];
                  $id_rubro = $rcs["id_rubro"];

                  $rrcs = mysql_fetch_array(mysql_query("select * from rubro where id_rubro = '$id_rubro'"));
                  $rubro = $rrcs["rubro"];

                  $hectareas = $rcs["hectareas"];
                  $observacion = $rcs["observacion"];
 
                  ?>
                  <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $rubro; ?></td>
                    <td><?php echo $hectareas; ?></td>
                    <td><?php echo $rubro; ?></td>
                    <td class="t-opciones" data-valor='{"id":"<?php echo $id_siembra; ?>", "nombre":"<?php echo $rubro; ?>"}'>
                      <center><a  class="eliminar" href="#!"><i class="fa fa-trash" title="Eliminar Siembra"></i></a></center>
                    </td>
                  </tr>
                  <?php
                }
                ?>
                </tbody>
              </table>
              <?php
            }else{
              ?>
              <div class="alert alert-default">
                El productor no posee registros de siembra en el sistema.
              </div>
              <?php
            }
            ?>
          </div>




          <!-- ------------------------ REGISTRAR  ------------------------ -->
          <div class="modal fade" role="dialog" aria-labelledby="Modal" id="Msiembra" style="display: none;">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="ModalLabel" style="font-weight: bold;"><i class="fa fa-cogs"></i> Registrar Siembra</h4>
                </div>

                <form action="coord_gestion.php?productor=<?php echo $id_productor ?>" method="POST">
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label class="col-sm-12 text-left">Tipo de Rubro</label>
                        <div class="col-sm-12">

                          <select class="form-control" name="clasificacion" required>
                            <option value="">Seleccione</option>
                            <?php
                            $cc = mysql_query("select * from clasificacion where status = 'Habilitado' and id_clasificacion in (select id_clasificacion from rubro where status = 'Habilitado') order by clasificacion");
                            $ec = mysql_num_rows($cc);
                            if($ec > 0){
                              while($rc = mysql_fetch_array($cc)){
                                $id_clasificacion = $rc["id_clasificacion"];
                                $clasificacion = $rc["clasificacion"];
                                ?>
                                <option value="<?php echo $id_clasificacion ?>"><?php echo $clasificacion ?></option>
                                <?php
                              }
                            }
                            ?>
                          </select>

                          <br/><br/>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <label class="col-sm-12 text-left">Rubro</label>
                        <div class="col-sm-12">

                          <select class="form-control" name="rubro" required>
                            <option value="">Seleccione</option>
                          </select>

                          <br/><br/>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <label class="col-sm-12 text-left">Hectáreas</label>
                        <div class="col-sm-12">

                          <input type="number" name="hectareas" class="form-control" required>

                          <br/>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <label class="col-sm-12 text-left">Observaciones</label>
                        <div class="col-sm-12">

                          <textarea class="form-control" name="observacion"></textarea>

                          <br/><br/>
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

          <!-- --------------------------------------------- -->



        </div>
      </div>

      <?php  
    }
    ?>



  </section>
  <?php include("partials/footer.php"); ?> 
  <?php include("partials/js.php"); ?>
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
  <script src="plugins/select2/select2.full.min.js"></script>
  <script src="plugins/select2/i18n/es.js"></script>
  <script src="plugins/input-mask/inputmask.js" /></script>
  <script src="plugins/input-mask/jquery.inputmask.js" /></script>
  <script src="plugins/datepicker/bootstrap-datepicker.min.js"></script>
  <script src="plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>

  <script type="text/javascript">

    $(document).ready(function() {

      var id_productor = "<?php echo $id_productor; ?>";

      $("select.form-control").css('width', '100%');
      $.fn.select2.defaults.set('language', 'es');
      $("select.form-control").select2();

      $("select[name='clasificacion']").change(function(){

        var id_clasi = $(this).val();

        if(id_clasi.length){
          $("select[name='rubro']").empty();
          $("select.form-control").select2();
          $("select[name='rubro']").load('ajax/rubros_select.php?clasificacion='+id_clasi);
          $("select.form-control").select2();
        }else{
          $("select[name='rubro']").empty();
          $("iframe").empty();
        }

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
              url = 'coord_gestion.php?productor='+id_productor+'&id='+id;
              $(location).attr('href', url);
            }
          });

        });

    });

  </script>
</body>
</html>