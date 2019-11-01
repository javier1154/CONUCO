<?php
// Inclusión del archivo que contiene la conexion con la base de datos.
include( "../funciones/conexion bd mysql.php" );
include( "../funciones/session.php" );

// Conexión a la base de datos.
conectar_bd_mysql();

$id_censo = $_SESSION["CENSOid_censo"];
$id_municipio = $_GET["municipio"];

$cc = mysql_query("select * from comuna where id_comuna in (select id_comuna from censo_comuna where id_censo = '$id_censo') and id_municipio = '$id_municipio'");
$existe = mysql_num_rows($cc);

if($existe > 0){
	?>
	<option value="">Seleccione</option>
	<option value="NO">No está adscrito a comuna</option>
	<?php
    while ($fila = mysql_fetch_array($cc)) {
		$id_comuna = $fila["id_comuna"];
		$comuna = $fila["comuna"];
		?>
		<option value="<?php echo $id_comuna; ?>"><?php echo $comuna ?></option>
		<?php
    }
}else{
	?>
	<option value="" disabled selected="selected">No existen comunas registradas en el municipio.</option>
	<?php
}

