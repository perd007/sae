<?php require_once('Connections/conexion.php'); ?>

<?php

//recibimos 
$id=$_GET["id"];

mysql_select_db($database_conexion, $conexion);
$query_compra_prodcutos = "SELECT * FROM compra_productos where producto='$id'";
$compra_prodcutos = mysql_query($query_compra_prodcutos, $conexion) or die(mysql_error());
$row_compra_prodcutos = mysql_fetch_assoc($compra_prodcutos);
$totalRows_compra_prodcutos = mysql_num_rows($compra_prodcutos);

if($totalRows_compra_prodcutos>=1){
echo"<script type=\"text/javascript\">alert ('Este Producto esta Registrado en Compras no se puede Eliminar'); location.href='consultar_productos.php' </script>";
exit;
	
}

mysql_select_db($database_conexion, $conexion);
$sql="delete from productos where id_productos='$id'";
$verificar=mysql_query($sql,$conexion) or die(mysql_error());

if($verificar){
	echo"<script type=\"text/javascript\">alert ('Producto Eliminado'); location.href='consultar_productos.php' </script>";
}
else{
	echo"<script type=\"text/javascript\">alert ('Error'); location.href='consultar_productos.php' </script>";
	
}//fin de l primer else



mysql_free_result($compras);

mysql_free_result($compra_prodcutos);
?>