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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<style type="text/css">
.centrar {text-align: center;
	color:#EFEFEF;
}
</style>
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" width="760" >
  <tbody>
    <tr>
      <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
      <td colspan="5" align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita"><strong>Visualizar Gastos Ingresados</strong></span></td>
      <td align="right" height="1" width="13"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
    </tr>
    <tr align="center" class="centrar">
      <td height="1" align="center" background="imagenes/v_back_izq.gif">&nbsp;</td>
      <td width="89" align="left"  valign="baseline" bgcolor="#0000FF"class="negrita">Fecha</td>
      <td width="76" align="left" valign="baseline" bgcolor="#0000FF" class="negrita">Monto</td>
      <td width="300" align="center" valign="baseline" bgcolor="#0000FF" class="negrita">Descripcion</td>
      <td width="22" align="center" valign="baseline" bgcolor="#0000FF" class="negrita">Opc</td>
      <td width="22" align="center" valign="baseline" bgcolor="#0000FF" class="negrita">Opc</td>
      <td align="center" background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <?php
mysql_select_db($database_conexion, $conexion);
$query_ingresos = "SELECT * FROM ingresos_diarios order by fecha desc";
$ingresos = mysql_query($query_ingresos, $conexion) or die(mysql_error());
$row_ingresos = mysql_fetch_assoc($ingresos);
$totalRows_ingresos = mysql_num_rows($ingresos);

$queryString_gastos = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_gastos") == false && 
        stristr($param, "totalRows_gastos") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_gastos = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_gastos = sprintf("&totalRows_gastos=%d%s", $totalRows_gastos, $queryString_gastos);

	
	?>
    <?php do { ?>
    <tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?php echo $row_prod['id_productos']; ?>">
      <td background="imagenes/v_back_izq.gif" height="1">&nbsp;</td>
      <td align="left" ><?php echo $row_ingresos['fecha']; ?></td>
      <td align="left"><?=(number_format($row_ingresos['monto'],"2",",","."))?></td>
      <td align="left"><?php echo $row_ingresos['observaciones']; ?></td>
      <td align="center"><a href="modificar_ingresos.php?id=<?php echo $row_ingresos['id_ingresos']; ?>">
        <input type="button"  value="M" />
      </a></td>
      <td align="center"><a onclick='return validar()' href="eliminar_ingresos.php?id=<?php echo $row_ingresos['id_ingresos']; ?>">
        <input type="button"  value="E" />
      </a></td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <?php } while ($row_ingresos = mysql_fetch_assoc($ingresos)); ?>
    <tr>
      <td background="imagenes/v_back_izq.gif" height="1" width="13"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
      <td colspan="5" class="tituloDOSE_2"><table border="0" align="center">
        <tr>
          <td><?php if ($pageNum_gastos > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_gastos=%d%s", $currentPage, 0, $queryString_gastos); ?>"><img src="First.gif" /></a>
            <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_gastos > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_gastos=%d%s", $currentPage, max(0, $pageNum_gastos - 1), $queryString_gastos); ?>"><img src="Previous.gif" /></a>
            <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_gastos < $totalPages_gastos) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_gastos=%d%s", $currentPage, min($totalPages_gastos, $pageNum_gastos + 1), $queryString_gastos); ?>"><img src="Next.gif" /></a>
            <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_gastos < $totalPages_gastos) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_gastos=%d%s", $currentPage, $totalPages_gastos, $queryString_gastos); ?>"><img src="Last.gif" /></a>
            <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
      <td background="imagenes/v_back_der.gif" width="13">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" height="1" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
      <td height="1" colspan="5" background="imagenes/v_back_inf.gif"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
      <td align="right" height="1" width="13"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
    </tr>
  </tbody>
</table>
</body>
</html>
<?php
mysql_free_result($ingresos);
?>
