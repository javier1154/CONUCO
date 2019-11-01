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

  if(isset($_POST["rubro"])){

    $clasificacion = $_POST["clasificacion"];
    $rubro = ucwords( mb_strtolower($_POST["rubro"],'UTF-8'));
    
    $existe_rubro = mysql_num_rows(mysql_query("select * from rubro where rubro = '$rubro' and id_clasificacion = '$clasificacion'"));

    if($existe_rubro == 0){
      $cons2 = mysql_query("insert into rubro values (NULL, '$clasificacion', '$rubro', 'Habilitado')");
      if($cons2){
        header("location: rubros.php?oper=reg");
      }else{
        $alert = array("error", "Ocurrió un error al intentar registrar el rubro.");
      }
    }else{
      $alert = array("error", "El rubro ya se encuentra registrado en el sistema.");
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
  <style type="text/css">
    label.control-label{
      margin-top: 6px;
    }
  </style>
</head>
<body class="hold-transition skin-red sidebar-mini fixed">
  <div class="wrapper">
    <?php include("partials/header.php"); ?>
    <?php include("partials/aside.php"); ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1><i class="fa fa-shopping-basket"></i> 
          Rubros
          <small>Registrar</small>
          <a href="rubros.php" title="Atrás" class="atras opciones"></a>
        </h1>
      </section>

      <section class="content">

        <form action="registro_rubro.php" method="POST">

          <div class="row datos">Datos del rubro</div>
          <br>
          <div class="row">

            <div class="col-md-8 col-md-offset-2">
              <div class="row">
                
                <div class="form-group col-md-12">
                  <label class="control-label col-md-3">Clasificación</label>
                  <div class="col-md-9">
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

                <div class="form-group col-md-12">
                  <label class="control-label col-md-3">Rubro</label>
                  <div class="col-md-9">
                    <input type="text" class="form-control" name="rubro" value="<?php echo $rubro; ?>" required>
                  </div>
                </div>


              </div>  
            </div>

            

          </div>
          
          <div class="row datos"><br></div>
          <br>

          <div class="row">
            <div class="form-group col-md-12">
              <center><button type="submit" class="btn btn-danger"><i class="fa fa-save"></i> Registrar</button>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-default" href="rubros.php"><i class="fa fa-close"></i> Cancelar</a></center>
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

      $( "ul.sidebar-menu li.rubros" ).addClass('active');

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
