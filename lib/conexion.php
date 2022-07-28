<?php
session_start();

//	FUNCION PARA CONECTARSE CON EL SERVIDO MYSQL
function connect() {
	mysql_connect("localhost","root", "root") or die ("NO SE PUDO CONECTAR CON EL SERVIDOR MYSQL!");
mysql_select_db("sae") or die ("¡NO SE PUDO CONECTAR CON LA BASE DE DATOS!");
	
}
?>