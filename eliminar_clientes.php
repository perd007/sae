<?php require_once('Connections/conexion.php'); ?>

<?php

$id=$_GET["id"];


//validamos si posee pedidos o compras
mysql_select_db($database_conexion, $conexion);
$query_pedidos = "SELECT * FROM pedidos where id_cliente='$id'";
$pedidos = mysql_query($query_pedidos, $conexion) or die(mysql_error());
$row_pedidos = mysql_fetch_assoc($pedidos);
$totalRows_pedidos = mysql_num_rows($pedidos);

if($totalRows_pedidos>=1){
	echo"<script type=\"text/javascript\">alert ('Este Cliente posee Pedidos no se puede Eliminar'); location.href='consultar_clientes.php' </script>";
	exit;
}

mysql_select_db($database_conexion, $conexion);
$query_facturas = "SELECT * FROM facturas where id_cliente='$id'";
$facturas = mysql_query($query_facturas, $conexion) or die(mysql_error());
$row_facturas = mysql_fetch_assoc($facturas);
$totalRows_facturas = mysql_num_rows($facturas);

if($totalRows_facturas>=1){
	echo"<script type=\"text/javascript\">alert ('Este Cliente posee Facturas no se puede Eliminar'); location.href='consultar_clientes.php' </script>";
	exit;
}
//

mysql_select_db($database_conexion, $conexion);
$sql="delete from clientes where cedula='$id'";
$verificar=mysql_query($sql,$conexion) or die(mysql_error());

if($verificar){
	echo"<script type=\"text/javascript\">alert ('Cliente Eliminado'); location.href='consultar_clientes.php' </script>";
}
else{
	echo"<script type=\"text/javascript\">alert ('Error'); location.href='consultar_clientes.php' </script>";
	
}//fin de l primer else



mysql_free_result($pedidos);

mysql_free_result($facturas);
?>