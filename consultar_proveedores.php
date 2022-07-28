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

$maxRows_prove = 10;
$pageNum_prove = 0;
if (isset($_GET['pageNum_prove'])) {
  $pageNum_prove = $_GET['pageNum_prove'];
}
$startRow_prove = $pageNum_prove * $maxRows_prove;

mysql_select_db($database_conexion, $conexion);
$query_prove = "SELECT * FROM proveedor";
$query_limit_prove = sprintf("%s LIMIT %d, %d", $query_prove, $startRow_prove, $maxRows_prove);
$prove = mysql_query($query_limit_prove, $conexion) or die(mysql_error());
$row_prove = mysql_fetch_assoc($prove);

if (isset($_GET['totalRows_prove'])) {
  $totalRows_prove = $_GET['totalRows_prove'];
} else {
  $all_prove = mysql_query($query_prove);
  $totalRows_prove = mysql_num_rows($all_prove);
}
$totalPages_prove = ceil($totalRows_prove/$maxRows_prove)-1;

$queryString_prove = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_prove") == false && 
        stristr($param, "totalRows_prove") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_prove = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_prove = sprintf("&totalRows_prove=%d%s", $totalRows_prove, $queryString_prove);

if($totalRows_prove==0){
  echo "<script type=\"text/javascript\">alert ('No existen Proveedores Registrados');  location.href='fondo.php' </script>";
  exit;
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
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

			var valor=confirm('¿Esta seguro de Eliminar este Proveedor?');
			if(valor==false){
			return false;
			}
			else{
			return true;
			}
		
}
//-->
</script>
<body>
<table border="0" cellpadding="0" cellspacing="0" width="760" >
  <tbody>
    <tr>
      <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
      <td colspan="6" align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Consulta de Proveedores</span></td>
      <td align="right" height="1" width="13"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
    </tr>
    <tr align="center" class="centrar">
      <td background="imagenes/v_back_izq.gif" height="1">&nbsp;</td>
      <td width="356"  valign="baseline" bgcolor="#0000FF"class="negrita">Nombre</td>
      <td width="66" valign="baseline" bgcolor="#0000FF" class="negrita"><label for="tipo2">Rif</label></td>
      <td width="102" valign="baseline" bgcolor="#0000FF" class="negrita">Telefono</td>
      <td width="80" valign="baseline" bgcolor="#0000FF" class="negrita">Tipo</td>
      <td width="68" valign="baseline" bgcolor="#0000FF" class="negrita">Opciones</td>
      <td width="62" valign="baseline" bgcolor="#0000FF" class="negrita">Opciones</td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
   
    <?php do { ?>
      <tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?php echo $row_prod['id_productos']; ?>">
        <td background="imagenes/v_back_izq.gif" height="1">&nbsp;</td>
        <td align="center" ><?php echo $row_prove['nombre']; ?></td>
        <td align="center"><?php echo $row_prove['rif']; ?></td>
        <td align="center"><?php echo $row_prove['telefono']; ?></td>
        <td align="center"><?php echo $row_prove['tipo']; ?></td>
        <td align="center"><a href="modificar_proveedor.php?id=<?php echo $row_prove['id_proveedor']; ?>">
          <input type="button"  value="Modificar" />
        </a></td>
        <td align="center"><a onclick='return validar()' href="eliminar_proveedor.php?id=<?php echo $row_prove['id_proveedor']; ?>&rif=<?php echo $row_prove['rif']; ?>">
          <input type="button"  value="Eliminar" />
        </a></td>
        <td background="imagenes/v_back_der.gif">&nbsp;</td>
      </tr>
      <?php } while ($row_prove = mysql_fetch_assoc($prove)); ?>
  
    <tr>
      <td background="imagenes/v_back_izq.gif" height="1" width="13"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
      <td colspan="6" class="tituloDOSE_2"><table border="0" align="center">
        <tr>
          <td><?php if ($pageNum_prove > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_prove=%d%s", $currentPage, 0, $queryString_prove); ?>"><img src="First.gif" /></a>
            <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_prove > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_prove=%d%s", $currentPage, max(0, $pageNum_prove - 1), $queryString_prove); ?>"><img src="Previous.gif" /></a>
            <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_prove < $totalPages_prove) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_prove=%d%s", $currentPage, min($totalPages_prove, $pageNum_prove + 1), $queryString_prove); ?>"><img src="Next.gif" /></a>
            <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_prove < $totalPages_prove) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_prove=%d%s", $currentPage, $totalPages_prove, $queryString_prove); ?>"><img src="Last.gif" /></a>
            <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
      <td background="imagenes/v_back_der.gif" width="13">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" height="1" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
      <td height="1" colspan="6" background="imagenes/v_back_inf.gif"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
      <td align="right" height="1" width="13"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
    </tr>
  </tbody>
</table>
</body>
</html>
<?php
mysql_free_result($prove);
?>
