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

$maxRows_proveedores = 20;
$pageNum_proveedores = 0;
if (isset($_GET['pageNum_proveedores'])) {
  $pageNum_proveedores = $_GET['pageNum_proveedores'];
}
$startRow_proveedores = $pageNum_proveedores * $maxRows_proveedores;


if ($fBuscar != "") { 
	$filtro.=" and rif LIKE '%".utf8_decode($fBuscar)."%' OR
					nombre LIKE '%".utf8_decode($fBuscar)."%' OR
					telefono LIKE '%".utf8_decode($fBuscar)."%' OR
					direccion LIKE '%".utf8_decode($fBuscar)."%'";
} 



mysql_select_db($database_conexion, $conexion);
$query_proveedores = "SELECT * FROM proveedor where tipo='CREDITO' $filtro";
$query_limit_proveedores = sprintf("%s LIMIT %d, %d", $query_proveedores, $startRow_proveedores, $maxRows_proveedores);
$proveedores = mysql_query($query_limit_proveedores, $conexion) or die(mysql_error());
$row_proveedores = mysql_fetch_assoc($proveedores);

if (isset($_GET['totalRows_proveedores'])) {
  $totalRows_proveedores = $_GET['totalRows_proveedores'];
} else {
  $all_proveedores = mysql_query($query_proveedores);
  $totalRows_proveedores = mysql_num_rows($all_proveedores);
}
$totalPages_proveedores = ceil($totalRows_proveedores/$maxRows_proveedores)-1;

$queryString_proveedores = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_proveedores") == false && 
        stristr($param, "totalRows_proveedores") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_proveedores = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_proveedores = sprintf("&totalRows_proveedores=%d%s", $totalRows_proveedores, $queryString_proveedores);


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link type="text/css" rel="stylesheet" href="css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="css/estilo.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="js2/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js2/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js2/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="js2/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="js2/fscript.js" charset="utf-8"></script>
<link href="css.css" rel="stylesheet" type="text/css" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>

<form id="form1" name="form1" method="post" action="listaProveedores.php">

<div style="overflow:scroll; width:100%; height:250px;">
<div class="divBorder" style="width:400px;">
  <table width="400" class="tblFiltro">
    <tr b="b">
      <td width="54" height="29" align="right" class="negrita">Buscar:</td>
      <td width="261"><input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:250px;"/></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="submit" class="negrita" value="Buscar" /></td>
    </tr>
</table>
</div>
<p>&nbsp;</p>
<table width="800" class="tblLista" >
<thead>
  <tr>
    <th width="324" scope="col">Nombre</th>
    <th width="106" scope="col">Rif</th>
    <th width="141" scope="col">Telefono</th>
    <th width="209" scope="col">Direccion</th>
    </tr>
    <thead>
  <tbody>


  
 
    <?php do { ?>
      <tr class="trListaBody" onclick="selListado2('<?=$row_proveedores['rif']?>', '<?=($row_proveedores['nombre'])?>', 'rif2', 'proveedor2', '', '');" id="<?=$row_proveedores['rif']?>">
        <td><?php echo $row_proveedores['nombre']; ?></td>
        <td><?php echo $row_proveedores['rif']; ?></td>
        <td><?php echo $row_proveedores['telefono']; ?></td>
        <td><?php echo $row_proveedores['direccion']; ?></td>
      </tr>
      <?php } while ($row_proveedores = mysql_fetch_assoc($proveedores)); ?>
  </tbody>
</table>
<table border="0" align="center">
  <tr>
    <td><?php if ($pageNum_proveedores > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_proveedores=%d%s", $currentPage, 0, $queryString_proveedores); ?>"><img src="First.gif" /></a>
      <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_proveedores > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_proveedores=%d%s", $currentPage, max(0, $pageNum_proveedores - 1), $queryString_proveedores); ?>"><img src="Previous.gif" /></a>
      <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_proveedores < $totalPages_proveedores) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_proveedores=%d%s", $currentPage, min($totalPages_proveedores, $pageNum_proveedores + 1), $queryString_proveedores); ?>"><img src="Next.gif" /></a>
      <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_proveedores < $totalPages_proveedores) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_proveedores=%d%s", $currentPage, $totalPages_proveedores, $queryString_proveedores); ?>"><img src="Last.gif" /></a>
      <?php } // Show if not last page ?></td>
  </tr>
</table>

</div>
</form>
</body>
</html>
<?php
mysql_free_result($proveedores);

mysql_free_result($clientes);
?>
