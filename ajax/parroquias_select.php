<?php
// Inclusión del archivo que contiene la conexion con la base de datos.
include( "../funciones/conexion bd mysql.php" );

// Conexión a la base de datos.
conectar_bd_mysql();

$municipio = $_GET["municipio"];

$cons = mysql_query("select * from parroquia where id_municipio = '$municipio' order by parroquia");
$existe = mysql_num_rows($cons);

if($existe > 0){
	?>
	<option value="">Seleccione</option>
	<?php
    while ($fila = mysql_fetch_array($cons)) {

    	$id_parroquia = $fila["id_parroquia"];
    	$parroquia = $fila["parroquia"];
    	?>
		<option value = "<?php echo $id_parroquia; ?>"><?php echo $parroquia; ?></option>
		<?php
    }
}else{
	?>
	<option value="" disabled selected="selected">No existen parroquias registrados en este municipio.</option>
	<?php
}

