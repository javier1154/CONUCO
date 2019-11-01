<?php
// Inclusión del archivo que contiene la conexion con la base de datos.
include( "../funciones/conexion bd mysql.php" );

// Conexión a la base de datos.
conectar_bd_mysql();

$cedula = $_POST["id_usuario"];

$jsondata = array();

$cons = mysql_query("select * from usuario where cedula = '$cedula' and id_usuario in (select id_usuario from permisologia)");
$existe = mysql_num_rows($cons);

if($existe > 0){
	$jsondata = array("status" => false, "tipo" => 1);
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