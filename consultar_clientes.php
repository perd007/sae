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

$maxRows_clie = 15;
$pageNum_clie = 0;
if (isset($_GET['pageNum_clie'])) {
  $pageNum_clie = $_GET['pageNum_clie'];
}
$startRow_clie = $pageNum_clie * $maxRows_clie;

mysql_select_db($database_conexion, $conexion);
$query_clie = "SELECT * FROM clientes";
$query_limit_clie = sprintf("%s LIMIT %d, %d", $query_clie, $startRow_clie, $maxRows_clie);
$clie = mysql_query($query_limit_clie, $conexion) or die(mysql_error());
$row_clie = mysql_fetch_assoc($clie);

if (isset($_GET['totalRows_clie'])) {
  $totalRows_clie = $_GET['totalRows_clie'];
} else {
  $all_clie = mysql_query($query_clie);
  $totalRows_clie = mysql_num_rows($all_clie);
}
$totalPages_clie = ceil($totalRows_clie/$maxRows_clie)-1;

$queryString_clie = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_clie") == false && 
        stristr($param, "totalRows_clie") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_clie = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_clie = sprintf("&totalRows_clie=%d%s", $totalRows_clie, $queryString_clie);

if($totalRows_clie==0){
  echo "<script type=\"text/javascript\">alert ('No existen Clientes Registrados');  location.href='fondo.php' </script>";
  exit;
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css.css" rel="stylesheet" type="text/css" />
<title>Documento sin título</title>
<style type="text/css">
.centrar {	text-align: center;
	color:#EFEFEF;
}
a{text-decoration:none}
</style>
</head>
<script language="javascript">
<!--

function validar(){

			var valor=confirm('¿Esta seguro de Eliminar este Cliente?');
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
<table border="0" cellpadding="0" cellspacing="0" width="769" >
  <tbody>
    <tr>
      <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
      <td colspan="7" align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Consulta de Clientes</span></td>
      <td align="right" height="1" width="13"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
    </tr>
    <tr align="center" class="centrar">
      <td background="imagenes/v_back_izq.gif" height="1">&nbsp;</td>
      <td width="167"  valign="baseline" bgcolor="#0000FF"class="negrita">Nombre</td>
      <td width="97" valign="baseline" bgcolor="#0000FF" class="negrita">Cedula o Rif</td>
      <td width="64" valign="baseline" bgcolor="#0000FF" class="negrita">Tipo</td>
      <td width="87" valign="baseline" bgcolor="#0000FF" class="negrita">Telefono</td>
      <td width="183" valign="baseline" bgcolor="#0000FF" class="negrita">Direccion</td>
      <td width="68" valign="baseline" bgcolor="#0000FF" class="negrita">Opciones</td>
      <td width="77" valign="baseline" bgcolor="#0000FF" class="negrita">Opciones</td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
  
    <?php do { ?>
      <tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?php echo $row_clie['id_clientes']; ?>">
        <td background="imagenes/v_back_izq.gif" height="1">&nbsp;</td>
        <td align="center" ><?php echo $row_clie['nombres']; ?></td>
        <td align="center"><?php echo $row_clie['cedula']; ?></td>
        <td align="center"><?php echo $row_clie['tipo']; ?></td>
        <td align="center"><?php echo $row_clie['telefono']; ?></td>
        <td align="center"><?php echo $row_clie['direccion']; ?></td>
        <td align="center"><a href="modificar_clientes.php?id=<?php echo $row_clie['id_clientes']; ?>">
          <input type="button"  value="Modificar" />
        </a></td>
        <td align="center"><a onclick='return validar()' href="eliminar_clientes.php?id=<?php echo $row_clie['cedula']; ?>">
          <input type="button"  value="Eliminar" />
        </a></td>
        <td background="imagenes/v_back_der.gif">&nbsp;</td>
      </tr>
      <?php } while ($row_clie = mysql_fetch_assoc($clie)); ?>
   
    <tr>
      <td background="imagenes/v_back_izq.gif" height="1" width="13"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
      <td colspan="7" class="tituloDOSE_2">&nbsp;
        <table border="0" align="center">
          <tr>
            <td><?php if ($pageNum_clie > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_clie=%d%s", $currentPage, 0, $queryString_clie); ?>"><img src="First.gif" /></a>
            <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_clie > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_clie=%d%s", $currentPage, max(0, $pageNum_clie - 1), $queryString_clie); ?>"><img src="Previous.gif" /></a>
            <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_clie < $totalPages_clie) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_clie=%d%s", $currentPage, min($totalPages_clie, $pageNum_clie + 1), $queryString_clie); ?>"><img src="Next.gif" /></a>
            <?php } // Show if not last page ?></td>
            <td><?php if ($pageNum_clie < $totalPages_clie) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_clie=%d%s", $currentPage, $totalPages_clie, $queryString_clie); ?>"><img src="Last.gif" /></a>
            <?php } // Show if not last page ?></td>
          </tr>
      </table></td>
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
mysql_free_result($clie);
?>
