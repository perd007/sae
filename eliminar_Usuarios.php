<?php require_once('Connections/conexion.php'); ?>
<?php


//recibimos la id
$cedula=$_GET["cedula"];

mysql_select_db($database_conexion, $conexion);
$query_valUsu = "SELECT * FROM usuarios where id_empleado='$cedula' ";
$valUsu = mysql_query($query_valUsu, $conexion) or die(mysql_error());
$row_valUsu = mysql_fetch_assoc($valUsu);
$totalRows_valUsu = mysql_num_rows($valUsu);


$sql="delete from empleados where cedula='$cedula'";
$verificar=mysql_query($sql,$conexion) or die(mysql_error());


if($verificar==1){
	  if($_COOKIE["usr"]==$row_valUsu['login']){
 echo "<script type=\"text/javascript\">alert ('INICIE SESION NUEVAMENTE');  location.href='consultar_usuarios.php' </script>";

 } else{
	echo"<script type=\"text/javascript\">alert ('usuario Eliminado'); location.href='consultar_usuarios.php' </script>";
}}
else{
	echo"<script type=\"text/javascript\">alert ('Error'); location.href='consultar_usuarios.php' </script>";
	
}//fin de l primer else


?>