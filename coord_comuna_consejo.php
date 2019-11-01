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

  $id_consejo = $_GET["consejo"];

  if(isset($_GET["oper"])){
    if(isset($_GET["oper"]) == "reg"){
      $alert = array("success", "Consejo registrado satisfactoriamente, proceda a realizar el registro de los voceros del consejo.");
    }
  }

  $id_censo = $_SESSION["CENSOid_censo"];
  $rc = mysql_fetch_array(mysql_query("select * from censo where id_censo = '$id_censo'"));
  $anio = $rc["anio"];
  $periodo = $rc["periodo"];

  $id_permisologia = $_SESSION[ 'CENSOid_permisologia' ];

  $cons = mysql_query("select * from censo_consejo where id_censo_consejo in (select id_censo_consejo from censo_comuna_consejo where id_censo_comuna in (select id_censo_comuna from censo_comuna_coordinador where id_permisologia = '$id_permisologia')) and id_censo = '$id_censo' and id_consejo = '$id_consejo'");
  $existe = mysql_num_rows($cons);

  if($existe > 0){

    $rcc = mysql_fetch_array($cons);
    $id_consejo = $rcc["id_consejo"];
    $id_censo_consejo = $rcc["id_censo_consejo"];

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

    $productores = mysql_num_rows(mysql_query("select * from censo_productor where id_censo_consejo in (select id_censo_consejo from censo_consejo where id_consejo = '$id_consejo')"));



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
      $tipo = "Coordinador Consejo";
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

            $cep = mysql_query("select * from permisologia where id_usuario = '$aid_usuario'");
            $ep = mysql_num_rows($cep);

            if($ep == 0){

              $cip = mysql_query("insert into permisologia values (NULL, '$aid_usuario', '$clave', '$tipo', 'Habilitado', '$encargado_registro', '$fecha_registro')");

              if($cip){

                $id_permisologia = mysql_insert_id();

                $rccc = mysql_query("insert into censo_consejo_coordinador values (NULL, '$id_permisologia', '$id_censo_consejo', '$encargado_registro', '$fecha_registro')");

                if($rccc){
                  $alert = array("success", "La permisología del usuario <b>".$ausuario."</b> ha sido asignada satisfactoriamente.");
                  unset($_POST);
                }else{
                  $alert = array("error", "No se pudo registrar la permisología del usuario <b>".$ausuario."</b>.");
                }
                
              }else{
                $alert = array("error", "Ocurrió un error al intentar la permisología del usuario <b>".$ausuario."</b>.");
              }

            }else{

              $rep = mysql_fetch_array($cep);
              $tipo_permisologia = $rep["tipo"];
              $id_permisologia = $rep["id_permisologia"];

              if($tipo_permisologia == "Administrador"){

                $alert = array("error", "El usuario <b>".$ausuario."</b> ya posee una permisología de Administrador asignada.");

              }elseif($tipo_permisologia == "Coordinador Consejo"){

                $cpc = mysql_query("select * from censo_consejo_coordinador where id_permisologia = '$id_permisologia' and id_censo_consejo in (select id_censo_consejo from censo_consejo where id_censo = '$id_censo')");
                $ecpc = mysql_num_rows($cpc);

                if($ecpc > 0){

                  $alert = array("error", "El usuario <b>".$ausuario."</b> ya posee una permisología de Vocero de Consejo asignada.");

                }else{

                  $apu = mysql_query("update permisologia set clave = '$clave', tipo = '$tipo' where id_permisologia = '$id_permisologia'");

                  if($apu){

                    $rccc = mysql_query("insert into censo_consejo_coordinador values (NULL, '$id_permisologia', '$id_censo_consejo', '$encargado_registro', '$fecha_registro')");

                    if($rccc){
                      $alert = array("success", "La permisología del usuario <b>".$ausuario."</b> ha sido asignada satisfactoriamente.");
                      unset($_POST);
                    }else{
                      $alert = array("error", "No se pudo registrar la permisología del usuario <b>".$ausuario."</b>.");
                    }

                  }else{
                    $alert = array("error", "Ocurrió un error al intentar actualizar la permisología del usuario <b>".$ausuario."</b>.");
                  }
                }

              }elseif($tipo_permisologia == "Coordinador Comuna"){

                $cpc = mysql_query("select * from censo_comuna_coordinador where id_permisologia = '$id_permisologia' and id_censo_comuna in (select id_censo_comuna from censo_comuna where id_censo = '$id_censo')");
                $ecpc = mysql_num_rows($cpc);

                if($ecpc > 0){
                  $alert = array("error", "El usuario <b>".$ausuario."</b> ya posee una permisología de Vocero de Comuna asignada.");
                }else{

                  $rccc = mysql_query("insert into censo_consejo_coordinador values (NULL, '$id_permisologia', '$id_censo_consejo', '$encargado_registro', '$fecha_registro')");

                  if($rccc){
                    $alert = array("success", "La permisología del usuario <b>".$ausuario."</b> ha sido asignada satisfactoriamente.");
                    unset($_POST);
                  }else{
                    $alert = array("error", "No se pudo registrar la permisología del usuario <b>".$ausuario."</b>.");
                  }

                }
              }
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

              $id_permisologia = mysql_insert_id();

              $rccc = mysql_query("insert into censo_consejo_coordinador values (NULL, '$id_permisologia', '$id_censo_consejo', '$encargado_registro', '$fecha_registro')");

              if($rccc){
                $alert = array("success", "La permisología del usuario <b>".$nombres."</b> ha sido asignada satisfactoriamente.");
                unset($_POST);
              }else{
                $alert = array("error", "No se pudo registrar la permisología del usuario <b>".$nombres."</b>.");
              }
              
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


    if(isset($_GET["id"])){

      $id_coordinaro = $_GET["id"];

      $existe = mysql_num_rows(mysql_query("select * from censo_consejo_coordinador where id_censo_consejo = '$id_censo_consejo' and id_censo_consejo_coordinador = '$id_coordinaro'"));

      if($existe > 0){

        $cep = mysql_query("delete from censo_consejo_coordinador where id_censo_consejo_coordinador = '$id_coordinaro'");

        if($cep){
          $alert = array("success", "El vocero ha sido eliminado satisfactoriamente.");
        }else{
          $alert = array("error", "No se pudo eliminar el vocero.");
        }
      }
    }

  }else{
    header("location: coord_comuna.php");
  }
}else{
  header("location: coord_comuna.php");
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
      <a href="coord_comuna.php" title="Atrás" class="atras opciones" style="margin-top: 0px; margin-right: 10px"></a>
    </section>

    <?php
    if($existe > 0){
      ?>

      <br><br><br><br><br>

      <div class="row">
        <div class="col-md-8">

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

                  }
                  ?>

                </td>
              </tr>
            </table>

          </div>
          
        </div>
        <div class="col-md-4">

          <div class="panel-info">
            <div class="panel-heading" style="text-transform: uppercase;">
              <i class="fa fa-tag"></i> VOCEROS<button class="btn btn-danger btn-xs" style="float: right" href="#Mcoordinador" role="button" data-toggle="modal"><i class="fa fa-plus"></i> Agregar</button>
            </div>
            <?php  
            $cecc = mysql_query("select * from censo_consejo_coordinador where id_censo_consejo = '$id_censo_consejo'");
            $ecc = mysql_num_rows($cecc);
            if($ecc > 0){

              ?>
              <table class="table table-striped" style="margin: 0px">
                <thead>
                  <tr>
                    <th>N°</th>
                    <th>Cédula</th>
                    <th>Usuario</th>
                    <th>Opción</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $c = 0;
                  while($roc = mysql_fetch_array($cecc)){
                    $c++;
                    $id_censo_consejo_coordinador = $roc["id_censo_consejo_coordinador"];
                    $id_permisologia = $roc["id_permisologia"];

                    $roa = mysql_fetch_array(mysql_query("select * from usuario where id_usuario in (select id_usuario from permisologia where id_permisologia = '$id_permisologia')"));
                    $cedula = $roa["cedula"];
                    $nombres = $roa["nombres"];
                    ?>
                    <tr>
                      <td><?php echo $c; ?></td>
                      <td><?php echo $cedula; ?></td>
                      <td><?php echo $nombres; ?></td>
                      <td class="t-opciones" data-valor='{"id":"<?php echo $id_censo_consejo_coordinador; ?>", "nombre":"<?php echo $nombres; ?>"}'>
                        <center><a  class="eliminar" href="#!"><i class="fa fa-trash" title="Eliminar Permisología"></i></a></center>
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
              <div class="alert" style="margin:0px">No existen voceros asignados a esta comuna.</div>
              <?php
            }
            ?>
          </div>


          <!-- ------------------------------------------------ REGISTRAR  ------------------------------ -->

          <div class="modal fade" role="dialog" aria-labelledby="Modal" id="Mcoordinador" style="display: none;">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="ModalLabel" style="font-weight: bold;"><i class="fa fa-plus"></i> Registrar Vocero</h4>
                </div>

                <form action="coord_comuna_consejo.php?consejo=<?php echo $id_consejo ?>" method="POST">

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

                        <div class="col-md-6">
                          <label class="col-sm-12 text-left">Contraseña</label>
                          <div class="col-sm-12">
                            <input type="password" name="clave1" class="form-control" required min="4" max="20"><br/>
                          </div>
                        </div>

                        <div class="col-md-6">
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

          
        </div>
      </div>

      
      <legend style="margin-bottom: 4px;"><i class="fa fa-users"></i> 
        <b>Productores</b> 
        <a style="float:right; margin-top: -4px;" href="coord_comuna.php?oper=reg" class="btn btn-danger"><i class="fa fa-plus"></i> Registrar otro consejo</a>
      </legend>
      <?php  
      $cons2 = mysql_query("select * from censo_productor where id_censo_consejo = '$id_censo_consejo' order by fecha_registro");
      $productores = mysql_num_rows($cons2);

      if($productores > 0){
        ?>
        <table class="table table-hover table-striped" id="table">
          <thead>
            <tr>
              <th>N°</th>
              <th class="col-md-1">Cédula</th>
              <th class="col-md-4">Nombres</th>
              <th class="col-md-1">Sexo</th>
              <th class="col-md-1">Fecha de Nacimiento</th>
              <th class="col-md-1">Teléfono</th>
              <th class="col-md-2">Rubro</th>
              <th class="col-md-1">Hectáreas Disponibles</th>
              <th class="col-md-1">¿Mecanizable?</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 0;
            while($row2 = mysql_fetch_array($cons2)){

              $i++;

              $id_productor = $row2["id_productor"];
              $id_usuario = $row2["id_usuario"];
              $id_rubro = $row2["id_rubro"];
              $hectareas = $row2["hectareas"];
              $mecanizable = $row2["mecanizable"];

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
                <td><?php echo $sexo; ?></td>
                <td><?php echo traducefecha($fecha_nacimiento); ?></td>
                <td><?php echo $telefono1; ?></td>
                <td><?php echo $rubro; ?></td>
                <td class="text-center"><?php echo $hectareas; ?></td>
                <td class="text-center"><?php echo $mecanizable; ?></td>
              </tr>

              <?php
            }
            ?>
          </tbody>
          
        </table>
        <?php
      }else{
        ?>
        <div class="alert alert-info text-left" style="margin-top: 20px;">
          <i class="fa fa-exclamation-triangle" style="font-size: 40px; float:left; margin-right: 16px; margin-top: 6px;"></i>
          <h4>AVISO!</h4>
          No existen productores registrados en este <?php echo $tipo ?>.
        </div>
        <?php
      }
  
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

      var id_consejo = "<?php echo $id_consejo ?>";

      $( "ul.sidebar-menu li.comunas" ).addClass('active');

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


    $("table.table").on('click', 'a.eliminar', function() {

      var id = $(this).parents('td').data('valor').id;
      var nombre = $(this).parents('td').data('valor').nombre;
      
        swal({
          title: "Aviso!",
          text: "¿Desea <b>eliminar</b> al vocero <b>"+nombre+"</b>?",
          type: "info",
          showCancelButton: true,
          closeOnConfirm: false
        },function(isConfirm){
          if (isConfirm){
            url = 'coord_comuna_consejo.php?id='+id+'&consejo='+id_consejo;
            $(location).attr('href', url);
          }
        });
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

    });

  </script>
</body>
</html>