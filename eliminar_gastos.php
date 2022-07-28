<?php require_once('Connections/conexion.php'); ?>

<?php



$id=$_GET["id"];


mysql_select_db($database_conexion, $conexion);
$query_gastos = "SELECT * FROM gastos where id_gastos='$id'";
$gastos = mysql_query($query_gastos, $conexion) or die(mysql_error());
$row_gastos = mysql_fetch_assoc($gastos);
$totalRows_gastos = mysql_num_rows($gastos);




//

mysql_select_db($database_conexion, $conexion);
$sql="delete from gastos where id_gastos='$id'";
$verificar=mysql_query($sql,$conexion) or die(mysql_error());

if($verificar){
	echo"<script type=\"text/javascript\">alert ('Gasto Eliminado'); location.href='consultar_gastos.php' </script>";
}
else{
	echo"<script type=\"text/javascript\">alert ('Error'); location.href='consultar_gastos.php' </script>";
	
}//fin de l primer else



mysql_free_result($pedidos);

mysql_free_result($facturas);

mysql_free_result($gastos);
?>