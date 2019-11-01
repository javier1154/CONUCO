<?php
// Inclusión del archivo que contiene la conexion con la base de datos.
include( "../funciones/conexion bd mysql.php" );

// Conexión a la base de datos.
conectar_bd_mysql();

$estado = $_GET["estado"];

$cons = mysql_query("select * from municipio where id_estado = '$estado' order by municipio");
$existe = mysql_num_rows($cons);

if($existe > 0){
	?>
	<option value="">Seleccione</option>
	<?php
    while ($fila = mysql_fetch_array($cons)) {

    	$id_municipio = $fila["id_municipio"];
    	$municipio = $fila["municipio"];
    	?>
		<option value = "<?php echo $id_municipio; ?>"><?php echo $municipio; ?></option>
		<?php
    }
}else{
	?>
	<option value="" disabled selected="selected">No existen municipios registrados en este estado.</option>
	<?php
}

