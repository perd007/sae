<?php require_once('Connections/conexion.php'); ?>

<?php

//recibimos la cedula
$id=$_GET["id"];
$rif=$_GET["rif"];

//validamos si no posee ventas 
mysql_select_db($database_conexion, $conexion);
$query_compras = "SELECT * FROM compras where proveedor='$rif'";
$compras = mysql_query($query_compras, $conexion) or die(mysql_error());
$row_compras = mysql_fetch_assoc($compras);
$totalRows_compras = mysql_num_rows($compras);

if($totalRows_compras>=1){
	echo"<script type=\"text/javascript\">alert ('Este Proveedor posee Facturas de Compras no se puede Eliminar'); location.href='consultar_proveedores.php' </script>";
}
//

mysql_select_db($database_conexion, $conexion);
$sql="delete from proveedor where id_proveedor='$id'";
$verificar=mysql_query($sql,$conexion) or die(mysql_error());

if($verificar){
	echo"<script type=\"text/javascript\">alert ('Proveedor Eliminado'); location.href='consultar_proveedores.php' </script>";
}
else{
	echo"<script type=\"text/javascript\">alert ('Error'); location.href='consultar_proveedores.php' </script>";
	
}//fin de l primer else



mysql_free_result($compras);
?>