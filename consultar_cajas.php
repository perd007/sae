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

$maxRows_cajas = 20;
$pageNum_cajas = 0;
if (isset($_GET['pageNum_cajas'])) {
  $pageNum_cajas = $_GET['pageNum_cajas'];
}
$startRow_cajas = $pageNum_cajas * $maxRows_cajas;

mysql_select_db($database_conexion, $conexion);
$query_cajas = "SELECT * FROM caja order by fecha_ap desc";
$query_limit_cajas = sprintf("%s LIMIT %d, %d", $query_cajas, $startRow_cajas, $maxRows_cajas);
$cajas = mysql_query($query_limit_cajas, $conexion) or die(mysql_error());
$row_cajas = mysql_fetch_assoc($cajas);

if (isset($_GET['totalRows_cajas'])) {
  $totalRows_cajas = $_GET['totalRows_cajas'];
} else {
  $all_cajas = mysql_query($query_cajas);
  $totalRows_cajas = mysql_num_rows($all_cajas);
}
$totalPages_cajas = ceil($totalRows_cajas/$maxRows_cajas)-1;

$queryString_cajas = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_cajas") == false && 
        stristr($param, "totalRows_cajas") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_cajas = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_cajas = sprintf("&totalRows_cajas=%d%s", $totalRows_cajas, $queryString_cajas);


if($totalRows_cajas==0){
  echo "<script type=\"text/javascript\">alert ('No existen Cajas Registradas');  location.href='fondo.php' </script>";
  exit;
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
<title>Documento sin título</title>
<style type="text/css">
.centrar {
	text-align: center;
	color:#EFEFEF;
}

a{text-decoration:none}
</style>
</head>
<script language="javascript">
<!--

function validar(){

			var valor=confirm('¿Esta seguro de Eliminar este Producto?');
			if(valor==false){
			return false;
			}
			else{
			return true;
			}
		
}
//-->
</script>

<body vlink="0">
<table border="0" cellpadding="0" cellspacing="0" width="760" >
  <tbody>
    <tr>
      <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
      <td colspan="4" align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Consultar Cajas</span></td>
      <td align="right" height="1" width="13"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
    </tr>
    
    <tr align="center" class="centrar">
      <td background="imagenes/v_back_izq.gif" height="1">&nbsp;</td>
      <td width="248"  valign="baseline" bgcolor="#0000FF"class="negrita">Fecha y Hora de Apertura</td>
      <td width="246" valign="baseline" bgcolor="#0000FF" class="negrita">
      <label for="tipo2">Fecha y Hora de Cierra</label></td>
      <td width="159" valign="baseline" bgcolor="#0000FF" class="negrita">Estado</td>
      <td width="80" valign="baseline" bgcolor="#0000FF" class="negrita">Opciones</td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
   
    
   
    <?php do { ?>
      <tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?php echo $row_prod['id_productos']; ?>">
        <td background="imagenes/v_back_izq.gif" height="1">&nbsp;</td>
        <td align="center" ><?php echo $row_cajas['fecha_ap']; ?></td>
        <td align="center"><?php echo $row_cajas['fecha_ci']; ?></td>
        <td align="center"><?php echo $row_cajas['estado']; ?></td>
        <td align="center"><a  href="Ver_Caja.php?id=<?php echo $row_cajas['id_caja']; ?>"><input type="button"  value="VER" /></a></td>
        <td background="imagenes/v_back_der.gif">&nbsp;</td>
      </tr>
      <?php } while ($row_cajas = mysql_fetch_assoc($cajas)); ?>
    <tr>
      <td background="imagenes/v_back_izq.gif" height="1" width="14"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
      <td colspan="4" class="tituloDOSE_2">&nbsp;
        <table border="0" align="center">
          <tr>
            <td><?php if ($pageNum_cajas > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_cajas=%d%s", $currentPage, 0, $queryString_cajas); ?>"><img src="First.gif" /></a>
                <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_cajas > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_cajas=%d%s", $currentPage, max(0, $pageNum_cajas - 1), $queryString_cajas); ?>"><img src="Previous.gif" /></a>
                <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_cajas < $totalPages_cajas) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_cajas=%d%s", $currentPage, min($totalPages_cajas, $pageNum_cajas + 1), $queryString_cajas); ?>"><img src="Next.gif" /></a>
                <?php } // Show if not last page ?></td>
            <td><?php if ($pageNum_cajas < $totalPages_cajas) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_cajas=%d%s", $currentPage, $totalPages_cajas, $queryString_cajas); ?>"><img src="Last.gif" /></a>
                <?php } // Show if not last page ?></td>
          </tr>
      </table></td>
      <td background="imagenes/v_back_der.gif" width="13">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" height="1" width="14"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
      <td height="1" colspan="4" background="imagenes/v_back_inf.gif"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
      <td align="right" height="1" width="13"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
    </tr>
  </tbody>
</table>
</body>
</html>
<?php
mysql_free_result($prod);

mysql_free_result($cajas);
?>
