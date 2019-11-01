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

  $id_consejo = $_GET["consejo"];

  $cons = mysql_query("select * from consejo where id_consejo = '$id_consejo' and id_consejo in (select id_consejo from censo_consejo where id_censo = '$id_censo')");

  $existe = mysql_num_rows($cons);

  if($existe > 0){

    $row = mysql_fetch_array($cons);

    $id_consejo   = $row[ "id_consejo" ];
    $id_tipo      = $row[ "id_tipo" ];
    $codigo       = $row[ "codigo" ];
    $rif          = $row[ "rif" ];
    $consejo      = $row[ "consejo" ];
    $comunidad    = $row[ "comunidad" ];
    $direccion    = $row[ "direccion" ];
    $id_parroquia = $row[ "id_parroquia" ];

    if(strlen($rif) == 0){
      $rif = "SINF.";
    }
    if(strlen($codigo) == 0){
      $codigo = "SINF.";
    }

    $rc = mysql_fetch_array(mysql_query("select * from censo_consejo where id_consejo = '$id_consejo' and id_censo = '$id_censo'"));
    $id_censo_consejo  = $rc[ "id_censo_consejo" ];

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
      $rubro = $_POST["rubro"];
      $hectareas = str_replace(",",".",$_POST["hectareas"]);
      $hectareas_plan = str_replace(",",".",$_POST["hectareas_plan"]);
      $mecanizable = $_POST["mecanizable"];
      $semillas = $_POST["semillas"];
      $tipo_semilla = $_POST["tipo_semilla"];
      $cantidad = $_POST["cantidad"];

      $encargado_registro = $_SESSION["CENSOid_usuario"];
      $fecha_registro = date("Y-m-d H:i:s");

      $cons2 = mysql_query("select * from usuario where cedula = '$cedula'");
      $existe_usuario = mysql_num_rows($cons2);

      if($existe_usuario > 0){

        $rowu = mysql_fetch_array($cons2);
        $aid_usuario = $rowu["id_usuario"];
        $ausuario = $rowu["nombres"];

        $cons4 = mysql_query("update usuario set sexo = '$sexo', fecha_nacimiento = '$fecha_nacimiento', telefono1 = '$telefono1', telefono2 = '$telefono2', correo = '$correo', ccp = '$ccp', scp = '$scp' where id_usuario = '$aid_usuario'");

        if($cons4){

          $cons3 = mysql_query("select * from censo_productor where id_censo_consejo = '$id_censo_consejo' and id_usuario in (select id_usuario from usuario where cedula = '$cedula') and id_censo_consejo in (select id_censo_consejo from censo_consejo where id_consejo in (select id_consejo from consejo where id_tipo = '$id_tipo') and id_censo = '$id_censo')");
          $existe_productor = mysql_num_rows($cons3);

          if($existe_productor == 0){

            $cons5 = mysql_query("insert into censo_productor values (NULl, '$aid_usuario', '$id_censo_consejo', '$rubro', '$hectareas', '$hectareas_plan', '$mecanizable', '$semillas', '$tipo_semilla', '$cantidad', '$encargado_registro', '$fecha_registro')");

            if($cons5){
              $alert = array("success", "El productor <b>".$ausuario."</b>  ha sido registrado satisfactoriamente.");
              unset($_POST);
            }else{
              $alert = array("error", "No se pudo registrar al productor <b>".$ausuario."</b>.");
            }

          }else{
            $rcp = mysql_fetch_array($cons3);
            $aid_censo_consejo = $rcp["id_censo_consejo"];

            $rcc = mysql_fetch_array(mysql_query("select * from consejo where id_consejo in (select id_consejo from censo_consejo where id_censo_consejo = '$aid_censo_consejo')"));
            $aconsejo = $rcc["consejo"];

            $alert = array("error", "El productor <b>".$ausuario."</b> ya se encuentra regitrado en el ".$tipo." <b>".$aconsejo."</b>.");
          }
        }else{
          $alert = array("error", "No se pudo actualizar los datos del usuario <b>".$ausuario."</b>.");
        }

      }else{
        $cru = mysql_query("insert into usuario values (NULL, '$cedula', '$nombres', '$sexo', '$fecha_nacimiento', '$telefono1', '$telefono2', '$ccp', '$scp', '$correo', '$fecha_registro')");

        if($cru){

          $aid_usuario = mysql_insert_id();

          $cons5 = mysql_query("insert into censo_productor values (NULl, '$aid_usuario', '$id_censo_consejo', '$rubro', '$hectareas', '$hectareas_plan', '$mecanizable', '$semillas', '$tipo_semilla', '$cantidad', '$encargado_registro', '$fecha_registro')");

          if($cons5){
            $alert = array("success", "El productor <b>".$nombres."</b>  ha sido registrado satisfactoriamente.");
            unset($_POST);
          }else{
            $alert = array("error", "No se pudo registrar al productor <b>".$nombres."</b>.");
          }

        }else{
          $alert = array("error", "No se pudo registrar al usuario.");
        }
      }
    }


    if(isset($_GET["id"])){

      $id_productor = $_GET["id"];

      $existe = mysql_num_rows(mysql_query("select * from censo_productor where id_productor = '$id_productor' and id_censo_consejo = '$id_censo_consejo'"));

      if($existe > 0){

        $cep = mysql_query("delete from censo_productor where id_productor = '$id_productor'");

        if($cep){
          $alert = array("success", "El productor ha sido eliminado satisfactoriamente.");
        }else{
          $alert = array("error", "No se pudo eliminar el productor.");
        }
      }
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
            $alert = array("success", "No se pudo vincular la comuna.");
          }

        }
      }
    }














    if ( isset( $_POST[ "clave1" ] ) ) {

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
          $alert = array("error", "No se pudo eliminar el coordinador.");
        }
      }
    }

    if(isset($_GET["idp"])){

    $id_productor = $_GET["idp"];

    $existe_id = mysql_num_rows(mysql_query("select * from censo_productor where id_productor = '$id_productor' and id_censo_consejo = '$id_censo_consejo'"));

    if($existe_id > 0){

      $cep = mysql_query("delete from censo_productor where id_productor = '$id_productor'");

      if($cep){
        $alert = array("success", "El productor ha sido eliminado satisfactoriamente.");
      }else{
        $alert = array("error", "No se pudo eliminar el productor.");
      }
    }
  }


    $productores = mysql_num_rows(mysql_query("select * from censo_productor where id_censo_consejo = '$id_censo_consejo'"));

  }else{
    header( "location:inicio.php" );
  }
}else{
  header( "location:inicio.php" );
}

?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="plugins/datepicker/datepicker3.min.css">
  <link rel="stylesheet" href="plugins/select2/select2.min.css">
  <?php include("partials/css.php"); ?>
  <style type="text/css">
    table thead tr th{
      vertical-align: middle !important;
    }
  </style>
</head>
<body class="hold-transition skin-red sidebar-mini fixed">
  <div class="wrapper">
    <?php include("partials/header.php"); ?>
    <?php include("partials/aside.php"); ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1><i class="fa fa-cube"></i> 
          Consejo
          <small>Gestión</small>
          <a href="consejos.php" title="Atrás" class="atras opciones"></a>
        </h1>
      </section>

      <section class="content">

        <div class="row">
          <div class="col-md-8">
            <div class="panel panel-danger">
              <div class="panel-heading" style="text-transform: uppercase;">
                <span style="font-weight: normal"><i class="fa fa-cube"></i> <?php echo $tipo ?></span> <?php echo $consejo ?>
              </div>
              <div class="panel-body" style="padding: 4px; border-spacing: 1px">

                <div class="row">
                  
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="row">
                          <div class="col-md-6 text-right"><b> Código</b></div>
                          <div class="col-md-6"><?php echo $codigo ?></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="row">
                          <div class="col-md-6 text-right"><b> RIF</b></div>
                          <div class="col-md-6"><?php echo $rif ?></div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="row">
                          <div class="col-md-6 text-right"><b> Ubicación</b></div>
                          <div class="col-md-6"><?php echo $estado." | ".$municipio." | ".$parroquia ?></div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="row">
                          <div class="col-md-6 text-right"><b> Comunidad</b></div>
                          <div class="col-md-6"><?php echo $comunidad ?></div>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-3 text-right"><b> Dirección</b></div>
                          <div class="col-md-9"><?php echo $direccion ?></div>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-3 text-right"><b> Comuna</b></div>
                          <div class="col-md-9">
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
                              <span style="color: red">El consejo no se encuentra vinculado a una comuna</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary btn-xs" href="#Mvincular" role="button" data-toggle="modal"><i class="fa fa-plus"></i> Vincular</button>



                              <!-- ------------------------ REGISTRAR  ------------------------ -->

                              <div class="modal fade" role="dialog" aria-labelledby="Modal" id="Mvincular" style="display: none;">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title" id="ModalLabel" style="font-weight: bold;"><i class="fa fa-cogs"></i> Vincular a comuna</h4>
                                    </div>

                                    <form action="gestion_consejo.php?consejo=<?php echo $id_consejo ?>" method="POST">
                                      <input type="hidden" name="cid_permisologia">
                                      <div class="modal-body">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <label class="col-sm-12 text-left">Comuna</label>
                                            <div class="col-sm-12">

                                              <select class="form-control" name="comuna" required>
                                                <option value="">Seleccione</option>
                                                <?php  
                                                $cc = mysql_query("select * from comuna where id_comuna in (select id_comuna from censo_comuna where id_censo = '$id_censo') and id_parroquia in (select id_parroquia from municipio where id_municipio = '$id_municipio')");
                                                $ecc = mysql_num_rows($cc);
                                                if($ecc > 0){
                                                  $recc = mysql_fetch_array($cc);
                                                  $id_comuna = $recc["id_comuna"];
                                                  $comuna = $recc["comuna"];
                                                  ?>
                                                  <option value="<?php echo $id_comuna; ?>"><?php echo $comuna ?></option>
                                                  <?php
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

                          </div>
                        </div>
                      </div>

                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="row">
                      <div class="col-md-12 text-center"><b>Productores</b></div>
                      <div class="col-md-12 text-center" style="font-size: 60px; padding: 0px; margin-bottom: -12px; font-weight: bold;"><?php echo $productores ?></div>
                    </div>
                  </div>

                </div>
                
              </div>
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
                <div class="alert" style="margin:0px">No existen voceros asignados a este consejo.</div>
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

                  <form action="gestion_consejo.php?consejo=<?php echo $id_consejo ?>" method="POST">
                    
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


        
        <legend style="margin-bottom: 4px;"><i class="fa fa-users"></i> <b>Productores</b>
          <a style="float:right; margin-top: -4px;" href="#Mproductor" class="btn btn-danger" role="button" data-toggle="modal"><i class="fa fa-plus"></i> Agregar</a>
        </legend>

        <?php  
        $cons2 = mysql_query("select * from censo_productor where id_censo_consejo = '$id_censo_consejo' order by fecha_registro");
        $productores = mysql_num_rows($cons2);

        if($productores > 0){
          ?>
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th>N°</th>
                <th class="col-md-1">Cédula</th>
                <th class="col-md-3">Nombres</th>
                <th class="col-md-1">Sexo</th>
                <th class="col-md-1">Fecha de Nacimiento</th>
                <th class="col-md-1">Teléfono</th>
                <th class="col-md-2">Rubro</th>
                <th class="col-md-1">Hectáreas Disponibles</th>
                <th class="col-md-1">¿Mecanizable?</th>
                <th class="col-md-1">Opciones</th>
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
                  <td class="t-opciones" data-valor='{"id":"<?php echo $id_productor; ?>", "nombre":"<?php echo $nombres; ?>"}'>
                    <center>
                      <a  class="eliminar2" href="#!"><i class="fa fa-trash" title="Eliminar Productor"></i></a>
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
            No existen productores registrados en este <?php echo $tipo ?>.
          </div>
          <?php
        }
        ?>


        <!-- ------------------------------------------------ REGISTRAR  ------------------------------ -->

        <div class="modal fade" role="dialog" aria-labelledby="Modal" id="Mproductor" style="display: none;">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="ModalLabel" style="font-weight: bold;"><i class="fa fa-plus"></i> Registrar Productor</h4>
              </div>

              <form action="gestion_consejo.php?consejo=<?php echo $id_consejo ?>" method="POST">
                
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
                          </select><br/><br/>
                        </div>
                      </div>

                      <div class="col-md-8">
                        <label class="col-sm-12 text-left">Rubro</label>
                        <div class="col-sm-12">
                          <select class="form-control" name="rubro" required>
                            <option value="">Seleccione</option>
                          </select><br/><br/>
                        </div>
                      </div>

                    </div>










                    <div class="form-group">
                      
                      <div class="col-md-6">
                        <label class="col-sm-12 text-left">Hectáreas disponibles para la siembra</label>
                        <div class="col-sm-12">
                          <input type="number" name="hectareas" class="form-control" value="<?php echo $_POST["hectareas"] ?>" required min="0,1"><br/>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <label class="col-sm-12 text-left">Hectáreas que voy a sembrar para este plan</label>
                        <div class="col-sm-12">
                          <input type="number" name="hectareas_plan" class="form-control" value="<?php echo $_POST["hectareas"] ?>" required min="0,1" max="3"><br/>
                        </div>
                      </div>

                    </div>



                    <div class="form-group">

                      <div class="col-md-2">
                        <label class="col-sm-12 text-left">¿Mecanizable?</label>
                        <div class="col-sm-12">
                          <select class="form-control" name="mecanizable" required>
                            <option value="">Seleccione</option>
                            <option <?php if($_POST["mecanizable"] == "SI") echo "selected = 'selected'" ?> value="SI">SI</option>
                            <option <?php if($_POST["mecanizable"] == "NO") echo "selected = 'selected'" ?> value="NO">NO</option>
                          </select><br/>
                        </div>
                      </div>

                      <div class="col-md-3">
                        <label class="col-sm-12 text-left">¿Posee Semillas?</label>
                        <div class="col-sm-12">
                          <select class="form-control" name="semillas" required>
                            <option value="">Seleccione</option>
                            <option <?php if($_POST["semillas"] == "SI") echo "selected = 'selected'" ?> value="SI">SI</option>
                            <option <?php if($_POST["semillas"] == "NO") echo "selected = 'selected'" ?> value="NO">NO</option>
                          </select><br/>
                        </div>
                      </div>

                      <div class="col-md-5">
                        <label class="col-sm-12 text-left">Tipo de Semilla</label>
                        <div class="col-sm-12">
                          <input type="text" name="tipo_semilla" class="form-control" value="<?php echo $_POST["tipo_semilla"] ?>" required <?php if(!isset($_POST["tipo_semilla"])) echo "readonly='readonly required='false''" ?>><br/>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <label class="col-sm-12 text-left">Cantidad(Kg)</label>
                        <div class="col-sm-12">
                          <input type="number" name="cantidad" class="form-control" value="<?php echo $_POST["cantidad"] ?>" required min="0" <?php if(!isset($_POST["cantidad"])) echo "readonly='readonly' required='false'" ?>><br/>
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
  <?php include("partials/footer.php"); ?> 
  <?php include("partials/js.php"); ?>
  <script src="plugins/select2/select2.full.min.js"></script>
  <script src="plugins/select2/i18n/es.js"></script>
  <script type="text/javascript" charset="utf-8" src="js/jquery.validate.min.js" /></script>
  <script src="plugins/input-mask/inputmask.js" /></script>
  <script src="plugins/input-mask/jquery.inputmask.js" /></script>
  <script src="plugins/datepicker/bootstrap-datepicker.min.js"></script>
  <script src="plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
  <script>
    $(document).ready(function() {

      var id_consejo = "<?php echo $id_consejo ?>";

      $( "ul.sidebar-menu li.consejos" ).addClass('active');

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



    $("table.table").on('click', 'a.eliminar', function() {

      var id = $(this).parents('td').data('valor').id;
      var nombre = $(this).parents('td').data('valor').nombre;
      
        swal({
          title: "Aviso!",
          text: "¿Desea <b>eliminar</b> al coordinador <b>"+nombre+"</b>?",
          type: "info",
          showCancelButton: true,
          closeOnConfirm: false
        },function(isConfirm){
          if (isConfirm){
            url = 'gestion_consejo.php?id='+id+'&consejo='+id_consejo;
            $(location).attr('href', url);
          }
        });

    });

    $("table.table").on('click', 'a.eliminar2', function() {

      var id = $(this).parents('td').data('valor').id;
      var nombre = $(this).parents('td').data('valor').nombre;
      
        swal({
          title: "Aviso!",
          text: "¿Desea <b>eliminar</b> al productor <b>"+nombre+"</b>?",
          type: "info",
          showCancelButton: true,
          closeOnConfirm: false
        },function(isConfirm){
          if (isConfirm){
            url = 'gestion_consejo.php?idp='+id+'&consejo='+id_consejo;
            $(location).attr('href', url);
          }
        });

    });

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

    $("select[name='semillas']").change(function(){

      var semillas = $(this).val();
      if(semillas == "SI"){
        $("input[name='cantidad'], input[name='tipo_semilla']").removeAttr('readonly');
        $("input[name='cantidad'], input[name='tipo_semilla']").removeAttr('required');
      }else{
        $("input[name='cantidad'], input[name='tipo_semilla']").attr('readonly', 'readonly');
        $("input[name='cantidad'], input[name='tipo_semilla']").attr('required', 'required');
      }


    });



});
</script>
</body>
</html>
