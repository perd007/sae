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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_producto = 15;
$pageNum_producto = 0;
if (isset($_GET['pageNum_producto'])) {
  $pageNum_producto = $_GET['pageNum_producto'];
}
$startRow_producto = $pageNum_producto * $maxRows_producto;


mysql_select_db($database_conexion, $conexion);
$query_producto = "SELECT * FROM productos where tipo='Vehiculo' ";
$query_limit_producto = sprintf("%s LIMIT %d, %d", $query_producto, $startRow_producto, $maxRows_producto);
$producto = mysql_query($query_limit_producto, $conexion) or die(mysql_error());
$row_producto = mysql_fetch_assoc($producto);

if (isset($_GET['totalRows_producto'])) {
  $totalRows_producto = $_GET['totalRows_producto'];
} else {
  $all_producto = mysql_query($query_producto);
  $totalRows_producto = mysql_num_rows($all_producto);
}
$totalPages_producto = ceil($totalRows_producto/$maxRows_producto)-1;

$queryString_producto = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_producto") == false && 
        stristr($param, "totalRows_producto") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_producto = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_producto = sprintf("&totalRows_producto=%d%s", $totalRows_producto, $queryString_producto);


if( $totalRows_producto==0){
  echo "<script type=\"text/javascript\">alert ('No existen Productos Registrados');  location.href='fondo.php' </script>";
  exit;
  }
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="css.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="css/estilo.css" charset="utf-8" />
<script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
<title>Documento sin título</title>
<style type="text/css">
.centrar {
	text-align: center;
	color:#EFEFEF;
}

a{text-decoration:none}
</style>
<style type="text/css">
.centrar {	text-align: center;
	color:#EFEFEF;
}
</style>
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" width="646" >
  <tbody>
    <tr>
      <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
      <td colspan="3" align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Cantidad de Motos Apartadas </span></td>
      <td align="right" height="1" width="13"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
    </tr>
    <tr align="center" class="centrar">
      <td background="imagenes/v_back_izq.gif" height="1">&nbsp;</td>
      <td width="417"  valign="baseline" bgcolor="#c3131e"class="negrita">Modelo</td>
      <td width="99" valign="baseline" bgcolor="#c3131e" class="negrita">Año</td>
      <td width="103" valign="baseline" bgcolor="#c3131e" class="negrita">Apartados</td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <?php 
	do {
		
		$query_apartado = "SELECT sum(cantidad) FROM productos_apartados where  id_producto='$row_producto[id_productos]' ";
		$apartado = mysql_query($query_apartado, $conexion) or die(mysql_error());
		$row_apartado = mysql_fetch_assoc($apartado);
		
	?>
    <tr class="trListaBody">
      <td background="imagenes/v_back_izq.gif" height="1">&nbsp;</td>
      <td align="center" ><?php echo $row_producto['modelo']; ?></td>
      <td align="center"><?php echo $row_producto['ano']; ?></td>
      <td align="center"><?php if($row_apartado["sum(cantidad)"]!=""){ echo $row_apartado["sum(cantidad)"]; $total_motos=+$row_apartado["sum(cantidad)"]; }if($row_apartado["sum(cantidad)"]=="")echo 0; ?></td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <?php } while ($row_producto = mysql_fetch_assoc($producto)); ?>
    <tr>
      <td background="imagenes/v_back_izq.gif" height="1" width="14"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
      <td colspan="3" class="tituloDOSE_2"><table border="0" align="center">
        <tr>
          <td><?php if ($pageNum_producto > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_producto=%d%s", $currentPage, 0, $queryString_producto); ?>"><img src="First.gif" /></a>
            <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_producto > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_producto=%d%s", $currentPage, max(0, $pageNum_producto - 1), $queryString_producto); ?>"><img src="Previous.gif" /></a>
            <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_producto < $totalPages_producto) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_producto=%d%s", $currentPage, min($totalPages_producto, $pageNum_producto + 1), $queryString_producto); ?>"><img src="Next.gif" /></a>
            <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_producto < $totalPages_producto) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_producto=%d%s", $currentPage, $totalPages_producto, $queryString_producto); ?>"><img src="Last.gif" /></a>
            <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
      <td background="imagenes/v_back_der.gif" width="13"><img src="imagenes/cargar_productos.php" alt="1" height="2" width="13" /></td>
    </tr>
    <tr>
      <td background="imagenes/v_back_izq.gif" height="1">&nbsp;</td>
      <td colspan="3" align="right" class="tituloDOSE_2"><strong>Total de Motos Apartadas: </strong>        <?=$total_motos;?></td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" height="1" width="14"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
      <td height="1" colspan="3" background="imagenes/v_back_inf.gif"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
      <td align="right" height="1" width="13"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
    </tr>
  </tbody>
</table>
</body>
</html>