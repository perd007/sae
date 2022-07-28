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

$maxRows_precios = 20;
$pageNum_precios = 0;
if (isset($_GET['pageNum_precios'])) {
  $pageNum_precios = $_GET['pageNum_precios'];
}
$startRow_precios = $pageNum_precios * $maxRows_precios;

mysql_select_db($database_conexion, $conexion);
$query_precios = "SELECT * FROM precios where id_producto='$_POST[id_productos]' order by id_precios desc";
$query_limit_precios = sprintf("%s LIMIT %d, %d", $query_precios, $startRow_precios, $maxRows_precios);
$precios = mysql_query($query_limit_precios, $conexion) or die(mysql_error());
$row_precios = mysql_fetch_assoc($precios);

if (isset($_GET['totalRows_precios'])) {
  $totalRows_precios = $_GET['totalRows_precios'];
} else {
  $all_precios = mysql_query($query_precios);
  $totalRows_precios = mysql_num_rows($all_precios);
}
$totalPages_precios = ceil($totalRows_precios/$maxRows_precios)-1;

$queryString_precios = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_precios") == false && 
        stristr($param, "totalRows_precios") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_precios = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_precios = sprintf("&totalRows_precios=%d%s", $totalRows_precios, $queryString_precios);

//
if($_POST["MM_insert"]=="form1"){
	if($totalRows_precios==0){
  	echo "<script type=\"text/javascript\">alert ('No existen Ajustes Registrados');  location.href='fondo.php' </script>";
  	exit;
  	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="css.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="css/estilo.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript" src="jscalendar-1.0/lang/calendar-es.js"></script>

<script type="text/javascript" src="js2/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js2/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js2/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="js2/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="js2/fscript.js" charset="utf-8"></script>

<script type="text/javascript" src="js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="js/fscript.js" charset="utf-8"></script>
<script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="js/fscript.js" charset="utf-8"></script>
<script type="text/javascript" src="js/form.js" charset="utf-8"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<style type="text/css">
a{text-decoration:none}
 
.Letras {
	font-size: 14px;
	font-style: italic;
	background-color: transparent;
	border: none;
	font-weight: bold;
}

.boton {	font-size: 14px;
	font-style: italic;
	font-weight: bold; 
}

.dato {background-color: transparent; 

font-size:14px;
font-style:italic;
text-decoration:none
 
}

.Letras1 {font-size:14px;
font-style:italic;

background-color:transparent;
border:none;
}
</style>
</head>

<body>
<!-- pretty -->
<form id="form1" name="form1" method="post" action="consultar_ajustar_precios.php">
<span class="gallery clearfix"></span>
<table border="0" cellpadding="0" cellspacing="0" width="639">
  <tbody>
    <tr>
      <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
      <td class="tituloDOSE_3" align="center" background="imagenes/v_back_sup.jpg" valign="center"><span class="negrita">Visualizar Precios Registrados </span></td>
      <td align="right" height="1" width="13"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
    </tr>
    <tr>
      <td width="13" height="140" rowspan="3" align="right" background="imagenes/v_back_izq.gif"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
      <td valign="baseline" align="left"class="gallery clearfix">
      <input type="text" name="marca" id="marca" readonly="readonly" class="dato"  style="text-align:center; font-weight:bold;" value="<?=$_POST["marca"]?>" size="20"  /><input type="text" name="nombre" id="nombre" readonly="readonly" class="dato" value="<?=$_POST["nombre"]?>" style="text-align:center; font-weight:bold;" size="32" />
      <a  href="listado_productos_ajuste.php?iframe=true&amp;width=950&amp;height=525" target="_blank" rel="prettyPhoto[iframe1]" ><input name="Productos" value="Productos" type="button" /></a><a  href="listado_servicios_ajuste.php?iframe=true&amp;width=950&amp;height=525" target="_blank" rel="prettyPhoto[iframe1]" ><input name="Servicios" value="Servicios" type="button" /></a></td>
      <td width="13" rowspan="3" background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <tr>
      <td valign="baseline" align="center"class="gallery clearfix"><input name="button" type="submit" class="boton"  id="button" value="Consultar" /></td>
    </tr>
    <tr>
      <td valign="top"><table width="459" align="center" class="tblLista">
        <thead>
          <tr>
            <th width="220">Fecha</th>
            <th  width="227">Precio</th>
          </tr>
          <?php do { ?>
            <tr>
              <td align="center" class="Letras"><?php echo $row_precios['fecha']; ?></td>
              <td align="center" class="Letras"><input type="text" name="precio" disabled="disabled" class="Letras1"  style="text-align:center; font-weight:bold;" value="<?php echo $row_precios['precio']; ?>" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" />
                </td>
            </tr>
            <?php } while ($row_precios = mysql_fetch_assoc($precios)); ?>
        </thead>
        <tbody id="lista_detalles">
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="19" align="right" background="imagenes/v_back_izq.gif">&nbsp;</td>
      <td align="center" valign="top">&nbsp;
        <table border="0">
          <tr>
            <td><?php if ($pageNum_precios > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_precios=%d%s", $currentPage, 0, $queryString_precios); ?>"><img src="First.gif" /></a>
              <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_precios > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_precios=%d%s", $currentPage, max(0, $pageNum_precios - 1), $queryString_precios); ?>"><img src="Previous.gif" /></a>
              <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_precios < $totalPages_precios) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_precios=%d%s", $currentPage, min($totalPages_precios, $pageNum_precios + 1), $queryString_precios); ?>"><img src="Next.gif" /></a>
              <?php } // Show if not last page ?></td>
            <td><?php if ($pageNum_precios < $totalPages_precios) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_precios=%d%s", $currentPage, $totalPages_precios, $queryString_precios); ?>"><img src="Last.gif" /></a>
              <?php } // Show if not last page ?></td>
          </tr>
      </table></td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" height="12" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
      <td background="imagenes/v_back_inf.gif" height="12" width="613"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
      <td align="right" height="12" width="13"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
    </tr>
  </tbody>
</table>
    <input type="hidden" name="MM_insert" value="form1" />
     <input type="hidden" name="id_productos" id="id_productos" value="" />
</form>

</body>
</html>
<?php
mysql_free_result($precios);
?>
