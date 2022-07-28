<?php require_once('Connections/conexion.php'); ?>
<?php

 //session_start();



 $cedula= $_REQUEST['cedula'];


mysql_select_db($database_conexion, $conexion);
$query_clientes = "SELECT * FROM clientes where cedula='$cedula'";
$clientes = mysql_query($query_clientes, $conexion) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);


mysql_select_db($database_conexion, $conexion);
$query_ecos = "SELECT * FROM ecos where cliente='$cedula'";
$ecos = mysql_query($query_ecos, $conexion) or die(mysql_error());
$row_ecos = mysql_fetch_assoc($ecos);
$totalRows_ecos = mysql_num_rows($ecos);

	 //envia los datos al ajax/

    echo $row_clientes["cedula"]."-".$row_clientes["nombres"]."-".$row_clientes["telefono"]."-".$row_clientes["direccion"]."-".$row_clientes["tipo"]."-".$row_ecos["tipo_eco"]."-".$row_ecos["diagnostico"]."-".$row_ecos["moneda"]."-".$row_ecos["monto"]."-".$row_clientes["fecha_nac"]."-".$row_ecos["fecha"];
	 



 
mysql_free_result($clientes);
mysql_free_result($ecos);
?>
