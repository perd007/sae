<?php require_once('Connections/conexion.php'); ?>

<?php


$id=$_GET["id"];


//

mysql_select_db($database_conexion, $conexion);
$sql="delete from ingresos_diarios where id_ingresos='$id'";
$verificar=mysql_query($sql,$conexion) or die(mysql_error());

if($verificar){
	echo"<script type=\"text/javascript\">alert ('Ingreso Eliminado'); location.href='consultar_ingresos.php' </script>";
}
else{
	echo"<script type=\"text/javascript\">alert ('Error'); location.href='consultar_ingresos.php' </script>";
	
}//fin de l primer else


?>