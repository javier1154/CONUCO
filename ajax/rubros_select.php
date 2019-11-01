<?php
// Inclusión del archivo que contiene la conexion con la base de datos.
include( "../funciones/conexion bd mysql.php" );

// Conexión a la base de datos.
conectar_bd_mysql();

$clasificacion = $_GET["clasificacion"];

$cr = mysql_query("select * from rubro where status = 'Habilitado' and id_clasificacion = '$clasificacion' order by rubro");
$existe = mysql_num_rows($cr);

if($existe > 0){
	?>
	<option value="">Seleccione</option>
	<?php
    while ($fila = mysql_fetch_array($cr)) {

    	$id_rubro = $fila["id_rubro"];
    	$rubro = $fila["rubro"];
    	?>
		<option value = "<?php echo $id_rubro; ?>"><?php echo $rubro; ?></option>
		<?php
    }
}else{
	?>
	<option value="" disabled selected="selected">No existen rubros para esta clasificación.</option>
	<?php
}

