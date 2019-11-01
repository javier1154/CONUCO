<?php

function alert($alert){

  if(count($alert)){
    ?>
    <script>
      swal({
            <?php if($alert[0] == "error"){ ?>
              title: "ERROR!",
            <?php }elseif($alert[0] == "success"){ ?>
              title: "OPERACIÓN EXITOSA!",
            <?php } ?>
            text: "<?php echo $alert[1]; ?>",
            type: "<?php echo $alert[0]; ?>",
          });
    </script>
    <?php
  }
  
}

function detalles_usuario($id_usuario){

    $q  = mysql_query( "select * from usuario where id_usuario='$id_usuario'" );

    $qqq = mysql_fetch_array( $q );

    $nombre           = $qqq[ "nombres" ];
    $apellido         = $qqq[ "apellidos" ];
    $telefono         = $qqq[ "telefono" ];
    $id_ente   = $qqq[ "id_ente" ];

    $r2 = mysql_fetch_array(mysql_query("select * from ente where id_ente = '$id_ente'"));
    $ente = $r2["tipo"]." | ".$r2["nombre"];

    if(strlen($telefono) == 0){
        $telefono = "N/T";
    }

    $query = mysql_query("select * from usuario_institucional where id_usuario = '$id_usuario'");
    $usuario_institucional = mysql_num_rows($query);

    if($usuario_institucional > 0){
        
        $row1 = mysql_fetch_array($query);
        $indicador      = $row1["indicador"];
        $id_gerencia    = $row1["id_gerencia"];
        $id_localidad   = $row1["id_localidad"];
        $extension      = $row1[ "extension" ];

        $ger = mysql_fetch_array(mysql_query("select gerencia from gerencia where id_gerencia = '$id_gerencia'"));
        $gerencia = $ger["gerencia"];

        $loc = mysql_fetch_array(mysql_query("select localidad from localidad where id_localidad = '$id_localidad'"));
        $localidad = $loc["localidad"];

        if((strlen($extension) == 0) or ($extension == 0)){
          $extension = "N/T";
        }

        if(strlen($correo) == 0){
          $correo = "N/T";
        }

        if(strlen($indicador) == 0){
          $indicador = "N/T";
        }

    }else{
        $indicador = "N/A";
        $correo         = "N/A";
        $extension      = "N/A";
        $localidad = "N/A";
        $gerencia = "<font color='red'>EXTERNO</font>";
    }
    
    if($indicador != "N/A"){

        if (file_exists("../SCVC/img/usuarios/".$id_usuario.".jpg")) {
          $imagen = '../SCVC/img/usuarios/'.$id_usuario.'.jpg';
        }else{
          $imagen = 'img/usuario.jpg';
        }

      }else{
        if(strlen($extension) == 0){
          $extension = "SINF.";
      }
    }

    ?>
    <div class="panel panel-danger">

      <div class="panel-heading" id="DU">Detalles de Usuario</div>

      <table class="table table-condensed table-border" width="100%">

        <?php
        if( $indicador != "N/A" ){ 
          ?>
          <tr><td rowspan='5' width="20%"><p>
            <?php echo "<center><img class=\"img-circle\" src='".$imagen."' width=\"120\"><br><center>"; ?></p></td></tr>

            <tr>
                <td width="90px"><strong>C&eacute;dula</strong></td><td><?php echo $id_usuario; ?></td>
                <td width="96px"><strong>Gerencia</strong></td><td><?php echo $gerencia; ?></td>
            </tr>
            <tr>
                <td><strong>Indicador</strong></td><td><?php echo $indicador; ?></td>
                <td><strong>Localidad</strong></td><td><?php echo $localidad; ?></td>
            </tr>
            <tr>
                <td><strong>Tel&eacute;fono</strong></td><td><?php echo $telefono; ?></td>
                <td><strong>Extensi&oacute;n</strong></td><td><?php echo $extension; ?></td>
            </tr>
            <tr>
                <td><strong>Ente</strong></td><td colspan="3"><?php echo $ente; ?></td>
            </tr>

            <?php
        }else{
            ?>
            <tr>
                <td width="90px"><strong>C&eacute;dula</strong></td><td><?php echo $id_usuario; ?></td>
                <td width="96px"><strong>Tel&eacute;fono</strong></td><td><?php echo $telefono; ?></td>
            </tr>
            <tr>
                <td><strong>Ente</strong></td><td colspan="3"><?php echo $ente; ?></td>
            </tr>

            <?php
        }
        ?>
            
    
        </table>             
    </div>

    <?php
}

// función para traducion las fechas de formato yyyy-mm-dd a dd-mm-yyy
function traducefecha($fecha){

    $str = explode('-',$fecha);
    $str = "{$str[2]}-{$str[1]}-{$str[0]}";

    return $str;

}

function extraedia($fecha){

    $str = explode('-',$fecha);
    return $str[2];

}

function traducefechahorac($fecha){

    $str = explode(' ',$fecha);
    $fecha = "{$str[0]}";
    $hora = "{$str[1]}";

    $str1 = explode('-',$hora);
    $hora = "{$str1[0]}";
    
    $str2 = explode('-',$fecha);
    $str2 = "{$str2[2]}-{$str2[1]}-{$str2[0]}";
    
    $str3 = $str2." ".$hora;
    
    return $str3;

}

function obtenerfechac($fecha){

    $str = explode(' ',$fecha);
    $fecha = "{$str[0]}";
    
    return $fecha;

}

//funcion para obtener el año de una fecha
function obteneranio($fecha){
    $str = explode('-',$fecha);
    $anio = "{$str[0]}";
    
    return $anio;
}

// función para obtener la hora de un datetime
function obtenerhora($fecha){

    $str = explode(' ',$fecha);
    $hora = "{$str[1]}";

    $str1 = explode('-',$hora);
    $hora1 = "{$str1[0]}";
        
    return $hora1;

}

// función para traducir las fechas de formato yyyy-mm-dd hh:mm:ss a mm/dd/yyyy hh:mm:ss
function traducehoracntdr($hora){

    $str3 = date('m/d/Y')." ".$hora;
    
    return $str3;

}

//funcion para obtener el mes de una fecha
function mes($mes){
    
    if ( $mes == 01 ){
        $mes = "Enero";
    }elseif( $mes == 02 ){
        $mes = "Febrero";
    }elseif( $mes == 03 ){
        $mes = "Marzo";
    }elseif( $mes == 04 ){
        $mes = "Abril";
    }elseif( $mes == 05 ){
        $mes = "Mayo";
    }elseif( $mes == 06 ){
        $mes = "Junio";
    }elseif( $mes == 07 ){
        $mes = "Julio";
    }elseif( $mes == 8 ){
        $mes = "Agosto";
    }elseif( $mes == 9 ){
        $mes = "Septiembre";
    }elseif( $mes == 10 ){
        $mes = "Octubre";
    }elseif( $mes == 11 ){
        $mes = "Noviembre";
    }elseif( $mes == 12 ){
        $mes = "Diciembre";
    }

    return $mes;
}

//funcion para obtener el mes de una fecha
function obtenermesn($fecha){
    $str = explode('-',$fecha);
    $mes = "{$str[1]}";

    return $mes;
}

function popUsuario($id, $usuario){

    ?>
    <a href="javascript:ventanaEmergente('detalle_usuario.php?id_usuario=<?php echo $id; ?>','768','348','50%','100%')" title='Detalles del usuario'><b><?php echo $usuario; ?></b></a>
    <?php
}
