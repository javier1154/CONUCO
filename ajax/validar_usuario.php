<?php
// Inclusión del archivo que contiene la conexion con la base de datos.
include( "../funciones/conexion bd mysql.php" );
include( "../funciones/session.php" );


// Conexión a la base de datos.
conectar_bd_mysql();

$cedula = $_POST["id_usuario"];
$actividad = $_SESSION["CENSOid_censo"];

$jsondata = array();

$cons = mysql_query("select * from usuario where cedula = '$cedula'");
$existe = mysql_num_rows($cons);

if($existe > 0){

	$row = mysql_fetch_array($cons);

	$id_usuario = $row["id_usuario"];
	$cedula = $row["cedula"];
	$nombres = $row["nombres"];
	$sexo = $row["sexo"];
	$fecha_nacimiento = $row["fecha_nacimiento"];
	$telefono1 = $row["telefono1"];
	$telefono2 = $row["telefono2"];
	$ccp = $row["ccp"];
	$scp = $row["scp"];
	$correo = $row["correo"];

	$jsondata = array("status" => true, "id_usuario" => $id_usuario, "cedula" => $cedula, "nombres" => utf8_encode($nombres), "sexo" => $sexo, "fecha_nacimiento" => $fecha_nacimiento, "telefono1" => $telefono1, "telefono2" => $telefono2, "ccp" => $ccp, "scp" => $scp, "correo" => $correo, "tipo" => 1);
}else{
	// Edit the four values below
	$PROXY_HOST = "10.172.31.3"; // Proxy server address
	$PROXY_PORT = "8000";    // Proxy server port
	$PROXY_USER = "velasquezjtp";    // Username
	$PROXY_PASS = "2037654211";   // Password
	// Username and Password are required only if your proxy server needs basic authentication

	$auth = base64_encode("$PROXY_USER:$PROXY_PASS");
	stream_context_set_default(
		array(
			'http' => array(
				'proxy' => "tcp://$PROXY_HOST:$PROXY_PORT",
				'request_fulluri' => true,
				'header' => "Proxy-Authorization: Basic $auth"
	   // Remove the 'header' option if proxy authentication is not required
			)
		)
	);

	$pc = explode("-", $cedula);

	$nac 	= $pc[0];
	$ci 	= $pc[1];

	$data = file_get_contents("http://www.cne.gob.ve/web/registro_civil/buscar_rep.php?ced=$ci&nac=$nac");

	if ( preg_match('|<td><b>(.*?)</b></td>|is' , $data , $cap ) )
                {
		$jsondata = array("status" => true, "nombres" => $cap[1], "cedula" => $cedula, "tipo" => 2);
	}else{
		$jsondata = array("status" => false);
	}

}

header('Content-type: application/json; charset=utf-8');
echo json_encode((object)$jsondata);