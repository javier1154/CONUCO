<?php
// Chequea que ningun cliente llame directamente a este archivo
// y si lo hace, lo envia directo al inicio
if (stristr($_SERVER['PHP_SELF'],'session.php')) {
	header("location:../index.php");
	die();	
}
// Inicia la session 
//session_start();
// Si no existe una session lo manda al index
if (empty($_SESSION['CENSO_activa'])) 
	die( "<html><body onload=\"javascript:parent.location='index.php'\"></body></html>" );	
?>
