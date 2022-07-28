<?

//posibles correlativos

$factura=58; //Ejemplo = 0058 
$tamNumero = strlen($factura); 

if($tamNumero==1) 
  $factura = "000$factura"; 
elseif ($tamNumero==2) 
  $factura = "00$factura"; 
elseif ($tamNumero == 3) 
  $factura = "0$factura"; 
elseif ($tamNumero == 4) 
  $factura = "$factura"; 
  
echo $factura; 
//

//	FUNCION PARA GENERAR UN NUEVO CODIGO
function getCodigo($tabla, $campo, $digitos) {
	mysql_select_db($database_conexion, $conexion);
	$sql="select max($campo) FROM $tabla";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($query);
	$codigo=(int) ($field[0]+1);
	$codigo=(string) str_repeat("0", $digitos-strlen($codigo)).$codigo;
	return ($codigo);
}
////	genero codigo
		$CodPersona = getCodigo("probar", "cod", 6);
		echo $CodPersona;

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
</body>
</html>