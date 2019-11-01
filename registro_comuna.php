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

  if(isset($_POST["codigo"])){

    $codigo = $_POST["codigo"];
    $nombre = $_POST["nombre"];
    $rif = $_POST["rif"];
    $municipio = $_POST["municipio"];
    $parroquia = $_POST["parroquia"];
    $direccion = $_POST["direccion"];
    $comuna = $_POST["comuna"];

    if(strlen($codigo) > 0){
      $existe_codigo = mysql_num_rows(mysql_query("select * from comuna where codigo = '$codigo'"));
    }else{
      $existe_codigo == 0;
    }

    if($existe_codigo == 0){

      if(strlen($rif) > 0){
        $existe_rif = mysql_num_rows(mysql_query("select * from comuna where rif = '$rif'"));
      }else{
        $existe_rif == 0;
      }

      if($existe_rif == 0){

        $encargado = $_SESSION["CENSOid_usuario"];
        $hoy = date("Y-m-d H:i:s");

        $cons2 = mysql_query("insert into comuna values (NULL, '$codigo', '$rif', '$nombre', '$municipio', '$parroquia', '$direccion', 'Habilitado', '$encargado', '$hoy')");

        if($cons2){

          $id_comuna = mysql_insert_id();
          $id_censo = $_SESSION["CENSOid_censo"];

          $cons3 = mysql_query("insert into censo_comuna values (NULL, '$id_censo', '$id_comuna')");

          if($cons3){
            header("location: comunas.php?oper=reg");
          }else{
            mysql_query("delete from comuna where id_comuna = '$id_comuna'");
            $alert = array("error", "Ocurrió un error al intentar registrar la comuna.");
          }

        }else{
          $alert = array("error", "Ocurrió un error al intentar registrar la comuna.");
        }
      }else{
        $alert = array("error", "El RIF que ingresó ya se encuentra registrado en el sistema.");
      }
    }else{
      $alert = array("error", "El Código que ingresó ya se encuentra registrado en el sistema.");
    }
  }

}else{
  header( "location:inicio.php" );
}

?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="plugins/select2/select2.min.css">
  <?php include("partials/css.php"); ?>
</head>
<body class="hold-transition skin-red sidebar-mini fixed">
  <div class="wrapper">
    <?php include("partials/header.php"); ?>
    <?php include("partials/aside.php"); ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1><i class="fa fa-cube"></i> 
          Comunas
          <small>Registrar</small>
          <a href="comunas.php" title="Atrás" class="atras opciones"></a>
        </h1>
      </section>

      <section class="content">

        <form action="registro_comuna.php" method="POST">

          
          <div class="row datos">Datos de ubicación</div>
          <br>
          <div class="row">

            <div class="form-group col-md-6">
              <label class="control-label col-md-3">Estado</label>
              <div class="col-md-9">
                <select name="estado" class="form-control" required>
                  <option value="">Seleccione</option>
                  <?php  
                  $ce = mysql_query("select * from estado order by estado");
                  $ee = mysql_num_rows($ce);
                  if($ee > 0){
                    while($re = mysql_fetch_array($ce)){
                      $id_estado = $re["id_estado"];
                      $estado = $re["estado"];
                      ?>
                      <option value="<?php echo $id_estado ?>"><?php echo $estado ?></option>
                      <?php
                    }
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="form-group col-md-6">
              <label class="control-label col-md-3">Municipio</label>
              <div class="col-md-9">
                <select name="municipio" class="form-control" required>
                  <option value="">Seleccione</option>
                </select>
              </div>
            </div>

            <div class="form-group col-md-6">
              <label class="control-label col-md-3">Parroquia</label>
              <div class="col-md-9">
                <select name="parroquia" class="form-control" required>
                  <option value="">Seleccione</option>
                </select>
              </div>
            </div>

            <div class="form-group col-md-12">
              <label class="control-label col-md-12">Dirección</label>
              <div class="col-md-12">
                <textarea class="form-control" name="direccion"><?php echo $direccion; ?></textarea>
              </div>
            </div>

          </div>

          <div class="row datos">Datos de la comuna</div>
          <br>
          <div class="row">

            <div class="form-group col-md-6">
              <label class="control-label col-md-3">Código SITUR</label>
              <div class="col-md-9">
                <input type="text" class="form-control" name="codigo" value="<?php echo $codigo; ?>">
              </div>
            </div>

            <div class="form-group col-md-6">
              <label class="control-label col-md-3">RIF</label>
              <div class="col-md-9">
                <input type="text" class="form-control" name="rif" value="<?php echo $rif; ?>">
              </div>
            </div>

            <div class="form-group col-md-6">
              <label class="control-label col-md-3">Nombre</label>
              <div class="col-md-9">
                <input type="text" class="form-control" name="nombre" value="<?php echo $nombre; ?>" required>
              </div>
            </div>

          </div>
          
          <div class="row datos"><br></div>
          <br>

          <div class="row">
            <div class="form-group col-md-12">
              <center><button type="submit" class="btn btn-danger"><i class="fa fa-save"></i> Registrar</button>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-default" href="comunas.php"><i class="fa fa-close"></i> Cancelar</a></center>
            </div>
          </div>

        </form>

      </section>
    </div>
  </div>
  <?php include("partials/footer.php"); ?> 
  <?php include("partials/js.php"); ?>
  <script src="js/jquery.validate.min.js" /></script>
  <script src="plugins/input-mask/inputmask.js" /></script>
  <script src="plugins/input-mask/jquery.inputmask.js" /></script>
  <script src="plugins/select2/select2.full.min.js"></script>

  <script>
    $(document).ready(function() {

      $( "ul.sidebar-menu li.comunas" ).addClass('active');

      $("input[name='rif']").inputmask("A-9{4,9}-9", {
        definitions: {
          "A": {
            cardinality: 1,
            casing: "upper"
          }
        }
      });

      $("select.form-control").css('width', '100%');
      $.fn.select2.defaults.set('language', 'es');
      $("select.form-control").select2();

      $("select[name='estado']").change(function(){

        var id_esta = $(this).val();

        if(id_esta.length){
          $("select[name='parroquia'], select[name='municipio']").empty();
          $("select.form-control").select2();
          $("select[name='municipio']").load('ajax/municipios_select.php?estado='+id_esta);
          $("select.form-control").select2();
        }else{
          $("select[name='parroquia'], select[name='municipio']").empty();
          $("iframe").empty();
        }

      });

      $("select[name='municipio']").change(function(){

        var id_muni = $(this).val();

        if(id_muni.length){
          $("select[name='parroquia']").empty();
          $("select.form-control").select2();
          $("select[name='parroquia']").load('ajax/parroquias_select.php?municipio='+id_muni);
          $("select.form-control").select2();
        }else{
          $("select[name='parroquia']").empty();
          $("iframe").empty();
        }

      });

      $("form").validate({

       errorElement: "em",
       errorPlacement: function ( error, element ) {
        error.addClass( "help-block" );

        if ( element.prop( "type" ) === "checkbox" ) {
          error.insertAfter( element.parent( "label" ) );
        } else {

          if(element[0].tagName === "SELECT"){
            error.insertAfter( element.parent().find("span.select2") );
          }else{
            error.insertAfter( element );
          }

        }
      },
      highlight: function ( element, errorClass, validClass ) {
        $( element ).parents( ".form-group" ).addClass( "has-error" ).removeClass( "has-success" );

        if(element == "[object HTMLSelectElement]"){
          var ele = $( element ).parent().find("span.select2-selection");
          ele.addClass( "has-error" ).removeClass( "has-success" );
        }

      },
      unhighlight: function (element, errorClass, validClass) {
        $( element ).parents( ".form-group" ).addClass( "has-success" ).removeClass( "has-error" );

        if(element == "[object HTMLSelectElement]"){
          var ele = $( element ).parent().find("span.select2-selection");
          ele.addClass( "has-success" ).removeClass( "has-error" );

        }
      }
    });

});
</script>
</body>
</html>
