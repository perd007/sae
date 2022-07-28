<?
//Destruimos las cookies para cerrar la sesion
		setcookie("usr","x",time()-3600);
		setcookie("clv","x",time()-3600);
		
		
		if($_SESSION["usuario"]){
			session_destroy();		
	}
	
		//eliminar cache del navegador
header ("Expires: Thu, 27 Mar 1980 23:59:00 GMT"); //la pagina expira en una fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); 
//llamamos la pagina index.php
header("location: index.php");
?>