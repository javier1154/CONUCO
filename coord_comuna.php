<?php
// Inclusión del archivo que contiene la conexion con la base de datos.
include( "funciones/conexion bd mysql.php" );

// Inclusión del archivo que contiene la función de inicio de la sesión.
include( "funciones/session.php" );

// Inclusión del archivo que contiene la función de la barra de navegación y las funciones para dar formatos a las fechas.
include( "funciones/fechas_barra.php" );


// Conexión a la base de datos.
conectar_bd_mysql();

if ( ( $_SESSION[ "CENSOpermisologia" ] == "Coordinador Comuna" ) and ( $_SESSION[ "CENSOstatus_permisologia" ] == "Habilitado" ) ) {

  $id_censo = $_SESSION["CENSOid_censo"];
  $rc = mysql_fetch_array(mysql_query("select * from censo where id_censo = '$id_censo'"));
  $anio = $rc["anio"];
  $periodo = $rc["periodo"];

  $id_permisologia = $_SESSION[ 'CENSOid_permisologia' ];

  $cons = mysql_query("select * from censo_comuna where id_censo_comuna in (select id_censo_comuna from censo_comuna_coordinador where id_permisologia = '$id_permisologia') and id_censo = '$id_censo'");
  $existe = mysql_num_rows($cons);

  if($existe > 0){

    $rcc = mysql_fetch_array($cons);
    $id_comuna = $rcc["id_comuna"];
    $id_censo_comuna = $rcc["id_censo_comuna"];

    $rc = mysql_fetch_array(mysql_query("select * from comuna where id_comuna = '$id_comuna'"));
    $id_comuna   = $rc[ "id_comuna" ];
    $codigo       = $rc[ "codigo" ];
    $rif          = $rc[ "rif" ];
    $comuna      = $rc[ "comuna" ];
    $direccion    = $rc[ "direccion" ];
    $id_parroquia = $rc[ "id_parroquia" ];
    $id_municipio = $rc[ "id_municipio" ];

    if(strlen($rif) == 0){
      $rif = "SINF.";
    }
    if(strlen($codigo) == 0){
      $codigo = "SINF.";
    }

    $rc2 = mysql_fetch_array(mysql_query("select * from censo_comuna where id_comuna = '$id_comuna' and id_censo = '$id_censo'"));
    $id_censo_comuna  = $rc2[ "id_censo_comuna" ];

    $rp = mysql_fetch_array(mysql_query("select * from parroquia where id_parroquia = '$id_parroquia'"));
    $parroquia  = $rp[ "parroquia" ];
    $id_municipio  = $rp[ "id_municipio" ];

    $rm = mysql_fetch_array(mysql_query("select * from municipio where id_municipio = '$id_municipio'"));
    $municipio  = $rm[ "municipio" ];
    $id_estado  = $rm[ "id_estado" ];

    $re = mysql_fetch_array(mysql_query("select * from estado where id_estado = '$id_estado'"));
    $estado  = $re[ "estado" ];

    if(isset($_POST["nombre"])){

      $codigo = $_POST["codigo"];
      $nombre = $_POST["nombre"];
      $tipo = $_POST["tipo"];
      $rif = $_POST["rif"];
      $parroquia = $_POST["parroquia"];
      $comunidad = $_POST["comunidad"];
      $direccion = $_POST["direccion"];

      if(strlen($codigo) > 0){
        $existe_codigo = mysql_num_rows(mysql_query("select * from consejo where codigo = '$codigo'"));
      }else{
        $existe_codigo == 0;
      }

      if($existe_codigo == 0){

        if(strlen($rif) > 0){
          $existe_rif = mysql_num_rows(mysql_query("select * from consejo where rif = '$rif'"));
        }else{
          $existe_rif == 0;
        }

        if($existe_rif == 0){

          $mep = mysql_num_rows(mysql_query("select * from parroquia where id_parroquia = '$parroquia' and id_municipio = '$id_municipio'"));

          if($mep > 0){

            $eecc = mysql_num_rows(mysql_query("select * from consejo where codigo = '$codigo' and rif = '$rif' and consejo = '$nombre' and id_tipo = '$tipo' and id_parroquia = '$parroquia' and comunidad = '$comunidad' and direccion = '$direccion'"));

            if($eecc == 0){

              $encargado = $_SESSION["CENSOid_usuario"];
              $hoy = date("Y-m-d H:i:s");

              $cons2 = mysql_query("insert into consejo values (NULL, '$codigo', '$rif', '$nombre', '$parroquia', '$tipo', '$comunidad', '$direccion', 'Habilitado', '$encargado', '$hoy')");

              if($cons2){

                $id_consejo = mysql_insert_id();
                $id_censo = $_SESSION["CENSOid_censo"];

                $cons3 = mysql_query("insert into censo_consejo values (NULL, '$id_censo', '$id_consejo')");

                if($cons3){

                  $id_censo_consejo = mysql_insert_id();

                  $cons4 = mysql_query("insert into censo_comuna_consejo values (NULL, '$id_censo_comuna', '$id_censo_consejo')");

                  if($cons4){

                    //$alert = array("success", "Consejo registrado satisfactoriamente.");
                    //unset($_POST);
                    header("location: coord_comuna_consejo.php?oper=reg&consejo=".$id_consejo);

                  }else{
                    mysql_query("delete from consejo where id_consejo = '$id_consejo'");
                    $alert = array("error", "Ocurrió un error al intentar registrar el consejo.");
                  }

                }else{
                  mysql_query("delete from consejo where id_consejo = '$id_consejo'");
                  $alert = array("error", "Ocurrió un error al intentar registrar el consejo.");
                }

              }else{
                $alert = array("error", "Ocurrió un error al intentar registrar el consejo.");
              }

            }

          }
        }else{
          $alert = array("error", "El RIF que ingresó ya se encuentra registrado en el sistema.");
        }
      }else{
        $alert = array("error", "El Código que ingresó ya se encuentra registrado en el sistema.");
      }
    }

    if(isset($_GET["id"])){

      $id_consejo = $_GET["id"];

      $existe_id = mysql_num_rows(mysql_query("select * from consejo where id_consejo = '$id_consejo' and id_consejo in (select id_consejo from censo_consejo where id_censo_consejo in (select id_censo_consejo from censo_comuna_consejo where id_censo_comuna = '$id_censo_comuna'))"));

      if($existe_id > 0){

        $productores = mysql_num_rows(mysql_query("select * from censo_productor where id_censo_consejo in (select id_censo_consejo from censo_consejo where id_consejo = '$id_consejo')"));

        if($productores == 0){
          $cep = mysql_query("delete from consejo where id_consejo = '$id_consejo'");

          if($cep){
            $alert = array("success", "El consejo ha sido eliminado satisfactoriamente.");
          }else{
            $alert = array("error", "No se pudo eliminar el consejo.");
          }
        }
      }
    }

    $consejos = mysql_num_rows(mysql_query("select * from censo_comuna_consejo where id_censo_comuna = '$id_censo_comuna'"));

    $productores = mysql_num_rows(mysql_query("select * from censo_productor where id_censo_consejo in (select id_censo_consejo from censo_comuna_consejo where id_censo_comuna = '$id_censo_comuna')"));

  }else{
    header("location: index.php?cmd=cerrar");
  }

}else{
  header("location: index.php?cmd=cerrar");
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
      <button id="salir-sistema" style="float:right; height: 40px; border-radius: 4px" class="btn btn-danger btn-flat salir-focal"><b><i class="fa fa-sign-out"></i></b></button>
    </section>

    <?php
    if($existe > 0){
      ?>

      <br><br><br><br><br>

      <div class="panel panel-danger">
        <div class="panel-heading" style="text-transform: uppercase;">
          <span>
            <i class="fa fa-cube"></i> COMUNA <b><?php echo $comuna ?></b> 
          </span>

          <span style="float: right">
          <?php echo $estado." - ".$municipio." - ".$parroquia; ?> <i class="fa fa-globe"></i> 
          </span>
        </div>

        <table class="table table-striped">
          <tr>
            <th class="text-right col-md-1">Código SITUR</th><td class="col-md-3"><?php if(strlen($codigo) > 0){ echo $codigo; }else{ echo "SINF."; } ?></td>
            <th rowspan=3 class="col-md-3 text-center">
              <div class="col-md-12 text-center"><b>Consejos</b></div>
              <div class="col-md-12 text-center" style="font-size: 60px; padding: 0px; margin-bottom: -12px; font-weight: bold;"><?php echo $consejos ?></div>
            </th>
            <th rowspan=3 class="col-md-3 text-center">
              <div class="col-md-12 text-center"><b>Productores</b></div>
              <div class="col-md-12 text-center" style="font-size: 60px; padding: 0px; margin-bottom: -12px; font-weight: bold;"><?php echo $productores ?></div>
            </th>
          </tr>
          <tr>
            <th class="text-right col-md-1">RIF</th><td class="col-md-3"><?php if(strlen($rif) > 0){ echo $rif; }else{ echo "SINF."; } ?></td>
          </tr>
          <tr>
            <th class="text-right">Dirección</th><td colspan="3"><?php echo $direccion ?></td>
          </tr>
        </table>

      </div>

      
      <legend style="margin-bottom: 4px"><i class="fa fa-cube"></i> <b>Consejos</b> 
        <a style="float:right; margin-top: -4px;" href="#Mconsejo" class="btn btn-danger" role="button" data-toggle="modal"><i class="fa fa-plus"></i> Agregar</a>
      </legend>
        <?php  

        $cons2 = mysql_query("select * from consejo where id_consejo in (select id_consejo from censo_consejo where id_censo_consejo in (select id_censo_consejo from censo_comuna_consejo where id_censo_comuna = '$id_censo_comuna')) order by fecha_registro");
        $consejos = mysql_num_rows($cons2);

        if($consejos > 0){
          ?>
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th>N°</th>
                <th class="col-md-1">Código</th>
                <th class="col-md-1">RIF</th>
                <th class="col-md-2">Tipo</th>
                <th class="col-md-4">Consejo</th>
                <th class="col-md-1">Productores</th>
                <th class="col-md-1">Estado</th>
                <th class="col-md-1">Municipio</th>
                <th class="col-md-1">Parroquia</th>
                <th class="opciones col-md-1">Opci&oacute;nes</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 0;
              while($row = mysql_fetch_array($cons2)){

                $i++;
                $id_consejo   = $row[ "id_consejo" ];
                $id_tipo      = $row[ "id_tipo" ];
                $codigo       = $row[ "codigo" ];
                $rif          = $row[ "rif" ];
                $consejo      = $row[ "consejo" ];
                $id_parroquia = $row[ "id_parroquia" ];
              
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

                if(strlen($rif) == 0){
                  $rif = "SINF.";
                }
                if(strlen($codigo) == 0){
                  $codigo = "SINF.";
                }
                ?>
              <tr>
                <th><?php echo $i; ?></th>
                <td><?php echo $codigo; ?></td>
                <td><?php echo $rif; ?></td>
                <td><?php echo $tipo; ?></td>
                <td><?php echo $consejo; ?></td>
                <td class="text-center"><?php echo $productores; ?></td>
                <td><?php echo $estado; ?></td>
                <td><?php echo $municipio; ?></td>
                <td><?php echo $parroquia; ?></td>
                <td class="t-opciones" data-valor='{"id":"<?php echo $id_consejo; ?>", "nombre":"<?php echo $consejo; ?>"}'>
                <center>
                  <a href="coord_comuna_consejo.php?consejo=<?php echo $id_consejo; ?>"><i class="fa fa-cogs" title="Gestionar consejo"></i></a>
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
                <td colspan="10" class="opciones"><center>
                  <i class="fa fa-cogs"></i>&nbsp;Gestionar&nbsp;
                  <i class="fa fa-trash"></i>&nbsp;Eliminar&nbsp;
                  </center></td>
              </tr>
            </tfoot>
            
          </table>
          <?php
        }else{
          ?>
          <div class="alert alert-info text-left" style="margin-top: 20px;">
            <i class="fa fa-exclamation-triangle" style="font-size: 40px; float:left; margin-right: 16px; margin-top: 6px;"></i>
            <h4>AVISO!</h4>
            No existen consejos registrados en esta comuna
          </div>
          <?php
        }
        ?>



        <!-- ------------------------------------------------ REGISTRAR  ------------------------------ -->

        <div class="modal fade" role="dialog" aria-labelledby="Modal" id="Mconsejo" style="display: none;">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="ModalLabel" style="font-weight: bold;"><i class="fa fa-plus"></i> Registrar Consejo</h4>
              </div>

              <form action="coord_comuna.php" method="POST">

                <div class="modal-body">

                  <div class="row">

                    <div class="form-group">
                      <div class="col-md-6">
                        <label class="col-sm-12 text-left">Código SITUR</label>
                        <div class="col-sm-12">
                          <input type="text" class="form-control" name="codigo" value="<?php echo $_POST['codigo']; ?>">
                          <br/>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <label class="col-sm-12 text-left">RIF</label>
                        <div class="col-sm-12">
                          <input type="text" class="form-control" name="rif" value="<?php echo $_POST['rif']; ?>">
                          <br/>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">

                      <div class="col-md-6">
                        <label class="col-sm-12 text-left">Tipo</label>
                        <div class="col-sm-12">
                          <select name="tipo" class="form-control" required>
                            <option value="">Seleccione</option>
                            <?php  
                            $ct = mysql_query("select * from consejo_tipo where status = 'Habilitado' order by tipo");
                            $et = mysql_num_rows($ct);

                            if($et > 0){
                              while($rt = mysql_fetch_array($ct)){
                                $id_consejo_tipo = $rt["id_consejo_tipo"];
                                $tipo = $rt["tipo"];
                                ?>
                                <option <?php if($_POST["tipo"] == $id_consejo_tipo) echo "selected='selected'" ?> value="<?php echo $id_consejo_tipo ?>"><?php echo $tipo ?></option>
                                <?php
                              }
                            }
                            ?>
                          </select>
                          <br/>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <label class="col-sm-12 text-left">Nombre</label>
                        <div class="col-sm-12">
                          <input type="text" class="form-control" name="nombre" value="<?php echo $_POST['nombre']; ?>" required><br/>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-6">
                        <label class="col-sm-12 text-left">Parroquia</label>
                        <div class="col-sm-12">
                          <select name="parroquia" class="form-control" required>
                            <option value="">Seleccione</option>
                            <?php  
                            $ce = mysql_query("select * from parroquia where id_municipio = '$id_municipio' order by parroquia");
                            $ee = mysql_num_rows($ce);
                            if($ee > 0){
                              while($re = mysql_fetch_array($ce)){
                                $id_parroquia = $re["id_parroquia"];
                                $parroquia = $re["parroquia"];
                                ?>
                                <option value="<?php echo $id_parroquia ?>"><?php echo $parroquia ?></option>
                                <?php
                              }
                            }
                            ?>
                          </select><br/>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <label class="col-sm-12 text-left">Comunidad</label>
                        <div class="col-sm-12">
                          <input type="text" class="form-control" name="comunidad" value="<?php echo $_POST['comunidad']; ?>" required><br/>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-12">
                        <label class="col-sm-12 text-left">Dirección</label>
                        <div class="col-sm-12">
                          <textarea class="form-control" name="direccion"><?php echo $_POST["direccion"]; ?></textarea><br/>
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

      var oper = "<?php echo $_GET['oper']; ?>";

      if(oper == "reg"){
        $("div#Mconsejo").modal();
      }

      $("select.form-control").css('width', '100%');
      $.fn.select2.defaults.set('language', 'es');
      $("select.form-control").select2();

      $("input[name='rif']").inputmask("A-9{4,9}-9", {
        definitions: {
          "A": {
            cardinality: 1,
            casing: "upper"
          }
        }
      });

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
            url = 'coord_comuna.php?id='+id+'';
            $(location).attr('href', url);
          }
        });
      
    });


    });

  </script>
</body>
</html>