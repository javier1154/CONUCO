<?php

/*


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


$nac = "V";
$ci = "20376542";

$data = file_get_contents("http://www.cne.gov.ve/web/registro_electoral/ce.php?nacionalidad=$nac&cedula=$ci");

if ( preg_match('|<td align="left"><b><font color="#00387b">Nombre:</font></b></td>\s+<td align="left"><b>(.*?)</b></td>|is' , $data , $cap ) )
{
    $comunero = array('existe' => 'SI', 'nombres' => $cap[1]);
}else{
    $comunero = array('existe' => 'SINF');
}

print_r($comunero);

*/
