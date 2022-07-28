<?php require_once('Connections/conexion.php'); ?>
<?php
$validacion=false;
$admin=0;


//verificar si existen la cookis
if(isset($_COOKIE["usr"]) && isset($_COOKIE["clv"]))
{
	
	
	
	$administrador="ADMINISTRADOR";
	$claveAdm="ADMIN1511";
	
	if($administrador==$_COOKIE["usr"] and $claveAdm==$_COOKIE["clv"]){
	$admin=1;
	$validacion=true;
	//generamos nuevas cookis para aumentar su tiempo de destruccion
		setcookie("usr",$_COOKIE["usr"],time()+7776000);
		setcookie("clv",$_COOKIE["clv"],time()+7776000);
		setcookie("val",$validacion,time()+7776000);
	mysql_select_db($database_conexion, $conexion);
	$query_permisos = "SELECT * FROM permisos_usuarios where id_usuario='0'";
	$permisos = mysql_query($query_permisos, $conexion) or die(mysql_error());
	$row_permisos = mysql_fetch_assoc($permisos);
		
	}else{

	mysql_select_db($database_conexion, $conexion);
	$result = mysql_query("SELECT * FROM usuarios WHERE login='".$_COOKIE["usr"]."' AND clave='".$_COOKIE["clv"]."'") or die(mysql_error());

	if($row = mysql_fetch_array($result)){
		
		$validacion = true;
		//generamos nuevas cookis para aumentar su tiempo de destruccion
		setcookie("usr",$_COOKIE["usr"],time()+7776000);
		setcookie("clv",$_COOKIE["clv"],time()+7776000);
		setcookie("val",$validacion,time()+7776000);
	
		//consultamos Permisos en la tabla
		mysql_select_db($database_conexion, $conexion);
		$query_permisos = "SELECT * FROM permisos_usuarios where id_usuario='$row[id_empleado]'";
		$permisos = mysql_query($query_permisos, $conexion) or die(mysql_error());
		$row_permisos = mysql_fetch_assoc($permisos);
		setcookie("p",$row_permisos['p'],time()+7776000);
		setcookie("f",$row_permisos['f'],time()+7776000);
		setcookie("c",$row_permisos['c'],time()+7776000);
		setcookie("a",$row_permisos['a'],time()+7776000);
		setcookie("v",$row_permisos['v'],time()+7776000);
		setcookie("r",$row_permisos['r'],time()+7776000);
		setcookie("cl",$row_permisos['cl'],time()+7776000);
		setcookie("prv",$row_permisos['prv'],time()+7776000);
		setcookie("s",$row_permisos['s'],time()+7776000);
		setcookie("u",$row_permisos['u'],time()+7776000);
		setcookie("ac",$row_permisos['ac'],time()+7776000);
		setcookie("cc",$row_permisos['cc'],time()+7776000);
		setcookie("d",$row_permisos['d'],time()+7776000);
	}
	else
	{
		//Destruimos las cookies.
		setcookie("usr","x",time()-3600);
		setcookie("clv","x",time()-3600);
		setcookie("val",$validacion,time()-3600);
		echo "<script type=\"text/javascript\">alert ('Error con la validacion');  location.href='index.php' </script>";
		exit;
	}
	
	}//fin del else validador del administrador

}



?>