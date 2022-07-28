<?php require_once('Connections/conexion.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_conexion, $conexion);
$query_caja = "SELECT * FROM caja where id_caja='$_GET[id]'";
$caja = mysql_query($query_caja, $conexion) or die(mysql_error());
$row_caja = mysql_fetch_assoc($caja);
$totalRows_caja = mysql_num_rows($caja);


if($row_caja['estado']=="CERRADA"){
	
mysql_select_db($database_conexion, $conexion);
$query_facturas = "SELECT * FROM facturas where fecha>='$row_caja[fecha_ap]' and fecha<='$row_caja[fecha_ci]' and  transaccion!='DEVOLUCION'";
$facturas = mysql_query($query_facturas, $conexion) or die(mysql_error());
$row_facturas = mysql_fetch_assoc($facturas);
$totalRows_facturas = mysql_num_rows($facturas);

}elseif($row_caja['estado']=="ABIERTA"){
	
mysql_select_db($database_conexion, $conexion);
$query_facturas = "SELECT * FROM facturas where fecha>='$row_caja[fecha_ap]' and  transaccion!='DEVOLUCION'";
$facturas = mysql_query($query_facturas, $conexion) or die(mysql_error());
$row_facturas = mysql_fetch_assoc($facturas);
$totalRows_facturas = mysql_num_rows($facturas);
	
}

	$efectivo=0;
	$debito=0;
	$cheque=0;
	$deposito=0;
//OBTENEMOS EL DINERO DE LAS CAJAS
do{
	
	//validamos si los pagos fueron deun solo tipo
	if($row_facturas["efectivo"]<0 and $row_facturas["debito"]=="" and $row_facturas["cheque"]=="" and $row_facturas["deposito"]==""){
		$efectivo=$efectivo + $row_facturas["total"];
	}
	if($row_facturas["debito"]<0 and $row_facturas["efectivo"]=="" and $row_facturas["cheque"]=="" and $row_facturas["deposito"]==""){
		$debito=$debito + $row_facturas["total"];
		
	}
	if($row_facturas["efectivo"]=="" and $row_facturas["debito"]=="" and $row_facturas["cheque"]<0 and $row_facturas["deposito"]==""){
		$cheque=$cheque + $row_facturas["total"];
	}
	if($row_facturas["efectivo"]=="" and $row_facturas["debito"]=="" and $row_facturas["cheque"]=="" and $row_facturas["deposito"]<0){
		$deposito=$deposito + $row_facturas["total"];
	}
	//validamos si los pago fueron combinados y continuamos sumando
	
	if($row_facturas["efectivo"]>0 or $row_facturas["debito"]>0 or $row_facturas["cheque"]>0 or $row_facturas["deposito"]>0){
		if($row_facturas["efectivo"]!="") $efectivo=$efectivo + $row_facturas["efectivo"];
		if($row_facturas["debito"]!="") $debito=$debito + $row_facturas["debito"];
		if($row_facturas["cheque"]!="") $cheque=$cheque + $row_facturas["cheque"];
		if($row_facturas["deposito"]!="") $deposito=$deposito + $row_facturas["deposito"];
	}
	
	
}while($row_facturas = mysql_fetch_assoc($facturas));

$total=$efectivo+$debito+$cheque;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="css.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="css/estilo.css" charset="utf-8" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<style type="text/css">
.centrar {	text-align: center;
	color:#EFEFEF;
}
</style>
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" width="755" >
  <tbody>
    <tr>
      <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
      <td colspan="7" align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Visualizar Caja </span></td>
      <td align="right" height="1" width="13"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
    </tr>
    <tr align="center" >
      <td background="imagenes/v_back_izq.gif" height="1">&nbsp;</td>
      <td align="right"  valign="baseline" bgcolor="#FFFFFF"class="negrita">Estado de la Caja:</td>
      <td align="center"  valign="baseline" bgcolor="#FFFFFF"class="negrita"><?php echo $row_caja['estado']; ?></td>
      <td colspan="2" valign="baseline" bgcolor="#FFFFFF" class="negrita">&nbsp;</td>
      <td valign="baseline" bgcolor="#FFFFFF" class="negrita">&nbsp;</td>
      <td valign="baseline" bgcolor="#FFFFFF" class="negrita">&nbsp;</td>
      <td valign="baseline" bgcolor="#FFFFFF" class="negrita">&nbsp;</td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <tr align="center" class="centrar">
      <td background="imagenes/v_back_izq.gif" height="1">&nbsp;</td>
      <td width="141"  valign="baseline" bgcolor="#0000FF"class="negrita">Apetura</td>
      <td width="147"  valign="baseline" bgcolor="#0000FF"class="negrita">Cierre</td>
      <td width="87" valign="baseline" bgcolor="#0000FF" class="negrita">Efectivo </td>
      <td width="87" valign="baseline" bgcolor="#0000FF" class="negrita">Debito</td>
      <td width="87" valign="baseline" bgcolor="#0000FF" class="negrita">Cheque</td>
      <td width="87" valign="baseline" bgcolor="#0000FF" class="negrita">Deposito</td>
      <td width="93" valign="baseline" bgcolor="#0000FF" class="negrita">Total </td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <tr class="trListaBody">
      <td background="imagenes/v_back_izq.gif" height="1">&nbsp;</td>
      <td align="center" ><?php echo $row_caja['fecha_ap']; ?></td>
      <td align="center" ><?php echo $row_caja['fecha_ci']; ?></td>
      <td align="center"><?php echo number_format($efectivo,"2",",","."); ?></td>
      <td align="center"><?php echo number_format($debito,"2",",","."); ?></td>
      <td align="center"><?php echo number_format($cheque,"2",",","."); ?></td>
      <td align="center"><?php echo number_format($deposito,"2",",","."); ?></td>
      <td align="center"><?php echo number_format($total,"2",",","."); ?></td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <tr>
      <td background="imagenes/v_back_izq.gif" height="1" width="13"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
      <td colspan="7" align="center" class="tituloDOSE_2">&nbsp;</td>
      <td background="imagenes/v_back_der.gif" width="13">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" height="1" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
      <td height="1" colspan="7" background="imagenes/v_back_inf.gif"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
      <td align="right" height="1" width="13"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
    </tr>
  </tbody>
</table>
</body>
</html>
<?php
mysql_free_result($caja);

mysql_free_result($facturas);
?>
