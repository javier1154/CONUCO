<?php

session_start();

ob_start();

setlocale(LC_TIME, 'es_VE', 'es_VE.utf-8', 'es_VE.utf8');

date_default_timezone_set('America/Caracas');
//date_default_timezone_set('America/Havana');

if (stristr($_SERVER['PHP_SELF'],'conexion bd mysql.php')) {
	header('Location: ../index.php');	
 die();
}
/*
 * Funcion conectar_bd_mysql(): Realiza la conexión con la base de datos, si la 
 * conexión no se realiza muestra un error por pantalla. 
 *
 */


function conectar_bd_mysql(){
  
    $conexion=@mysql_connect("localhost","root","123456");
    //$conexion=@mysql_connect("localhost","root","123456");

    if(!$conexion){
       ?>
        <!DOCTYPE html>
        <html>
        <head>
          <?php include("partials/css.php"); ?>
          <style>
            a.sidebar-toggle{
              display: none;
            }
            footer.main-footer{
              margin-left: 0px;
            }
            footer{
              position: absolute;
              bottom: 0px;
              width: 100%;
            }
          </style>
        </head>
        <body class="hold-transition">
          <?php
          include("partials/header.php");
          ?>
          <br><br><br><br><br><br>
          <center>
            <table width="100%" class="text-center">
              <tr><img src="img/error.png" class="blink" style="width:200px;"></th><tr>
              <tr><th style="font-size:20px; text-transform: uppercase;"><br>No se pudo establecer la conexi&oacute;n con el servidor <br><br><br><br></th><tr>
            </table>
          </center>
          <?php
          include("partials/footer.php");
          ?>
        </body>
        </html>
    <?php
        exit();
    }

    else{

        $seleccion=@mysql_select_db("conuco",$conexion);

        if(!$seleccion){
        ?>
          <!DOCTYPE html>
          <html>
          <head>
            <?php include("partials/css.php"); ?>
            <style>
              a.sidebar-toggle{
                display: none;
              }
              footer.main-footer{
                margin-left: 0px;
              }
              footer{
                position: absolute;
                bottom: 0px;
                width: 100%;
              }
            </style>
          </head>
          <body class="hold-transition">
            <?php
            include("partials/header.php");
            ?>
            <br><br><br><br><br><br>
            <center>
              <table width="100%" class="text-center">
                <tr><img src="img/error.png" class="blink" style="width:200px;"></th><tr>
                <tr><th style="font-size:20px; text-transform: uppercase;"><br>No se pudo establecer la conexi&oacute;n con la Base de Datos del Sistema <br><br><br><br></th><tr>
              </table>
            </center>
            <?php
            include("partials/footer.php");
            ?>
          </body>
          </html>
          <?php

            exit();
            
        }

        return $conexion;
    }
  }

?>
